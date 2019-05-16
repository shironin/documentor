@extends('layouts.app')

@section('content')
    <h2>@lang('dictionary.messages_text')</h2>
    <hr>
    <script src="{{ asset('js/jspdf.min.js') }}"></script>


    <ul class="list-group">
        @forelse ($messages as $message)
            @if ($message->status == 0)
                <a href="#" onclick="readMessage( {{ $message->id }}, {{ $message->doc_number }} )" class="list-group-item list-group-item-action col-lg-6 col-md-6 col-sm-12 col-xs-12" style="background-color: #f5c6cb">
                <i class="far fa-envelope"></i>
            @else
                <a href="#" onclick="readMessage( {{ $message->id }}, {{ $message->doc_number }} )" class="list-group-item list-group-item-action col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <i class="far fa-envelope-open"></i>
            @endif

                <b>{{ \App\Document::find($message->doc_number)->getDoctypeName() }} | @lang('dictionary.document_number_text'): {{ $message->doc_number }}</b><br>
                <small>
                    @lang('dictionary.received_from_text'): <b>{{ \App\User::find($message->sender_id)->name }}</b> | @lang('dictionary.date_text'): <b>{{ $message->time }}</b><br>
                    @lang('dictionary.message_text'): <b>{{ $message->message }}</b>
                </small>
            </a>
        @empty
            <div class="alert alert-warning" role="alert">
                @lang('dictionary.no_messages_yet_text')
            </div>
        @endforelse
    </ul>

    <script>

        function readMessage(id, doc)
        {
            $.ajax({
                url: "{{ url('/readMessage') }}",
                method: "POST",
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id,
                },
                success: function (data) {
                    window.location.replace("documents/"+doc);
                }
            });
        }

    </script>

@stop