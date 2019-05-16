
<div class="form-group col-5" id="hidden_row_text" style="display: none;">
    <label for="fields_content"></label>
    <input id="fields_content" class="form-control" name="fields_content[]" type="text">
</div>
<div class="form-group col-5" id="hidden_row_number" style="display: none;">
    <label for="fields_content"></label>
    <input id="fields_content" class="form-control" name="fields_content[]" type="number">
</div>
<div class="form-group col-5" id="hidden_row_date" style="display: none;">
    <label for="fields_content"></label>
    <input id="fields_content" class="form-control" name="fields_content[]" type="date">
</div>

{!! Form::open(array('action' => "DocumentsController@storeDocument")) !!}
{{--<input name="doctype" type="hidden" value="{{ $doctype->id }}"/>--}}

@php
    $doc_types = $doctypes->prepend(Lang::get('dictionary.select_doctype_placeholder'), '')->toArray();
@endphp

<div class="form-group col-5">
    {!! Form::label('doctype', Lang::get('dictionary.doctype_select_text')) !!}
    {!! Form::select('doctypes[]', $doc_types, null, ['id' => 'doctype', 'class' => 'form-control']) !!}
</div>

<div id="fields_area">

</div>


<button type="submit" class="btn btn-success">@lang('dictionary.save_text')</button>
<button type="button" onclick="getDoctypeFields()" class="btn btn-danger">@lang('dictionary.cancel_text')</button>
{!! Form::close() !!}

<script>

    $('#doctype').on('change',function(){
        if($('#doctype').val() !== '') {
            getDoctypeFields();
        }
    });

    function getDoctypeFields()
    {
        $.ajax({
            url: "/documentor/public/getDoctypeFields",
            method: "POST",
            data: {
                '_token': '{{ csrf_token() }}',
                'id': $('#doctype').val(),
            },
            success: function (fields) {
                $('#fields_area').empty();
                for(var i=0; i < fields.length; i++) {

                    if(fields[i].type == 0) {
                        $('#hidden_row_text').clone().appendTo('#fields_area');
                    }
                    if(fields[i].type == 1) {
                        $('#hidden_row_number').clone().appendTo('#fields_area');
                    }
                    if(fields[i].type == 2) {
                        $('#hidden_row_date').clone().appendTo('#fields_area');
                    }
                    var added_row = $( "#fields_area div:last-of-type" );
                    added_row.attr("id",'added_row');
                    added_row.css("display","");
                    added_row.children().html(fields[i].name);
                    console.log(fields[i].name);
                }
            }
        });
    }

    $(document).ready(function(){
        //getDoctypeFields();
    });

</script>
