<div id="loading" style="display:none;position:absolute;left:0px;width:100%;height:100%;">
    <i class="fas fa-sync fa-spin fa-4x" style="text-align:center;display: inline-block;width: 100%;"></i>
</div>

@if ($document->file != '')
    <button type="button" onclick="deleteFile()" class="btn btn-outline-danger" id="deleteFileButton">@lang('dictionary.delete_doc_file_button')</button>
    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#fileModal" id="addFileButton">
        @lang('dictionary.add_pages_to_this_doc')
    </button>
@else
    <button type="button" disabled onclick="deleteFile()" class="btn btn-outline-danger" id="deleteFileButton">@lang('dictionary.delete_doc_file_button')</button>
    <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#fileModal" id="addFileButton">
        @lang('dictionary.select_file_label')
    </button>
@endif

<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headerModalLabel">
                    @if ($document->file != '')
                        @lang('dictionary.add_pages_to_this_doc')
                    @else
                        @lang('dictionary.select_file_label')
                    @endif
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="image">@lang('dictionary.image_label_for_filetype')</label>
                <input type="file" name="image" id="image" onchange="getImageData(this)" class="form-control-file" accept=".jpg,.jpeg,.png,.gif"><br>
                <label for="pdf">@lang('dictionary.pdf_label_for_filetype')</label>
                <input type="file" name="pdf" id="pdf" class="form-control-file" accept=".pdf">
            </div>
        </div>
    </div>
</div>