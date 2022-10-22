@extends('layout.master')

@section('navbar-title') Data Anak @endsection
@section('content')

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="text-center h3">Edit Anak</h6>
                <form action="{{ route('orangtua-anak.update',$anak->id) }}" id="form-anak" class="forms-sample" method="post" nctype="multipart/form-data" files=true >
                    <input type="hidden" id="id" value="{{$anak->id}}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label @error('nama') is-invalid @enderror">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" value="{{$anak->nama}}"
                            placeholder="Nama">
                            @error('nama')
                               <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                    </div>

                        
                    <div class="mb-3">
                        <label class="col-md-12 col-sm-12 mb-2"> Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            
                            <input type="radio" class="form-check-input" value="laki-laki" name="jenis_kelamin"
                                id="radioInline" {{ ($anak->jenis_kelamin=="laki-laki")? "checked" : "" }} >
                                
                            <label class="form-check-label" for="radioInline">
                                Laki-Laki
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" value="perempuan" class="form-check-input" name="jenis_kelamin"
                                id="radioInline1" {{ ($anak->jenis_kelamin=="perempuan")? "checked" : "" }}>
                            <label class="form-check-label" for="radioInline1">
                                Perempuan
                            </label>
                        </div>
                    </div>

                    <div class="row col-md-10">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir"
                                    autocomplete="off" placeholder="Tempat Lahir" value="{{$anak->tempat_lahir}}">
                                    @error('tempat_lahir')
                               <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir"
                                    autocomplete="off" placeholder="masukkan tanggal lahir" value="{{$anak->tanggal_lahir->format('d/m/Y')}}">
                                    @error('tempat_lahir')
                               <div class="invalid-feedback">{{ $message }}</div>
                                 @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div style="float:right">
                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
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

        $("#form-anak").validate({
            rules: {
                nama: "required",   
                tempat_lahir:"required", 
                tanggal_lahir:"required", 
                jenis_kelamin:"required",   

             
            },
            messages: {
                nama: "Nama wajib diisi", 
                tempat_lahir: "Tempat lahir wajib diisi",
                tanggal_lahir: "Tanggal lahir wajib diisi",
                jenis_kelamin: "Jenis kelamin wajib diisi",



             
            },
             errorPlacement: function(error, element) 
    {
        if ( element.is(":radio") ) 
        {
            error.appendTo( element.parents('.gender') );
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


@endpush
