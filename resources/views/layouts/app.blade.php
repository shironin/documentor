<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Documentor</title>

    <!-- Fonts -->
    <link href="{{ asset('css/fontawesome_all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">


    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">

<!-- Navbar -->
<div class="nav-side-menu">
    <div class="brand">
        Documentor
        @if (Auth::check())
            <br>
            <small>
                <i class="fas fa-user"></i>&nbsp{{ Auth::user()->name }}
                <select id="lang">
                    @if(app()->getLocale() == 'en')
                        <option value="en" selected>EN</option>
                        <option value="ro">RO</option>
                    @else
                        <option value="en">EN</option>
                        <option value="ro" selected>RO</option>
                    @endif
                </select>
            </small>
        @endif
    </div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
    <div class="menu-list">
        <ul id="menu-content" class="menu-content collapse out">
            <li>
                <a href="{{ url('/') }}" style="display:block">
                    <i class="fas fa-home fa-lg"></i>
                    @lang('dictionary.homepage_text')
                </a>
            </li>
            <li>
                <a href="{{ url('/documents/create') }}" style="display:block">
                    <i class="fas fa-plus-square fa-lg"></i>
                    @lang('dictionary.add_new_doc_text')
                </a>
            </li>
            <li>
                <a href="{{ url('/documents') }}" style="display:block">
                    <i class="fas fa-search fa-lg"></i>
                    @lang('dictionary.search_documents_text')
                </a>
            </li>
            {{--@if (Auth::check())--}}
                {{--<li data-toggle="collapse" data-target="#user_menu" class="collapsed">--}}
                    {{--<a href="#">--}}
                        {{--<i class="fa fa-user fa-lg"></i>--}}
                         {{--<i class="fas fa-sort-down" style="float: right;"></i>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--<ul class="sub-menu collapse" id="user_menu">--}}
                    {{--<li><a href="#">@lang('dictionary.my_profile_text')</a></li>--}}
                    {{--<li><a href="{{ url('/logout') }}">@lang('dictionary.log_out_text')</a></li>--}}
                {{--</ul>--}}
            {{--@else--}}
                {{--<li>--}}
                    {{--<a href="{{ url('/login') }}">--}}
                        {{--<i class="fa fa-dashboard fa-lg"></i> @lang('dictionary.log_in_text')--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                    {{--<a href="{{ url('/register') }}">--}}
                        {{--<i class="fa fa-dashboard fa-lg"></i> @lang('dictionary.register_text')--}}
                    {{--</a>--}}
                {{--</li>--}}
            {{--@endif--}}

            <li>
                <a href="{{ url('/doctypes') }}" style="display:block">
                    <i class="fas fa-file-alt fa-lg"></i>
                    @lang('dictionary.document_types_text')
                </a>
            </li>
            <li>
                <a href="{{ url('/departments') }}" style="display:block">
                    <i class="fas fa-briefcase fa-lg"></i>
                    @lang('dictionary.departments_text')
                </a>
            </li>
            <li>
                <a href="{{ url('/messages') }}" style="display:block">
                    <i class="far fa-comments fa-lg"></i>
                    @lang('dictionary.messages_text')
                    @if (Auth::check() && Auth::user()->checkMessages() > 0)
                        <span class="badge badge-light">{{ Auth::user()->checkMessages() }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ url('/statistics') }}" style="display:block">
                    <i class="fas fa-chart-bar fa-lg"></i>
                    @lang('dictionary.statistics_text')
                </a>
            </li>
            <li>
                <a href="{{ url('/faq') }}" style="display:block">
                    <i class="fas fa-question-circle fa-lg"></i>
                    @lang('dictionary.faq_text')
                </a>
            </li>
            <li>
                <a href="{{ url('/report') }}" style="display:block">
                    <i class="fas fa-bug fa-lg"></i>
                    @lang('dictionary.report_bug_text')
                </a>
            </li>
            <li>
                <a href="{{ url('/logout') }}" style="display:block">
                    <i class="fas fa-sign-out-alt"></i>
                    @lang('dictionary.log_out_text')
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="container" id="main">
    <div class="row">
        <div class="col-md-12">
            <br>
            @yield('content')
        </div>
    </div>
</div>
<br><br><br>



</body>
<script>

    $('#lang').on('change',function () {
        $.ajax({
            url: "{{ url('/setLanguage') }}",
            method: "POST",
            data: {
                '_token': '{{ csrf_token() }}',
                'locale': $("#lang").val(),
            },
            success: function (success) {
                window.location.reload();
            }
        });
    })

</script>
</html>
