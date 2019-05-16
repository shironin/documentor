@extends('layouts.app')

@section('content')
    <h1>@lang('dictionary.add_new_document_text')</h1>
    <hr>
    <script src="{{ asset('js/jspdf.min.js') }}"></script>

    <div class="alert alert-warning" role="alert" id="help">
        @lang('dictionary.first_select_doctype_helptext')
    </div>
    <br>


    <div class="form-group col-12" id="hidden_row_text" style="display: none;">
        <label for="fields_content"></label>
        <input id="fields_content" class="form-control" name="fields_content[]" type="text">
    </div>
    <div class="form-group col-12" id="hidden_row_number" style="display: none;">
        <label for="fields_content"></label>
        <input id="fields_content" class="form-control" name="fields_content[]" type="number">
    </div>
    <div class="form-group col-12" id="hidden_row_date" style="display: none;">
        <label for="fields_content"></label>
        <input id="fields_content" class="form-control" name="fields_content[]" type="date">
    </div>

    {!! Form::open(array('action' => "DocumentsController@storeDocument", 'files' => true)) !!}
    <input name="document_id" type="hidden" value="{{ $document->id }}"/>

    @php
        $doc_types = $doctypes->prepend(Lang::get('dictionary.select_doctype_placeholder'), '')->toArray();
    @endphp

    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group col-12">
                {!! Form::label('doctype', Lang::get('dictionary.select_user_to_send_text')) !!}
                {!! Form::select('doctype', $doc_types, null, ['id' => 'doctype', 'class' => 'form-control']) !!}
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div id="fields_area">

            </div>
        </div>
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div id="file_area" style="display: none;">
                <div class="form-group col-12" id="hidden_row_date" >
                    @include('documents.modalupload')
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="buttons_area" class="form-group col-12" style="display: none;">
        <button type="submit" class="btn btn-outline-success">@lang('dictionary.save_text')</button>
        <button type="button" onclick="cancelDoc()" class="btn btn-outline-danger">@lang('dictionary.cancel_text')</button>
    </div>


    {!! Form::close() !!}

    <script>

        $('#doctype').on('change',function(){
            if($('#doctype').val() !== '') {
                getDoctypeFields();
                $('#help').html("@lang('dictionary.fill_data_or_change_doctype_helptext')");
            }
        });

        function deleteFile()
        {
            $.ajax({
                url: "{{ url('/deleteDocumentFile') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': '{{ $document->id }}'
                },
                success: function (fields) {
                    $("#deleteFileButton").attr("disabled", true);
                    $("#headerModalLabel").html("@lang('dictionary.select_file_label')");
                    $("#addFileButton").html("@lang('dictionary.select_file_label')");
                }
            });
        }

        function cancelDoc()
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

        function getDoctypeFields()
        {
            $.ajax({
                url: "{{ url('/getDoctypeFields') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': $('#doctype').val(),
                },
                success: function (fields) {
                    $('#fields_area').empty();
                    for(var i=0; i < fields.length; i++) {

                        if(fields[i].type == 0) {
                            $('#hidden_row_text').clone().appendTo('#fields_area');
                        }
                        if(fields[i].type == 1) {
                            $('#hidden_row_number').clone().appendTo('#fields_area');
                        }
                        if(fields[i].type == 2) {
                            $('#hidden_row_date').clone().appendTo('#fields_area');
                        }
                        var added_row = $( "#fields_area div:last-of-type" );
                        added_row.attr("id",'added_row');
                        added_row.css("display","");
                        added_row.children().html(fields[i].name);
                        $("#buttons_area").css("display","");
                        $("#file_area").css("display","");
                    }
                    $("#fields_area").append('<div class="form-group col-12"><label for="comment">@lang("dictionary.comment_label")</label><textarea class="form-control" name="comment" id="comment" rows="3"></textarea></div>');
                }
            });
        }

        document.getElementById('pdf').addEventListener('change', getPdfData, false);

        function getPdfData(evt) {
            var f = evt.target.files[0]; // FileList object
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    var binaryData = e.target.result;
                    var base64String = window.btoa(binaryData);
                    sendPdf(base64String);
                };
            })(f);
            reader.readAsBinaryString(f);
        }

        // function PdfToBase64() {
        //     var selectedFile = document.getElementById("pdf").files;
        //     if (selectedFile.length > 0) {
        //         var fileToLoad = selectedFile[0];
        //         var fileReader = new FileReader();
        //         var base64;
        //         fileReader.onload = function(fileLoadedEvent) {
        //             base64 = fileLoadedEvent.target.result;
        //             sendPdf(base64);
        //         };
        //         fileReader.readAsDataURL(fileToLoad);
        //     }
        // }

        function getImageData(element) {
            var img = new Image();
            var file = element.files[0];
            var reader = new FileReader();
            img.onload = function() {
                sendImage(this.src,this.width,this.height);
            };
            reader.onloadend = function() {
                img.src = reader.result;
            };
            reader.readAsDataURL(file);
        }

        function sendPdf(pdf)
        {
            $('#fileModal').modal('toggle');
            $("#addFileButton").css("display","none");
            $("#loading").show();
            $.ajax({
                url: "{{ url('addPageToDocument') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': '{{ $document->id }}',
                    'file': pdf,
                },
                success: function (fields) {
                    $("#loading").hide();
                    $("#addFileButton").css("display","");
                    $('#pdf').replaceWith($('#pdf').val('').clone(true));
                    $("#headerModalLabel").html("@lang('dictionary.add_pages_to_this_doc')");
                    $("#addFileButton").html("@lang('dictionary.add_pages_to_this_doc')");
                    $("#deleteFileButton").attr("disabled", false);
                }
            });
        }

        function sendImage(img,width,height)
        {
            $('#fileModal').modal('toggle');
            $("#addFileButton").css("display","none");
            $("#loading").show();
            var doc = new jsPDF();
            if(height/(width/210)<297) {
                doc.addImage(img, 'JPEG', 0, 0, 209, Math.round(height/(width/210))-1);
            } else {
                doc.addImage(img, 'JPEG', 0, 0, Math.round(width/(height/297))-1, 296);
            }
            $.ajax({
                url: "{{ url('/addPageToDocument') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': '{{ $document->id }}',
                    'file': btoa(doc.output()),
                },
                success: function (fields) {
                    $("#loading").hide();
                    $("#addFileButton").css("display","");
                    $('#image').replaceWith($('#image').val('').clone(true));
                    $("#headerModalLabel").html("@lang('dictionary.add_pages_to_this_doc')");
                    $("#addFileButton").html("@lang('dictionary.add_pages_to_this_doc')");
                    $("#deleteFileButton").attr("disabled", false);
                }
            });
        }

    </script>


    @include('errors.list')

@stop