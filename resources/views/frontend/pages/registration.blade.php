



<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <!-- Title -->
        <title>Login</title>
        <link rel="shortcut icon" href="{{ asset('frontend') }}/images/webLogo/Logo/Bashundhara-Foundation-LOGO-MARK.png" />
        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../assets/plugins/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('frontend/login') }}/perfect-scrollbar.css" rel="stylesheet">

      
        <!-- Theme Styles -->
        <link href="{{ asset('frontend/login') }}/main.min.css" rel="stylesheet">
        <link href="{{ asset('frontend/login') }}/custom.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="login-page">        
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-12 col-lg-4">
                    <div class="card login-box-container">
                        <div class="card-body">
                            <div class="authent-logo">
                                <img  width="150" src="{{ asset('frontend') }}/images/webLogo/Logo/Bashundhara-Foundation.png" alt="" />
                            </div>
                            <div class="authent-text">                                
                                <p>Please Sign-in to your account.</p>
                            </div>
                            <form method="post" action="{{route('customersignUp')}}">
                                @csrf
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        <label for="floatingInput">Name</label>
                                      </div>
                                      @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        <label for="floatingInput">Email address</label>
                                      </div>
                                      @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                                        <label for="floatingInput">Phone</label>
                                      </div>
                                      @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-floating">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="floatingPassword">Password</label>
                                      </div>
                                </div>
                                <div class="d-grid">
                                <button type="submit" class="btn btn-info m-b-xs">Sign Up</button>                                
                            </div>
                              </form>
                              <div class="authent-reg">
                                  <p>Already registered? <a href="{{route('customerLoginPage')}}">Sing In</a></p>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        
        <!-- Javascripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script src="{{ asset('frontend/login') }}/perfect-scrollbar.min.js"></script>
        <script src="{{ asset('frontend/login') }}/main.min.js"></script>
    </body>
</html>


