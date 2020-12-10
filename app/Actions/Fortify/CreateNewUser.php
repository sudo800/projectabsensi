<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use File;

class CreateNewUser implements CreatesNewUsers
// class User extends models implements authenticatable
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // $cekcarbon=Carbon::now(format('l, d F Y H:i'));
        // $cekcarbonmd5=md5($cekcarbon);
        // echo 'carbon='.$cekcarbon.'<br>carbonmd5='.$cekcarbonmd5;
        Validator::make($input, [
            'fullname' => ['required', 'string', 'max:255'],
            'file' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'password' => $this->passwordRules(),
        ])->validate();
        // dd($input);
        // menyimpan data file yang diupload ke variabel $file
		// $file = $input->file('file');
        $file = $input['file'];
		$nama_file = time()."_".$file->getClientOriginalName();
        // echo '<br>'.$file;
      	        // isi dengan nama folder tempat kemana file diupload
		$tujuan_upload = 'uploadgambar/user_photo';
        $file->move($tujuan_upload,$nama_file);

        $input['user_id']=md5(Carbon::now());
        $input['role_id']=md5(Carbon::now());
        $input['name_role']='User';
        $input['status']=md5('Non Aktif');

         Role::create([
            'role_id' => $input['role_id'],
            'user_id' => $input['user_id'],
            'role_name' => $input['name_role'],
        ]);

        return User::create([
            'user_photo' => $nama_file,
            'user_id' => $input['user_id'],
            'fullname' => $input['fullname'],
            'username' => $input['username'],
            'email' => $input['email'],
            'status' => $input['status'],
            'role_id' => $input['role_id'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
