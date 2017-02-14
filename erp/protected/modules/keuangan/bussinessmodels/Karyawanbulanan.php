<?php

class Karyawanbulanan extends Gajiperjabatan
{       
        public $nik;
        public $namakaryawan;
        public $namabarang;
        public $keterangan;
		
		public function attributeLabels()
		{	
			return array(
				'karyawanid' => 'Karyawan',
				'jabatanid' => 'Jabatan',
				'jumlah' => 'jumlah',
			);
		}
        
        public function searchKaryawanbulanan()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'k.nik,k.nama as namakaryawan,gj.gaji,gj.jabatanid,gj.id';
                $criteria->alias = 'gj';
                $criteria->join = 'INNER JOIN master.karyawan AS k ON gj.jabatanid=k.jabatanid ';                 
                $criteria->condition = "gj.karyawanid=k.id AND gj.gaji !=0 AND gj.gaji>=1000000";
                
                if(isset($_GET['Karyawanbulanan']))
                {
                    $criteria->compare('upper(k.nik)',strtoupper($_GET['Karyawanbulanan']['nik']));  
						$criteria->compare('upper(k.nama)',strtoupper($_GET['Karyawanbulanan']['karyawan']));                    
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
