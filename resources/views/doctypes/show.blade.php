@extends('layouts.app')

@section('content')
    <h1>@lang('dictionary.edit_doctype_text')</h1>
    <hr>

    <div class="alert alert-danger" role="alert">
        @lang('dictionary.edit_doctype_alert')
    </div>

    <div class="row" id="hidden_rows" style="display: none;">
        <div class="form-group col-6">
            {!! Form::label('field_name', Lang::get('dictionary.field_name_text')) !!}
            {!! Form::text('field_name[]', null, ['id' => 'field_name', 'class' => 'form-control', 'required']) !!}
        </div>
        <div class="form-group col-4">
            {!! Form::label('type', Lang::get('dictionary.content_type_text')) !!}
            {!! Form::select('type[]', ['text','integer','date'], null, ['id' => 'type', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group col-1">
            <button type="button" id="del" class="delete_row btn btn-danger"  style="position:absolute;bottom:0;" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.delete_row_tooltip')">X</button>
        </div>
    </div>


    <br>
    {!! Form::open(array('action' => "DoctypesController@editDoctype")) !!}
    <input name="doctype_id" type="hidden" value="{{ $doctype->id }}"/>

    <div class="row">
        <div class="form-group col-11">
            {!! Form::label('doctype_name', Lang::get('dictionary.doctype_name_text')) !!}
            {!! Form::text('name', $doctype->name, ['id' => 'name', 'class' => 'form-control']) !!}
        </div>
    </div>

    <div id="fields_area">

        @foreach ($fields as $field)
        <div class="row">
            <div class="form-group col-6">
                {!! Form::label('field_name', Lang::get('dictionary.field_name_text')) !!}
                {!! Form::text('field_name[]', $field->name, ['id' => 'field_name', 'class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group col-4">
                {!! Form::label('type', Lang::get('dictionary.content_type_text')) !!}
                {!! Form::select('type[]', ['text','integer','date'], $field->type, ['id' => 'type', 'class' => 'form-control']) !!}
            </div>
            <div class="form-group col-1">
                <button type="button" id="del" class="delete_row btn btn-danger" style="position:absolute;bottom:0;" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.delete_row_tooltip')">
                    X
                </button>
            </div>
        </div>
        @endforeach

    </div>

    <br>

    <button type="button" id="add_1" class="add_row btn btn-outline-info">+@lang('dictionary.add_field_button')</button>
    <button type="submit" class="btn btn-outline-success">@lang('dictionary.save_text')</button>
    <a href="{{ action('DoctypesController@index') }}" class="btn btn-outline-warning">@lang('dictionary.cancel_text')</a>
    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal">@lang('dictionary.delete_button')</button>
    {!! Form::close() !!}


    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('dictionary.confirm_deletion_text')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        @lang('dictionary.delete_doctype_alert')
                    </div>
                    <form method="post" action="{{$doctype->id}}">
                        {!! Form::token() !!}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-outline-danger">@lang('dictionary.yes_button')</button>
                        <button type="button" data-toggle="modal" data-target="#deleteModal" class="btn btn-outline-primary">@lang('dictionary.cancel_text')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <br>





    @include('errors.list')


    <script>

        $(".add_row").on( "click", function() {
            $('#hidden_rows').clone().appendTo('#fields_area');
            $( "#fields_area div:last-of-type" ).attr("id",'added_row');
            $( "#fields_area div:last-of-type" ).css("display","");
        });

        $('.card-body').on('click', '.delete_row', function() {
            //do something
            $(this).parent().parent().remove();
        });


    </script>

@stop