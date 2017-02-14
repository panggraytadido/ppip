<?php

class Karyawanharian extends Gajiperjabatan
{       
        public $nik;
        public $namakaryawan;
        public $namabarang;
        public $keterangan;
        
        public function searchKaryawanharian()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'gj.id,k.nik,k.nama as namakaryawan,gj.gaji,gj.jabatanid';
                $criteria->alias = 'gj';
                $criteria->join = 'INNER JOIN master.karyawan AS k ON gj.jabatanid=k.jabatanid ';                 
                $criteria->condition = "gj.karyawanid=k.id AND gj.gaji !=0 AND gj.gaji<=100000";
                
                if(isset($_GET['Karyawanharian']))
                {
                    $criteria->compare('upper(k.nik)',strtoupper($_GET['Karyawanharian']['nik']));      
					$criteria->compare('upper(k.nama)',strtoupper($_GET['Karyawanharian']['karyawan']));      	
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'nik desc',                    
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
