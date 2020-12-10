<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert as FacadesAlert;

class BankController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->role->role_name!='Admin'){
            abort(404,"Sorry, You can do this actions");
        }

        $banks = Bank::when($request->keyword, function ($query) use ($request) {
            $query->where('bank_name', 'like', "%{$request->keyword}%")
                ->orWhere('no_rekening', 'like', "%{$request->keyword}%")
                ->orWhere('alias', 'like', "%{$request->keyword}%");
        })->paginate(4);
        // $banks['cari']=$request->only('keyword');
        $banks->appends($request->only('keyword'));
        return view('bank.index',compact('banks'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->role->role_name!='Admin'){
            abort(404,"Sorry, You can do this actions");
        }

        $this->validate($request, [
            'bank_name' => 'required|min:3',
			'no_rekening' => 'required',
		]);
        $request['bank_id']=md5(Carbon::now());
        // dd($request);
        bank::create([
            'bank_id' => $request->bank_id,
            'bank_name' => $request->bank_name,
			'no_rekening' => $request->no_rekening,
            'alias' => $request->alias,
			'user_id' => auth()->user()->user_id,
        ]);

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
            'bank_name' => 'required|min:3',
			'no_rekening' => 'required',
		]);

        $bank = bank::findOrFail($request->bank_id);
        // dd($request->all());
        $bank->update([
                        'bank_id' => $request->bank_id,
                        'bank_name' => $request->bank_name,
                        'no_rekening' => $request->no_rekening,
                        'alias' => $request->alias,
                        'user_id' => auth()->user()->user_id,
                    ]);

        FacadesAlert::info('Update', 'Data Berhasil Diupdate');
        return back();
    }

     public function destroy(Request $request)
    {
        if(auth()->user()->role->role_name!='Admin'){
            abort(404,"Sorry, You can do this actions");
        }

        $bank = bank::findOrFail($request->bank_id);
        $bank->delete();

        FacadesAlert::error('Delete', 'Data Berhasil Dihapus');
        return back();

    }
}
