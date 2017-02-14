<?php

class Datasetoran extends Penjualanbarang
{       
        public $kodebarang;
        public $nama;
        public $bayar;
        public $barang;
        public $pelanggan;
        public $tanggal;
		public $tanggalcetak;
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
                $criteria=new CDbCriteria;

                $criteria->select = "cast(p.tanggal as date) as tanggal,cast(p.tanggalcetak as date) as tanggalcetak,pel.nama as pelanggan,p.pelangganid,p.pembelianke, 										
										case when cast(p.tanggalcetak as date) is null 
					then p.pelangganid|| '--' ||cast(p.tanggal as date)|| '--' ||p.pembelianke|| '--' ||'0'
				ELSE
					p.pelangganid|| '--' ||cast(p.tanggal as date)|| '--' ||p.pembelianke|| '--' ||cast(p.tanggalcetak as date)
				end
										as id";
                $criteria->alias = "p";
                $criteria->join = "INNER JOIN master.pelanggan AS pel ON pel.id=p.pelangganid";             
                $criteria->group="pelangganid,cast(tanggal as date),pel.nama,pembelianke,cast(tanggalcetak as date)";
                $criteria->condition = "issendtokasir=true AND isdeleted=false AND jenispembayaran='kredit' and p.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
                $criteria->order="cast(p.tanggal as date) desc";
                
                if(isset($_GET['Datasetoran']))
                {                                        
                    if($_GET['Datasetoran']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Datasetoran']['tanggal'])));
					
					if($_GET['Datasetoran']['tanggalcetak']!='')
                        $criteria->compare('tanggalcetak', date("Y-m-d", strtotime($_GET['Datasetoran']['tanggalcetak'])));
                    
                    if($_GET['Datasetoran']['pelanggan']!='')
                       $criteria->compare('upper(pel.nama)', strtoupper ($_GET['Datasetoran']['pelanggan']),true);
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
        
        public function listDetail($pelangganid,$tanggal,$pembelianke)
        {
            $sql ="select d.nama as divisi,b.nama as barang,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,pb.id as id
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.jenispembayaran='kredit' and pb.issendtokasir=true 
								and pb.isdeleted=false and  pb.pelangganid=$pelangganid  and pb.pembelianke=$pembelianke
									and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
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
        
        public function getTotalBelanja($pelangganid,$tanggal,$pembelianke)
        {
            $connection=Yii::app()->db;
            $sql ="select sum(pb.hargatotal) as totalbelanja
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.issendtokasir=true and pb.isdeleted=false 
								and pb.jenispembayaran='kredit' and pb.pelangganid=$pelangganid 
									and cast(pb.tanggal as date)='$tanggal' and pb.pembelianke=$pembelianke
										and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
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
        
        public function updateStatusPenjualan($pelangganid,$tanggalpembelian,$pembelianke,$tanggalcetak)
        {
            $connection =Yii::app()->db;
            $sql = "update transaksi.penjualanbarang set statuspenjualan=TRUE,tanggalcetak='$tanggalcetak'
						where issendtokasir=true and statuspenjualan=false 
							and isdeleted=false and jenispembayaran='kredit' and pembelianke=$pembelianke
								and  pelangganid=$pelangganid and cast(tanggal as date)='$tanggalpembelian' 
									and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            $command = $connection->createCommand($sql);
            $command->execute();
        }
		
		
        
        public function detailFormFaktur($pelangganid,$tanggalPembelian,$pembelianke,$tanggalcetak)
        {
            $connection=Yii::app()->db;
            $sql ="select d.nama as divisi,b.nama as barang,pb.box,pb.jumlah,pb.hargatotal,
						pb.hargasatuan,pb.kategori,pb.id as id,b.id as barangid
							from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
								inner join master.barang b on b.id=pb.barangid
									where pb.issendtokasir=true and pb.statuspenjualan=true and pb.isdeleted=false 
										and pb.jenispembayaran='kredit' and pb.pelangganid=$pelangganid  and pb.pembelianke=$pembelianke
											and cast(pb.tanggal as date)='$tanggalPembelian' and pb.tanggalcetak='$tanggalcetak'
												and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function detailFormFakturCetak($pelangganid,$tanggalcetak,$tanggalpembelian,$pembelianke)
        {
            $connection=Yii::app()->db;           
			$sql="SELECT
						transaksi.faktur.nofaktur,
						transaksi.penjualanbarang.pelangganid,
						transaksi.penjualanbarang.barangid,
						master.barang.nama AS barang,
						transaksi.penjualanbarang.jumlah,
						transaksi.penjualanbarang.divisiid,
						master.divisi.nama AS divisi,
						transaksi.penjualanbarang.hargatotal,
						transaksi.penjualanbarang.box,
						transaksi.penjualanbarang.hargasatuan,
						transaksi.penjualanbarang.kategori,
						transaksi.penjualanbarang.id
						FROM
						transaksi.penjualanbarang
						INNER JOIN transaksi.fakturpenjualanbarang ON transaksi.fakturpenjualanbarang.penjualanbarangid = transaksi.penjualanbarang.id
						INNER JOIN transaksi.faktur ON transaksi.fakturpenjualanbarang.fakturid = transaksi.faktur.id
						INNER JOIN master.barang ON transaksi.penjualanbarang.barangid = master.barang.id
						INNER JOIN master.divisi ON transaksi.penjualanbarang.divisiid = master.divisi.id
						WHERE
						transaksi.faktur.pelangganid = $pelangganid AND
						transaksi.faktur.pembelianke = $pembelianke AND 
						cast(transaksi.faktur.tanggalcetak as date)='$tanggalcetak' AND 
						cast(transaksi.faktur.tanggal as date)='$tanggalpembelian' AND 
						transaksi.faktur.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];    	
			
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
