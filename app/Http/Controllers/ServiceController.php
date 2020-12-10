<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alert;
use App\Models\Service;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert as FacadesAlert;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->role->role_name!='Admin'){
            abort(404,"Sorry, You can do this actions");
        }

        $services = Service::when($request->keyword, function ($query) use ($request) {
            $query->where('service_name', 'like', "%{$request->keyword}%")
                ->orWhere('service_description', 'like', "%{$request->keyword}%")
                ->orWhere('service_price', 'like', "%{$request->keyword}%")
                ->orWhere('min_user', 'like', "%{$request->keyword}%");
        })->paginate(4);
        // $services['cari']=$request->only('keyword');
        $services->appends($request->only('keyword'));
        return view('service.index',compact('services'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->role->role_name!='Admin'){
            abort(404,"Sorry, You can do this actions");
        }

        $this->validate($request, [
            'service_name' => 'required|min:5',
			'service_price' => 'required|numeric',
            'min_user' => 'required|numeric',
		]);
        $request['service_id']=md5(Carbon::now());
        // dd($request);
        Service::create([
            'service_id' => $request->service_id,
            'service_name' => $request->service_name,
			'service_description' => $request->service_description,
            'service_price' => $request->service_price,
			'min_user' => $request->min_user,
        ]);

        // Category::updateOrCreate(['id' => $request->category_id],
        //         ['title' => $request->title, 'deskripsi' => $request->deskripsi, 'foto' => $nama_file]);
        // $a = Category
        // dd(Category);
        // Category::create($request->all());
            // dd($request->all());
        // $this->alert('success', $request->title ? 'Category Berhasil Ditambahkan' : 'Category Berhasil Ditambahkan');
        FacadesAlert::success('Success', 'Data Berhasil Ditambahkan');
        return back();
    }

     public function show($id)
    {
        //
    }

      public function edit($id)
    {
        //
    }

     public function update(Request $request)
    {

        // if(auth()->user()->role->role_name!='Admin'){
        //     abort(404,"Sorry, You can do this actions");
        // }

        $this->validate($request, [
            'service_name' => 'required|min:5',
			'service_price' => 'required|numeric',
            'min_user' => 'required|numeric',
		]);

        $service = Service::findOrFail($request->service_id);
        // dd($request->all());
        $service->update([
                    'service_name' => $request->service_name,
                    'service_description' => $request->service_description,
                    'service_price' => $request->service_price,
                    'min_user' => $request->min_user,
                    ]);

        FacadesAlert::info('Update', 'Data Berhasil Diupdate');
        return back();
    }

     public function destroy(Request $request)
    {
        if(auth()->user()->role->role_name!='Admin'){
            abort(404,"Sorry, You can do this actions");
        }

        $service = Service::findOrFail($request->service_id);
        $service->delete();

        FacadesAlert::error('Delete', 'Data Berhasil Dihapus');
        return back();

    }
}
