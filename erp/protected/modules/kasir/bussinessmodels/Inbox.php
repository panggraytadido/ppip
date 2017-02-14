<?php

class Inbox extends Penjualanbarang
{       
        public $kodebarang;
        public $nama;
        public $divisi;
        public $barang;
        public $pelanggan;
        public $tanggal;
        public $barangid;
        public $jumlah;
        public $id;

	public function listInbox()
	{
				$criteria=new CDbCriteria;

                $criteria->select = "cast(p.tanggal as date) as tanggal,
										pel.nama as pelanggan,p.pelangganid,p.pembelianke, 
											case when cast(p.tanggalcetak as date) is null 
					then p.pelangganid|| '--' ||cast(p.tanggal as date)|| '--' ||p.pembelianke|| '--' ||'0'
				ELSE
					p.pelangganid|| '--' ||cast(p.tanggal as date)|| '--' ||p.pembelianke|| '--' ||cast(p.tanggalcetak as date)
				end
										as id";
                $criteria->alias = "p";
                $criteria->join = "INNER JOIN master.pelanggan AS pel ON pel.id=p.pelangganid";             
                $criteria->group="pelangganid,cast(tanggal as date),pel.nama,pembelianke,p.tanggalcetak";
                $criteria->condition = "statuspenjualan=false AND issendtokasir=true 
											AND isdeleted=false AND jenispembayaran='tunai' 
												AND p.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
                $criteria->order="cast(p.tanggal as date) desc";
                
                if(isset($_GET['Inbox']))
                {                                        
                    if($_GET['Inbox']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Inbox']['tanggal'])));
                    
                    if($_GET['Inbox']['pelanggan']!='')
                        $criteria->compare('upper(pel.nama)',  strtoupper ($_GET['Inbox']['pelanggan']),true);        
                        //$criteria->addCondition("pelangganid=".$_GET['Inbox']['pelanggan']);
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
					'pembelianke'=>array(
                                      'asc'=>'pembelianke',
                                      'desc'=>'pembelianke desc',
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
        
        public function listDetailInbox($pelangganid,$tanggal,$pembelianke)
        {
            $sql ="select d.nama as divisi,b.nama as barang,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,pb.id as id
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.jenispembayaran='tunai' and pb.issendtokasir=true and pb.isdeleted=false 
								and pb.statuspenjualan=false and pembelianke=$pembelianke 
										and pb.pelangganid=$pelangganid and cast(pb.tanggal as date)='$tanggal' 
											and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
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
        
        public function detailFormFaktur($pelangganid,$tanggal,$pembelianke)
        {
            $connection=Yii::app()->db;
            $sql ="select d.nama as divisi,b.nama as barang,pb.box,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,pb.id as id,b.id as barangid
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.statuspenjualan=false and pb.issendtokasir=true
								and pb.isdeleted=false and pb.jenispembayaran='tunai' and pb.pembelianke=$pembelianke
									and pb.pelangganid=$pelangganid and cast(pb.tanggal as date)='$tanggal' 
										and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function detailFormFakturCetak($pelangganid,$tanggal,$pembelianke)
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
						cast(transaksi.penjualanbarang.tanggal as date)='$tanggal' 
						AND transaksi.faktur.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];    	                         
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function cekFaktur($pelangganid,$tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="select d.nama as divisi,b.nama as barang,pb.box,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,pb.id as id,b.id as barangid
                    from transaksi.penjualanbarang pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.jenispembayaran='tunai'and pb.statuspenjualan=true and  pb.pelangganid=$pelangganid and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }
        
        public function updateStatusPenjualan($pelangganid,$tanggal,$pembelianke,$tanggalcetak)
        {
            $connection =Yii::app()->db;
            $sql = "update transaksi.penjualanbarang set statuspenjualan=TRUE,tanggalcetak='$tanggalcetak' "
                    . "     where statuspenjualan=FALSE and pembelianke=$pembelianke and jenispembayaran='tunai' 
									and isdeleted=false and issendtokasir=true and  pelangganid=$pelangganid "
                    . "         		and cast(tanggal as date)='$tanggal' and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            $command = $connection->createCommand($sql);
            $command->execute();
        }
        		
		
		public function setJenisPembayaran($pelangganid,$tanggal,$pembelianke)
        {
            $connection =Yii::app()->db;
            $sql = "update transaksi.penjualanbarang set jenispembayaran='kredit'
						where  pelangganid=$pelangganid and cast(tanggal as date)='$tanggal' and statuspenjualan=false and pembelianke=$pembelianke
							and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            $command = $connection->createCommand($sql);
            $command->execute();
        }
        
        public function getHargaBarangGrosir($barangid)
        {
            $connection=Yii::app()->db;
            $sql="select hargagrosir from master.barang where barang.id=$barangid";					

            $data=$connection->createCommand($sql)->queryRow();
            return number_format($data["hargagrosir"]);
        }
        
                
		public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}
}
