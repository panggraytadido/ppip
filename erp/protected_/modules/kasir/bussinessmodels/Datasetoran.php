<?php

class Datasetoran extends Penjualanbarang
{       
        public $kodebarang;
        public $nama;
        public $bayar;
        public $barang;
        public $pelanggan;
        public $tanggal;
        public $barangid;
        public $totalbelanja;
        public $totalbayar;
        public $sisatagihan;
        public $id;
        public $diskon;
        public $nofaktur;
		public $rekening;
		public $tanggalsetoran;

    public function listDataSetoran()
	{
		
		
        /*
                $criteria=new CDbCriteria;
		$criteria->select = "cast(f.tanggal as date) as tanggal,pel.nama as pelanggan, cast(f.tanggal as date)|| '' ||pelangganid as id ,f.pelangganid";
                $criteria->alias = "f";
                $criteria->join = "INNER JOIN master.pelanggan AS pel ON pel.id=f.pelangganid"; 
                $criteria->group="pelangganid,cast(tanggal as date),pel.nama";
                $criteria->condition = "f.jenispembayaran='kredit' and f.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
                $criteria->order="cast(f.tanggal as date) desc";
                
		$criteria->compare('nofaktur',$this->nofaktur,true);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
             * 
             */
            
                
		$criteria=new CDbCriteria;

                $criteria->select = "cast(p.tanggal as date) as tanggal,pel.nama as pelanggan, cast(p.tanggal as date)|| '' ||pelangganid as id ,p.pelangganid";
                $criteria->alias = "p";
                $criteria->join = "INNER JOIN master.pelanggan AS pel ON pel.id=p.pelangganid";             
                $criteria->group="pelangganid,cast(tanggal as date),pel.nama";
                $criteria->condition = "statuspenjualan=false AND jenispembayaran='kredit' and p.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
                $criteria->order="cast(p.tanggal as date) desc";
                
                if(isset($_GET['Datasetoran']))
                {                                        
                    if($_GET['Datasetoran']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Datasetoran']['tanggal'])));
                    
                    if($_GET['Datasetoran']['pelanggan']!='')
                       $criteria->compare('upper(pel.nama)', strtoupper ($_GET['Datasetoran']['pelanggan']));
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'p.id desc',
                    'p.id',                  
                    'tanggal'=>array(
                                      'asc'=>'tanggal',
                                      'desc'=>'tanggal desc',
                                    ),                  
                    'pelanggan'=>array(
                                      'asc'=>'pelanggan',
                                      'desc'=>'pelanggan desc',
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
        
        public function listDetail($pelangganid,$tanggal)
        {
            $sql ="select d.nama as divisi,b.nama as barang,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,pb.id as id
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.jenispembayaran='kredit' and  pb.pelangganid=$pelangganid and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
            $rawData = Yii::app()->db->createCommand($sql); //or use ->queryAll(); in CArrayDataProvider
            $count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM (' . $sql . ') as count_alias')->queryScalar(); //the count

            return new CSqlDataProvider($rawData, array( //or $model=new CArrayDataProvider($rawData, array(... //using with querAll...
                    'keyField' => 'id', 
                    'totalItemCount' => $count,                      
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));   
        }
        
        public function getTotalBelanja($pelangganid,$tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="select sum(pb.hargatotal) as totalbelanja
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.jenispembayaran='kredit' and pb.statuspenjualan=false and pb.pelangganid=$pelangganid and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalbelanja"];
        }
        
        public function getTotalSetoran($pelangganid,$tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="select sum(jumlah) as totalsetoran
                    from transaksi.setoran s inner join transaksi.faktur f on f.id=s.fakturid                       
                            where f.pelangganid=$pelangganid and f.tanggal='$tanggal'";
            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalsetoran"];
        }
        
        public function updateStatusPenjualan($pelangganid,$tanggal)
        {
            $connection =Yii::app()->db;
            $sql = "update transaksi.penjualanbarang set statuspenjualan=TRUE where jenispembayaran='kredit' and  pelangganid=$pelangganid and tanggal='$tanggal' and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            $command = $connection->createCommand($sql);
            $command->execute();
        }
        
        public function detailFormFaktur($pelangganid,$tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="select d.nama as divisi,b.nama as barang,pb.box,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,pb.id as id,b.id as barangid
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.jenispembayaran='kredit' and pb.pelangganid=$pelangganid and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function detailFormFakturCetak($pelangganid,$tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="select d.nama as divisi,b.nama as barang,pb.box,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,pb.id as id,b.id as barangid
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.statuspenjualan=true and pb.jenispembayaran='kredit' and pb.pelangganid=$pelangganid and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        
        public function checkDataFakturTotalBayar($pelangganid,$tanggal)
        {
             //$connection=Yii::app()->db;
             //$noFaktur = Pelanggan::model()->findByPk($pelangganid)->kode."/".$tanggal;
             $cekFaktur = Faktur::model()->find("tanggal='$tanggal' AND pelangganid=".$pelangganid);
             if(count($cekFaktur)!=0)
             {
                 return number_format($cekFaktur->bayar);
             }
             else
             {
                 return '0';
             }
        }               
        
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
