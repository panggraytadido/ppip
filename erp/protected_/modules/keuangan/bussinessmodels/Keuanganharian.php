<?php

class Keuanganharian extends Penjualanbarang
{       
        public $nilai;
        
        public function jumlahUangCash($tanggal)
	{
		$criteria=new CDbCriteria;

                $criteria=new CDbCriteria;

                $criteria->select = 'sum(jumlah) as jumlah';
                $criteria->condition = "cast(tanggalsetoran as date)='$tanggal' and jenisbayar='cash'";
                
                $nilai = Setoran::model()->find($criteria)->jumlah;
                
                if($nilai==null)
                    $nilai = 0;
                
                return $nilai;
	}
        
        public function jumlahUangSetoran($tanggal)
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'sum(jumlah) as jumlah';
                $criteria->condition = "cast(tanggalsetoran as date)='$tanggal' and jenisbayar='transfer'";
                
                $nilai = Setoran::model()->find($criteria)->jumlah;
                
                if($nilai==null)
                    $nilai = 0;
                
                return $nilai;
	}
        
        public function jumlahUangTabungan($tanggal)
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'sum(jumlah) as jumlah';
                $criteria->condition = "cast(tanggal as date)='$tanggal'";
                
                $nilai = Jumlahtabungan::model()->find($criteria)->jumlah;
                
                if($nilai==null)
                    $nilai = 0;
                
                return $nilai;
	}
        
        public function jumlahBeliTunai($tanggal)
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'sum(jumlah) as jumlah';
                $criteria->condition = "cast(tanggal as date)='$tanggal'";
                
                $nilai = Pembelianbarangtunai::model()->find($criteria)->jumlah;
                
                if($nilai==null)
                    $nilai = 0;
                
                return $nilai;
	}
        
        public function jumlahPengeluaran($tanggal)
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'sum(total) as total';
                $criteria->condition = "cast(tanggal as date)='$tanggal'";
                
                $nilai = Pengeluaran::model()->find($criteria)->total;
                
                if($nilai==null)
                    $nilai = 0;
                
                return $nilai;
	}
        
        public function jumlahTransfer($tanggal)
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'sum(jumlah) as jumlah';
                $criteria->condition = "cast(tanggal as date)='$tanggal'";
                
                $nilai = Transferkasir::model()->find($criteria)->jumlah;
                
                if($nilai==null)
                    $nilai = 0;
                
                return $nilai;
	}

        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
