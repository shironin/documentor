@extends('layouts.app')

@section('content')
    <h1>@lang('dictionary.edit_department_text')</h1>
    <hr>

    <br>
    {!! Form::open(array('action' => "DepartmentsController@editDepartment")) !!}
    <input name="department_id" type="hidden" value="{{ $department->id }}"/>

    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            {!! Form::label('name', Lang::get('dictionary.department_name_text')) !!}
            {!! Form::input('text','name', $department->name, ['class' => 'form-control', 'required']) !!}
        </div>

        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            {!! Form::label('contact', Lang::get('dictionary.department_contact_text')) !!}
            {!! Form::input('text','contact', $department->contact, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-12">
            {!! Form::label('address', Lang::get('dictionary.department_address_text')) !!}
            {!! Form::input('text','address', $department->address, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-12">
            {!! Form::label('comment', Lang::get('dictionary.comment_text')) !!}
            {!! Form::textarea('comment', $department->comment, ['class' => 'form-control','rows' => '3']) !!}
        </div>

        <div class="form-group col-12">
            <button type="submit" class="btn btn-outline-success">@lang('dictionary.save_text')</button>
            <a href="{{ action('DepartmentsController@index') }}" class="btn btn-outline-warning">@lang('dictionary.cancel_text')</a>
            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal">@lang('dictionary.delete_button')</button>
        </div>
    </div>

    {!! Form::close() !!}
    <br>


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
                        @lang('dictionary.delete_department_alert')
                    </div>
                    <form method="post" action="{{$department->id}}">
                        {!! Form::token() !!}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-outline-danger">@lang('dictionary.yes_button')</button>
                        <button type="button" data-toggle="modal" data-target="#deleteModal" class="btn btn-outline-primary">@lang('dictionary.cancel_text')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@stop