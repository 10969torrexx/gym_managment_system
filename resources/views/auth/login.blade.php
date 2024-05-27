<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Gym Shark</title>
<link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="javascript::void(0)" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <h3><strong>Gym Shark</strong></h3>
                </a>
                <p class="text-center">{{ config('const.sub_name') }}</p>
                <div class="p-1" id="message-alert"></div>
                <div id="g_id_onload" data-client_id="{{env('GOOGLE_CLIENT_ID')}}" data-callback="onSignIn"></div>
                <div class="g_id_signin form-control" data-type="standard"></div>
                <form action="" method="POST">
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <button type="button" id="btnLogin" class="btn btn-primary w-100 py-8 fs-4 mb-1 rounded-2">Sign In</button>
                  <div class="p-1 text-danger mb-2" id="message_attempt">
                  </div>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">New to Modernize?</p>
                    <a class="text-primary fw-bold ms-2" href="{{ route('register') }}">Create an account</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src = "https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        function decodeJwtResponse(token){
            let base64url = token.split('.')[1];
            let base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
            let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) { 
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
            return JSON.parse(jsonPayload);

        }
        window.onSignIn = googleUser =>{
            var user = decodeJwtResponse(googleUser.credential);
            if(user){
                $.ajaxSetup({
                    headers: {  'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') }
                });

                $.ajax({
                    url: `{{ route('googleLogin') }}`,
                    method: 'POST',
                    data: {
                        email: user.email
                    },
                    beforeSend: function(){
                        $('#btnLogin').html("REDIRECTING...").prop("disabled", true);
                    },
                    success:function(response){
                        switch (response.status) {
                            case 200:
                                window.location.href = '/home';
                                break;
                            case 500:
                                $('#message-alert').html(' ');
                                $('#message-alert').append(`
                                    <div class="alert alert-danger" role="alert">
                                        ${response.message}
                                    </div>
                                `);
                                $('#btnLogin').html("Sign In").prop("disabled", false);
                                break;
                            default:
                                break;
                        }
                    },
                    error:function(xhr, status, error){
                    alert(xhr.responseJSON.message);
                    }
                });
            }
        }

        var loginAttempts = 3
        $('#btnLogin').on('click', function() {
            var email = $('#email').val();
            var password = $('#password').val();

            $.ajaxSetup({
                headers: {  'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') }
            });
            $.ajax({
                url: `{{ route('usersLogin') }}`,
                method: 'POST',
                data: {
                    email: email,
                    password: password
                },
                beforeSend: function(){
                    $('#btnLogin').html("LOADING...").prop("disabled", true);
                },
                success:function(response){
                    if(response.status == 200) {
                        $('#message-alert').html(' ');
                        $('#message-alert').append(`
                            <div class="alert alert-success" role="alert">
                                ${response.message}
                            </div>
                        `);
                        setTimeout(function() {
                            $('#btnLogin').html("Login").prop("disabled", false);
                            window.location.href ="/home";
                        }, 2000);
                    }
                    if (response.status == 300) {
                        $('#btnLogin').html("Login").prop("disabled", false);
                        $('#message-alert').html(' ');
                        $('#message-alert').append(`
                            <div class="alert alert-danger" role="alert">
                                ${response.message}
                            </div>
                        `);
                        loginAttempts--;
                        if (loginAttempts > 0) {
                            console.log("Remaining login attempts: " + loginAttempts);
                            $('#message_attempt').html("Remaining login attempts: " + loginAttempts);
                        } else {
                            console.log("No remaining login attempts. Please try again later.");
                            $('#loginbutton').prop("disabled", true); // Disable the login button
                        }
                    }
                },
                error:function(xhr, status, error){
                    var countdown = 60;
                    var intervalId = setInterval(function() {
                        if(countdown <= 0) {
                            clearInterval(intervalId);
                            loginAttempts = 5;
                            $('#message_attempt').html("");
                            $('#btnLogin').html("Login").prop("disabled", false);
                        } else {
                            $('#btnLogin').html("Please wait").prop("disabled", true);
                            countdown--;
                            $('#message_attempt').html(`Too many login attempts. Please try again in ${countdown} seconds`);
                        }
                    }, 1000);
                }
            });
        });
    </script>
</body>

</html>