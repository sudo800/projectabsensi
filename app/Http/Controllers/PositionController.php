<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use File;

class PositionController extends Controller
{
    public function index(Request $request)
    {

        $positions = position::where('user_id','=',auth()->user()->user_id)->when($request->keyword, function ($query) use ($request) {
            $query->where('position_description', 'like', "%{$request->keyword}%")
                ->orWhere('position_name', 'like', "%{$request->keyword}%");
        })->paginate(4);
        // $positions['cari']=$request->only('keyword');
        $positions->appends($request->only('keyword'));
        return view('position.index',compact('positions'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'position_name' => 'required',
            // 'position_description' => 'required',
        ]);

        $request['position_id']=md5(Carbon::now());
        position::create([
            'position_id' => $request->position_id,
            'position_description' => $request->position_description,
			'position_name' => $request->position_name,
			'user_id' => auth()->user()->user_id,
        ]);

         Alert::success('Success', 'Data Berhasil Ditambahkan');
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

        $this->validate($request, [
            'position_name' => 'required',
            // 'position_description' => 'required',
        ]);
        // dd($request->all());
        $position = Position::findOrFail($request->position_id);
        $position->update([
                        'position_name' => $request->position_name,
                        'position_description' => $request->position_description,
                    ]);

        Alert::info('Update', 'Data Berhasil Diupdate');
        return back();
    }

     public function destroy(Request $request)
    {
        $position = position::findOrFail($request->position_id);
        $position->delete();

        Alert::error('Delete', 'Data Berhasil Dihapus');
        return back();

    }
}
