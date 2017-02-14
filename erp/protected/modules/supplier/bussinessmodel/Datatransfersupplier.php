<?php

class Datatransfersupplier extends Transfer
{       
        public $divisi;
        public $barang;
        public $stockkeseluruhan;
        public $terjual;
        public $sisa;

        public function search()
	{
		$criteria=new CDbCriteria;
                $criteria->condition='supplierid='.Yii::app()->session['supplierid'];;

                if(isset($_GET['Databarangpersupplier']))
                {
                    $criteria->compare('upper(d.nama)',  strtoupper($_GET['Databarangpersupplier']['divisi']),true);
                    $criteria->compare('upper(d.nama)',  strtoupper($_GET['Databarangpersupplier']['barang']),true);                                        
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'b.id desc',
                  
                );
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
		));
	}
        
        public function listDetail($id)
        {
            $sql ="select b.nama as barang,lpb.nama as lokasi,ss.jumlah as stock from transaksi.stocksupplier ss inner join transaksi.lokasipenyimpananbarang lpb on lpb.id=ss.lokasipenyimpananbarangid
                        inner join master.barang b on b.id=ss.barangid
                            where ss.supplierid=".Yii::app()->session['supplierid']." and ss.barangid=$id order by lpb.nama asc";
            
            $rawData = Yii::app()->db->createCommand($sql); //or use ->queryAll(); in CArrayDataProvider
            $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_alias')->queryScalar(); //the count

            return new CSqlDataProvider($rawData, array( //or $model=new CArrayDataProvider($rawData, array(... //using with querAll...
                    'keyField' => 'barang', 
                    'totalItemCount' => $count,                      
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));   
        }
                
                                
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
