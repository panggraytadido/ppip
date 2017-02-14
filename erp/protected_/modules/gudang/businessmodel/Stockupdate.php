<?php

class Stockupdate extends Stock
{       
        public $kode;
        public $nama;
        public $namalokasi;
        public $jumlah;
        public $supplierid;
        
        
        public function rules()
	{
		return array(			
			array('jumlah', 'required'),
			
		);
	}
        
        public function searchStockupdate()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'kode, b.nama, l.nama as namalokasi, jumlah,b.id';
                $criteria->alias = 's';
                $criteria->join = 'INNER JOIN master.barang AS b ON s.barangid=b.id '; 
                $criteria->join .= 'LEFT JOIN transaksi.lokasipenyimpananbarang AS l ON s.lokasipenyimpananbarangid=l.id ';
                $criteria->condition = 's.isdeleted=false and b.divisiid='.Yii::app()->session['divisiid'];
                $criteria->condition .= ' and s.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
                $criteria->order='jumlah desc';
                
                if(isset($_GET['Stockupdate']))
                {
                    $criteria->compare('upper(kode)',  strtoupper($_GET['Stockupdate']['kode']),true);
                    $criteria->compare('upper(b.nama)',  strtoupper($_GET['Stockupdate']['nama']),true);
                    $criteria->compare('upper(l.nama)',  strtoupper($_GET['Stockupdate']['namalokasi']),true);
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'b.id',
                    'kode'=>array(
                                      'asc'=>'b.kode',
                                      'desc'=>'b.kode desc',
                                    ),
                    'nama'=>array(
                                      'asc'=>'b.nama',
                                      'desc'=>'b.nama desc',
                                    ),
                    'namalokasi'=>array(
                                      'asc'=>'l.nama',
                                      'desc'=>'l.nama desc',
                                    ),
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
        
        public function listDetail($id)
        {
            $sql ="select distinct supplierid,jumlah,supplierid||'-'||barangid as id,updateddate from transaksi.stocksupplier
                            where barangid=$id and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
            $rawData = Yii::app()->db->createCommand($sql); //or use ->queryAll(); in CArrayDataProvider
            $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_alias')->queryScalar(); //the count

            return new CSqlDataProvider($rawData, array( //or $model=new CArrayDataProvider($rawData, array(... //using with querAll...
                    'keyField' => 'supplierid', 
                    'totalItemCount' => $count,                      
                    'pagination' => array(
                        'pageSize' => 10,
                    ),
                ));   
        }

        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
