<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pembelianbarangtunai2 extends Pembelianbarangtunai
{   
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('nama,pelangganid,tanggal', 'required'),		
                        array('nama, pelangganid, tanggal', 'safe', 'on'=>'search'),
		);
	}    
        
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
            return array(
                    'id' => 'ID',
                    'nama' => 'Nama Barang',			
                    'pelangganid' => 'Pelanggan',			
            );
    }
    
    
    public function search()
    {
                $criteria=new CDbCriteria;

                $criteria->select = 'pbt.id,pbt.tanggal, pbt.nama, pbt.pelangganid, pbt.jumlah, pbt.harga, pbt.total';
                $criteria->alias = 'pbt';
                $criteria->join = 'INNER JOIN master.pelanggan AS p ON p.id=pbt.pelangganid ';              

                if(isset($_GET['Pembelianbarangtunai2']))
                {
                    $criteria->compare('p.nama',$_GET['Pembelianbarangtunai2']['nama'],true);                
                    if($_GET['Pembelianbarangtunai2']['tanggal']!='')
                        $criteria->compare('pbt.tanggal', date("Y-m-d", strtotime($_GET['Pembelianbarangtunai2']['tanggal'])));

                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'pbt.id desc',
                    'pbt.id',      
                    'pelanggan'=>array(
                                      'asc'=>'pelanggan',
                                      'desc'=>'pelanggan desc',
                                    ),
                     'tanggal'=>array(
                                      'asc'=>'pbt.tanggal',
                                      'desc'=>'pbt.tanggal desc',
                                    ),
                    'nama'=>array(
                                      'asc'=>'pbt.nama',
                                      'desc'=>'pbt.nama desc',
                                    ),                 
                );

                return new CActiveDataProvider($this, array(
                        'criteria'=>$criteria,
                        'sort'=>$sort,
                ));
    }
    
    
    
}