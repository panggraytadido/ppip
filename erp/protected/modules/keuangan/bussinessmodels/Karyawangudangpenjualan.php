<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Karyawangudang
 *
 * @author DIDSI-IPB
 */
class Karyawangudangpenjualan extends Bongkarmuat {
    //put your code here
    
    public $pelanggan;
    public $barang;
    public $jumlah;
    public $karyawan;
    public $divisi;
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    
    public function listKaryawanPenjualanBarang()
    {
        $criteria=new CDbCriteria;

        $criteria->select = 'k.nama as karyawan,d.nama as divisi,p.nama as pelanggan,b.nama as barang,pb.jumlah as jumlah,bk.upah,bk.tanggal';
        $criteria->alias = 'bk';
        $criteria->join = 'INNER JOIN master.karyawan AS k ON k.id=bk.karyawanid ';
        $criteria->join .= ' INNER JOIN transaksi.penjualanbarang AS pb ON pb.id=bk.penjualanbarangid ';
        $criteria->join .= ' INNER JOIN master.barang AS b ON b.id=pb.barangid ';
        $criteria->join .= ' INNER JOIN master.pelanggan AS p ON p.id=pb.pelangganid ';
        $criteria->join .= ' INNER JOIN master.divisi AS d ON d.id=pb.divisiid ';
        
        if(isset($_GET['Karyawangudang']))
                {                                        
                    if($_GET['Karyawangudang']['divisiid']!='')
                        $criteria->addCondition("b.divisiid= $this->divisiid");
                    
                     $criteria->compare('b.nama',$_GET['Karyawangudang']['nama'],true);
                     
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
							'pageSize'=>10,
					),
		));
        
        
    }
       
}
