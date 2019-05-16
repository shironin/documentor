@if (Auth::check())

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
            <br><small><i class="fas fa-user"></i>&nbsp{{ Auth::user()->name }}</small>
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
                    @if (Auth::user()->checkMessages() > 0)
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

</script>
</html>



@else

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Documentor</title>

    <!-- Fonts -->
    <link href="{{ asset('css/fontawesome_all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">


    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    <style>
        body {
            font-family: 'Lato';
        }
        .box {
            margin: 0 auto;
        }

    </style>
</head>
<br>
<p style="text-align: center">
    <a class="btn btn-outline-primary btn-lg" href="{{ url('/login') }}">
        <i class="fas fa-user fa-lg"></i>
        @lang('dictionary.you_need_to_login_text')
    </a>
</p>

<br>
<div class="container" style="text-align: center">
    <h3>@lang('dictionary.about_documentor_text')</h3>
</div>
<br>
<div class="row" style="margin:10px;">

    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: mediumseagreen;">
        <div class="card" style="border: 1px solid mediumseagreen;">
            <div class="card-body" style="text-align: center">
                <i class="fas fa-file-medical fa-6x"></i>&nbsp&nbsp&nbsp&nbsp
                <i class="fas fa-file-signature fa-6x"></i>
                <i class="fas fa-trash-alt fa-6x"></i>
            </div>
            <div class="card-footer" style="text-align: center"><b>@lang('dictionary.create_edit_delete_doc_text')</b></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: darkslateblue;">
        <div class="card" style="border: 1px solid darkslateblue;">
            <div class="card-body" style="text-align: center">
                <i class="fas fa-folder fa-6x"></i>
            </div>
            <div class="card-footer" style="text-align: center"><b>@lang('dictionary.docs_classification_text')</b></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: cadetblue;">
        <div class="card" style="border: 1px solid cadetblue;">
            <div class="card-body" style="text-align: center">
                <i class="fas fa-paperclip fa-6x"></i>
            </div>
            <div class="card-footer" style="text-align: center"><b>@lang('dictionary.attach_files_to_docs_text')</b></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: coral;">
        <div class="card" style="border: 1px solid coral;">
            <div class="card-body" style="text-align: center">
                <i class="fas fa-search fa-6x"></i>
            </div>
            <div class="card-footer" style="text-align: center"><b>@lang('dictionary.advanced_search_text')</b></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: darkgoldenrod;">
        <div class="card" style="border: 1px solid darkgoldenrod;">
            <div class="card-body" style="text-align: center">
                <i class="fas fa-user-friends fa-6x"></i>
            </div>
            <div class="card-footer" style="text-align: center"><b>@lang('dictionary.access_control_text')</b></div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12" style="padding-bottom: 15px; color: crimson;">
        <div class="card" style="border: 1px solid crimson;">
            <div class="card-body" style="text-align: center">
                <i class="far fa-eye fa-6x"></i>
            </div>
            <div class="card-footer" style="text-align: center;padding-left: 0;padding-right: 0;"><b>@lang('dictionary.ocr_implementation_text')</b></div>
        </div>
    </div>



</div>
</html>

@endif