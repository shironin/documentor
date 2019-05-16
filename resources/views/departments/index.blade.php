@extends('layouts.app')

@section('content')
    <h2>@lang('dictionary.departments_text')</h2>
    <p>
        <br>
        <button class="btn btn-outline-info" type="button" data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
            + @lang('dictionary.add_department_text')
        </button>
    </p>

    <div class="collapse" id="collapseForm">
        <div class="card card-body" style="background-color:oldlace;">

            @include('departments.form')

        </div>
    </div>
    <br>

    <p>@lang('dictionary.registered_deps_listed_below_text')</p>

    <ul class="list-group">
        @forelse ($departments as $department)
            <a href="{{ action('DepartmentsController@show',[$department->id]) }}" class="list-group-item list-group-item-action col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <i class="fas fa-briefcase"></i>
                <b>{{ $department->name }}</b><br>
                <small>@lang('dictionary.department_address_text'): <b>{{ $department->address }}</b></small><br>
                <small>@lang('dictionary.department_contact_text'): <b>{{ $department->contact }}</b></small>
            </a>
        @empty
            <div class="alert alert-danger" role="alert">
                @lang('dictionary.no_departments_text')
            </div>
        @endforelse
    </ul>



@stop