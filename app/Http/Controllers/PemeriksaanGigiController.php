<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanGigi;
use Illuminate\Http\Request;
use File;
use Auth;
use App\Models\User;
use App\Models\Orangtua;
use App\Models\Anak;
use App\Models\Kelurahan;
use App\Models\ResikoKaries;
use App\Models\Dokter;
use App\Models\SkriningIndeks;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Notification;
use Illuminate\Support\Facades\Http;

class PemeriksaanGigiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();
        $kelurahan=Kelurahan::all()->pluck('nama','id');
        return view('orangtua.pemeriksaan.pemeriksaanGigi',compact('anak','kelurahan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $messages = [

            'gambar1.required' => 'Gambar 1 wajib diisi.',
            'gambar2.required' => 'Gambar 2 wajib diisi.',
            'gambar3.required' => 'Gambar 3 wajib diisi.',


        ];
        $validator = $request->validate([
            'gambar1' => 'required',
            'gambar2' => 'required',
            'gambar3' => 'required'
        ], $messages);
        try {
            $waktu_pemeriksaan = Carbon::now();
            $imageArray = array();
            $pgigi = new PemeriksaanGigi();
            $pgigi->id_anak = $request->anak;
            $pgigi->id_sekolah= $request->id_sekolah ?: $request->id_posyandu;
            $pgigi->id_kelas = $request->kelas;
            $pgigi->waktu_pemeriksaan = $waktu_pemeriksaan;
            if(!empty($request->gambar1)){
                $file = $request->file('gambar1');
                $extension = strtolower($file->getClientOriginalExtension());
                $filename1 = uniqid() . '.' . $extension;
                $imageArray[0] = ['gambar' => $file, 'filename' => $filename1];

                Storage::put('public/gigi/' . $filename1, File::get($file));
                $pgigi->gambar1=$filename1;
            }
            if(!empty($request->gambar2)){
                $file = $request->file('gambar2');
                $extension = strtolower($file->getClientOriginalExtension());
                $filename2 = uniqid() . '.' . $extension;
                $imageArray[1] = ['gambar' => $file, 'filename' => $filename2];

                Storage::put('public/gigi/' . $filename2, File::get($file));
                $pgigi->gambar2=$filename2;
            }
            if(!empty($request->gambar3)){
                $file = $request->file('gambar3');
                $extension = strtolower($file->getClientOriginalExtension());
                $filename3 = uniqid() . '.' . $extension;
                $imageArray[2] = ['gambar' => $file, 'filename' => $filename3];

                Storage::put('public/gigi/' . $filename3, File::get($file));
                $pgigi->gambar3=$filename3;
            }
            if(!empty($request->gambar4)){
                $file = $request->file('gambar4');
                $extension = strtolower($file->getClientOriginalExtension());
                $filename4 = uniqid() . '.' . $extension;
                $imageArray[3] = ['gambar' => $file, 'filename' => $filename4];

                Storage::put('public/gigi/' . $filename4, File::get($file));
                $pgigi->gambar4=$filename4;
            }
            if(!empty($request->gambar5)){
                $file = $request->file('gambar5');
                $extension = strtolower($file->getClientOriginalExtension());
                $filename5 = uniqid() . '.' . $extension;
                $imageArray[4] = ['gambar' => $file, 'filename' => $filename5];

                Storage::put('public/gigi/' . $filename5, File::get($file));
                $pgigi->gambar5=$filename5;
            }

            $pgigi->gsoal1= $request->gsoal1;
            $pgigi->gsoal2= $request->gsoal2;

            $pgigi->save();

            // rk = resiko karies
            if($pgigi->id_kelas==NULL){
            $rk= new ResikoKaries();
            $rk->id_pemeriksaan_gigi=$pgigi->id;
            $rk->rksoal1=$request->rksoal1;
            $rk->rksoal2=$request->rksoal2;
            $rk->rksoal3=$request->rksoal3;
            $rk->rksoal4=$request->rksoal4;
            $rk->rksoal5=$request->rksoal5;
            $rk->rksoal6=$request->rksoal6;
            $rk->rksoal7=$request->rksoal7;
            $rk->rksoal8=$request->rksoal8;
            $rk->rksoal9=$request->rksoal9;
            $rk->rksoal10=$request->rksoal10;
            $rk->rksoal11=$request->rksoal11;
            $rk->rksoal12=$request->rksoal12;
            $rk->rksoal13=$request->rksoal13;
            $rk->save();
            }

            if($request->kelas){
                $kecamatan = $pgigi->kelas->sekolah->kelurahan->kecamatan->id;
            }else{
                $kecamatan = $pgigi->sekolah->kelurahan->kecamatan->id;
            }
            $response = Http::withBasicAuth('dentistsmile', 'asekasekjosh');
            foreach ($imageArray as $key => $value) {
                $response = $response->attach(
                    'image[]', file_get_contents($value['gambar']), $value['filename']
                );
            }
            $response = $response->post(config('app.ai_url').'/api/ai/dentistsmile',[
                'id' => $pgigi->id,
            ])
            ->throw()
            ->json();

            $d = array_key_exists('GigiBerlubang', $response['data']) ? $response['data']['GigiBerlubang'] : 0;
            $e = (array_key_exists('GigiHilang', $response['data']) ? $response['data']['GigiHilang'] : 0) + (array_key_exists('SisaAkar', $response['data']) ? $response['data']['SisaAkar'] : 0);
            $f = 0;

            $diagnosa = 'Terdapat ';
            $rekomendasi = '';
            $arrayRekomendasi = ['GigiBerlubang'=>'Pasien bisa ke dokter gigi di puskesmas untuk dilakukan penambalan gigi',
                                'SisaAkar'=>'Pasien bisa ke dokter gigi di puskesmas untuk dilakukan pencabutan sisa akar',
                                'GigiHilang'=>'Pasien silahkan ke dokter gigi untuk pembuatan gigi palsu'];
            foreach ($response['data'] as $key => $value) {
                $diagnosa .= $key . ' sebanyak ' . $value;
                $rekomendasi .= 'untuk ' . $key . ' ' . $arrayRekomendasi[$key];
                if (last($response['data']) != $value) {
                    $diagnosa .= ';';
                    $rekomendasi .= ';';
                } else {
                    $diagnosa .= ';';
                    $rekomendasi .= ';';
                }
            }
            SkriningIndeks::updateOrCreate(
                ['id_pemeriksaan' => $pgigi->id],
                ['def_d' => $d,'def_e' => $e,'def_f' => $f,'dmf_d' => $d,'dmf_e' => $e,'dmf_f' => $f,'diagnosa' => $diagnosa,'rekomendasi' => $rekomendasi]
                

            );

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        $dokter = User::whereHas('dokter',function($query) use($kecamatan){
            $query->where('id_kecamatan',$kecamatan);
        })->get();
        Notification::send($dokter, new \App\Notifications\PemeriksaanGigiNotification(PemeriksaanGigi::with('anak','sekolah')->find($pgigi->id)));
        return redirect()->route('view-riwayat')->with('success','sukses mengisi data pemeriksaan gigi');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PemeriksaanGigi  $pemeriksaanGigi
     * @return \Illuminate\Http\Response
     */
    public function show(PemeriksaanGigi $pemeriksaanGigi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PemeriksaanGigi  $pemeriksaanGigi
     * @return \Illuminate\Http\Response
     */
    public function edit(PemeriksaanGigi $pemeriksaanGigi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PemeriksaanGigi  $pemeriksaanGigi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PemeriksaanGigi $pemeriksaanGigi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PemeriksaanGigi  $pemeriksaanGigi
     * @return \Illuminate\Http\Response
     */
    public function destroy(PemeriksaanGigi $pemeriksaanGigi)
    {
        //
    }

    public function rePemeriksaanAi()
    {
        $pemeriksaanGigi = PemeriksaanGigi::doesntHave('skriningIndeks')->select('id','gambar1','gambar2','gambar3','gambar4','gambar5')->get();
        foreach ($pemeriksaanGigi as $key => $value) {
            $response = Http::withBasicAuth('dentistsmile', 'asekasekjosh');
            $imageArray = array();
            if (!empty($value->gambar1)) {
                $imageArray[0] = ['gambar' => Storage::disk('local')->get('public/gigi/'.$value->gambar1), 'filename' => $value->gambar1];
            }
            if (!empty($value->gambar2)) {
                $imageArray[1] = ['gambar' => Storage::disk('local')->get('public/gigi/'.$value->gambar2), 'filename' => $value->gambar2];
            }
            if (!empty($value->gambar3)) {
                $imageArray[2] = ['gambar' => Storage::disk('local')->get('public/gigi/'.$value->gambar3), 'filename' => $value->gambar3];
            }
            if (!empty($value->gambar4)) {
                $imageArray[3] = ['gambar' => Storage::disk('local')->get('public/gigi/'.$value->gambar4), 'filename' => $value->gambar4];
            }
            if (!empty($value->gambar5)) {
                $imageArray[4] = ['gambar' => Storage::disk('local')->get('public/gigi/'.$value->gambar5), 'filename' => $value->gambar5];
            }
            foreach ($imageArray as $j => $img) {
                $response = $response->attach(
                    'image[]', $img['gambar'], $img['filename']
                );
            }
            $response = $response->post(config('app.ai_url').'/api/ai/dentistsmile',[
                'id' => $value->id,
            ])
            ->throw()
            ->json();

            $d = array_key_exists('GigiBerlubang', $response['data']) ? $response['data']['GigiBerlubang'] : 0;
            $e = (array_key_exists('GigiHilang', $response['data']) ? $response['data']['GigiHilang'] : 0) + (array_key_exists('SisaAkar', $response['data']) ? $response['data']['SisaAkar'] : 0);
            $f = 0;

            if ($d == 0 && $e == 0 && $f == 0) {
                $diagnosa = '';
            } else {
                $diagnosa = 'Terdapat ';
            }
            $rekomendasi = '';
            $arrayRekomendasi = ['GigiBerlubang'=>'Pasien bisa ke dokter gigi di puskesmas untuk dilakukan penambalan gigi',
                                'SisaAkar'=>'Pasien bisa ke dokter gigi di puskesmas untuk dilakukan pencabutan sisa akar',
                                'GigiHilang'=>'Pasien silahkan ke dokter gigi untuk pembuatan gigi palsu'];
            foreach ($response['data'] as $key => $res) {
                $diagnosa .= $key . ' sebanyak ' . $res;
                $rekomendasi .= 'untuk ' . $key . ' ' . $arrayRekomendasi[$key];
                if (last($response['data']) != $res) {
                    $diagnosa .= ';';
                    $rekomendasi .= ';';
                } else {
                    $diagnosa .= ';';
                    $rekomendasi .= ';';
                }
            }
            SkriningIndeks::updateOrCreate(
                ['id_pemeriksaan' => $value->id],
                ['def_d' => $d,'def_e' => $e,'def_f' => $f,'dmf_d' => $d,'dmf_e' => $e,'dmf_f' => $f,'diagnosa' => $diagnosa,'rekomendasi' => $rekomendasi]
            );
        }
        return 'done';
    }
}
