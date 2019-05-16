<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@lang('dictionary.reset_password_text')</title>

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


<div class="container  col-lg-4 col-md-8 col-sm-12 col-xs-12">
    <br><br><br>
    <div class="card box" style="padding:0">
        <div class="card-header">Documentor | @lang('dictionary.reset_password_text')</div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-12 control-label">@lang('dictionary.email_address_text')</label>

                    <div class="col-md-12">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-md-offset-4">
                        <button type="submit" class="btn btn-outline-primary">
                            @lang('dictionary.send_pass_reset_link_text')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <a class="btn btn-link" href="{{ url('/') }}">@lang('dictionary.home_button')</a>
</div>

</html>

