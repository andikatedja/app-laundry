<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin_model extends Model
{
    public static function getUserInfo($logged_email)
    {
        return DB::table('users')->where([
            'email' => $logged_email,
            'role' => 1
        ])->first();
    }

    public static function getTransaksiForCetak($id)
    {
        return DB::table('detail_transaksi')->select('barang.nama_barang', 'kategori.nama_kategori', 'servis.nama_servis', 'detail_transaksi.banyak', 'detail_transaksi.sub_total', 'detail_transaksi.harga')
            ->join('daftar_harga', 'detail_transaksi.id_harga', '=', 'daftar_harga.id_harga')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('kategori', 'daftar_harga.id_kategori', '=', 'kategori.id_kategori')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('detail_transaksi.id_transaksi', '=', $id)
            ->get();
    }

    public static function cekHarga($barang, $kategori, $servis)
    {
        return DB::table('daftar_harga')->where([
            'id_barang' => $barang,
            'id_kategori' => $kategori,
            'id_servis' => $servis
        ])->exists();
    }

    public static function cekMember($id)
    {
        return DB::table('users')->where([
            'id' => $id,
            'role' => 2
        ])->exists();
    }

    public static function getHarga($barang, $kategori, $servis)
    {
        return DB::table('daftar_harga')->where([
            'id_barang' => $barang,
            'id_kategori' => $kategori,
            'id_servis' => $servis
        ])->pluck('harga');
    }

    public static function simpanTransaksi($transaksi, $id_member, $total_harga, $id_admin, $potongan)
    {
        $id_transaksi = DB::table('transaksi')->insertGetId([
            'tgl_masuk' => date('Y-m-d H:i:s'),
            'id_status' => 1,
            'id_member' => $id_member,
            'id_admin' => $id_admin,
            'tgl_selesai' => null,
            'potongan' => $potongan,
            'total_harga' => $total_harga
        ]);

        foreach ($transaksi as $key => $value) {
            $harga = DB::table('daftar_harga')->select('id_harga', 'harga')->where([
                'id_barang' => $transaksi[$key]['id_barang'],
                'id_kategori' => $transaksi[$key]['id_kategori'],
                'id_servis' => $transaksi[$key]['id_servis']
            ])->get();

            DB::table('detail_transaksi')->insert([
                'id_transaksi' => $id_transaksi,
                'id_harga' => $harga[0]->id_harga,
                'banyak' => $transaksi[$key]['banyak'],
                'harga' => $harga[0]->harga,
                'sub_total' => $transaksi[$key]['harga']
            ]);
        }

        $poin = DB::table('users')->where('id', '=', $id_member)->pluck('poin')[0];
        $poin += 1;
        DB::table('users')->where('id', '=', $id_member)->update([
            'poin' => $poin
        ]);

        return $id_transaksi;
    }

    public static function getRiwayatTransaksi()
    {
        return DB::table('transaksi')->join('users', 'transaksi.id_member', '=', 'users.id')
            ->select('transaksi.*', 'users.nama')->get();
    }

    public static function getDetailTransaksi($id)
    {
        return DB::table('detail_transaksi')->select('barang.nama_barang', 'kategori.nama_kategori', 'servis.nama_servis', 'detail_transaksi.banyak', 'detail_transaksi.sub_total', 'detail_transaksi.harga')
            ->join('daftar_harga', 'detail_transaksi.id_harga', '=', 'daftar_harga.id_harga')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('kategori', 'daftar_harga.id_kategori', '=', 'kategori.id_kategori')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('detail_transaksi.id_transaksi', '=', $id)
            ->get();
    }

    public static function tambahHarga($barang, $kategori, $servis, $harga)
    {
        DB::table('daftar_harga')->insert([
            'id_harga' => null,
            'id_barang' => $barang,
            'id_kategori' => $kategori,
            'id_servis' => $servis,
            'harga' => $harga
        ]);
    }

    public static function getSaranKomplain($tipe)
    {
        return DB::table('saran_komplain')->join('users', 'saran_komplain.id_member', '=', 'users.id')
            ->select('saran_komplain.id', 'users.nama')
            ->where([
                'tipe' => $tipe,
                'balasan' => NULL
            ])->get();
    }

    public static function getDaftarHarga($kategori)
    {
        return DB::table('daftar_harga')->select('daftar_harga.id_harga', 'daftar_harga.harga', 'servis.nama_servis', 'barang.nama_barang')
            ->join('barang', 'daftar_harga.id_barang', '=', 'barang.id_barang')
            ->join('servis', 'daftar_harga.id_servis', '=', 'servis.id_servis')->where('daftar_harga.id_kategori', '=', $kategori)->get();
    }

    public static function tambahVoucher($request)
    {
        DB::table('vouchers')->insert([
            'nama_voucher' => 'Potongan ' . number_format($request->input('potongan'), 0, ',', '.'),
            'potongan' => $request->input('potongan'),
            'poin_need' => $request->input('poin'),
            'keterangan' => 'Mendapatkan potongan harga ' . number_format($request->input('potongan'), 0, ',', '.') . ' dari total transaksi',
            'aktif' => 1
        ]);
    }
}
