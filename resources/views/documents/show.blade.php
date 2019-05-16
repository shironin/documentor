@extends('layouts.app')

@section('content')
    <h2>@lang('dictionary.document_number_text'): <b>{{ $document->id }}</b></h2><br>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
            <div class="card" style="padding-right:0;padding-left:0;">
                <div class="card-header">
                    @lang('dictionary.action_bar_text')
                </div>
                <div class="btn-group" role="group" aria-label="actionBar">
                    <a href="{{ action('DocumentsController@edit',[$document->id]) }}" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.edit_document_tooltip')">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if ($document->file != '')
                        <a href="" id="downloadFile" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.download_document_tooltip')" download="{{ $document->file }}">
                            <i class="fas fa-file-download"></i>
                        </a>
                        <a href="" id="makeOcr" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.make_ocr_document_tooltip')">
                            <i id="wait_while_ocr" class="fas fa-eye"></i>
                        </a>
                    @else
                        <a href="" id="downloadFile" class="btn btn-outline-primary disabled" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.no_file_document_tooltip')">
                            <i class="fas fa-file-download"></i>
                        </a>
                        <a href="" id="makeOcr" class="btn btn-outline-primary disabled" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.no_file_document_tooltip')">
                            <i class="fas fa-eye"></i>
                        </a>
                    @endif
                    <button type="button" id="sendDoc" class="btn btn-outline-primary" data-toggle="modal" data-target="#sendModal" data-placement="top" title="@lang('dictionary.send_to_user_document_tooltip')">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary"  data-toggle="tooltip" data-placement="top" title="@lang('dictionary.send_via_email_document_tooltip')"><i class="fas fa-at"></i></button>
                    <button type="button" class="btn btn-outline-primary" onclick="getDocActivity()" data-toggle="tooltip" data-placement="top" title="@lang('dictionary.view_history_of_document_tooltip')"><i class="fas fa-history"></i></button>
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal" data-placement="top" title="@lang('dictionary.delete_document_tooltip')"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
            <div class="card" style="padding-right:0;padding-left:0;">
                <div class="card-header">
                    @lang('dictionary.document_file_header')
                </div>
                    <div class="btn-group" role="group" aria-label="openFile">
                        @if ($document->file != '')
                            <button type="button" class="btn btn-outline-primary" onclick="getDocFile()"  data-toggle="tooltip" data-placement="top" title="@lang('dictionary.open_file_document_tooltip')">
                                @lang('dictionary.open_file_button')
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-primary" disabled>
                                @lang('dictionary.no_file_button')
                            </button>
                        @endif
                    </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
            <div class="card" style="padding-right:0;padding-left:0;">
                <div class="card-header">
                    @lang('dictionary.document_content_text')
                </div>
                <table class="table">
                    @foreach ($document->fields as $field)
                        <tr class="content_row">
                            <td><small>{{ $field->name }}:</small></td>
                            <td>{{ $field->pivot->value }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
            <div class="card" style="padding-right:0;padding-left:0;">
                <div class="card-header">
                    @lang('dictionary.document_info_text')
                </div>
                <table class="table">
                    <tr class="content_row">
                        <td><small>@lang('dictionary.document_type_text'):</small></td>
                        <td>{{ $document->getDoctypeName() }}</td>
                    </tr>
                    <tr class="content_row">
                        <td><small>@lang('dictionary.document_department_text'):</small></td>
                        <td>{{ $document->getDepartmentName() }}</td>
                    </tr>
                    <tr class="content_row">
                        <td><small>@lang('dictionary.document_created_text'):</small></td>
                        <td>{{ $document->created_at }}</td>
                    </tr>
                    <tr class="content_row">
                        <td><small>@lang('dictionary.document_last_update_text'):</small></td>
                        <td>{{ $document->updated_at }}</td>
                    </tr>
                    <tr class="content_row">
                        <td><small>@lang('dictionary.document_user_text'):</small></td>
                        <td>{{ $document->getUserName() }}</td>
                    </tr>
                    <tr class="content_row">
                        <td><small>@lang('dictionary.ocr_status_text'):</small></td>
                        <td id="ocrStatus">
                            @if ($document->ocr_status != '')
                                <i class="fas fa-check-circle"></i>
                            @endif
                        </td>
                    </tr>
                    <tr class="content_row">
                        <td><small>@lang('dictionary.comment_text'):</small></td>
                        <td>{{ $document->comment }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

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
                    <button type="button" onclick="deleteDoc()" class="btn btn-outline-danger">@lang('dictionary.yes_button')</button>
                    <button type="button" data-toggle="modal" data-target="#deleteModal" class="btn btn-outline-primary">@lang('dictionary.cancel_text')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $document->file }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="fileView">
                    {{--<iframe src=""></iframe>--}}
                    {{--<object id="fileView" type="application/pdf" data="" width="100%" height="100%">No Support</object>--}}
                    <div id="loading" style="display:none;position:absolute;left:0px;width:100%;height:100%;">
                        <i class="fas fa-sync fa-spin fa-4x" style="text-align:center;display: inline-block;width: 100%;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('dictionary.send_doc_text')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="send_body">
                    <div class="form-group col-12">
                        {!! Form::label('user_to_send', Lang::get('dictionary.select_user_to_send_text')) !!}
                        {!! Form::select('user_to_send[]', $users, null, ['id' => 'user_to_send', 'class' => 'form-control', 'multiple']) !!}
                    </div>
                    <div class="form-group col-12">
                        <label for="message">@lang("dictionary.message_label")</label>
                        <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer" id="send_footer">
                    <button type="button" onclick="sendDoc()" class="btn btn-outline-success">@lang('dictionary.send_button')</button>
                    <button type="button" data-toggle="modal" data-target="#sendModal" class="btn btn-outline-danger">@lang('dictionary.cancel_text')</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="activityModal" tabindex="-1" role="dialog" aria-labelledby="activityModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('dictionary.last_activity_text')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="activityResult">
                </div>
            </div>
        </div>
    </div>

    <script>

        var docFile = "";

        function setFile(data)
        {
            docFile = data;
        }

        $(".content_row").on( "mouseenter", function(){
            $(this).css("background","lightgray");
        }).on( "mouseleave", function(){
            $(this).css("background","");
        });

        @if ($document->file != '')

        $(document).ready(function(){
            $.ajax({
                url: "{{ url('/getDocumentFile') }}",
                method: "GET",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': '{{ $document->id }}',
                },
                success: function (data) {
                    $("#downloadFile").attr("href","data:application/pdf;base64,"+data);
                    setFile(data);
                }
            });
        });

        function getDocFile() {
            $('#fileModal').modal('show');
            $("#loading").show();
            $("#fileView").empty();
            var obj = document.createElement('object');
            obj.style.width = '100%';
            obj.style.height = $(window).height()-300+'pt';
            obj.type = 'application/pdf';
            obj.data = 'data:application/pdf;base64,' + docFile;
            $("#fileView").append(obj);
            $("#loading").hide();
        }

        $("#makeOcr").on("click",function (e) {
            e.preventDefault();
            $("#wait_while_ocr").removeClass("fa-eye").addClass("fa-sync fa-spin");
            $.ajax({
                url: "{{ url('/makeOcrforDocument') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'file': docFile,
                    'id': '{{ $document->id }}',
                },
                success: function (fields) {
                    $("#wait_while_ocr").removeClass("fa-sync fa-spin").addClass("fa-eye");
                    $("#ocrStatus").empty().html('<i class="fas fa-check-circle"></i>');
                }
            });
        });
        @endif

        function deleteDoc()
        {
            $.ajax({
                url: "{{ url('/documents/'.$document->id) }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'DELETE',
                    'id': '{{ $document->id }}',
                },
                success: function (fields) {
                    window.location.replace("{{ url('/documents') }}");
                }
            });
        }

        function getDocActivity() {
            $("#activityResult").empty();
            $('#activityModal').modal('show');
            $.ajax({
                url: "{{ url('/getDocumentActivity') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': '{{ $document->id }}'
                },
                success: function (result) {
                    $("#activityResult").html(result);
                }
            });
        }

        function sendDoc()
        {
            $.ajax({
                url: "{{ url('/sendDocument') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': '{{ $document->id }}',
                    'user': $('#user_to_send').val(),
                    'message': $.trim($("#message").val())
                },
                success: function (result) {
                    $("#send_footer").empty();
                    $("#send_body").empty();
                    $("#send_body").html(result);
                    setTimeout(function(){
                        $('#sendModal').modal('toggle');
                    }, 1000);
                    $('#sendDoc').attr("disabled", true);
                }
            });
        }

    </script>

@stop