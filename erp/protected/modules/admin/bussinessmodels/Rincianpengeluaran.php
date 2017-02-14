<?php

class Rincianpengeluaran extends Pengeluaran
{       
       
    public $tanggal;    
	public $divisi;
    
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

                    array('tanggal', 'required'),		                       
            );
    }    

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
            return array(
                    'tanggal' => 'Tanggal',            
					'divisi' => 'Divisi',            
            );
    }   
    
    public function listPengeluaran($tanggal,$divisi)
    {
        $criteria = new CDbCriteria;
        $criteria->select ="cast(tanggal as date) as tanggal,nama,jumlah,hargasatuan,total";
        $criteria->condition="cast(tanggal as date)='$tanggal' and divisiid=".$divisi;
        $data = Rincianpengeluaran::model()->findAll($criteria);
        return $data;
    }
    
    
    
    
}
