<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orangtua;
use App\Models\Anak;
use App\Models\PemeriksaanGigi;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use File;


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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
            $waktu_pemeriksaan = Carbon::now();
            $imageArray = array();
            $pgigi = new PemeriksaanGigi();
            $pgigi->id_anak = $request->id_anak;
            $pgigi->id_sekolah= $request->id_sekolah;
            $pgigi->id_kelas = $request->id_kelas;
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

            return response()->json([
                'messages'=>'success',
                'data'=> $pgigi
            ]);
        
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
}
