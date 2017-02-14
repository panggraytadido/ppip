<?php

class Datajabatan extends Karyawan
{       
    
    public $jumlahgaji;
    public $potongan;
    public $jabatan;
    
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('nik,nama', 'required'),		
                        array('nik, nama', 'safe', 'on'=>'search'),
		);
	}    
    
    public function search()
    {
                $criteria=new CDbCriteria;

                $criteria->select = 'k.nik,k.nama, j.nama as jabatan,k.id';
                $criteria->alias = 'k';
                $criteria->join = 'INNER JOIN referensi.jabatan AS j ON j.id=k.jabatanid ';              

                if(isset($_GET['Datajabatan']))
                {
                    $criteria->compare('k.nama',$_GET['Datajabatan']['nama'],true);    
                    $criteria->compare('k.nik',$_GET['Datajabatan']['nik'],true);          
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'k.id desc',                
                    'nama'=>array(
                                      'asc'=>'nama',
                                      'desc'=>'nama desc',
                                    ),
                     'jabatan'=>array(
                                      'asc'=>'jabatan',
                                      'desc'=>'jabatan desc',
                                    ),
                    'nik'=>array(
                                      'asc'=>'nik',
                                      'desc'=>'nik desc',
                                    ),                 
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
