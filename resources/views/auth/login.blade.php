<!DOCTYPE html>

<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="NobleUI">
    <meta name="keywords"
        content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>DentistSmile - Login </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/core/core.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/select2/select2.min.css')}}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('assets/fonts/feather-font/css/iconfont.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('assets/css/demo1/style.css')}}">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="{{asset('assets/images/logo-gemastik2.png')}}" />
</head>
<style>

    .error{
        color: red;
    }
    .page-content {
        background-image: url('{{ asset('assets/images/bg-app.jpeg')}}')
    }

    .btn-login {
        background: #56CFE1;

    }

    .form-control {
        border-radius: 50px;
        border: 2px solid #0000;
    }

    .auth-page {
        width: 65%
    }



    @media only screen and (max-width: 767px) {
      .auth-page{
        width: 100%
      }

    }

    @media only screen and (min-device-width:767px) and (max-device-width:960px){
      .auth-page{
        width: 100%
      }
    }

</style>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">

                <div class="row  mx-0 auth-page  ">
                    <div class="col-md-6 col-xl-6 mx-auto">
                        <div class="card shadow p-3 mb-5 bg-body rounded">
                            <div class="row ">
                                <div class="col-md-12 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-3">
                                        <div class="text-center">
                                            <img class="w-50 mb-3" src="{{asset('assets/images/logo-gemastik.png')}}" alt=""
                                                srcset="">
                                  
                                            <h4 class="text-muted fw-normal mb-1">Selamat datang!</h4>
                                            <h5 style="color:#32838F" class=" fw-normal mb-4">silahkan masuk untuk
                                                melanjutkan</h6>
                                                @if(Session::has('error'))
                                                <div class="alert alert-warning">{{Session::get('error')}}</div>
                                                @endif
                                        </div>
                                        <form class="forms-sample" action="{{route('login')}}" id="form-login" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="userEmail" class="form-label">Email</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{old('email')}}" id="userEmail"
                                                    placeholder="masukkan email">
                                                    
                                                @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- <div class="mb-3">
                                                <label for="userPassword" class="form-label">Password</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" id="userPassword" autocomplete="current-password"
                                                    placeholder="masukkan password">

                                                @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                <label class="form-check-label" for="exampleCheck1">
                                                    Tampilkan Password
                                                </label>
                                            </div> --}}
                                            <label for="password" class="form-label">Kata sandi</label>
                                            <div class="input-group mb-2 ">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" id="password" placeholder="masukkan password">
                                                <div style="background: transparent" class="input-group-prepend ml-2">
                                                    <div style="padding:10px"class="input-group-text"><i style="width: 100%" class="fas fa-eye-slash "
                                                            id="eye"></i></div>
                                                </div>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            <br>
                                            <div class="text-center">
                                                <button type="submit"
                                                    class="btn btn-login w-100 me-2 mb-2 mb-md-0 text-white">
                                                    Masuk
                                                </button>
                                            </div>
                                            {{-- <a href="/register" class="d-block mt-3 text-muted">Belum punya akun?
                                                Daftar</a> --}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- core:js -->
    <script src="{{asset('assets/vendors/core/core.js')}}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{asset('assets/vendors/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('assets/js/template.js')}}"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <!-- End custom js for this page -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#eye').click(function () {

                if ($(this).hasClass('fa-eye-slash')) {

                    $(this).removeClass('fa-eye-slash');

                    $(this).addClass('fa-eye');

                    $('#password').attr('type', 'text');

                } else {

                    $(this).removeClass('fa-eye');

                    $(this).addClass('fa-eye-slash');

                    $('#password').attr('type', 'password');
                }
            }); 

            $("#form-login").validate({
                rules: {
                    email: "required",                    
                    password: {
                        required: true,
                        
                    },
                  
                 
                },
                messages: {
                    email: "Email tidak boleh kosong",                   
                    password: {
                        required: "Password tidak boleh kosong",
                        
                    },
                 
                },
                 errorPlacement: function(error, element) 
            {

            
            if ( element.is(":radio") ) 
            {
                error.appendTo( element.parents('.form-group') );
            }
            else 
            { // This is the default behavior 
                error.insertAfter( element );
            }
         },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

        });

    </script>

</body>

</html>
