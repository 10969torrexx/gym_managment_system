<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>One Time Password - Gym Shark</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="/assets/css/styles.min.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
                    <h3><strong>{{ config('const.app_name') }}</strong></h3>
                </a>
                    <div class="" id="message">
                       
                    </div>
                    <div class="mb-3">
                        <p class="text-left text-wrap">We've sent an <strong>OTP (One Time Password)</strong> to <strong>{{ $email }}</strong>. Please enter the OTP to verify your email.
                        Please check the spam folder if you don't find the email in your inbox.</p>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputtext1" class="form-label">One Time Password</label>
                        <input id="email_otp" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" id="submit_otp_button">Submit</button>
                    <div class="d-flex align-items-center justify-content-center">
                        <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                        <a class="text-primary fw-bold ms-2" href="{{ route('login') }}">Sign In</a>
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
  <script>
    $.ajaxSetup({
        headers: {  'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') }
    });
    $(document).ready(function () {
        $('#message').html(' ');
        $("#submit_otp_button").click(function () {
            var email_otp = $("#email_otp").val();
            var email = "{{ $email }}";
            console.log(email_otp);
            $.ajax({
                url: `{{ route('OtpVerify') }}`,
                method: 'POST',
                data: {
                    email: email,
                    otp: email_otp
                },
                success:function(response){
                    console.log(response);
                    if(response.status == 200) {
                        window.location.href = '/home';
                    }
                    if (response.status == 500) {
                        $('#message').html(`<div class="alert alert-danger" role="alert">${response.message}</div>`)
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 429) {
                        $('#message').html(`<div class="alert alert-danger" role="alert">You have exceeded the rate limit. Please wait a moment and try again.</div>`)
                    }
                }
            });
      });
    });
  </script>
</body>

</html>