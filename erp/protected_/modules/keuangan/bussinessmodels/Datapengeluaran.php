<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Datapengeluaran extends Pengeluaran {
    //put your code here
    
    public $nik;
    public $tujuan;
    public $jumlah;
    public $karyawan;
    public $kendaraan;
    public $bayar;
    public $divisiid;
    public $divisi;
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    
    public function rules()
    {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                    array('tanggal,divisiid,nama', 'required'),			
            );
    }
    
    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tanggal' => 'Tanggal',
			'divisiid' => 'Divisi',
			'jumlah' => 'jumlah',
		);
	}
    
    public function listPengeluaran()
    {
        $criteria=new CDbCriteria;

        $criteria->select = 'p.id,d.nama as divisi,p.nama,p.tanggal as tanggal,p.jumlah as jumlah,p.hargasatuan,p.total';
        $criteria->alias = 'p';
        $criteria->join = 'INNER JOIN master.divisi AS d ON d.id=p.divisiid ';             
       
        
        if(isset($_GET['Datapengeluaran']))
        {                                        
            if($_GET['Datapengeluaran']['divisiid']!='')
                $criteria->addCondition("b.divisiid= $this->divisiid");

             $criteria->compare('b.nama',$_GET['Datapengeluaran']['nama'],true);
        }

        $sort = new CSort();
        $sort->attributes = array(
            'defaultOrder'=>'p.id desc',                                              
            'divisi',
            'tanggal',
            'nama',
            'jumlah',
            'hargasatuan',
            'total',    
        );
           
        
        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                                    'pageSize'=>50,
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
