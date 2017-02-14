<?php

class Datapendapatan extends Reportpendapatan
{       
    
    public $pilihan;
    public $tanggal1;    
    public $tanggal2;    
    public $divisi;
    
    
        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('pilihan', 'required'),		                       
		);
	}    
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tanggal1' => 'Tanggal',
                        'tanggal2' => 'Tanggal',			
		);
	}
        
        
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function selectPendapatanByDivisinTanggal($tanggal,$divisiid)
        {
            $criteria = new CDbCriteria;
            $criteria->condition ="cast(tanggal as date)='$tanggal' AND divisiid=$divisiid";
            return Datapendapatan::model()->find($criteria);
        }
        
        //total penjualan per divisi dan tanggal
        public function getTotalPenjualanPerDivisi($tanggal,$divisi)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(hargatotal) as totalpenjualan FROM transaksi.penjualanbarang WHERE divisiid=$divisi 
						AND statuspenjualan=TRUE AND cast(tanggal as date)='$tanggal'";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalpenjualan"];
        }
        
        //total penjualan all divisi
        public function getTotalPenjualanAllDivisi($tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(hargatotal) as totalpenjualan FROM transaksi.penjualanbarang WHERE  cast(tanggal as date)='$tanggal'
						AND statuspenjualan=TRUE";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalpenjualan"];
        }
        
        //total laba per divisi dan tanggal
        public function getTotalLabaPerDivisi($tanggal,$divisi)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(labarugi) as totallabarugi FROM transaksi.penjualanbarang 
						WHERE divisiid=$divisi AND cast(tanggal as date)='$tanggal' AND statuspenjualan=TRUE";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totallabarugi"];
        }
        
        //total laba per all divisi
        public function getTotalLabaAllDivisi($tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(labarugi) as totallabarugi FROM transaksi.penjualanbarang WHERE  cast(tanggal as date)='$tanggal'
					AND statuspenjualan=TRUE";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totallabarugi"];
        }
        
        
        //total pengeluaran per divisi dan tanggal
        public function getTotalPengeluaranPerDivisi($tanggal,$divisi)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(total) as totalpengeluaran FROM transaksi.pengeluaran 
					WHERE divisiid=$divisi AND cast(tanggal as date)='$tanggal' AND statuspenjualan=TRUE";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalpengeluaran"];
        }
        
        //total pengeluaran all divisi
        public function getTotalPengeluaranAllDivisi($tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(total) as totalpengeluaran FROM transaksi.pengeluaran WHERE  cast(tanggal as date)='$tanggal'";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalpengeluaran"];
        }
        
        //total pengeluaran per divisi dan tanggal
        public function getTotalDiskonPerDivisi($tanggal,$divisi)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(diskon) as totaldiskon FROM transaksi.penjualanbarang WHERE divisiid=$divisi AND cast(tanggal as date)='$tanggal'";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totaldiskon"];
        }
        
        //total pengeluaran all divisi 
        public function getTotalDiskonAllDivisi($tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(diskon) as totaldiskon FROM transaksi.penjualanbarang WHERE cast(tanggal as date)='$tanggal'";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totaldiskon"];
        }
        
        //total pituang per divisi dan tanggal
        public function getTotalPiutangPerDivisi($tanggal,$divisi)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(sisa) as totalpiutang FROM transaksi.penjualanbarang pb inner join transaksi.fakturpenjualanbarang fjb on fjb.penjualanbarangid=pb.id"
                    . " inner join transaksi.faktur f on f.id=fjb.fakturid WHERE pb.divisiid=$divisi and cast(f.tanggal as date)='$tanggal'";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalpiutang"];
        }
        
        //total pengeluaran all divisi 
        public function getTotalPiutangAllDivisi($tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT SUM(sisa) as totalpiutang FROM transaksi.penjualanbarang pb inner join transaksi.fakturpenjualanbarang fjb on fjb.penjualanbarangid=pb.id"
                    . " inner join transaksi.faktur f on f.id=fjb.fakturid WHERE cast(f.tanggal as date)='$tanggal'";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalpiutang"];
        }
        
        public function getTotalModalPerDivisi($tanggal,$divisi)
        {
            $connection=Yii::app()->db;
            $sql ="select sum(hargamodal) totalmodal from transaksi.penjualanbarang pb inner join transaksi.hargabarang hb on hb.barangid=pb.barangid where pb.supplierid=hb.supplierid 
                            and cast(pb.tanggal as date)='$tanggal' and pb.divisiid=$divisi";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalmodal"];
        }
        
        public function getTotalModalAllDivisi($tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="select sum(hargamodal) totalmodal from transaksi.penjualanbarang pb inner join transaksi.hargabarang hb on hb.barangid=pb.barangid where pb.supplierid=hb.supplierid 
                            and cast(pb.tanggal as date)='$tanggal'";            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["totalmodal"];
        }
        
        
}
