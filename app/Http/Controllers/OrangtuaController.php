<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orangtua;
use App\Models\Anak;
use App\Models\Kelurahan;
use App\Models\PemeriksaanFisik;
use App\Models\PemeriksaanGigi;
use App\Models\Artikel;
use App\Models\Video;
use App\Models\SkriningIndeks;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use File;

class OrangtuaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //  HALAMAN ADMIN

    // function untuk menampilkan data data akun orangtua yang terdaftar
    public function data(){
        $orangtua = Orangtua::all();
        return datatables()->of($orangtua)
        ->addColumn('action', function($row){
  
            $btn = '<a href="'.route('orangtua.edit',$row->id).'" type="button" id="btn-edit" class="btn btn-warning "><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit</a>';
            $btn = $btn.' <button title="Delete" id="btn-delete" class="delete-modal btn btn-danger "><i class="fa fa-trash " ></i>Hapus</button>';

           
            return $btn;
        })->addColumn('email',function($row){
            return $row->user->email;
        })
        ->rawColumns(['action'])->addIndexColumn()->make(true);
    }

    // function untuk menampilkan halaman index akun orangtua
    public function index()
    {
        return view('admin.orangtua.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.orangtua.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  FUNCTION UNTUK MENDAFTARKAN AKUN ORANGTUA DI HALAMAN ADMIN
    public function store(Request $request)
    {
        $user = New User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role ="orangtua";
        if($user){
            $user->save();
        }
        if($user){
            $orangtua = new Orangtua();
            $orangtua->id_users=$user->id;
            $orangtua->nama = $request->nama;
            $orangtua->alamat = $request->alamat;
            $orangtua->id_kecamatan = $request->id_kecamatan;
            $orangtua->id_kelurahan = $request->id_kelurahan;
            $orangtua->pendidikan= $request->pendidikan;
            if($orangtua){
                $orangtua->save();
                return redirect('/');
            }

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
        $orangtua= Orangtua::find($id);
        $kelurahan=Kelurahan::all();

        return view('admin.orangtua.edit', compact('orangtua','kelurahan'));
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
            $orangtua = orangtua::find($id);
            if(!empty($request->password)){
                $user = User::where('id', $orangtua->id_users)->update([

                    'email' => $request->email,

                    'password' => bcrypt($request->password),
                    'role' => "orangtua"
                ]);
            }else{
                $user = User::where('id', $orangtua->id_users)->update([

                    'email' => $request->email,

                    'role' => "orangtua"
                ]);
            };




        if($user){
            $orangtua = $orangtua->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'id_kecamatan' => $request->id_kecamatan,
            'id_kelurahan' => $request->id_kelurahan,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'pendidikan' => $request->pendidikan,
            ]);
            return redirect()->route('orangtua.index');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // Function untuk menghapus akun orangtua dihalaman admin
    public function destroy($id)
    {
        $orangtua = Orangtua::find($id);
        $user = User::where('id', $orangtua->id_users);
        $user->delete();
        $orangtua->delete();
        return response()->json(['data'=>'success delete data']);
    }




    // HALAMAN ORANGTUA

    // function untuk register akun orangtua dihalaman register
    public function registerUser(Request $request)
    {
        $messages = [

            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.unique'=>'Password wajib diisi',

        ];
        $validator = $request->validate([
            // 'NIK' => ['required', 'min:16',
            //             Rule::unique('dokter', 'NIK')],
            // 'nama' => 'required|min:3',
            'email' => ['required', 'email',
                        Rule::unique('users', 'email')],
            'password' => 'required',
            // 'no_telp' => 'required',
            // 'id_kecamatan' => 'required',
            // 'jenis_kelamin' => 'required',
            // 'tempat_lahir' => 'required',
            // 'tanggal_lahir' => 'required',
            // 'no_str' => 'required',

        ], $messages);
        DB::beginTransaction();
        try{


        $user = New User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role ="orangtua";

        $user->save();


            $orangtua = new Orangtua();
            $orangtua->id_users=$user->id;
            $orangtua->nama = $request->nama;
            $orangtua->id_kecamatan = $request->id_kecamatan;
            $orangtua->id_kelurahan = $request->id_kelurahan;
            $orangtua->tempat_lahir = $request->tempat_lahir;
            $orangtua->tanggal_lahir = $request->tanggal_lahir;
            $orangtua->alamat = $request->alamat;
            $orangtua->pendidikan= $request->pendidikan;
            if(!empty($request->foto)){
                $file = $request->file('foto');
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = uniqid() . '.' . $extension;
                Storage::put('public/orangtua/' . $filename, File::get($file));
               $orangtua->foto=$filename;
           }


            $orangtua->save();
            DB::commit();

            Auth::loginUsingId($user->id);
            return redirect('/');
        }catch(Exception $e){
            DB::rollback();
            return redirect('/register')->with('error','Gagal menambahkan data');
        }
    }

    // function untuk menampilkan data data anak berupa json untuk datatable dihalaman orangtua berdasarkan id orangtua ketika login
    public function dataAnak(){
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');
        $anak = Anak::Where('id_orangtua',$orangtua)->get();
        return datatables()->of($anak)
        ->addColumn('action', function($row){
           
            $btn = '<a href="'.route('orangtua-anak.edit',$row->id).'" class="btn btn-warning "><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>';
            $btn = $btn. ' <button title="Delete" id="btn-delete" class="delete-modal btn btn-danger "><i class="fa fa-trash " ></i>Hapus</button>';

          
            return $btn;
        })
        ->addColumn('tanggal_lahir', function($row){
            return $tanggal = date('d-m-Y', strtotime($row->tanggal_lahir));

         })

        ->rawColumns(['action'])->addIndexColumn()->make(true);
    }

    public function viewDashboard(Request $request){
        $user = Orangtua::with('anak')->where('id_users', Auth::user()->id)->first();
        $artikel = Artikel::All();
        $video = Video::All();
        if ($request->ajax()) {
            $data = PemeriksaanFisik::select('tinggi_badan','berat_badan','waktu_pemeriksaan')->where('id_anak', $request->id)->get();
            $arrayLabel = [];
            $arrayData = [];
            if ($request->type == 'tb') {
                foreach ($data as $key => $value) {
                    $arrayLabel[] = \Carbon\Carbon::parse($value->waktu_pemeriksaan)->format('j M Y');
                    $arrayData[] = $value->tinggi_badan;
                }
            } elseif ($request->type == 'bb') {
                foreach ($data as $key => $value) {
                    $arrayLabel[] = \Carbon\Carbon::parse($value->waktu_pemeriksaan)->format('j M Y');
                    $arrayData[] = $value->berat_badan;
                }
            }
            return response()->json([
                'type' => $request->type,
                'label' => $arrayLabel,
                'data' => $arrayData,
            ]);
        }
        return view('orangtua.dashboard.dashboard',compact('user','artikel','video'));
    }

    // function untuk menampilkan data anak di halaman orangtua
    public function viewAnak()
    {
        return view('orangtua.anak.index');
    }
    // function untuk menampilkan halaman tambah anak
    public function viewTambahAnak()
    {
        return view ('orangtua.anak.create');
    }


    // function untuk menambah anak di halaman tambah anak orangtua
    public function tambahAnak(Request $request)
    {

        $messages = [
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis Kelamin wajib diisi',
            'tempat_lahir.required'  => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi'

        ];
        $validator = $request->validate([

            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required'

        ], $messages);

        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');

        $anak = new Anak();
        $anak->id_orangtua= $orangtua;
        $anak->nama = $request->nama;
        $anak->jenis_kelamin = $request->jenis_kelamin;
        $anak->tempat_lahir = $request->tempat_lahir;
        $anak->tanggal_lahir = $request->tanggal_lahir;


        $anak->save();
        return redirect()->route('viewanak');
    }

    public function editAnak($id){
        $anak = Anak::find($id);
        return view('orangtua.anak.edit',compact('anak'));
    }

    public function updateAnak(Request $request, $id){
        $messages = [
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis Kelamin wajib diisi',
            'tempat_lahir.required'  => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi'

        ];
        $validator = $request->validate([

            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required'

        ], $messages);
        $anak = Anak::find($id);
        $user = Auth::user();
        $orangtua = Orangtua::Where('id_users', Auth::user()->id)->value('id');

        $anak->nama = $request->nama;
        $anak->jenis_kelamin=$request->jenis_kelamin;
        $anak->tempat_lahir=$request->tempat_lahir;
        $anak->tanggal_lahir=$request->tanggal_lahir;


        $anak->save();
        return redirect()->route('viewanak')->with('error',$messages);

    }

    public function deleteAnak($id){
        $anak=Anak::find($id);
        $anak->delete();
    }

    public function profil(){

        $user=User::find(Auth::user()->id);
        return view('orangtua.profil.edit',compact('user'));
    }

    public function updateProfil(Request $request){
        $user=User::find(Auth::user()->id);

        $user->profilorangtua->nama =$request->nama;
        $user->profilorangtua->tempat_lahir=$request->tempat_lahir;
        $user->profilorangtua->tanggal_lahir=$request->tanggal_lahir;
        $user->profilorangtua->pendidikan = $request->pendidikan;
        $user->profilorangtua->alamat= $request->alamat;
        if(!empty($request->foto)){

            $file = $request->file('foto');
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = uniqid() . '.' . $extension;
            Storage::delete('/public/orangtua/'.$user->profilorangtua->foto);
            Storage::put('public/orangtua/' . $filename, File::get($file));
            $user->profilorangtua->foto=$filename;
       }


        $user->profilorangtua->save();

        return redirect()->route('viewanak');;

    }

    public function reservasi($id){

        $reservasi=SkriningIndeks::find($id);
        $pgigi= PemeriksaanGigi::where('id',$reservasi->id_pemeriksaan)->first();
        $anak = Anak::where('id',$pgigi->id_anak)->first();
        dd($anak,$pgigi);
    }

}
