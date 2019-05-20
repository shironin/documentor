@extends('layouts.app')

@section('content')
    <h2>@lang('dictionary.report_bug_text')</h2>
    <hr>

    <div class="row col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding: 10px">

        <form>
            <div class="form-group col-12">
                <label for="theme">@lang('dictionary.bug_theme_label')</label>
                <input id="theme" class="form-control" name="theme" type="text">
            </div>
            <div class="form-group col-12">
                <label for="description">@lang('dictionary.describe_bug_label')</label>
                <textarea class="form-control" rows="3" name="description" cols="50" id="description"></textarea>
            </div>
            <div class="form-group col-12">
                <label for="file">@lang('dictionary.attache_file_label')</label>
                <input id="file" type="file">
            </div>
            <div class="form-group col-12">
                <input type="submit" class="btn btn-outline-success" value="@lang('dictionary.send_report_button')">
            </div>
        </form>

    </div>



@stop