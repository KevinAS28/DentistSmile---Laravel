@extends('layout.master')

@section('navbar-title') Data Anak @endsection
@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-10 col-sm-6">
               	<div class="text-center">
								<h4 class="mb-0">Data Anak</h4>
							</div>
            </div>
            <div class="col-md-2 col-sm-6">
            <a href="{{route('view-anak.create')}}" type="button" id="btn-create"  class="btn btn-custom text-white">Tambah data</a>
            </div>
        </div>
        <hr />
			<div class="table-responsive">
            <table id="table-anak" class="table " >
                <thead>
                    <tr>
						<th>id</th>
                        <th style="width: 1px;">no</th>
                        <th>Nama Anak</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('after-script')

<script  type="text/javascript"> 
var tableData;

$(document).ready(function () {
        tableData = $('#table-anak').DataTable({
            processing: true,
			serverSide: true,
            responsive: true,
            language: {
                "lengthMenu": "Tampilkan _MENU_ entri",
                "paginate":{
                    "next":"Selanjutnya",
                    "previous":"Sebelumnya"
                },
                search: "_INPUT_",
                searchPlaceholder: "Cari"
            },
			"searching": true,
            "bPaginate": true,
            serverSide: true,
            stateSave: true,
            ajax: {
                url: "{{ url('orangtua/table/data-anak') }}",
                type: "GET",
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false
                },
				{
					data: 'DT_RowIndex', name:'DT_RowIndex', visible:true
				},

                {
                    data: 'nama',
                    name: 'nama',
                    visible: true
                },
                {
                    data: 'jenis_kelamin',
                    name: 'jenis_kelamin',
                    visible: true
                },
                {
                    data: 'tanggal_lahir',
                    name: 'tanggal_lahir',
                    visible: true
                },
                 { data: 'action', name:'action', visible:true},

            ],

        });

        $('#table-anak tbody').on( 'click', '#btn-delete', function () {
        var data = tableData.row( $(this).parents('tr') ).data();
       Swal.fire({
            title: 'Harap Konfirmasi',
            text: "Anda tidak dapat mengembalikan data yang telah dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Lanjutkan'
        }).then((willDelete) => {
          if (willDelete.isConfirmed) {
            $.ajax({
              url: "{{ url('delete/orangtua-anak') }}"+"/"+data['id'],
              method: 'get',
              success: function(result){
                tableData.ajax.reload();
                Swal.fire(
                'Hapus',
                  'Data Berhasil di hapus.',
                 'success'
                 )
              }

            });
          }
        });
      });

      
        


})
</script>
@endpush