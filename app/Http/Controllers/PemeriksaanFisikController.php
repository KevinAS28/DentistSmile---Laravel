<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemeriksaanFisik;
use App\Models\PemeriksaanMata;
use App\Models\PemeriksaanTelinga;
use App\Models\PemeriksaanGigi;
use App\Models\User;
use App\Models\Orangtua;
use App\Models\Anak;
use App\Models\Kelurahan;
use App\Models\Sekolah;
use App\Models\Kelas;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

//----------------- HALAMAN ORANGTUA-----------------//

class PemeriksaanFisikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // menambah data pemeriksaan fisik diHALAMAN ORANGTUA
    public function create()
    {
        $kelurahan=Kelurahan::all()->pluck('nama','id');
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();  //mendapatkan list anak berdasarkan id orangtua yang login
        
        return view('orangtua.pemeriksaan.create',compact('anak','kelurahan'));
    }
    public function listAnak($anak){
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();
          //mendapatkan list anak berdasarkan id orangtua yang login
        $kelas = Kelas::Where('id_sekolah',$anak)->get();
        return response()->json($kelas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $messages = [
           
        //     'email.required' => 'Email wajib diisi.',
        //     'email.unique' => 'Email sudah terdaftar.',

        // ];
        // $validator = $request->validate([
        //     'kelas' => 'required',
            
           
        // ], $messages);
        
        DB::beginTransaction();
        try{

        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();

        $waktu_pemeriksaan = Carbon::now(); //mendapatkan waktu sekarang

        $pFisik = new PemeriksaanFisik();
        $pFisik->id_anak =  $request->anak;
        $pFisik->id_sekolah= $request->id_sekolah;
        $pFisik->id_kelas = $request->kelas;
        $pFisik->tinggi_badan = $request->tinggi_badan;
        $pFisik->berat_badan = $request->berat_badan;
        if($request->tinggi_badan > 0 && $request->berat_badan > 0){
            $pFisik->imt = $request->berat_badan/((($request->tinggi_badan)/100)*(($request->tinggi_badan)/100)); // perhitungan IMT
        }
        $pFisik->sistole = $request->sistole;
        $pFisik->diastole = $request->diastole;
        $pFisik->waktu_pemeriksaan = $waktu_pemeriksaan;
        $pFisik->save(); // MENYIMPAN PEMERIKSAAN FISIK
        
        $pMata = new PemeriksaanMata();
        $pMata->id_anak =  $request->anak;
        $pMata->id_sekolah= $request->id_sekolah;
        $pMata->id_kelas = $request->kelas;
        $pMata->msoal1=$request->msoal1; // menyimpan jawaban soal mata
        $pMata->msoal2=$request->msoal2; // menyimpan jawaban soal mata
        $pMata->msoal3=$request->msoal3; // menyimpan jawaban soal mata
        $pMata->msoal4=$request->msoal4; // menyimpan jawaban soal mata
        $pMata->msoal5=$request->msoal5; // menyimpan jawaban soal mata
        $pMata->msoal6=$request->msoal6; // menyimpan jawaban soal mata (menentukan keterangan mata minus plus atau butawarna)
        $pMata->msoal7=$request->msoal7; // menyimpan jawaban soal mata
        $pMata->waktu_pemeriksaan=$waktu_pemeriksaan;
        $pMata->save(); // MENYIMPAN PEMERIKSAAN MATA
        $pTelinga = new PemeriksaanTelinga();
        $pTelinga->id_anak =  $request->anak;
        $pTelinga->id_sekolah= $request->id_sekolah;
        $pTelinga->id_kelas = $request->kelas;
        $pTelinga->tsoal1=$request->tsoal1;
        $pTelinga->tsoal2=$request->tsoal2;
        $pTelinga->tsoal3=$request->tsoal3;
        $pTelinga->tsoal4=$request->tsoal4;
        $pTelinga->tsoal5=$request->tsoal5;
        $pTelinga->tsoal6=$request->tsoal6;
        $pTelinga->tsoal7=$request->tsoal7;
        $pTelinga->tsoal8=$request->tsoal8;
        $pTelinga->tsoal9=$request->tsoal9;
        $pTelinga->waktu_pemeriksaan=$waktu_pemeriksaan;
        $pTelinga->save(); // MENYIMPAN PEMERIKSAAN TELINGA

        
       
        DB::commit();

        return redirect()->route('periksa-gigi.create')->with('success','Sukses mengisi data pemeriksaan fisik');
    }catch(\Exception $e){
        DB::rollback();
        
        return redirect()->route('pemeriksaanfisik.create')->with('error','Terjadi kesalahan, silahkan coba lagi');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // ----------- HALAMAN ORANGTUA - > RIWAYAT --------------//
    public function riwayat(){
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();
        return view('orangtua.pemeriksaan.riwayat',compact('anak'));
    }

    // ----------- HALAMAN ORANGTUA - > RIWAYAT -> RIWAYAT FISIK --------------//
    public function riwayatfisik(Request $request){
        // MENAMPILKAN DATA PEMERIKSAAN FISIK
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');  // MENDAPATKAN ID ORANGTUA
        $anak = Anak::Where('id_orangtua',$orangtua)->get(); // MENDAPATKAN LIST ANAK BERDASARKAN ID ORANGTUA
        if(!empty($request->anak)){ 
        $pemeriksaanFisik = PemeriksaanFisik::Where('id_anak',$request->anak)->get(); // MENDAPATKAN LIST PEMERIKSAAN FISIK BERDASARKAN ID ANAK
       }else{
        $pemeriksaanFisik = PemeriksaanFisik::where(''); // MENDAPATKAN LIST PEMERIKSAAN FISIK
        }
        return datatables()->of($pemeriksaanFisik)
        ->addColumn('imt', function($pemeriksaanFisik){
            if($pemeriksaanFisik->imt < 18.5){
                $data= 'Kurus';
            }else if($pemeriksaanFisik->imt >= 18.5 && $pemeriksaanFisik->imt <= 25.0){
                $data = 'Ideal';
            }else if($pemeriksaanFisik->imt >= 25.1 && $pemeriksaanFisik->imt <= 29.9){
                $data = 'Gemuk';
            }else{
                $data = 'Obesitas';
            }
            return $data;
        })
        ->addColumn('tanggal', function($pemeriksaanFisik){
            return $tanggal = date('d-m-Y', strtotime($pemeriksaanFisik->waktu_pemeriksaan));
        })
        ->addColumn('jam', function($pemeriksaanFisik){
            return $jam = date('H:i', strtotime($pemeriksaanFisik->waktu_pemeriksaan));
        })
       ->addIndexColumn()
       ->make(true);
       
    }

    public function riwayatmata(Request $request){
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();
        if(!empty($request->anak)){
            $pemeriksaanMata = PemeriksaanMata::Where('id_anak',$request->anak)->get(); // mendatkan data pemeriksaan mata berdarkan request anak
           }
            return datatables()->of($pemeriksaanMata)
            ->addColumn('tanggal', function($pemeriksaanMata){
               return $tanggal = date('d-m-Y', strtotime($pemeriksaanMata->created_at));
                
            })
            ->addColumn('jam',function($pemeriksaanMata){
                return $jam = date('H:i', strtotime($pemeriksaanMata->created_at));
            })
            ->addColumn('hasil',function($pemeriksaanMata){
                if($pemeriksaanMata->msoal6=='normal'){
                    $data = 'Mata Normal';
                }else if($pemeriksaanMata->msoal6=='minus'){
                    $data = 'Mata Minus';
                }else{
                    $data = 'Mata Buta Warna';
                }
                return $data;
            })
            ->addIndexColumn()
            ->make(true);
    }
    public function riwayattelinga(Request $request){
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();
        if(!empty($request->anak)){
            $pemeriksaanTelinga = PemeriksaanTelinga::Where('id_anak',$request->anak)->get();
           }else{
            $pemeriksaanTelinga = PemeriksaanTelinga::all();
            }
            return datatables()->of($pemeriksaanTelinga)
            ->addColumn('tanggal', function($pemeriksaanTelinga){
               return $tanggal = date('d-m-Y', strtotime($pemeriksaanTelinga->created_at));
                
            })
            ->addColumn('jam',function($pemeriksaanTelinga){
                return $jam = date('H:i', strtotime($pemeriksaanTelinga->created_at));
            })
            ->addColumn('hasil',function($pemeriksaanTelinga){
                if($pemeriksaanTelinga->tsoal7=='ya'&& $pemeriksaanTelinga->tsoal8=='ya'){
                    $data = 'Serumen 2';
                }else if($pemeriksaanTelinga->tsoal7=='ya'&& $pemeriksaanTelinga->tsoal8=='tidak'){
                    $data = 'Serumen Kanan';
                }else if($pemeriksaanTelinga->tsoal7=='tidak'&& $pemeriksaanTelinga->tsoal8=='ya'){
                    $data = 'Serumen Kiri';
                }else{
                    $data = 'Serumen Tidak Ada';
                }
                return $data;
            })
            ->addIndexColumn()
            ->make(true);
    }
    public function riwayatgigi(Request $request){
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();
        if(!empty($request->anak)){
            $pemeriksaanGigi = PemeriksaanGigi::Where('id_anak',$request->anak)->get();
            
           }else{
            $pemeriksaanGigi = PemeriksaanGigi::all();
            }
            return datatables()->of($pemeriksaanGigi)
            ->addColumn('tanggal', function($pemeriksaanGigi){
               return $tanggal = date('d-m-Y', strtotime($pemeriksaanGigi->created_at));
                
            })
            ->addColumn('jam',function($pemeriksaanGigi){
                return $jam = date('H:i', strtotime($pemeriksaanGigi->created_at));
            })
            
            ->addColumn('gambar',function($pemeriksaanGigi){
                $url1= url('storage/gigi/'.$pemeriksaanGigi->gambar1);
                $url2= url('storage/gigi/'.$pemeriksaanGigi->gambar2);
                $url3= url('storage/gigi/'.$pemeriksaanGigi->gambar3);
                $url4= url('storage/gigi/'.$pemeriksaanGigi->gambar4);
                $url5= url('storage/gigi/'.$pemeriksaanGigi->gambar5);
                
                if(($pemeriksaanGigi->gambar4==NULL)  &&(!empty($pemeriksaanGigi->gambar5)) ){
                $gambar= '<img src="'.$url1.'"  width="50" class="img-fluid" align="center" />
                <img src="'.$url2.'"  width="50" class="img-fluid" align="center" />
                <img src="'.$url3.'"  width="50" class="img-fluid" align="center" />
                <img src="'.$url5.'"  width="50" class="img-fluid" align="center" />
                    
                ';
                }else if(($pemeriksaanGigi->gambar5==NULL)&&(!empty($pemeriksaanGigi->gambar4))){
                    $gambar= '<img src="'.$url1.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url2.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url3.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url4.'"  width="50" class="img-fluid" align="center" />
                    ';
                }else if(($pemeriksaanGigi->gambar4==NULL) && ($pemeriksaanGigi->gambar5==NULL)){
                    $gambar= '<img src="'.$url1.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url2.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url3.'"  width="50" class="img-fluid" align="center" />
                        
                    ';
                }
                else{
                    $gambar= '<img src="'.$url1.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url2.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url3.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url4.'"  width="50" class="img-fluid" align="center" />
                    <img src="'.$url5.'"  width="50" class="img-fluid" align="center" />
                        
                    ';
                }
                return $gambar;
            })
            ->addColumn('diagnosa',function($pemeriksaanGigi){
                $diagnosa = '';
                
                if(!empty($pemeriksaanGigi->skriningIndeks)){
                    $diagnosa .= '<td>'.$pemeriksaanGigi->skriningIndeks->diagnosa.'</td>';
                }else{
                    $diagnosa .= '<span class="badge bg-danger">Menunggu hasil dari dokter</span>';
                }
                return $diagnosa;
            })
            ->addColumn('rekomendasi',function($pemeriksaanGigi){
                $rekomendasi = '';
                
                
                if(!empty($pemeriksaanGigi->skriningIndeks->rekomendasi)){
                    if($pemeriksaanGigi->skriningIndeks->reservasi=="ya"){
                    
                    $rekomendasi = '<td>'.$pemeriksaanGigi->skriningIndeks->rekomendasi.'</td>'.'<br>';
                    $rekomendasi .= '<a href="'.route("reservasi",$pemeriksaanGigi->skriningIndeks->id).'" class="btn btn-primary">reservasi</a>';
                    }else{
                        $rekomendasi = '<td>'.$pemeriksaanGigi->skriningIndeks->rekomendasi.'</td>';
                    }
                }else{
                    $rekomendasi .= '<span class="badge bg-danger">Menunggu hasil dari dokter</span>';
                }
                return $rekomendasi;
                
            })
            ->addColumn('validasi',function($pemeriksaanGigi){

                if(($pemeriksaanGigi->skriningOdontogram->isEmpty()) Or ($pemeriksaanGigi->skriningIndeks->reservasi==NULL)){
                    $validasi = '<button class="btn btn-danger">Belum divalidasi </button>';
                }else{
                    $validasi = '<button class="btn btn-success">Sudah divalidasi</button>';

                }
                return $validasi;
            })
            
            ->rawColumns(['gambar','diagnosa','rekomendasi','validasi'])
            ->addIndexColumn()
            ->make(true);
        
    }

}
