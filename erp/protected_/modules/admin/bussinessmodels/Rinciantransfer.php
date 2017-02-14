<?php

class Rinciantransfer extends Setoran
{       
    public $nofaktur;
    public $pelanggan;    
    public $jumlah;
    public $hargatotal;
    public $bayar;
    public $tanggal;    
    
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
    
    public function listDataTransfer($tanggal)
    {
        $criteria = new CDbCriteria;
        $criteria->select ="cast(s.tanggalsetoran as date) as tanggal,f.nofaktur,p.nama as pelanggan,s.jumlah as bayar,f.hargatotal";
        $criteria->alias="s";
        $criteria->join = "INNER JOIN transaksi.faktur f on f.id=s.fakturid";
        $criteria->join .= " INNER JOIN master.pelanggan p on p.id=f.pelangganid";        
        $criteria->order='f.nofaktur asc';
        
        $criteria->condition="cast(s.tanggalsetoran as date)='$tanggal' and s.jenisbayar='transfer'";
        $data = Rinciantransfer::model()->findAll($criteria);
        return $data;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
            return array(
                    'tanggal' => 'Tanggal',                    
            );
    }   
    
    
    
    
}
