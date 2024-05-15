<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modernize Free</title>
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
                <div id="g_id_onload" data-client_id="{{env('GOOGLE_CLIENT_ID')}}" data-callback="onSignIn"></div>
                <div class="g_id_signin form-control" data-type="standard"></div>
                <form action="{{ route('login') }}" >
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1">
                  </div>
                  <button href="./index.html" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
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
                    data: {email: user.email},
                    beforeSend: function(){
                        $('#btnLogin').html("REDIRECTING...").prop("disabled", true);
                    },
                    success:function(response){
                        console.log(response);
                        if (response.status != 200) {
                            $('#message-alert').html(' ');
                            $('#message-alert').append(`
                                <div class="alert alert-danger" role="alert">
                                    ${response.message}
                                </div>
                            `);
                            $('#btnLogin').html("Sign In").prop("disabled", false);
                        }
                    },
                    error:function(xhr, status, error){
                    alert(xhr.responseJSON.message);
                    }
                });
            }
        }
    </script>
</body>

</html>