<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auth_model extends Model
{
    public static function insertNewMember($data)
    {
        $id_user = DB::table('users')->insertGetId([
            'id' => null,
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => '2'
        ]);

        DB::table('users_info')->insert([
            'id_user' => $id_user,
            'nama' => $data['nama'],
            'jenis_kelamin' => '',
            'alamat' => '',
            'no_telp' => '',
            'profil' => 'default.jpg',
            'poin' => 0
        ]);
    }

    public static function isEmailExist($email)
    {
        return DB::table('users')->where(['email' => $email])->exists();
    }
}
