<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auth_model extends Model
{
    public static function insertNewMember($data)
    {
        DB::table('users')->insert([
            'id' => NULL,
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => '2',
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
