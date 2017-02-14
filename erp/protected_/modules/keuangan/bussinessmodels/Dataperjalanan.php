<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Dataperjalanan extends Perjalanan {
    //put your code here
    
    public $nik;
    public $tujuan;
    public $jumlah;
    public $karyawan;
    public $kendaraan;
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    
    public function listPerjalanan()
    {
        $criteria=new CDbCriteria;

        $criteria->select = 'p.id,p.tanggal as tanggal,k.nama as karyawan,k.nik ,bp.nama as tujuan,bp.upah,knd.nama as kendaraan,p.bbm,p.upah';
        $criteria->alias = 'p';
        $criteria->join = 'INNER JOIN transaksi.biayaperjalanan AS bp ON bp.id=p.biayaperjalananid ';      
        $criteria->join .= ' INNER JOIN master.karyawan AS k ON k.id=p.karyawanid ';      
        $criteria->join .= ' INNER JOIN referensi.kendaraan AS knd ON knd.id=p.kendaraanid ';      
        
        if(isset($_GET['Dataperjalanan']))
        {                                        
            if($_GET['Dataperjalanan']['divisiid']!='')
                $criteria->addCondition("b.divisiid= $this->divisiid");

             $criteria->compare('b.nama',$_GET['Dataperjalanan']['nama'],true);
        }

        $sort = new CSort();
        $sort->attributes = array(
            'defaultOrder'=>'b.id desc',                                              
            'karyawan',
            'divisi',
            'pelanggan',
            'barang',
            'tanggal',                    
        );
           
        
        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                                    'pageSize'=>50,
                                ),
        ));        
        
    }
    
    public function hitungBiaya($kota,$kendaraan)
    {
        $connection=Yii::app()->db;
            $sql ="select sum(pb.hargatotal) as totalbelanja
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.jenispembayaran='kredit' and pb.pelangganid=$pelangganid and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalbelanja"];
    }
       
}
