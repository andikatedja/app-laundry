<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member_model extends Model
{
    public static function getUserInfo($logged_email)
    {
        return DB::table('users')->where('email', '=', $logged_email)->first();
    }

    public static function getSatuanKiloan($id_kategori)
    {
        return DB::table('daftar_harga')->select('daftar_harga.harga', 'servis.nama_servis', 'barang.nama_barang')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('daftar_harga.id_kategori', '=', $id_kategori)->get();
    }

    public static function getTransaksi($id_member, $limit = null)
    {
        if ($limit == null) {
            return DB::table('transaksi')->select('id_transaksi', 'tgl_masuk', 'transaksi.id_status', 'status.nama_status')
                ->join('status', 'transaksi.id_status', '=', 'status.id_status')->where('id_member', '=', $id_member)->orderBy('tgl_masuk', 'desc')->get();
        } else {
            return DB::table('transaksi')->select('id_transaksi', 'tgl_masuk', 'transaksi.id_status', 'status.nama_status')
                ->join('status', 'transaksi.id_status', '=', 'status.id_status')->where('id_member', '=', $id_member)->orderBy('tgl_masuk', 'desc')->limit($limit)->get();
        }
    }

    public static function getDetailTransaksi($id_transaksi)
    {
        return DB::table('detail_transaksi')->select('barang.nama_barang', 'kategori.nama_kategori', 'servis.nama_servis', 'detail_transaksi.banyak', 'detail_transaksi.sub_total', 'detail_transaksi.harga')
            ->join('daftar_harga', 'detail_transaksi.id_harga', '=', 'daftar_harga.id_harga')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('kategori', 'daftar_harga.id_kategori', '=', 'kategori.id_kategori')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('detail_transaksi.id_transaksi', '=', $id_transaksi)
            ->get();
    }

    public static function updateProfil($id_member, $data)
    {
        DB::table('users')->where('id', '=', $id_member)->update($data);
    }
}
