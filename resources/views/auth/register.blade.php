@extends('layouts.auth')

@section('title')
    Register
@endsection
@section('body')
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html">nano<b>DB</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Register a new user</p>
                <form method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <div class="form-group has-feedback">
                            <input id="name" type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @if ($errors->has('name'))
                                <strong class="text-danger">{{ $errors->first('name') }}</strong>
                            @endif
                    </div>

                    <div class="form-group has-feedback">
                        <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" autofocus>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if ($errors->has('email'))
                            <strong class="text-danger">{{ $errors->first('email') }}</strong>
                        @endif
                    </div>

                    <div class="form-group has-feedback">
                        <input name="password" type="password" class="form-control" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @if ($errors->has('password'))
                            <strong class="text-danger">{{ $errors->first('password') }}</strong>
                        @endif
                    </div>

                    <div class="form-group has-feedback">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                        <span class="glyphicon glyphicon glyphicon-ok form-control-feedback"></span>
                        @if ($errors->has('password-confirm'))
                            <strong class="text-danger">{{ $errors->first('password-confirm') }}</strong>
                        @endif
                    </div>

                    <div class="form-group">
                        {{--<div class="col-md-6 col-md-offset-4">--}}
                            <button type="submit" class="btn btn-primary btn-block">
                                Register
                            </button>
                        {{--</div>--}}
                    </div>
                </form>

        <div class="social-auth-links text-center">
            {{--<p>- OR -</p>--}}
            {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using--}}
            {{--Facebook</a>--}}
            {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using--}}
            {{--Google+</a>--}}
        </div>
        <!-- /.social-auth-links -->

        {{--<a href="{{ route('password.request') }}">I forgot my password</a><br>--}}
        {{--<a href="register.html" class="text-center">Register a new membership</a>--}}

    </div>
</div>
<script src="js/app.js"></script>
</body>

@endsection
