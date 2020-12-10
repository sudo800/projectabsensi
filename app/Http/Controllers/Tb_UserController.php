<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Tb_user;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use File;

class Tb_UserController extends Controller
{
    public function index(Request $request)
    {
        return view('auth.register');
    }
    public function store(Request $input)
    {

        // dd($input);
        $this->validate($input, [
            'fullname' => ['required', 'string', 'max:255'],
            'file' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
        ]);
        // menyimpan data file yang diupload ke variabel $file
		// $file = $input->file('file');
        $file = $input['file'];
		$nama_file = time()."_".$file->getClientOriginalName();
        echo '<br>'.$file;
      	        // isi dengan nama folder tempat kemana file diupload
		$tujuan_upload = 'uploadgambar/user_photo';
        $file->move($tujuan_upload,$nama_file);

        $input['user_id']=md5(Carbon::now());
        $input['role_id']=md5(Carbon::now());
        $input['name_role']='User';
        $input['status']='Non Aktif';

        Role::create([
            'role_id' => $input['role_id'],
            'user_id' => $input['user_id'],
            'role_name' => $input['name_role'],
        ]);

        Tb_user::create([
            'user_photo' => $nama_file,
            'user_id' => $input['user_id'],
            'fullname' => $input['fullname'],
            'username' => $input['username'],
            'email' => $input['email'],
            'status' => $input['status'],
            'role_id' => $input['role_id'],
            'password' => Hash::make($input['password']),
        ]);

        return view('/login');
    }
}
