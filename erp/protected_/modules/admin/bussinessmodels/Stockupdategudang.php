<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Stockupdategudang extends Stocksupplier
{
    public $kode;
    public $nama;
    public $hargamodal;
    public $hargaeceran;
    public $hargagrosir;
	public $totalstock;
        
    public function rules()
{
    return array(
        
        array('nama', 'safe', 'on'=>'search'),
    );
}
    
    public function listStock()
    {
        $criteria=new CDbCriteria;
        $divisiid = Divisi::model()->find("kode='1'")->id;
        $criteria->with=array('barang');  
        $criteria->condition = 'divisiid = :divisiid';
        $criteria->params = array(':divisiid' =>$divisiid);

        $sort = new CSort();					
        $sort->attributes = array(
                        'pb.tanggal',
                        'barang.kode',
                        'pelangganid',
                        'barang.nama', 
                        'supplierid',
                        'tanggal'
        );
        
        
        return new CActiveDataProvider('Stock', array(
            'criteria'=>$criteria,
            'sort'=>array(
                //'defaultOrder'=>'t.id ASC',
            ),
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));
    }
    
    public function listStockUpdate()
    {
		$criteria=new CDbCriteria;
        $divisiid = Divisi::model()->find("kode='1'")->id;
        $criteria->select = 'b.id as id,                                         
            sum(s.jumlah) as totalstock';

        $criteria->alias = 's';          
        $criteria->join = 'INNER JOIN master.barang AS b ON b.id=s.barangid';
        $criteria->group= 'b.id';
        $criteria->condition = 'b.divisiid='.$divisiid;
        $criteria->order ='b.id asc';

        if(isset($_GET['Stockupdategudang']))
        {                                        
            if($_GET['Stockupdategudang']['barang']!='')                                            
             $criteria->compare('upper(b.nama)',  strtoupper($_GET['Stockupdategudang']['barang']),true);                     
        }

        $sort = new CSort();
        $sort->attributes = array(
            'defaultOrder'=>'b.id desc',                                              

        );

        return new CActiveDataProvider('Stockupdategudang', array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
									'pageSize'=>10,
                                ),
        ));
		
		/*
        $criteria=new CDbCriteria;
        $divisiid = Divisi::model()->find("kode='1'")->id;
        $criteria->select = 'hb.barangid as id,                                         
            sum(s.jumlah) as totalstock,
            CASE 
				WHEN sum(hb.hargamodal*s.jumlah ) <> 0 THEN (sum(hb.hargamodal*s.jumlah )/sum(s.jumlah))
				ELSE 0 
			END  as harga,
			CASE 
				WHEN sum(s.jumlah)<> 0 THEN (sum(hb.hargamodal*s.jumlah )/sum(s.jumlah)) * sum(s.jumlah)
				ELSE 0
			END  as total';

        $criteria->alias = 's';
        $criteria->join = 'INNER JOIN transaksi.hargabarang AS hb ON hb.barangid=s.barangid ';         
        $criteria->join .= ' INNER JOIN master.barang AS b ON b.id=hb.barangid';
        $criteria->group= 'hb.barangid';
        $criteria->condition = 's.barangid=s.barangid and b.divisiid='.$divisiid;
        $criteria->order ='hb.barangid asc';

        if(isset($_GET['Stockupdategudang']))
        {                                        
            if($_GET['Stockupdategudang']['barang']!='')                                            
             $criteria->compare('upper(b.nama)',  strtoupper($_GET['Stockupdategudang']['barang']),true);                     
        }

        $sort = new CSort();
        $sort->attributes = array(
            'defaultOrder'=>'b.barangid desc',                                              

        );

        return new CActiveDataProvider('Databarang', array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
									'pageSize'=>10,
                                ),
        ));
		*/
    }
       
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}