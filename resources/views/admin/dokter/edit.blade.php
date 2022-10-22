@extends('layout.master')

@section('content')

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="text-center h3">Edit Dokter</h6>
                <form action="{{ route('dokter.update',$dokter->id) }}" class="forms-sample" id="dokter-update"
                    method="post" nctype="multipart/form-data" files=true>
                    <input type="hidden" id="id" value="{{$dokter->id}}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                            value="{{$dokter->user->email}}">
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="chk" type="reset" value="Reset">
                            <label class="form-check-label" id="labelchk" for="formSwitch1">Tidak mengubah Password</label>
                        </div>
                    </div>
                    <div id="ubah_password">

                        </div>

                        <div class="form-check mb-2" id="show_password">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">
                                Show Password
                            </label>
                        </div>
                    
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">NIK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nik" name="nik" autocomplete="off"
                            value="{{$dokter->nik}}" placeholder="NIK">
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off"
                            value="{{$dokter->nama}}" placeholder="Nama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Wilayah <span class="text-danger">*</span></label>
                        <select class="js-example-basic-single form-select" name="wilayah" data-width="100%">
                            <option selected disabled class="mb-2" value=" ">Pilih Kecamatan</option>
                            @foreach(\App\Models\Kecamatan::orderBy('nama','asc')->get() as $value => $key)
                            <option class="mb-2" value="{{$key->id}}"
                                {{$key->id == $dokter->id_kecamatan ? 'selected' : ''}}>{{$key->nama}}</option>
                            @endforeach
                        </select>
                        @error('id_kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                            autocomplete="off" value="{{$dokter->jenis_kelamin}}" placeholder="Nama">
                    </div>
                    <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="exampleInputPassword1" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" autocomplete="off"
                            value="{{$dokter->tempat_lahir}}" placeholder="Tempat Lahir">
                    </div>
                    <div class="col-md-6">
                        <label for="exampleInputPassword1" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal_lahir" autocomplete="off"
                            value="{{$dokter->tanggal_lahir}}" placeholder="Tempat Lahir">
                    </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">No Hp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" autocomplete="off"
                            value="{{$dokter->no_telp}}" placeholder="Tempat Lahir">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">No Str <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_str" name="no_str" autocomplete="off"
                            value="{{$dokter->no_str}}" placeholder="no_str">
                    </div>
                    <div style="float: right">
                    <button type="submit" class="btn btn-primary me-2">Ubah</button>
                    <a href="{{URL::previous()}}" type="button" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#exampleCheck1').click(function () {
            if ($(this).is(':checked')) {
                $('#password').attr('type', 'text');
            } else {
                $('#password').attr('type', 'password');
            }
        });
        $('#show_password').hide();
        $('#chk').on('change', function () {
            if ($(this).is(':checked')) {
                var html = `<div class="mb-3" id="pss">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="off"
                                value="" placeholder="Password">
                        </div>`
                $('#ubah_password').append(html);
                $('#show_password').show();
                
                $('#labelchk').text('Ubah Password');
              


            } else {

                $('#show_password').hide();
                $('#pss').remove();
                $('#labelchk').text('Tidak mengubah password');
            }
        });
    });

</script>



@endpush
