<?php

class Karyawansupir extends Perjalanan
{       
        public $nik;
        public $namakaryawan;
        public $tujuan;
        public $kendaraan;
        public $biaya;
        
        public function searchKaryawansupir()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'p.id, p.tanggal, k.nik, k.nama as namakaryawan, bp.nama as tujuan, m.nama as kendaraan, 
                                    bp.upah as biaya, bbm, p.upah';
                $criteria->alias = 'p';
                $criteria->join = 'INNER JOIN master.karyawan AS k ON p.karyawanid=k.id '; 
                $criteria->join .= 'INNER JOIN transaksi.biayaperjalanan AS bp ON p.biayaperjalananid=bp.id '; 
                $criteria->join .= 'LEFT JOIN referensi.kendaraan AS m ON p.kendaraanid=m.id '; 
               // $criteria->condition = "p.tanggal::text like '".date("Y-m-", time())."%'";
                
                if(isset($_GET['Karyawansupir']))
                {
                    $criteria->compare('p.tanggal',$_GET['Karyawansupir']['tanggal']);
                    $criteria->compare('k.nik',$_GET['Karyawansupir']['nik'],true);
                    $criteria->compare('k.nama',$_GET['Karyawansupir']['namakaryawan'],true);
                    $criteria->compare('bp.nama',$_GET['Karyawansupir']['tujuan'],true);
                    $criteria->compare('m.nama',$_GET['Karyawansupir']['kendaraan'],true);
                    $criteria->compare('bp.upah',$_GET['Karyawansupir']['biaya']);
                    $criteria->compare('p.bbm',$_GET['Karyawansupir']['bbm']);
                    $criteria->compare('p.upah',$_GET['Karyawansupir']['upah']);
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'tanggal desc',
                    'tanggal'=>array(
                                      'asc'=>'p.tanggal',
                                      'desc'=>'p.tanggal desc',
                                    ),
                    'nik'=>array(
                                      'asc'=>'k.nik',
                                      'desc'=>'k.nik desc',
                                    ),
                    'namakaryawan'=>array(
                                      'asc'=>'k.nama',
                                      'desc'=>'k.nama desc',
                                    ),
                    'tujuan'=>array(
                                      'asc'=>'bp.nama',
                                      'desc'=>'bp.nama desc',
                                    ),
                    'kendaraan'=>array(
                                      'asc'=>'m.nama',
                                      'desc'=>'m.nama desc',
                                    ),
                    'biaya'=>array(
                                      'asc'=>'bp.upah',
                                      'desc'=>'bp.upah desc',
                                    ),
                    'bbm',
                    'upah'
                );

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
		));
	}

        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
