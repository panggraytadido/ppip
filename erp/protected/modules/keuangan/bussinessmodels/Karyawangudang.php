<?php

class Karyawangudang extends Bongkarmuat
{       
        public $tanggalbm;
        public $namakaryawan;
        public $namabarang;
        public $keterangan;
        
        public function searchKaryawangudang()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'bm.id, bm.tanggal as tanggalbm, k.nama as namakaryawan, b.nama as namabarang, upah, 
                                    case when (penjualanbarangid>0 and penerimaanbarangid=0) then 
                                    \'Muat\'
                                    when (penjualanbarangid=0 and penerimaanbarangid>0) then 
                                    \'Bongkar\'
                                    end as keterangan';
                $criteria->alias = 'bm';
                $criteria->join = 'INNER JOIN master.karyawan AS k ON bm.karyawanid=k.id '; 
                $criteria->join .= 'left JOIN transaksi.penjualanbarang AS jual ON bm.penjualanbarangid=jual.id '; 
                $criteria->join .= 'left JOIN transaksi.penerimaanbarang AS beli ON bm.penerimaanbarangid=beli.id '; 
                $criteria->join .= 'left JOIN master.barang AS b ON b.id=jual.barangid or b.id=beli.barangid'; 
                $criteria->condition = "bm.isdeleted=false and bm.tanggal::text like '".date("Y-m-", time())."%'";
                
                if(isset($_GET['Karyawangudang']))
                {
                    $criteria->compare('bm.tanggal',$_GET['Karyawangudang']['tanggalbm']);
                    $criteria->compare('k.nama',$_GET['Karyawangudang']['namakaryawan'],true);
                    $criteria->compare('b.nama',$_GET['Karyawangudang']['namabarang'],true);
                    $criteria->compare('bm.upah',$_GET['Karyawangudang']['upah']);
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'tanggalbm desc',
                    'tanggalbm'=>array(
                                      'asc'=>'bm.tanggal',
                                      'desc'=>'bm.tanggal desc',
                                    ),
                    'namakaryawan'=>array(
                                      'asc'=>'k.nama',
                                      'desc'=>'k.nama desc',
                                    ),
                    'namabarang'=>array(
                                      'asc'=>'b.nama',
                                      'desc'=>'b.nama desc',
                                    ),
                    'upah'
                );
//                print_r($criteria);die();
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
                        'pagination'=>array(
                                    'pageSize'=>10,
                                ),
		));
	}

        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
