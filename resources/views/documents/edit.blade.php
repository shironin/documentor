@extends('layouts.app')

@section('content')
    <script src="{{ asset('js/jspdf.min.js') }}"></script>

    {!! Form::open(array('action' => "DocumentsController@editDocument", 'files' => true)) !!}
    <input name="document_id" type="hidden" value="{{ $document->id }}"/>

    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="padding-right:0;padding-left:0;">
                <div class="card-header">
                    @lang('dictionary.edit_doc_data_text')
                </div>
                <div id="fields_area" style="padding:10px">
                    <ul class="list-group">
                        @foreach ($document->fields as $field)
                            @if ($field->type === 0)
                                <div class="form-group" id="row_text">
                                    <label for="fields_content">{{ $field->name }}</label>
                                    <input value="{{ $field->pivot->value }}" id="fields_content" class="form-control" name="fields_content[]" type="text">
                                </div>
                            @endif
                            @if ($field->type === 1)
                                <div class="form-group" id="row_number">
                                    <label for="fields_content">{{ $field->name }}</label>
                                    <input value="{{ $field->pivot->value }}" id="fields_content" class="form-control" name="fields_content[]" type="number">
                                </div>
                            @endif
                            @if ($field->type === 2)
                                <div class="form-group" id="row_date">
                                    <label for="fields_content">{{ $field->name }}</label>
                                    <input value="{{ $field->pivot->value }}" id="fields_content" class="form-control" name="fields_content[]" type="date">
                                </div>
                            @endif
                        @endforeach
                        <div class="form-group">
                            <label for="comment">@lang("dictionary.comment_label")</label>
                            <textarea class="form-control" name="comment" id="comment" rows="3">{{ $document->comment }}</textarea>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div id="file_area"><br><br>
                <div class="form-group col-12" id="hidden_row_date" >
                    @include('documents.modalupload')
                </div>
            </div>
        </div>
    </div>

    <div id="buttons_area" class="form-group col-12">
        <br>
        <button type="submit" class="btn btn-outline-success">@lang('dictionary.save_text')</button>
        <button type="button" onclick="cancelDoc()" class="btn btn-outline-danger">@lang('dictionary.cancel_text')</button>
    </div>

    {!! Form::close() !!}



    <script>

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
            window.location.replace("{{ url('/documents/'.$document->id) }}");
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
                url: "{{ url('/addPageToDocument') }}",
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
                doc.addImage(img, 'JPEG', 0, 0, 210, height/(width/210));
            } else {
                doc.addImage(img, 'JPEG', 0, 0, width/(height/297), 297);
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

@stop