@extends('layouts.app')

@section('content')
    <h2>@lang('dictionary.frequently_asked_questions_text')</h2>
    <hr>

    <div class="row" style="padding: 10px">


        <button class="btn btn-outline-info btn-block" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1" style="text-align: left">
            Some FAQ
        </button>
        <div class="collapse" id="collapse1">
            <div class="card card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
            </div>
        </div>
        <button class="btn btn-outline-info btn-block" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2" style="text-align: left">
            Some FAQ 2
        </button>
        <div class="collapse" id="collapse2">
            <div class="card card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
            </div>
        </div>
        <button class="btn btn-outline-info btn-block" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3" style="text-align: left">
            Some FAQ 3
        </button>
        <div class="collapse" id="collapse3">
            <div class="card card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
            </div>
        </div>

    </div>



@stop