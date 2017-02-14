<?php

class Transferkeluar extends Transfer
{                       

        public $bank;
        public $supplier;
        public $tanggal;
        public $jenisTransfer;
        public $rekening;
        public $namaperusahaan;
		public $jumlah;
    
		public function rules()
		{
				// NOTE: you should only define rules for those attributes that
				// will receive user inputs.
				return array(

						array('jumlah,supplier,tanggal,jenisTransfer', 'required'),		                       
				);
		}    
		
		public function attributeLabels()
		{
			return array(
				'id' => 'ID',
				'jumlah' => 'Jumlah Pembayaran',				
			);
		}

      
		public function search()
		{
				// Warning: Please modify the following code to remove attributes that
				// should not be searched.

				$criteria=new CDbCriteria;
				$criteria->alias='t';
				$criteria->select='distinct supplierid,supplierid as id,s.namaperusahaan';	
				$criteria->join='INNER JOIN master.supplier s on s.id=t.supplierid';
				$criteria->condition='supplierid!=0';				
				
				if(isset($_GET['Transferkeluar']))
                {					
					 $criteria->compare('upper(s.namaperusahaan)',  strtoupper ($_GET['Transferkeluar']['namaperusahaan']),true); 
				}					
				
				$sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'supplierid desc',        
					 'namaperusahaan',    	
                );
				
				return new CActiveDataProvider($this, array(
						'criteria'=>$criteria,
						'sort'=>$sort,
						'pagination'=>array(
							'pageSize'=>10,
						),
				));
		}
                
        public function listDetail($supplierid)
        {
            $sql ="select distinct tf.id,jt.nama as jenistransfer,b.nama as barang,pb.jumlah as beli,tf.kredit as jual,tf.debit as debit, tf.saldo as harga,tf.kredit,tf.saldo  from master.barang b inner join transaksi.penerimaanbarang pb on pb.barangid=b.id 
		inner join transaksi.transfer tf on tf.supplierid=pb.supplierid 
		inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
        where pb.supplierid=$supplierid and tf.tanggal=pb.tanggal and jt.id=1 
        union 
        select distinct tf.id,jt.nama as jenistransfer,b.nama as barang,tf.debit as beli,pb.jumlah as jual,tf.debit as debit, tf.kredit as harga,tf.kredit,tf.saldo  
                from master.barang b  join transaksi.penjualanbarangkesupplier pb on pb.barangid=b.id 
                        inner join transaksi.transfer tf on tf.supplierid=pb.supplierpembeliid 
                                inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
                                        where tf.supplierid=$supplierid and tf.tanggal=pb.tanggal and jt.id=2";
            
            $rawData = Yii::app()->db->createCommand($sql); //or use ->queryAll(); in CArrayDataProvider
            $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_alias')->queryScalar(); //the count

            return new CSqlDataProvider($rawData, array( //or $model=new CArrayDataProvider($rawData, array(... //using with querAll...
                    'keyField' => 'id', 
                    'totalItemCount' => $count,                      
                    'pagination' => array(
                        'pageSize' => 20,
                    ),
                ));   

        }
	                           
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
