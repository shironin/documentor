{!! Form::open(array('action' => "DepartmentsController@storeDepartment")) !!}
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
        {!! Form::label('name', Lang::get('dictionary.department_name_text')) !!}
        {!! Form::input('text','name', null, ['class' => 'form-control', 'required' ]) !!}
    </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
        {!! Form::label('contact', Lang::get('dictionary.department_contact_text')) !!}
        {!! Form::input('text','contact', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-12">
        {!! Form::label('address', Lang::get('dictionary.department_address_text')) !!}
        {!! Form::input('text','address', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-12">
        {!! Form::label('comment', Lang::get('dictionary.comment_text')) !!}
        {!! Form::textarea('comment', null, ['class' => 'form-control','rows' => '3']) !!}
    </div>

    <div class="form-group col-12">
        <button type="submit" class="btn btn-outline-success">@lang('dictionary.save_text')</button>
        <button type="button" data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm" class="btn btn-outline-danger">@lang('dictionary.cancel_text')</button>
    </div>
</div>

{!! Form::close() !!}
