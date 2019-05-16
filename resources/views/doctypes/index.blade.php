@extends('layouts.app')

@section('content')
    <h2>@lang('dictionary.doctypes_text')</h2>
    {{--<a href="{{ url('/doctypes/create') }}">--}}
        {{--<button type="button" class="btn btn-info">+ @lang('dictionary.add_doctype_text')</button>--}}
    {{--</a>--}}
    <p>
        <br>
        <button class="btn btn-outline-info" type="button" data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
            + @lang('dictionary.add_doctype_text')
        </button>
    </p>

    <div class="collapse" id="collapseForm">
        <div class="card card-body" style="background-color:oldlace;">

            @include('doctypes.form')


        </div>
    </div>
    <br>

    <p>@lang('dictionary.registered_doctypes_listed_below_text')</p>

    <ul class="list-group">
    @forelse ($doctypes as $doctype)
            <a href="{{ action('DoctypesController@show',[$doctype->id]) }}" class="list-group-item list-group-item-action col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <i class="fas fa-file-alt"></i>
                <b>{{ $doctype->name }}</b><br>
                <small>{{ $doctype->getDepartmentName() }} | @lang('dictionary.nr_of_fields_text'): {{ count($doctype->getFields()) }}</small>
            </a>
    @empty
            <div class="alert alert-danger" role="alert">
                @lang('dictionary.no_doctypes_text')
            </div>
    @endforelse
    </ul>

@stop