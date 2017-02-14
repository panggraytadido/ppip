<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Lapstockupdategudang extends Stocksupplier
{
        public $pilih;
	public $tanggal;
	public $bulan;
        public $tahun;
        
        public function rules()
	{
		return array(
			// username and password are required
			array('pilih', 'required'),			
		);
	}
        
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getStockUpdate()
        {
            $connection=Yii::app()->db;
            $lokasi = Yii::app()->session['lokasiid'];
            $divisiid = Divisi::model()->find("kode='1'")->id;
            $sql="SELECT 
                    hargabarang.barangid as barangid,                     
                    master.barang.nama as barang,
                    sum(stocksupplier.jumlah) as totalstock,
                     CASE 
						WHEN sum(hargabarang.hargamodal*stocksupplier.jumlah ) <> 0 THEN (sum(hargabarang.hargamodal*stocksupplier.jumlah )/sum(stocksupplier.jumlah))
						ELSE 0 
					END  as harga,
					CASE 
						WHEN sum(stocksupplier.jumlah)<> 0 THEN (sum(hargabarang.hargamodal*stocksupplier.jumlah )/sum(stocksupplier.jumlah)) * sum(stocksupplier.jumlah)
						ELSE 0
					END  as total
                   FROM 
                     transaksi.hargabarang, 
                     transaksi.stocksupplier,
                     master.barang
                   WHERE 
                     hargabarang.supplierid = stocksupplier.supplierid AND
                     hargabarang.barangid = stocksupplier.barangid 
                     AND hargabarang.barangid=barang.id
                    group by hargabarang.barangid,master.barang.divisiid,master.barang.nama,stocksupplier.lokasipenyimpananbarangid
                   having master.barang.divisiid='$divisiid' and stocksupplier.lokasipenyimpananbarangid='$lokasi' order by hargabarang.barangid asc";					
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }      
        
}