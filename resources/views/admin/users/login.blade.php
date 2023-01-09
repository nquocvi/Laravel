<!doctype html>
<html class="no-js" lang="">

<head>
    @include('admin.partials.styles')
</head>

<body class="bg-dark">

    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html">
                        <img class="align-content" src="/template/images/logo.png" alt="">
                    </a>
                </div>
                <div class="login-form">
                @include('admin.alert')
                    <form action ="/admin/users/login/store" method ="post">
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                            <label class="pull-right">
                                <a href="#">Forgotten Password?</a>
                            </label>

                        </div>
                        <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
                        <div class="register-link m-t-15 text-center">
                            <p>Don't have account ? <a href="/admin/users/register"> Sign Up Here</a></p>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.scripts')
</body>
</html>
