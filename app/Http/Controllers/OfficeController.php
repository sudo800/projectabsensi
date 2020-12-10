<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use File;
class OfficeController extends Controller
{
    public function index(Request $request)
    {
        //
        $offices = Office::where('user_id','=',auth()->user()->user_id)
                ->when($request->keyword, function ($query) use ($request) {
            $query->where('email_address', 'like', "%{$request->keyword}%")
                ->orWhere('office_name', 'like', "%{$request->keyword}%")
                ->orWhere('office_address', 'like', "%{$request->keyword}%");
        })->paginate(4);
        // $offices['cari']=$request->only('keyword');
        $offices->appends($request->only('keyword'));
        $offices = Office::where('user_id','=',auth()->user()->user_id)->paginate(4);
        // return view('layouts.off');
        return view('office.index',compact('offices'));
    }

    public function create()
    {
        return view('office.form', ['offices'=> new Office(), 'submit'=>'Create', 'title'=>'Office Create', 'cekurl'=>'store']);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'office_name' => 'required',
            'office_address' => 'required',
            'status' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // 'email_address' => 'email',
            // 'no_telp' => 'required',
            // 'fax' => 'required',
            // 'web_address' => 'url',
            // 'latitude' => 'numeric',
            // 'longitude' => 'numeric',
            // 'radius' => 'required|numeric',
        ]);
        $file = $request['file'];
		$nama_file = time()."_".$file->getClientOriginalName();
        $tujuan_upload = 'uploadgambar/office';
        $file->move($tujuan_upload,$nama_file);

        $request['office_id']=md5(Carbon::now());
        $request['user_id']=auth()->user()->user_id;
        $request['office_photo']=$nama_file;
        $simpan = $request->all();
        // dd($simpan);
        Office::create($simpan);
        // [
        //     'office_id' => $request->office_id,
        //     'radius' => $request->radius,
		// 	'office_name' => $request->office_name,
        //     'office_address' => $request->office_address,
        //     'no_telp' => $request->no_telp,
        //     'fax' => $request->fax,
        //     'status' => $request->status,
        //     'web_address' => $request->web_address,
        //     'office_photo' => $nama_file,
		// 	'email_address' => $request->email_address,
        //     'latitude' => $request->latitude,
        //     'longitude' => $request->longitude,
		// 	'user_id' => auth()->user()->user_id,
        // ]
         Alert::success('Success', 'Data Berhasil Ditambahkan');
         return redirect()->route('office.index');
    }

     public function show($id)
    {
        //
    }

      public function edit($id)
    {
        $offices = office::findOrFail($id);
        $cekurl = 'update';
        return view('office.form',compact('offices','cekurl'));

    }

     public function update(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'office_name' => 'required',
            'office_address' => 'required',
            'status' => 'required',
        ]);

        $office = office::findOrFail($request->office_id);
        $nama_file = $office->office_photo;
        if($request->file != null){

            File::delete('uploadgambar/office'.$office->office_photo);

            // menyimpan data file yang diupload ke variabel $file
            $file = $request->file('file');

            $nama_file = time()."_".$file->getClientOriginalName();

                    // isi dengan nama folder tempat kemana file diupload
            $tujuan_upload = 'uploadgambar/office';
            $file->move($tujuan_upload,$nama_file);

        }
        $request['office_photo']=$nama_file;
        $request['user_id']=auth()->user()->user_id;
        $simpan = $request->all();
        // dd($simpan);
        $office->update($simpan);

        Alert::info('Update', 'Data Berhasil Diupdate');
        // return back();
        return redirect()->route('office.index');
    }

     public function destroy(Request $request)
    {
        $office = office::findOrFail($request->office_id);
        File::delete('uploadgambar/office'.$office->office_photo);
        $office->delete();

        Alert::error('Delete', 'Data Berhasil Dihapus');
        return back();

    }
}
