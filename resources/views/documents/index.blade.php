@extends('layouts.app')

@section('content')

    <style>
        .flex-parent {
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 30px 0;
        }
        .long-and-truncated {
            flex: 1;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
    {{--<h2>@lang('dictionary.doctypes_text')</h2>--}}
    {{--<a href="{{ url('/doctypes/create') }}">--}}
    {{--<button type="button" class="btn btn-info">+ @lang('dictionary.add_doctype_text')</button>--}}
    {{--</a>--}}
    <div class="card">
        <div class="card-header">
            @lang('dictionary.search_tool_text')
        </div>
        <div class="row input-group" style="padding:10px;margin-left:0;">
            <div class="row" style="width:100%;">
                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <label for="department">@lang('dictionary.search_by_department_label')</label>
                    <select id="department" class="form-control" name="department"><option value="" selected="selected">@lang('dictionary.select_department_placeholder')</option></select>
                </div>
                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <label for="doctype">@lang('dictionary.search_by_doctype_label')</label>
                    <select id="doctype" disabled class="form-control" name="doctype"><option value="" selected="selected">@lang('dictionary.select_doctype_placeholder')</option></select>
                </div>
                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <label for="number">@lang('dictionary.search_by_number_label')</label>
                    <input id="number" class="form-control" name="number" type="number" placeholder="@lang('dictionary.search_by_number_placeholder')">
                </div>
                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <label for="quick">@lang('dictionary.quick_search_label')</label>
                    <input id="quick" class="form-control" name="quick" type="text">
                </div>
                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="mydocs">
                        <label class="custom-control-label" for="mydocs">@lang('dictionary.only_my_docs_label')</label>
                    </div>
                </div>
                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="withfiles">
                        <label class="custom-control-label" for="withfiles">@lang('dictionary.only_with_files_label')</label>
                    </div>
                </div>
                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <button type="button" disabled id="specificField" class="btn btn-block btn-outline-secondary" data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                        @lang('dictionary.search_by_specific_field_button')
                    </button>
                </div>
            </div>
                <div class="collapse col-12" id="collapseForm">
                    <div class="card" style="margin-bottom:10px">

                        <div class="row" id="specificFields" style="background-color:oldlace;margin-right:0;margin-left:0">
                        <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                            <label for="quick">@lang('dictionary.quick_search_label')</label>
                            <input id="quick" class="form-control form-control-sm" name="quick" type="text">
                        </div>
                        </div>


                    </div>
                </div>
            <div class="row">
                <div class="col">
                    <div class="form-group" style="padding-left:15px;margin-bottom:0;">
                        <button type="button" id="search_button" class="btn btn-outline-success">@lang('dictionary.search_button')</button>
                        <button type="button" onclick="window.location.replace('{{ url('/documents') }}')" class="btn btn-outline-warning">@lang('dictionary.clear_search_tool_button')</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12" id="hidden_row_text" style="display: none;">
            <label for="fields_content"></label>
            <input id="specific_fields_content" class="form-control form-control-sm" type="text">
        </div>
        <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12" id="hidden_row_number" style="display: none;">
            <label for="fields_content"></label>
            <input id="specific_fields_content" class="form-control form-control-sm" type="number">
        </div>
        <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12" id="hidden_row_date" style="display: none;">
            <label for="fields_content"></label>
            <input id="specific_fields_content" class="form-control form-control-sm" type="date">
        </div>

    </div>
    <br>

    <div id="loading" style="display:none;position:absolute;left:0px;width:100%;height:100%;">
        <i class="fas fa-sync fa-spin fa-4x" style="text-align:center;display: inline-block;width: 100%;"></i>

    </div>
    <div id="search_result">


    </div>
    <br>


    <script>
        $(document).ready(function(){
            getAllDepartments();
            doSearch();
        });

        function getAllDepartments()
        {
            $.ajax({
                url: "{{ url('/getAllDepartments') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                success: function (result) {
                    $("#department").append(result);
                }
            });
        }

        function getDepartmentDoctypes()
        {
            $('#doctype').find('option').remove().end().append('<option value="">@lang("dictionary.select_doctype_placeholder")</option>').val("");
            $.ajax({
                url: '{{ url('/getDepartmentDoctypes') }}',
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'department': $('#department').val(),
                },
                success: function (result) {
                    $("#doctype").append(result);
                }
            });
        }

        $("#search_button").on("click", function(){
            doSearch();
        });

        $("#doctype").on("change", function(){
            if($("#doctype").val()=="")
            {
                $("#specificField").attr("disabled", true);
            }
            else {
                $.ajax({
                    url: "{{ url('/getDoctypeFields') }}",
                    method: "POST",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'id': $('#doctype').val(),
                    },
                    success: function (fields) {
                        $('#specificFields').empty();
                        for (var i = 0; i < fields.length; i++) {
                            if (fields[i].type == 0) {
                                $('#hidden_row_text').clone().appendTo('#specificFields');
                            }
                            if (fields[i].type == 1) {
                                $('#hidden_row_number').clone().appendTo('#specificFields');
                            }
                            if (fields[i].type == 2) {
                                $('#hidden_row_date').clone().appendTo('#specificFields');
                            }
                            var added_row = $("#specificFields div:last-of-type");
                            added_row.attr("id", 'added_row');
                            added_row.css("display", "");
                            added_row.children().html(fields[i].name);
                            $("#specificFields input:last-of-type").attr("name","specific_fields_content[]");
                        }
                    }
                });
                $("#specificField").attr("disabled", false);
            }
        });

        $("#department").on("change", function(){
            if($("#department").val()=="")
            {
                $("#doctype").val("");
                $("#doctype").attr("disabled", true);
            }
            else {
                $("#doctype").attr("disabled", false);
                getDepartmentDoctypes();
            }
        });



        function doSearch(page)
        {
            page = page || 1;
            var mydocs = 0;
            var withfiles = 0;
            var specificFields = $("input[name='specific_fields_content[]']").map(function(){return $(this).val();}).get();
            $("#search_result").empty();
            $("#loading").show();
            if ($('#mydocs').is(":checked")) mydocs = 1;
            if ($('#withfiles').is(":checked")) withfiles = 1;
            $.ajax({
                url: "{{ url('/getSearchResult') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'page': page,
                    'doctype': $('#doctype').val(),
                    'department': $('#department').val(),
                    'document_number': $('#number').val(),
                    'quick_search': $('#quick').val(),
                    'specific_fields': specificFields,
                    'mydocs': mydocs,
                    'withfiles': withfiles,
                },
                success: function (result) {
                    $("#loading").hide();
                    if(result == '0') {
                        $("#search_result").append("<div class=\"alert alert-danger\" role=\"alert\">\n" +
                            " @lang('dictionary.no_document_found')\n" +
                            "</div>");
                    } else {
                        $("#search_result").append(result);
                    }
                }
            });
        }


        $(".pagination").addClass("pagination-sm");
        $("ul.pagination > li").addClass("page-item");
        $("li.page-item > a").addClass("page-link");
        $("li.page-item > span").addClass("page-link");

        // $(".item").on( "mouseenter", function(){
        //     $(this).css("background","lightgray");
        // }).on( "mouseleave", function(){
        //     $(this).css("background","");
        // });
    </script>

@stop