<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employe;
use App\Models\Office;
use App\Models\Position;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use File;
use Illuminate\Support\Facades\Hash;

class EmployeController extends Controller
{

public function index(Request $request)
{

    $employes = employe::where('user_id','=',auth()->user()->user_id)->when($request->keyword, function ($query) use ($request) {
        $query->where('employe_email', 'like', "%{$request->keyword}%")
            ->orWhere('employe_name', 'like', "%{$request->keyword}%")
            ->orWhere('employe_place_of_birthday', 'like', "%{$request->keyword}%")
            ->orWhere('employe_nik', 'like', "%{$request->keyword}%")
            ->orWhere('employe_address', 'like', "%{$request->keyword}%");
    })->paginate(5);
    $positions = Position::where('user_id','=',auth()->user()->user_id)->get();
    $offices = Office::where('user_id','=',auth()->user()->user_id)->get();
    // $employes['cari']=$request->only('keyword');
    $employes->appends($request->only('keyword'));
    return view('employe.index',compact('employes','positions','offices'));
}

public function store(Request $request)
{
    $request['office_id']= $request->office_id;
    $this->validate($request, [
        'employe_name' => 'required',
        'employe_email' => 'required|email',
        'employe_nik' => 'required|numeric|unique:tb_employe,employe_nik',
        'employe_address' => 'required',
        'employe_place_of_birthday' => 'required',
        'employe_date_of_birthday' => 'required',
        'employe_gender' => 'required',
        'office_id' => 'required|array',
        'position_id' => 'required',
        'file' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $file = $request['file'];
    $nama_file = time()."_".$file->getClientOriginalName();
    $tujuan_upload = 'uploadgambar/employe';
    $file->move($tujuan_upload,$nama_file);

    $request['username']= $request->employe_nik;
    $request['password']= Hash::make($request->employe_nik);
    $request['employe_photo']=$nama_file;
    $request['user_id']=auth()->user()->user_id;
    $request['employe_date_of_birthday']=date('Y-m-d', strtotime($request->employe_date_of_birthday));
    $request['employe_id']=md5(Carbon::now());
    $simpan = $request->all();
    // echo $request['office_id'][0].'<br>';
    // echo $request['office_id'][1];
    // dd($simpan);
    $employe = Employe::create($simpan);
    $employe = Employe::find($request['employe_id']);
    $employe->offices()->attach(request('office_id'));

     Alert::success('Success', 'Data Berhasil Ditambahkan');
     return redirect()->route('employe.index');
}

public function create()
{
    $positions = Position::where('user_id','=',auth()->user()->user_id)->get();
    $offices = Office::where('user_id','=',auth()->user()->user_id)->get();
    $tanggal = date('m/d/Y');
    return view('employe.form', ['employes'=> new Employe(), 'position_name'=>'', 'tanggal'=>$tanggal, 'submit'=>'Create', 'title'=>'Employe Create', 'positions'=>$positions, 'offices'=>$offices,'cekurl'=>'store']);
}

 public function show($id)
{
    //
}

  public function edit($employe_id)
{
    $employes = employe::findOrFail($employe_id);
    $positions = Position::where('user_id','=',auth()->user()->user_id)->get();
    $offices = Office::where('user_id','=',auth()->user()->user_id)->get();
    $cekurl = 'update';
    return view('employe.form',compact('employes','positions','offices','cekurl'));
    //
}

 public function update(Request $request)
{
    $request['user_id']=auth()->user()->user_id;
    $request['employe_date_of_birthday']=date('Y-m-d', strtotime($request->employe_date_of_birthday));
    $request['username']= $request->employe_nik;
    $request['password']= Hash::make($request->employe_nik);
    $employe = employe::findOrFail($request->employe_id);
    $nama_file = $employe->employe_photo;
    if($request->file != null){

        File::delete('uploadgambar/employe'.$employe->employe_photo);
        $file = $request->file('file');

        $nama_file = time()."_".$file->getClientOriginalName();
        $tujuan_upload = 'uploadgambar/employe';
        $file->move($tujuan_upload,$nama_file);

    }
    $request['employe_photo']=$nama_file;
    $simpan = $request->all();

    $employe->update($simpan);
    if(request('office_id')!=''){
        $employe->offices()->sync(request('office_id'));
    }

    Alert::info('Update', 'Data Berhasil Diupdate');
    return redirect()->route('employe.index');
}

 public function destroy(Request $request)
{
    $employe = employe::findOrFail($request->employe_id);
    $employe->delete();

    Alert::error('Delete', 'Data Berhasil Dihapus');
    return back();

}
}
