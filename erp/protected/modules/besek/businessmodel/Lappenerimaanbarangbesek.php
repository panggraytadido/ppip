<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Lappenerimaanbarangbesek extends Penerimaanbarang
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
        
        public function getPenerimaanBarangPerTanggal($tanggal)
        {
            $connection=Yii::app()->db;
            $lokasi = Yii::app()->session['lokasiid'];
            $divisiid = Divisi::model()->find("kode='5'")->id;
            $sql="SELECT 
                    s.namaperusahaan as supplier,b.nama as barang,pb.jumlah,cast(pb.tanggal as date),hb.hargamodal,pb.jumlah*hb.hargamodal as totalharga
                  FROM 
                    transaksi.penerimaanbarang pb, 
                    master.supplier s, 
                    transaksi.hargabarang hb,
                    master.barang b
                  WHERE 
                    pb.supplierid = s.id AND
                    pb.barangid = hb.barangid AND
                    pb.supplierid = hb.supplierid AND
                    b.id=hb.barangid AND 
                    cast(pb.tanggal as date)='$tanggal' and pb.divisiid=$divisiid and pb.lokasipenyimpananbarangid=$lokasi order by cast(pb.tanggal as date) asc";					
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function getTotalPenerimaanBarangPerTanggal($tanggal)
        {
            $connection=Yii::app()->db;
            $lokasi = Yii::app()->session['lokasiid'];
            $divisiid = Divisi::model()->find("kode='5'")->id;
           $sql="SELECT 
                    sum(pb.jumlah) as totaljumlah,sum(hb.hargamodal) as totalmodal,sum(hb.hargamodal*pb.jumlah) as totalharga
                  FROM 
                    transaksi.penerimaanbarang pb, 
                    master.supplier s, 
                    transaksi.hargabarang hb,
                    master.barang b
                  WHERE 
                    pb.supplierid = s.id AND
                    pb.barangid = hb.barangid AND
                    pb.supplierid = hb.supplierid AND
                    b.id=hb.barangid AND 
                    cast(pb.tanggal as date)='$tanggal' and pb.divisiid=$divisiid and pb.lokasipenyimpananbarangid=$lokasi";					
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totaljumlah"].'|'.$data['totalmodal'].'|'.$data['totalharga'];
        }
        
        public function getPenerimaanBarangPerBulan($bulan)
        {
            $connection=Yii::app()->db;
            $currYear = date("Y");
            
            $lokasi = Yii::app()->session['lokasiid'];
            $divisiid = Divisi::model()->find("kode='5'")->id;
            $sql="SELECT 
                    s.namaperusahaan as supplier,b.nama as barang,pb.jumlah,cast(pb.tanggal as date),hb.hargamodal,pb.jumlah*hb.hargamodal as totalharga
                  FROM 
                    transaksi.penerimaanbarang pb, 
                    master.supplier s, 
                    transaksi.hargabarang hb,
                    master.barang b
                  WHERE 
                    pb.supplierid = s.id AND
                    pb.barangid = hb.barangid AND
                    pb.supplierid = hb.supplierid AND
                    b.id=hb.barangid AND 
                    EXTRACT(MONTH FROM pb.tanggal)='$bulan' and EXTRACT(YEAR FROM pb.tanggal)='$currYear' and pb.lokasipenyimpananbarangid=$lokasi order by cast(pb.tanggal as date) asc";					
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function getTotalPenerimaanBarangPerBulan($bulan)
        {
            $connection=Yii::app()->db;
            $currYear = date("Y");
            $lokasi = Yii::app()->session['lokasiid'];
            
            $divisiid = Divisi::model()->find("kode='5'")->id;
            $sql="  SELECT 
                    sum(pb.jumlah) as totaljumlah,sum(hb.hargamodal) as totalmodal,sum(hb.hargamodal*pb.jumlah) as totalharga
                        FROM 
                          transaksi.penerimaanbarang pb, 
                          master.supplier s, 
                          transaksi.hargabarang hb,
                          master.barang b
                        WHERE 
                          pb.supplierid = s.id AND
                          pb.barangid = hb.barangid AND
                          pb.supplierid = hb.supplierid AND
                          b.id=hb.barangid AND 
                          EXTRACT(MONTH FROM pb.tanggal)='$bulan' and EXTRACT(YEAR FROM pb.tanggal)='$currYear' and pb.lokasipenyimpananbarangid=$lokasi";					
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totaljumlah"].'|'.$data['totalmodal'].'|'.$data['totalharga'];
        }
}