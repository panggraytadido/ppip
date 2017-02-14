<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Datakasbon extends Kasbon {
    //put your code here
    
    public $nik;
    public $tujuan;
    public $jumlah;
    public $karyawan;
    public $kendaraan;
    public $bayar;
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal,karyawanid,jumlah', 'required'),			
		);
	}
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tanggal' => 'Tanggal',
			'karyawanid' => 'Karyawan',
			'jumlah' => 'jumlah',
		);
	}
    
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    
    public function listKasbon()
    {
        $criteria=new CDbCriteria;

        $criteria->select = 'k.id,kar.nama as karyawan,k.tanggal as tanggal,k.jumlah as jumlah,k.keterangan,kar.nik';
        $criteria->alias = 'k';
        $criteria->join = 'INNER JOIN master.karyawan AS kar ON kar.id=k.karyawanid ';     
        $criteria->condition = "status=false";
       
        
        if(isset($_GET['Datakasbon']))
        {                                        
            if($_GET['Datakasbon']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Datakasbon']['tanggal'])));

            $criteria->compare('kar.nik',$_GET['Datakasbon']['nik'],true);
            
            $criteria->compare('LOWER(kar.nama)',  strtolower($_GET['Datakasbon']['karyawan']),true);
        }

        $sort = new CSort();
        $sort->attributes = array(
            'defaultOrder'=>'b.id desc',                                              
            'karyawan',
            'tanggal',
            'jumlah',            
        );
           
        
        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                                    'pageSize'=>10,
                                ),
        ));        
        
    }
    
    public function getTotalBayar($id)
    {
        $connection=Yii::app()->db;
        $sql ="select sum(pk.jumlah) as totalbayar from transaksi.kasbon k inner join transaksi.pembayarankasbon pk on pk.kasbonid=k.id where k.id=$id";            
        $data=$connection->createCommand($sql)->queryRow();
        return $data["totalbayar"];
    }
      
       
}
