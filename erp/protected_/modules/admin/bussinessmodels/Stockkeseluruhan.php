<?php

class Stockkeseluruhan extends Barang
{       
        public $kode;
        public $nama;
        public $divisi;
        public $jumlah;
        public $supplierid;
        public $total;
        
        public function rules()
	{
		return array(			
			array('jumlah', 'required'),
			
		);
	}
        
        public function search()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'b.kode, b.nama,b.id as id,d.nama as divisi';
                $criteria->alias = 'b';                
                $criteria->join .= ' INNER JOIN master.divisi AS d ON d.id=b.divisiid ';               
                $criteria->order='d.id asc';
                
                if(isset($_GET['Stockkeseluruhan']))
                {
                    $criteria->compare('upper(b.kode)',  strtoupper($_GET['Stockkeseluruhan']['kode']),true);
                    $criteria->compare('upper(b.nama)',  strtoupper($_GET['Stockkeseluruhan']['nama']),true);      
                    
                    if($_GET['Stockkeseluruhan']['divisi']!='')
                    {
                        $criteria->addCondition("d.id=".$_GET['Stockkeseluruhan']['divisi']);
                    }
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
            $sql ="select distinct supplierid,jumlah,lokasipenyimpananbarangid,supplierid||'-'||barangid as id from transaksi.stocksupplier
                            where barangid=$id order by jumlah desc";
            
            $rawData = Yii::app()->db->createCommand($sql); //or use ->queryAll(); in CArrayDataProvider
            $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_alias')->queryScalar(); //the count

            return new CSqlDataProvider($rawData, array( //or $model=new CArrayDataProvider($rawData, array(... //using with querAll...
                    'keyField' => 'supplierid', 
                    'totalItemCount' => $count,                      
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));   
        }
        
        function getTotalStock($barangid)
        {
            $connection=Yii::app()->db;            
            $sql="select sum(jumlah)as jumlah
                   FROM 
                   transaksi.stock
                   WHERE 
                     barangid=".$barangid;					
            $data=$connection->createCommand($sql)->queryRow();
            return $data["jumlah"];
        }

        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
