<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="NobleUI">
    <meta name="keywords"
        content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>DentistSmile - Register</title>

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
    <link rel="stylesheet" href="{{asset('assets/vendors/dropify/dist/dropify.min.css')}}">

    <link href="{{asset('select2/dist/css/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('select2/dist/css/select2-bootstrap4.min.css')}}">

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
    .btn-register{
      background: #56CFE1;

    }
    .auth-page{
      width: 75%
    }
    .form-control{
        border-radius: 50px;
        border: 2px solid #0000;
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
                <div class="row  mx-0 auth-page">
                    <div class="col-md-6 col-xl-6 mx-auto">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12 ps-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <div class="text-center">
                                            <img class="w-25 mb-3" src="{{asset('assets/images/logo-gemastik.png')}}"
                                                alt="" srcset="">
                                            @if(Session::has('error'))
                                            <div class="alert alert-warning">{{Session::get('error')}}</div>
                                            @endif
                                            <h4 class="text-secondary fw-normal mb-1">Daftar</h4>
                                            
                                        </div>
                                        <form class="forms-sample" action="{{route('registeruser')}}" method="POST"
                                            enctype="multipart/form-data" id="form-register" files=true>
                                            @csrf
                                            <div class="mb-3">
                                                <label for="userEmail" class="form-label">Email address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control form-css" id="userEmail" name="email"
                                                    placeholder="Masukkan Email" required>
                                                @error('email')
                                                <div class="badge bg-danger mt-2 ">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                                <div class="input-group mb-2 ">
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" id="password" placeholder="masukkan password">
                                                    <div style="background: transparent" class="input-group-prepend ml-2">
                                                        <div style="padding:10px"class="input-group-text"><i style="width: 100%" class="fas fa-eye-slash "
                                                                id="eye"></i></div>
                                                    </div>
                                                </div>
                                            <div class="mb-3">
                                                <label for="exampleInputUsername1" class="form-label">Nama <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" autocomplete="Name"
                                                    placeholder="Masukkan nama" name="nama" id="name"
                                                    value="{{old('nama')}}" required>

                                            </div>
                                            <div class="row col-md-12">
                                                <div class="col-md-7">
                                                    <div class="mb-3">
                                                        <label for="exampleInputPassword1" class="form-label">Tempat
                                                            Lahir <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="tempat_lahir"
                                                            name="tempat_lahir" autocomplete="off"
                                                            placeholder="Tempat Lahir" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="mb-3">
                                                        <label for="exampleInputPassword1" class="form-label">Tanggal
                                                            Lahir <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="tanggal_lahir"
                                                            name="tanggal_lahir" autocomplete="off"
                                                            placeholder="masukkan tanggal lahir" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                                <select class="form-select" name="id_kecamatan" id="id_kecamatan"
                                                    data-width="100%">
                                                    <option class="mb-2" value=" ">---Pilih Kecamatan---</option>
                                                    @foreach(\App\Models\Kecamatan::orderBy('nama','asc')->get() as
                                                    $value => $key)

                                                    <option value="{{$key->id}}">{{$key->nama}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kelurahan <span class="text-danger">*</span></label>
                                                <select class="form-select" name="id_kelurahan" data-width="100%"
                                                    id="id_desa">

                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputUsername2" class="form-label">alamat <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" autocomplete="alamat"
                                                    placeholder="Alamat" name="alamat" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Pendidikan <span class="text-danger">*</span></label>
                                                <select class="form-select" name="pendidikan" id="pendidikan"
                                                    data-width="100%" required>
                                                    <option selected disabled>Pilih Pendidikan</option>


                                                    <option value="SD">SD</option>
                                                    <option value="SMP">SMP</option>
                                                    <option value="SMA">SMA/SMK</option>
                                                    <option value="D1">D1</option>
                                                    <option value="D2">D2</option>
                                                    <option value="D3">D3</option>
                                                    <option value="D4">D4</option>
                                                    <option value="S1">S1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="S3">S3</option>


                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputPassword1" class="form-label">Foto Gigi
                                                    Orangtua</label>
                                                <input type="file" class="form-control dropify" name="foto"
                                                    placeholder="masukkan gambar">
                                                @error('foto')
                                                <div class="badge bg-danger mt-2 ">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div>
                                                <button type="submit"
                                                    class="btn btn-register w-100 text-white me-2 mb-2 mb-md-0">
                                                    Sign up
                                                </button>
                                            </div>
                                            <a href="/" class="d-block mt-3 text-muted">Sudah punya akun?
                                                login</a>
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

    <script src="{{asset('assets/vendors/core/core.js')}}"></script>
    <script src="{{asset('assets/vendors/dropify/dist/dropify.min.js')}}"></script>
    <script src="{{asset('assets/vendors/js/dropify.js')}}"></script>
    <script src="{{asset('select2/dist/js/select2.min.js')}}"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {


            var email = $('#userEmail');
            var password = $('#userPassword');
            var name = $('#name');

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

            $('#id_kecamatan').change(function () {
                let kecamatan = $("#id_kecamatan").val()
                $("#id_desa").children().remove();
                $("#id_desa").val('');
                $("#id_desa").append('<option value="">---Pilih Kelurahan---</option>');
                $("#id_desa").prop('disabled', true)
                if (kecamatan != '' && kecamatan != null) {
                    $.ajax({
                        url: "{{url('')}}/list-desa/" + kecamatan,
                        success: function (res) {
                            $("#id_desa").prop('disabled', false)
                            let tampilan_option = '';
                            $.each(res, function (index, desa) {
                                tampilan_option +=
                                    `<option value="${desa.id}">${desa.nama}</option>`
                            })
                            $("#id_desa").append(tampilan_option);
                        },
                    });
                }
            });
            $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop a file here or click',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Hapus',
                    'error': 'Ooops, something wrong happended.'
                }
            });

            $('#id_kecamatan').select2()
            $('#id_desa , #pendidikan').select2()

            $("#form-register").validate({
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
                    nama:"Nama tidak boleh kosong",
                    tempat_lahir:"Tempat lahir tidak boleh kosong",
                    tanggal_lahir:"Tanggal lahir tidak boleh kosong",
                    pendidikan:"pendidikan tidak boleh kosong",
                    alamat:"Alamat tidak boleh kosong", 

                 
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
