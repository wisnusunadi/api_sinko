<?php

namespace App\Http\Controllers;

use App\Models\AcceptKirim;
use App\Models\Customer;
use App\Models\Ekatalog;
use App\Models\Ekspedisi;
use App\Models\Logistik;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriCoo;
use App\Models\PenjualanProduk;
use App\Models\SaveResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    public function ekspedisi()
    {
        $data = array();
        $ekspedisi = Ekspedisi::select('id', 'nama')->get();
        foreach ($ekspedisi as $key_ekspedisi => $ekspedisi) {
            $data[$key_ekspedisi] = array(
                'id' => $ekspedisi->id,
                'nama' => $ekspedisi->nama,
                'provinsi' => array(),
            );


            foreach ($ekspedisi->Provinsi as $key_prov => $detail) {
                $data[$key_ekspedisi]['provinsi'][$key_prov] = array(
                    'id' => $detail->id,
                    'nama' => $detail->nama,
                );
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'jumlah' => count($data),
            'data'    => $data
        ], 200);
    }

    public function stok()
    {
        $data = array();
        $paket = PenjualanProduk::with('Produk.GudangBarangJadi')->where('is_aktif', 1)->get();
        foreach ($paket as $key_paket => $paket) {
            $data[$key_paket] = array(
                'id' => $paket->id,
                'nama_paket' => $paket->nama,
                'nama_alias' => $paket->nama_alias,
                'harga' => $paket->harga,
                'produk' => array()
            );
            foreach ($paket->produk as $key_prd => $detail) {
                $data[$key_paket]['produk'][$key_prd] = array(
                    'id' => $detail->id,
                    'merk' => $detail->merk,
                    'nama' => $detail->nama,
                    'variasi' => array()
                );
                foreach ($detail->GudangBarangJadi as $key_gbj => $gbj) {
                    if ($gbj->nama == '') {
                        $nama_variasi = $detail->nama;
                    } else {
                        $nama_variasi = $gbj->nama;
                    }

                    if ($gbj->produk->merk == 'RGB') {
                        $stok = $gbj->stok_siap;
                    } else {
                        $stok = $gbj->stok_siap > 0 ? 'Tersedia' : "Kurang";
                    }
                    $data[$key_paket]['produk'][$key_prd]['variasi'][$key_gbj] = array(
                        'id' => $gbj->id,
                        'nama' => $nama_variasi,
                        'stok' => $stok,
                    );
                }
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'jumlah' => count($data),
            'data'    => $data
        ], 200);
    }


    public function paket_produk()
    {
        $data = array();
        $paket = PenjualanProduk::with('Produk')->get();
        foreach ($paket as $key_paket => $paket) {
            $data[$key_paket] = array(
                'id' => $paket->id,
                'nama_paket' => $paket->nama,
                'nama_alias' => $paket->nama_alias,
                'harga' => $paket->harga,
                'produk' => array()
            );
            foreach ($paket->produk as $key_prd => $detail) {
                $data[$key_paket]['produk'][$key_prd] = array(
                    'id' => $detail->id,
                    'nama' => $detail->nama,
                    'jumlah' => $detail->pivot->jumlah,

                );
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'jumlah' => count($data),
            'data'    => $data
        ], 200);
    }

    public function coo()
    {
        $save_response = SaveResponse::where(['tipe' => 'coo', 'response' => 'ok', 'method' => 'post'])->pluck('parameter');
        $get_coo = NoseriCoo::select('noseri_coo.id',)
            ->Join('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
            ->Join('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
            ->Join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
            ->Join('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->Join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->Join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->Join('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan.penjualan_produk_id')
            ->Join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'penjualan_produk.id')
            ->Join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
            ->Join('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
            ->Join('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->Join('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->Join('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->where(['noseri_coo.ket' => 'emiindo'])
            ->whereNotIN('noseri_barang_jadi.noseri', $save_response)
            ->pluck('noseri_coo.id');


        $coo = NoseriCoo::with(['NoseriDetailLogistik.NoseriDetailPesanan.NoseriTGbj.seri.GudangBarangJadi.Produk', 'NoseriDetailLogistik.DetailLogistik.Logistik', 'NoseriDetailLogistik.NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog'])->whereIN('noseri_coo.id', $get_coo)->orderByDesc('created_at')->get();
        $data = array();
        foreach ($coo as $key_coo => $coo) {
            $data[$key_coo] = array(
                'id' => $coo->id,
                'no_coo' => $coo->no_coo . '@SKA@' . $this->bulan_romawi($coo->NoseriDetailLogistik->DetailLogistik->Logistik->tgl_kirim) . '@SPA@' . $coo->tahun,
                'noseri' => $coo->NoseriDetailLogistik->NoseriDetailPesanan->NoseriTGbj->seri->noseri,
                'tgl_sj' => $coo->NoseriDetailLogistik->DetailLogistik->Logistik->tgl_kirim,
                'no_akd' => $coo->NoseriDetailLogistik->NoseriDetailPesanan->NoseriTGbj->seri->GudangBarangJadi->Produk->no_akd,
                'nama' => $coo->NoseriDetailLogistik->NoseriDetailPesanan->NoseriTGbj->seri->GudangBarangJadi->Produk->nama_coo,
                'tipe' => $coo->NoseriDetailLogistik->NoseriDetailPesanan->NoseriTGbj->seri->GudangBarangJadi->Produk->nama,
                'merk' => $coo->NoseriDetailLogistik->NoseriDetailPesanan->NoseriTGbj->seri->GudangBarangJadi->Produk->merk,
                'instansi' =>  $coo->NoseriDetailLogistik->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi,
                'no_akn' => $coo->NoseriDetailLogistik->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->no_paket,
                'deskripsi' => $coo->NoseriDetailLogistik->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->deskripsi,
                'pic' => $coo->ket == 'spa' ? 'Kusmardiana Rahayu' : 'Bambang Hendro M BE',
            );
        }


        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'data' => $data
        ], 200);
        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Berhasil',
        // ], 200);
    }


    public function pengiriman()
    {
        $save_response = SaveResponse::where(['tipe' => 'pengiriman', 'response' => 'ok', 'method' => 'post'])->pluck('parameter');
        $logistik_ekat = collect(Logistik::with(['DetailLogistik.DetailPesananProduk.GudangBarangJadi.Produk', 'DetailLogistik.NoseriDetailLogistik.NoseriDetailPesanan.NoseriTGbj'])->select(
            'logistik.nosurat as no_surat',
            'logistik.tgl_kirim as tgl_sj',
            'logistik.ekspedisi_id as ekspedisi_id',
            'ekspedisi.nama as nama_ekspedisi',
            'logistik.id as id',
            'logistik.noresi as no_resi',
            'pesanan.no_po as po',
            'pesanan.tgl_po as tglpo',
        )
            ->leftJoin('ekspedisi', 'ekspedisi.id', '=', 'logistik.ekspedisi_id')
            ->leftJoin('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->where('ekatalog.customer_id', 213)
            ->whereNotIN('logistik.nosurat', $save_response)
            ->groupby('logistik.nosurat')
            ->get());

        $logistik_spa = collect(Logistik::with(['DetailLogistik.DetailPesananProduk.GudangBarangJadi.Produk', 'DetailLogistik.NoseriDetailLogistik.NoseriDetailPesanan.NoseriTGbj'])->select(
            'logistik.nosurat as no_surat',
            'logistik.tgl_kirim as tgl_sj',
            'logistik.ekspedisi_id as ekspedisi_id',
            'ekspedisi.nama as nama_ekspedisi',
            'logistik.id as id',
            'logistik.noresi as no_resi',
            'pesanan.no_po as po',
            'pesanan.tgl_po as tglpo',
        )
            ->leftJoin('ekspedisi', 'ekspedisi.id', '=', 'logistik.ekspedisi_id')
            ->leftJoin('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
            ->leftJoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
            ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
            ->where('spa.customer_id', 213)
            ->whereNotIN('logistik.nosurat', $save_response)
            ->groupby('logistik.nosurat')
            ->get());

        $logistik = $logistik_ekat->merge($logistik_spa);

        foreach ($logistik as $key_logistik => $logistik) {
            $data[$key_logistik] = array(
                'id' => $logistik->id,
                'no_sj' => $logistik->no_surat,
                'tgl_sj' => $logistik->tgl_sj,
                'ekspedisi_id' => $logistik->ekspedisi_id,
                'pengirim' => $logistik->nama_pengirim,
                'no_resi' => $logistik->no_resi,
                'nopo' => $logistik->po,
                'tglpo' => $logistik->tglpo,
                'detail' => array()
            );

            foreach ($logistik->DetailLogistik as $key_detail => $detail) {
                $data[$key_logistik]['detail'][$key_detail] = array(
                    'id' => $detail->id,
                    'produk_id' => $detail->DetailPesananProduk->GudangBarangJadi->Produk->id,
                    'produk' => $detail->DetailPesananProduk->GudangBarangJadi->Produk->nama,
                    'jumlah' => $detail->jumlah(),
                    // 'seri' => array(),
                );

                // foreach ($detail->NoseriDetailLogistik as $key_seri => $seri) {
                //     $data[$key_logistik]['detail'][$key_detail]['seri'][$key_seri] = array(
                //         'id' => $seri->id,
                //         'noseri' => isset($seri->NoseriDetailPesanan->NoseriTGbj->seri->noseri) ? implode(',',$seri->NoseriDetailPesanan->NoseriTGbj->seri->noseri) : 'dd',
                //     );
                // }
            }
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
            'jumlah' => count($data),
            'data'    => $data
        ], 200);
    }

    public function accept_coo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'seri' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'No seri harus di isi'
            ], 400);
        } else {
            $cek_response = SaveResponse::where(['tipe' => 'coo', 'method' => 'post', 'response' => 'ok', 'parameter' => $request->seri])->count();
            $cek_seri = NoseriBarangJadi::where('noseri', $request->seri)->count();

            if ($cek_seri > 0) {
                if ($cek_response > 0) {
                    return response()->json([
                        'status' => 409,
                        'message' => 'Duplikasi data',
                    ], 409);
                } else {

                    $seri = $request->seri;
                    $accept =   SaveResponse::create([
                        'tipe' => 'coo',
                        'url' =>  URL::current(),
                        'parameter' => $seri,
                        'response' => 'ok',
                        'method' => 'post',
                        'created_at' => Carbon::now(),
                    ]);
                    return response()->json([
                        'status' => 200,
                        'message' => 'Berhasil',
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }
        }
    }

    public function accept_pengiriman(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sj' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'SJ Harus di isi'
            ], 400);
        } else {
            $cek_response = SaveResponse::where(['tipe' => 'pengiriman', 'method' => 'post', 'response' => 'ok', 'parameter' => $request->sj])->count();
            $cek_pengiriman = Logistik::where('nosurat', $request->sj)->count();

            if ($cek_pengiriman > 0) {
                if ($cek_response > 0) {
                    return response()->json([
                        'status' => 409,
                        'message' => 'Duplikasi data',
                    ], 409);
                } else {
                    SaveResponse::create([
                        'tipe' => 'pengiriman',
                        'url' =>  URL::current(),
                        'parameter' => $request->sj,
                        'response' => 'ok',
                        'method' => 'post',
                        'created_at' => Carbon::now(),
                    ]);
                    return response()->json([
                        'status' => 200,
                        'message' => 'Berhasil',
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }
        }
    }


    static function bulan_romawi($value)
    {
        $bulan =  Carbon::createFromFormat('Y-m-d', $value)->format('m');
        $to = new PostsController();
        $x = $to->toRomawi($bulan);
        return $x;
    }

    public function toRomawi($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
