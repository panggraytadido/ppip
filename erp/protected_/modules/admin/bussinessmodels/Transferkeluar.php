<?php

class Transferkeluar extends Transfer
{                       

        public $bank;
        public $supplier;
        public $tanggal;
        public $jenisTransfer;
        public $rekening;
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

                        //$criteria->compare('id',$this->id);
                        $criteria->compare('upper(kode)',  strtoupper($this->kode),true);
                        $criteria->compare('upper(namaperusahaan)',  strtoupper($this->namaperusahaan),true);
                        //$criteria->compare('status',$this->status);
                        //$criteria->compare('alamat',$this->alamat,true);
                        //$criteria->compare('telp',$this->telp);
                        //$criteria->compare('fax',$this->fax);
                        //$criteria->compare('hp',$this->hp);
                        //$criteria->compare('namapemilik',$this->namapemilik,true);
                        //$criteria->compare('tanggalbermitra',$this->tanggalbermitra,true);

                        return new CActiveDataProvider($this, array(
                                'criteria'=>$criteria,
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
