<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Lappenjualanbaranggudang extends Penjualanbarang
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
        
        public function getPenjualanBarangPerTanggal($tanggal)
        {
            $connection=Yii::app()->db;
            $lokasi = Yii::app()->session['lokasiid'];
            $divisiid = Divisi::model()->find("kode='1'")->id;
            $sql="select s.namaperusahaan as supplier,b.nama as barang,
                    p.nama as pelanggan,pb.tanggal,pb.jumlah,pb.hargasatuan,pb.hargatotal
                     from transaksi.penjualanbarang pb 
                            inner join master.barang b on b.id=pb.barangid
                                    inner join master.pelanggan p on p.id=pb.pelangganid
                                            inner join master.supplier s 
                                                    on s.id=pb.supplierid
                                                    where cast(pb.tanggal as date)='$tanggal'
                                                            and pb.divisiid=$divisiid and pb.lokasipenyimpananbarangid=$lokasi" ;					
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function getTotalPenjualanBarangPerTanggal($tanggal)
        {
            $connection=Yii::app()->db;
            $lokasi = Yii::app()->session['lokasiid'];
            $divisiid = Divisi::model()->find("kode='1'")->id;
           $sql="select sum(pb.jumlah) as totaljumlah,sum(pb.hargasatuan) as totalhargasatuan,sum(pb.hargatotal) as totalhargatotal
                     from transaksi.penjualanbarang pb 
                            inner join master.barang b on b.id=pb.barangid
                                    inner join master.pelanggan p on p.id=pb.pelangganid
                                            inner join master.supplier s 
                                                    on s.id=pb.supplierid
                                                    where cast(pb.tanggal as date)='$tanggal'
                                                            and pb.divisiid=$divisiid and pb.lokasipenyimpananbarangid=$lokasi";					
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totaljumlah"].'|'.$data['totalhargasatuan'].'|'.$data['totalhargatotal'];
        }
        
        public function getPenjualanBarangPerBulan($bulan)
        {
            $connection=Yii::app()->db;
            $currYear = date("Y");
            $lokasi = Yii::app()->session['lokasiid'];
            
            $divisiid = Divisi::model()->find("kode='1'")->id;
            $sql="select s.namaperusahaan as supplier,b.nama as barang,
                p.nama as pelanggan,pb.tanggal,pb.jumlah,pb.hargasatuan,pb.hargatotal
                 from transaksi.penjualanbarang pb 
                        inner join master.barang b on b.id=pb.barangid
                                inner join master.pelanggan p on p.id=pb.pelangganid
                                        inner join master.supplier s 
                                                on s.id=pb.supplierid
                                                where EXTRACT(MONTH FROM tanggal)='$bulan' and EXTRACT(YEAR FROM tanggal)='$currYear'
                                                        and pb.divisiid=$divisiid and pb.lokasipenyimpananbarangid=$lokasi";					
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function getTotalPenjualanBarangPerBulan($bulan)
        {
            $connection=Yii::app()->db;
            $currYear = date("Y");
            
            $lokasi = Yii::app()->session['lokasiid'];
            $divisiid = Divisi::model()->find("kode='1'")->id;
            $sql="select sum(pb.jumlah) as totaljumlah,sum(pb.hargasatuan) as totalhargasatuan,sum(pb.hargatotal) as totalhargatotal
                 from transaksi.penjualanbarang pb 
                        inner join master.barang b on b.id=pb.barangid
                                inner join master.pelanggan p on p.id=pb.pelangganid
                                        inner join master.supplier s 
                                                on s.id=pb.supplierid
                                                where EXTRACT(MONTH FROM tanggal)='$bulan' and EXTRACT(YEAR FROM tanggal)='$currYear'
                                                        and pb.divisiid=$divisiid and pb.lokasipenyimpananbarangid=$lokasi";					
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totaljumlah"].'|'.$data['totalhargasatuan'].'|'.$data['totalhargatotal'];
        }
        
}