<?php

class Transfermasuk extends Faktur
{                       
        public $pelanggan;
        public $bank;
    
	public function listTransferMasuk()
	{
		$criteria=new CDbCriteria;
                     
                $criteria->select = "cast(f.tanggal as date) as tanggal,pel.nama as pelanggan,f.pelangganid,"
                        . "f.nofaktur,f.hargatotal,f.bayar,f.sisa,f.id ";
                $criteria->alias = "f";
                $criteria->join = "INNER JOIN master.pelanggan AS pel ON pel.id=f.pelangganid";          
                $criteria->condition = "f.sisa!=0";
                $criteria->order="f.tanggal desc";
                
                if(isset($_GET['Transfermasuk']))
                {                                        
                    if($_GET['Transfermasuk']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Transfermasuk']['tanggal'])));
                    
                    if($_GET['Transfermasuk']['pelanggan']!='')
                        $criteria->compare('upper(pel.nama)',  strtoupper ($_GET['Transfermasuk']['pelanggan']),true); 
                    
                    if($_GET['Transfermasuk']['nofaktur']!='')
                        $criteria->compare('upper(f.nofaktur)',  strtoupper ($_GET['Transfermasuk']['nofaktur']),true); 
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'p.id desc',
                    'f.nofaktur',                  
                    'f.tanggal'=>array(
                                      'asc'=>'f.tanggal',
                                      'desc'=>'f.tanggal desc',
                                    ),                  
                    'f.pelanggan'=>array(
                                      'asc'=>'f.pelanggan',
                                      'desc'=>'f.pelanggan desc',
                                    ),                  
                );
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
                        'pagination'=>array(
                                            'pageSize'=>10,
					),
		));
	}                            
	
	
	public function detail($fakturid)
	{
		 $criteria=new CDbCriteria;
		 $criteria->select = "*";		
		 $criteria->condition = "jenisbayar='transfer' and fakturid=".$fakturid;		
                
               
		$sort = new CSort();
		$sort->attributes = array(
			'defaultOrder'=>'f.id desc',		
		);
			
		return new CActiveDataProvider('Setoran', array(
			'criteria'=>$criteria,
				'sort'=>$sort,
				'pagination'=>array(
						'pageSize'=>10,
			),
		));
		/*
		$sql ="select * from transaksi.setoran where jenisbayar='transfer' and fakturid=$fakturid";
            
            $rawData = Yii::app()->db->createCommand($sql); //or use ->queryAll(); in CArrayDataProvider
            $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_alias')->queryScalar(); //the count

            return new CSqlDataProvider($rawData, array( //or $model=new CArrayDataProvider($rawData, array(... //using with querAll...
                    'keyField' => 'id', 
                    'totalItemCount' => $count,                      
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));  
				*/
	}
	
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
