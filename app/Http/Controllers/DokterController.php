<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Anak;
use App\Models\SkriningOdontogram;
use App\Models\SkriningIndeks;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PemeriksaanFisik;
use App\Models\PemeriksaanMata;
use App\Models\PemeriksaanTelinga;
use App\Models\PemeriksaanGigi;
use App\Models\ResikoKaries;
use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // HALAMAN ADMIN UNTUK DOKTER

    // function untuk menampilkan data data akun dokter yang terdaftar DI HALAMAN ADMIN
    public function data(){
        $dokter = Dokter::all();
        return datatables()->of($dokter)
        ->addColumn('action', function($row){
            $btn = '<a href="'.route('dokter.edit',$row->id).'"  id="btn-edit" class=" btn btn-warning "><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit</a>';
            $btn = $btn.' <button title="Delete" id="btn-delete" class="delete-modal mr-3 btn btn-danger "><i class="fa fa-trash " ></i>Hapus</button>';

            return $btn;
        })
        ->rawColumns(['action'])->addIndexColumn()->make(true);
    }

    // function untuk menampilkan halaman index akun dokter DI HALAMAN ADMIN
    public function index()
    {

        return view('admin.dokter.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // function untuk menampilkan halaman create akun dokter DI HALAMAN ADMIN
    public function create()
    {
        return view('admin.dokter.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  FUNCTION UNTUK MENDAFTARKAN AKUN DOKTER DI HALAMAN ADMIN
    public function store(Request $request)
    {
        $messages = [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK tidak boleh sama.',
            'nik.min' => 'NIK harus 16 digit.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.min' => 'Nama minimal 3 huruf.',
            'jenis_kelamin.required' => 'Jenis Kelamin harus diisi.',
            'Tempat_lahir.required' => 'Tempat lahir harus diisi',
            'Tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terpakai.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 4 karaketer.',
            'no_telp.required' => 'No telepon wajib diisi.',

            'no_str.required' => 'No Str wajib diisi',

        ];
        $validator = $request->validate([
            'nik' => ['required', 'min:16',
                        Rule::unique('dokter', 'NIK')],
            'nama' => 'required',
            'email' => ['required', 'email',
                        Rule::unique('users', 'email')],
            'password' => 'required',
            'no_telp' => 'required',

            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'no_str' => 'required',

        ], $messages);
        DB::beginTransaction();


        try{
        $user = New User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role ="dokter";
        $user->save();

            $dokter = new Dokter();
            $dokter->id_users=$user->id;
            $dokter->id_kecamatan = $request->kecamatan;
            $dokter->nik = $request->nik;
            $dokter->nama = $request->nama;
            $dokter->jenis_kelamin = $request->jenis_kelamin;
            $dokter->tempat_lahir = $request->tempat_lahir;
            $dokter->tanggal_lahir = $request->tanggal_lahir;
            $dokter->no_telp = $request->no_telp;
            $dokter->no_str= $request->no_str;
            $dokter->save();


            DB::commit();
            return redirect()->route('dokter.index');

        }catch(\Exception $e){
        DB::rollback();
        return redirect()->route('dokter.create')->with('error',$messages);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dokter = Dokter::find($id);

        return view('admin.dokter.edit',compact('dokter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // function untuk mengupdate data akun dokter yang terdaftar di HALAMAN ADMIN
    public function update(Request $request, $id)
    {
        $dokter = Dokter::find($id);
        if(!empty($request->password)){
            $user = User::where('id', $dokter->id_users)->update([

                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => "dokter"
            ]);
        }else{
            $user = User::where('id', $dokter->id_users)->update([

                'email' => $request->email,
                'role' => "dokter"
            ]);
        }



        if($user){
            $dokter = $dokter->update([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kecamatan'=> $request->wilayah,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_telp' => $request->no_telp,
            'no_str' => $request->no_str

            ]);
            return redirect()->route('dokter.index');

        }

    }

    // function untuk menghapus data dokter dihalaman dokter
    public function destroy($id)
    {

        $dokter = Dokter::find($id);
        $dokter ->delete();
        return response()->json(['data'=>'success delete data']);
    }



    // -------- HALAMAN DOKTER --------------

    // function untuk menampilkan dashboard pada HALAMAN DOKTER
    public function viewDashboard()
    {
        return view('dokter.dashboard');
    }

    // function untuk menampilkan halaman profil pada HALAMAN DOKTER
    public function profil()
    {
        return view('dokter.profil');
    }
    // function untuk menampilkan halaman ubah profil pada HALAMAN DOKTER
    public function profil_edit($id)
    {
        $logdokter = Auth::user()->dokter;
        $dokter = $logdokter->find($id);
        return view('dokter.profil-edit',compact('dokter'));
    }

    // function untuk mengupdate data profil pada HALAMAN DOKTER
    public function profil_update(Request $request, $id)
    {

        $logdokter = Auth::user()->dokter;
        $dokter = $logdokter->find($id);
        $dokter->nik = $request->nik;
        $dokter->nama =$request->nama;
        $dokter->jenis_kelamin = $request->jenis_kelamin;
        $dokter->tempat_lahir=  $request->tempat_lahir;
        $dokter->tanggal_lahir= $request->tanggal_lahir;
        $dokter->no_telp = $request->no_telp;
        $dokter->no_str= $request->no_str;
        if($request->hasfile('avatar'))
        {
            $destination = 'dokter/avatar/'.$dokter->avatar;
            if(File::exists($destination))
            {
                File::delete($destination);
            }
            $file = $request->file('avatar');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('dokter/avatar/', $filename);
            $dokter->avatar = $filename;
        }
        if($request->hasfile('header'))
        {
            $destination = 'dokter/header/'.$dokter->header;
            if(File::exists($destination))
            {
                File::delete($destination);
            }
            $file = $request->file('header');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('dokter/header/', $filename);
            $dokter->header = $filename;
        }


        $dokter->save();
        return redirect()->route('dokter.profil');

    }

    // function untuk menampilkan halaman riwayat pemeriksaan pada HALAMAN DOKTER
    public function pemeriksaan_ukgs(){
        //$kelurahan = Kelurahan::all();
        $user = Auth::user();
        $dokter = Dokter::Where('id_users', Auth::user()->id)->value('id_kecamatan');
        $kelurahan = Kelurahan::where('id_kecamatan', $dokter)->pluck('nama','id');
        $sekolah = Sekolah::pluck('nama','id');
        //$sekolah   = Sekolah::all();
        return view('dokter.pemeriksaanData.ukgs',[
            'kelurahan' => $kelurahan, 'sekolah '=> $sekolah,
        ]);
    }






    public function pemeriksaan_data_ukgs(Request $request, $id){
        if ($request->open == 'notification') {
            // Auth::user()->unreadNotifications->when($request->input('id'), function ($query) use ($request) {
            //     return $query->where('id', $request->input('id'));
            // })->markAsRead();
            $kec = Dokter::find($request->kec)->id_kecamatan;
            $otherDokter = Dokter::where('id_kecamatan', $kec)->pluck('id_users')->toArray();
            Notification::whereIn('notifiable_id', $otherDokter)->whereNull('read_at')->update(['read_at' => now()]);
        }
        $data = PemeriksaanGigi::with('anak','resikoKaries','skriningOdontogram','skriningIndeks')->findOrFail($id);
        $aksi = ['belum-erupsi','erupsi-sebagian','karies','non-vital','tambalan-logam','tambalan-non-logam','mahkota-logam','mahkota-non-logam','sisa-akar','gigi-hilang','jembatan','gigi-tiruan-lepas'];
        $odontograms = [
            'b1k1' => ['p18','p17','p16','p15','p14','p13','p12','p11'],
            'b2k1' => ['p55','p54','p53','p52','p51'],
            'b3k1' => ['p85','p84','p83','p82','p81'],
            'b4k1' => ['p48','p47','p46','45','p44','p43','p42','p41'],
            'b1k2' => ['p21','p22','p23','p24','p25','p26','p27','p28'],
            'b2k2' => ['p61','p62','p63','p64','p65'],
            'b3k2' => ['p71','p72','p73','p74','p75'],
            'b4k2' => ['p31','p32','p33','p34','p35','p36','p37','p38']
        ];
        return view ('dokter.pemeriksaanData.pemeriksaanDataUKGS',compact('data','odontograms','aksi'));
    }

    public function pemeriksaan_data_ukgm(Request $request, $id){

        if ($request->open == 'notification') {
            // Auth::user()->unreadNotifications->when($request->input('id'), function ($query) use ($request) {
            //     return $query->where('id', $request->input('id'));
            // })->markAsRead();

            $kec = Dokter::find($request->kec)->id_kecamatan;
            $otherDokter = Dokter::where('id_kecamatan', $kec)->pluck('id_users')->toArray();
            Notification::whereIn('notifiable_id', $otherDokter)->whereNull('read_at')->update(['read_at' => now()]);
        }
        $data = PemeriksaanGigi::with('anak','resikoKaries','skriningOdontogram','skriningIndeks')->findOrFail($id);
        $aksi = ['belum-erupsi','erupsi-sebagian','karies','non-vital','tambalan-logam','tambalan-non-logam','mahkota-logam','mahkota-non-logam','sisa-akar','gigi-hilang','jembatan','gigi-tiruan-lepas'];
        $odontograms = [
            'b1k1' => ['p18','p17','p16','p15','p14','p13','p12','p11'],
            'b2k1' => ['p55','p54','p53','p52','p51'],
            'b3k1' => ['p85','p84','p83','p82','p81'],
            'b4k1' => ['p48','p47','p46','45','p44','p43','p42','p41'],
            'b1k2' => ['p21','p22','p23','p24','p25','p26','p27','p28'],
            'b2k2' => ['p61','p62','p63','p64','p65'],
            'b3k2' => ['p71','p72','p73','p74','p75'],
            'b4k2' => ['p31','p32','p33','p34','p35','p36','p37','p38']
        ];
        return view ('dokter.pemeriksaanData.pemeriksaanDataUKGM',compact('data','odontograms','aksi'));
    }

    public function storeSkriningGigiUkgm(Request $request){
        if($request->ajax()){
            SkriningOdontogram::where('id_pemeriksaan',$request->id_pemeriksaan)->delete();
            foreach ($request->aksi as $key => $value) {
                $data = new SkriningOdontogram();
                $data->id_pemeriksaan = $request->id_pemeriksaan;
                $data->aksi = $key;
                $data->posisi = $value;
                $data->save();
            }
            SkriningIndeks::updateOrCreate(
                [
                    'id_pemeriksaan' => $request->id_pemeriksaan
                ],
                [
                    'def_d' => $request->def_d,
                    'def_e' => $request->def_e,
                    'def_f' => $request->def_f,
                    'dmf_d' => $request->dmf_d,
                    'dmf_e' => $request->dmf_e,
                    'dmf_f' => $request->dmf_f,
                    'diagnosa' => $request->diagnosa,
                    'rekomendasi' => $request->rekomendasi,
                    'reservasi' => $request->reservasi
                ]
            );
            ResikoKaries::updateOrCreate(
                [
                    'id_pemeriksaan_gigi' => $request->id_pemeriksaan
                ],
                [
                    'rksoal1' => $request->rksoal1,
                    'rksoal2' => $request->rksoal2,
                    'rksoal3' => $request->rksoal3,
                    'rksoal4' => $request->rksoal4,
                    'rksoal5' => $request->rksoal5,
                    'rksoal6' => $request->rksoal6,
                    'rksoal7' => $request->rksoal7,
                    'rksoal8' => $request->rksoal8,
                    'rksoal9' => $request->rksoal9,
                    'rksoal10' => $request->rksoal10,
                    'rksoal11' => $request->rksoal11,
                    'rksoal12' => $request->rksoal12,
                    'rksoal13' => $request->rksoal13,
                    'penilaian' => $request->penilaian_risiko_karies
                ]
            );
            return response()->json(['success'=>'Data added successfully']);
        }
    }

    public function storeSkriningGigiUkgs(Request $request){
        if($request->ajax()){
            SkriningOdontogram::where('id_pemeriksaan',$request->id_pemeriksaan)->delete();
            foreach ($request->aksi as $key => $value) {
                $data = new SkriningOdontogram();
                $data->id_pemeriksaan = $request->id_pemeriksaan;
                $data->aksi = $key;
                $data->posisi = $value;
                $data->save();
            }
            SkriningIndeks::updateOrCreate(
                [
                    'id_pemeriksaan' => $request->id_pemeriksaan
                ],
                [
                    'def_d' => $request->def_d,
                    'def_e' => $request->def_e,
                    'def_f' => $request->def_f,
                    'dmf_d' => $request->dmf_d,
                    'dmf_e' => $request->dmf_e,
                    'dmf_f' => $request->dmf_f,
                    'diagnosa' => $request->diagnosa,
                    'rekomendasi' => $request->rekomendasi,
                    'reservasi' => $request->reservasi
                ]
            );



            return response()->json(['success'=>'Data added successfully']);
        }
    }

    public function rekap_ukgs(Request $request){
        $user = Auth::user();
        $dokter = Dokter::Where('id_users', Auth::user()->id)->value('id_kecamatan');
        $kelurahan = Kelurahan::where('id_kecamatan', $dokter)->pluck('nama','id');
        $sekolah = Sekolah::pluck('nama','id');
        //$sekolah   = Sekolah::all();
        return view('dokter.rekapData.ukgs',[
            'kelurahan' => $kelurahan, 'sekolah '=> $sekolah,
        ]);
    }

    public function rekap_ukgm(){
        $user = Auth::user();
        $dokter = Dokter::Where('id_users', Auth::user()->id)->value('id_kecamatan');
        $kelurahan = Kelurahan::where('id_kecamatan', $dokter)->pluck('nama','id');
        $sekolah = Sekolah::pluck('nama','id');
        //$sekolah   = Sekolah::all();
        return view('dokter.rekapData.ukgm',[
            'kelurahan' => $kelurahan, 'sekolah '=> $sekolah,
        ]);

    }
    public function rekap_detail_ukgs(){
        return view ('dokter.rekapData.rekapDataUKGS');
    }

    public function rekap_detail_ukgs_id($id){
        $pemeriksaanFisik = PemeriksaanFisik::where('id_anak', $id)->orderBy('waktu_pemeriksaan', 'desc')->first();
        $pemeriksaanMata = PemeriksaanMata::where('id_anak', $id)->orderBy('waktu_pemeriksaan', 'desc')->first();
        $pemeriksaanTelinga = PemeriksaanTelinga::where('id_anak', $id)->orderBy('waktu_pemeriksaan', 'desc')->first();
        $pemeriksaanGigi = PemeriksaanGigi::where('id_anak', $id)->orderBy('waktu_pemeriksaan', 'desc')->first();
        return view ('dokter.rekapData.rekapDataUKGSID', compact( 'pemeriksaanFisik', 'pemeriksaanMata', 'pemeriksaanTelinga', 'pemeriksaanGigi'));
    }



    public function rekap_detail_ukgm(){
        return view ('dokter.rekapData.rekapDataUKGM');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // function untuk menampilkan list list kelurahan
    public function listKelurahan(){
        $user = Auth::user();
        $dokter = Dokter::Where('id_users', Auth::user()->id)->value('id_kecamatan');
        $kelurahan = Kelurahan::where('id_kecamatan', $dokter)->get();
        return view('dokter.pemeriksaanData.ukgs',compact('kelurahan'));

    }

    // function untuk menampilkan list anak yang telah melakukan pemeriksaanfisik berdasarkan id kelas
    public function listAnak(Request $request){
        $pemeriksaanfisik = PemeriksaanGigi::with('anak','skriningOdontogram')->where('id_kelas',$request->id_kelas)->orderBy('id', 'DESC')->latest();

        return datatables()->of($pemeriksaanfisik)
        ->addColumn('action', function($row){
            $btn = '';
            $btn .= '<a type="button" class="btn btn-primary btn-xs text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Rekap Data " href="'.route('dokter.rekapDetailUKGSID',$row->id_anak).'"><i class="mdi mdi-book-open-page-variant"></i> Detail</a> ';

            if($row->skriningOdontogram->isEmpty()){
                $btn .= '<a type="button" class="btn btn-danger btn-xs text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Periksa" href="'.route('dokter.pemeriksaanDataUKGS',$row->id).'">Periksa  <i class="mdi mdi-tooth"></i></a>';
            }else{
                $btn .= '<a type="button" class="btn btn-info btn-xs text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Periksa" href="'.route('dokter.pemeriksaanDataUKGS',$row->id).'">Periksa  <i class="mdi mdi-tooth"></i></a>';
            }



            return $btn;
        })

        ->addColumn('tanggal', function($pemeriksaanfisik){
            return $tanggal = date('d-m-Y', strtotime($pemeriksaanfisik->waktu_pemeriksaan));
        })
        ->addColumn('waktu', function($pemeriksaanfisik){
            return $waktu = date('H:i', strtotime($pemeriksaanfisik->waktu_pemeriksaan));
        })
        ->addColumn('nama', function($pemeriksaanfisik){
            return $pemeriksaanfisik->anak->nama ?? " ";
        })
        ->addColumn('kelas', function($pemeriksaanfisik){
            return $pemeriksaanfisik->kelas->kelas;
        })
        ->addColumn('sekolah', function($pemeriksaanfisik){
            return $pemeriksaanfisik->kelas->sekolah->nama;
        })
        ->addColumn('jenis_kelamin', function($pemeriksaanfisik){
            return $pemeriksaanfisik->anak->jenis_kelamin ?? " ";
        })
        ->addIndexColumn()
       ->make(true);

    }

    // function untuk menampilkan list anak berdasarkan id kelas
    public function listAnakRekap(Request $request){
        $anak = DB::table('anak')
        ->leftJoin('sekolah', 'sekolah.id', '=', 'anak.id_sekolah')
        ->leftJoin('kelas', 'kelas.id', '=', 'anak.id_kelas')
        ->leftJoin(DB::raw('(SELECT
            id_anak,
            max(waktu_pemeriksaan) as waktu_pemeriksaan
            FROM pemeriksaan_fisik GROUP BY id_anak) pf'),
            function($join)
            {
            $join->on('anak.id', '=', 'pf.id_anak');
            })
        ->select('anak.id', 'anak.nama', 'anak.jenis_kelamin', 'sekolah.nama as sekolah', 'kelas.kelas', 'pf.waktu_pemeriksaan')
        ->get();

        return datatables()->of($anak)
        ->addColumn('action', function($row){
            $btn = '';
            $btn .= '<a type="button" class="btn btn-primary btn-xs text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Rekap Data" href="'.route('dokter.rekapDetailUKGSID', $row->id).'">Lihat Rekap <i class="mdi mdi-book-open-page-variant"></i></a> ';
            $btn .= '<a type="button" class="btn btn-info btn-xs text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Periksa" href="'.route('dokter.pemeriksaanDataUKGS').'"><i class="mdi mdi-tooth"></i></a>';

            return $btn;
        })
        ->addColumn('skrining', function($anak){
            if($anak->waktu_pemeriksaan==null)
                return "Belum";
            else
                return "Sudah (".date('d/m/Y', strtotime($anak->waktu_pemeriksaan)).")";
        })
        ->addIndexColumn()
        ->make(true);

    }

    //----------UKGM--------------//
    public function pemeriksaan_ukgm(){

        $user = Auth::user();
        $dokter = Dokter::Where('id_users', Auth::user()->id)->value('id_kecamatan');
        $kelurahan = Kelurahan::where('id_kecamatan', $dokter)->pluck('nama','id');
        $sekolah = Sekolah::pluck('nama','id');
        //$sekolah   = Sekolah::all();
        return view('dokter.pemeriksaanData.ukgm',[
            'kelurahan' => $kelurahan, 'sekolah '=> $sekolah,
        ]);

    }

    public function listAnakUkgm(Request $request){


        $pemeriksaanGigi = PemeriksaanGigi::with('anak','skriningOdontogram')->where('id_sekolah',$request->id_sekolah)->orderBy('id', 'DESC')-> latest();

        return datatables()->of($pemeriksaanGigi)
        ->addColumn('action', function($row){
            $btn = '';



            $btn .= '<a type="button" class="btn btn-primary btn-xs text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Rekap Data" href="'.route('dokter.rekapDetailUKGSID',$row->id_anak).'"><i class="mdi mdi-book-open-page-variant"></i> Detail</a> ';

            if($row->skriningOdontogram->isEmpty()){
                $btn .= '<a type="button" class="btn btn-danger btn-xs text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Periksa" href="'.route('dokter.pemeriksaanDataUKGM',$row->id).'">Periksa  <i class="mdi mdi-tooth"></i></a>';
            }else{
                $btn .= '<a type="button" class="btn btn-info btn-xs text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Periksa" href="'.route('dokter.pemeriksaanDataUKGM',$row->id).'">Periksa  <i class="mdi mdi-tooth"></i></a>';
            }


            return $btn;
        })

        ->addColumn('tanggal', function($pemeriksaanGigi){
            return $tanggal = date('d-m-Y', strtotime($pemeriksaanGigi->waktu_pemeriksaan));
        })
        ->addColumn('waktu', function($pemeriksaanGigi){
            return $waktu = date('H:i', strtotime($pemeriksaanGigi->waktu_pemeriksaan));
        })
        ->addColumn('nama', function($pemeriksaanGigi){
            return $pemeriksaanGigi->anak->nama;
        })

        ->addColumn('posyandu', function($pemeriksaanGigi){
            return $pemeriksaanGigi->sekolah->nama;
        })
        ->addColumn('jenis_kelamin', function($pemeriksaanGigi){
            return $pemeriksaanGigi->anak->jenis_kelamin;
        })
        ->addColumn('umur', function($pemeriksaanGigi){
            $born=Carbon::parse($pemeriksaanGigi->anak->tanggal_lahir);
            $age = $born->diff(Carbon::now())
            ->format('%y tahun %m bulan ');

            return $age;
        })

        ->addIndexColumn()
       ->make(true);

    }
    // public function pemeriksaan_data_ukgm($id)
    // {
    //     $ukgm=PemeriksaanGigi::with('resikoKaries')->find($id);
    //     return view('dokter.pemeriksaanData.pemeriksaanDataUKGM',compact('ukgm'));
    // }

    //------ Dashboard ----------//
    public function dashboard(){

    }

    public function lihat_gambar($id,$filename){
        $response = Http::withBasicAuth('dentistsmile', 'asekasekjosh');
        $response = $response->get(config('app.ai_url').'/api/ai/dentistsmile', [
            'id' => $id,
            'fileName' => $filename
        ]);
        return $response;
    }

}
