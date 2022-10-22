<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anak;

class AnakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function data(){
        $anak = Anak::all();
        return datatables()->of($anak)
        ->addColumn('action', function($row){

            $btn = '<a href="'.route('anak.edit',$row->id).'" class="btn btn-warning "><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit</a>';
            $btn = $btn. ' <button title="Delete" id="btn-delete" class="delete-modal btn btn-danger "><i class="fa fa-trash " ></i> Hapus</button>';
            
           
            return $btn;
        })
        ->addColumn('orangtua',function($row){
            return $row->orangtua->nama;
        })

        ->rawColumns(['action'])->addIndexColumn()->make(true);
    }

    public function index()
    {
        return view ('admin.anak.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.anak.create');
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

            'nama.required' => 'Nama wajib diisi.',
            'nama.min' => 'Nama minimal 3 huruf.',
            'jenis_kelamin.required' => 'Jenis Kelamin harus diisi.',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',


        ];
        $validator = $request->validate([
            'nama'=>'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
          
           
        ], $messages);

            $anak = new Anak();
            $anak->id_orangtua=$request->orangtua;
            $anak->nama = $request->nama;
            $anak->jenis_kelamin = $request->jenis_kelamin;
            $anak->tempat_lahir = $request->tempat_lahir;
            $anak->tanggal_lahir = $request->tanggal_lahir;


            $anak->save();
            
            return redirect()->route('anak.index');
            
        
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
        $anak = Anak::find($id);

        return view('admin.anak.edit',compact('anak'));
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
        $anak = Anak::find($id);

        $anak->id_orangtua=$request->orangtua;
        $anak->nama = $request->nama;
        $anak->jenis_kelamin = $request->jenis_kelamin;
        $anak->tempat_lahir = $request->tempat_lahir;
        $anak->tanggal_lahir = $request->tanggal_lahir;
     

        $anak->save();
        return redirect()->route('anak.index');
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

    // Orangtua
}
