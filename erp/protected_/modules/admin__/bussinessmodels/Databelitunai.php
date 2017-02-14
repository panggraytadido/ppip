<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Databelitunai extends Pembelianbarangtunai {
    //put your code here
    
    public $nik;
    public $tujuan;
    public $jumlah;
    public $karyawan;
    public $kendaraan;
    public $bayar;
	public $pelanggan;
	public $pelangganid;
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal,pelangganid,jumlah,harga,total', 'required'),			
		);
	}
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tanggal' => 'Tanggal',
			'pelangganid' => 'Pelanggan',
			'jumlah' => 'jumlah',
		);
	}
    
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    
    public function listDataBeliTunai()
    {        
        $criteria=new CDbCriteria;
		$criteria->select="pel.id as pelangganid,pel.nama as pelanggan,p.nama,p.jumlah,p.total,p.harga";
		$criteria->alias = "p";
		$criteria->join="INNER JOIN master.pelanggan pel on pel.id=p.pelangganid";

        if(isset($_GET['Databelitunai']))
        {                                        
            if($_GET['Databelitunai']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Databelitunai']['tanggal'])));

            if($_GET['Databelitunai']['pelanggan']!='')
            {
                $criteria->compare('upper(pel.nama)',  strtoupper ($_GET['Databelitunai']['pelanggan']),true);  
            }         
        }       

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>10,
                ),
        ));        
        
    }
          
}
