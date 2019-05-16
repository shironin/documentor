<div class="row" id="hidden_rows" style="display: none;">
    <div class="form-group col-6">
        {!! Form::label('field_name', Lang::get('dictionary.field_name_text')) !!}
        {!! Form::text('field_name[]', null, ['id' => 'field_name', 'class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('type', Lang::get('dictionary.content_type_text')) !!}
        {!! Form::select('type[]', ['text','number','date'], null, ['id' => 'type', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group col-1">
        {{--<label for="del">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>--}}
        <button type="button" id="del" class="delete_row btn btn-danger" style="position:absolute;bottom:0;" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.delete_row_tooltip')">X</button>
    </div>
</div>

{!! Form::open(array('action' => "DoctypesController@storeDoctype")) !!}
{{--<input name="doctype" type="hidden" value="{{ $doctype->id }}"/>--}}

<div class="row">
    <div class="form-group col-5">
        {!! Form::label('doctype_name', Lang::get('dictionary.doctype_name_text')) !!}
        {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-5">
        {!! Form::label('department_id', Lang::get('dictionary.department_text')) !!}
        {!! Form::select('department_id', $departments, null, ['id' => 'department_id', 'class' => 'form-control', 'required']) !!}
    </div>
</div>


<div id="fields_area">

    <div class="row">
        <div class="form-group col-6">
            {!! Form::label('field_name', Lang::get('dictionary.field_name_text')) !!}
            {!! Form::text('field_name[]', null, ['id' => 'field_name', 'class' => 'form-control', 'required']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('type', Lang::get('dictionary.content_type_text')) !!}
            {!! Form::select('type[]', ['text','number','date'], null, ['id' => 'type', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group col-1">
            <button type="button" id="del" class="delete_row btn btn-danger" style="position:absolute;bottom:0;">X</button>
        </div>
    </div>

</div>


<button type="button" id="add_1" class="add_row btn btn-outline-info">+@lang('dictionary.add_field_button')</button>
<button type="submit" class="btn btn-outline-success">@lang('dictionary.save_text')</button>
<button type="button" data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm" class="btn btn-outline-danger">@lang('dictionary.cancel_text')</button>
{!! Form::close() !!}

<script>

    $(".add_row").on( "click", function() {
        $('#hidden_rows').clone().appendTo('#fields_area');
        $( "#fields_area div:last-of-type" ).attr("id",'added_row');
        $( "#fields_area div:last-of-type" ).css("display","");
    });

    $('.card-body').on('click', '.delete_row', function() {
        $(this).parent().parent().remove();
    });

</script>
