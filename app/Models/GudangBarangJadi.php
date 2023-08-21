<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GudangBarangJadi extends Model
{
    /**
     * @var string
     */
    protected $table = 'gdg_barang_jadi';

    /**
     * @var array
     */

    public function Produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }


    public function Stok()
    {
        $id = $this->id;
        // $jumlah = DB::table('noseri_barang_jadi')
        //             ->where(['noseri_barang_jadi.gdg_barang_jadi_id'=>$id,
        //             'noseri_barang_jadi.is_aktif' => 1,
        //             'noseri_barang_jadi.is_ready' => 0,
        //             'noseri_barang_jadi.is_change' => 1,
        //             'noseri_barang_jadi.is_delete' => 0
        //             ])
        //             ->count();

        $data = GudangBarangJadi::addSelect(['count_barang' => function ($query) {
            $query->selectRaw('count(noseri_barang_jadi.id)')
            ->from('noseri_barang_jadi')
            ->where('noseri_barang_jadi.is_ready', '0')
            ->whereColumn('noseri_barang_jadi.gdg_barang_jadi_id', 'gdg_barang_jadi.id')
            ->limit(1);
        },'count_ekat_sepakat' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
            ->limit(1);
        },'count_ekat_nego' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
            ->limit(1);
        },'count_ekat_draft' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id in ("7")  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
            ->limit(1);
        },'count_ekat_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
            ->limit(1);
        },'count_spa_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
            ->limit(1);
        },'count_spb_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
            ->limit(1);
        }])->with('Produk')->find($id);

        $jumlahdiminta = intval($data->count_ekat_sepakat) + intval($data->count_ekat_nego) + intval($data->count_ekat_draft) + intval($data->count_ekat_po) + intval($data->count_spa_po) + intval($data->count_spb_po);
        $jumlahstok = intval($data->count_barang);
        $hasil = $jumlahstok - $jumlahdiminta;
        if($hasil > 0){
            $hasil = 'tersedia';
        }else{
            $hasil = 'kurang';
        }
        return $hasil;
    }


    public function StokRgb()
    {
        $id = $this->id;

        $data = GudangBarangJadi::addSelect(['count_barang' => function ($query) {
            $query->selectRaw('count(noseri_barang_jadi.id)')
            ->from('noseri_barang_jadi')
            ->where('noseri_barang_jadi.is_ready', '0')
            ->whereColumn('noseri_barang_jadi.gdg_barang_jadi_id', 'gdg_barang_jadi.id')
            ->limit(1);
        },'count_ekat_sepakat' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
            ->limit(1);
        },'count_ekat_nego' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
            ->limit(1);
        },'count_ekat_draft' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id in ("7")  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
            ->limit(1);
        },'count_ekat_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
            ->limit(1);
        },'count_spa_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
            ->limit(1);
        },'count_spb_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
            ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
            ->limit(1);
        }])->with('Produk')->find($id);

        $jumlahdiminta = intval($data->count_ekat_sepakat) + intval($data->count_ekat_nego) + intval($data->count_ekat_draft) + intval($data->count_ekat_po) + intval($data->count_spa_po) + intval($data->count_spb_po);
        $jumlahstok = intval($data->count_barang);
        $hasil = $jumlahstok - $jumlahdiminta;

        if($hasil <0){
            $hasil = 0;
        }

        return $hasil;
    }

}
