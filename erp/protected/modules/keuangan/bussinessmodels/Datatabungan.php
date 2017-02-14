<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Datatabungan extends Tabungan {
    //put your code here
    
    public $tanggal;
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pelangganid,tanggal,jumlah', 'required'),	
                        array('pelangganid,tanggal,jumlah', 'safe', 'on'=>'search'),
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
    
    public function listTabungan()
    {
       // Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('pelangganid',$this->pelangganid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('updateddate',$this->updateddate,true);

        
        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,                
                'pagination'=>array(
                                    'pageSize'=>50,
                                ),
        ));        
        
    }
    
   
       
}
