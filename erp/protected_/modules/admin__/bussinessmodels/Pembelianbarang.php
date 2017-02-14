<?php

class Pembelianbarang extends Transfer
{       
       
    public $pilihan;
    public $supplier;
    public $jenis;

    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
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
                    'pilihan' => 'Pilihan',
                    'supplier' => 'Supplier',
                    'jenis' => 'Jenis',
            );
    }      
    
    public function rincianPembelian()
    {
        $connection=Yii::app()->db;            
            /* $sql="select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date),jt.nama as jenistransfer,r.namabank as bank,b.nama as barang,pb.jumlah as beli,tf.kredit as jual,tf.debit as debit, tf.saldo as harga,tf.kredit,tf.saldo  from master.barang b 
inner join transaksi.penerimaanbarang pb on pb.barangid=b.id 
	inner join transaksi.transfer tf on tf.supplierid=pb.supplierid 
		inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
			inner join master.supplier s on s.id=tf.supplierid
				left join master.rekening r on r.id=tf.rekeningid
					where tf.tanggal=pb.tanggal and jt.id=1 
        union 
        select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date) as tanggal,jt.nama as jenistransfer,r.namabank as bank,b.nama as barang,tf.debit as beli,pb.jumlah as jual,tf.debit as debit, tf.kredit as harga,tf.kredit,tf.saldo  
                from master.barang b inner join transaksi.penjualanbarangkesupplier pb on pb.barangid=b.id 
                        inner join transaksi.transfer tf on tf.supplierid=pb.supplierpembeliid 
                                inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
					inner join master.supplier s on s.id=tf.supplierid
						left join master.rekening r on r.id=tf.rekeningid
							where tf.tanggal=pb.tanggal and jt.id=2
	union						
        select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date) as tanggal,jt.nama as jenistransfer,r.namabank as bank,cast(tf.debit as char) as barang,
	tf.debit as beli,tf.debit as jual,tf.debit as debit,tf.kredit as harga,tf.kredit as kredit,tf.saldo
         from transaksi.transfer tf 
        inner join master.rekening r on r.id=tf.rekeningid
                inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
                        inner join master.supplier s on s.id=tf.supplierid";
			*/

			$sql="select distinct 
				tf.id,
				s.id as supplierid,
				s.namaperusahaan as supplier,
				cast(tf.tanggal as date) as tanggal,
				jt.nama as jenistransfer,
				r.namabank as bank,
				b.nama as barang,
				pb.jumlah as beli,
				tf.kredit as jual,
				tf.debit as debit, 
				hb.hargamodal as harga,
				tf.kredit,
				tf.saldo  
				from transaksi.transfer tf 
				inner join transaksi.penerimaanbarang pb on pb.id=tf.penerimaanbarangid 
					left join master.supplier s on tf.supplierid=s.id 
						left join referensi.jenistransfer jt on jt.id=tf.jenistransferid			
								left join master.rekening r on r.id=tf.rekeningid
								left join master.barang b on b.id=pb.barangid
								inner join transaksi.hargabarang hb on hb.barangid=b.id
									where jt.id=1 and s.id=hb.supplierid
									union
				select distinct 
				tf.id, 
				s.id as supplierid,
				s.namaperusahaan as supplier,
				cast(tf.tanggal as date) as tanggal,
				jt.nama as jenistransfer,
				r.namabank as bank,
				b.nama as barang,
				tf.debit as beli,
				pb.jumlah as jual,
				tf.debit as debit, 
				pb.hargasatuan as harga,
				tf.kredit,
				tf.saldo  
								from transaksi.transfer tf inner join transaksi.penjualanbarangkesupplier pb on pb.id=tf.penjualanbarangkesupplierid 
							inner join master.barang b on b.id=pb.barangid
										inner join master.supplier s on tf.supplierid=s.id 
												inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid					
										left join master.rekening r on r.id=tf.rekeningid
										inner join transaksi.hargabarang hb on hb.barangid=b.id
											where jt.id=2
									union
				select distinct 
				tf.id, 
				s.id as supplierid,
				s.namaperusahaan as supplier,
				cast(tf.tanggal as date) as tanggal,
				jt.nama as jenistransfer,
				r.namabank as bank,
				cast(tf.debit as char) as barang,
				tf.debit as beli,
				tf.debit as jual,
				tf.debit as debit,
				tf.debit as harga,
				tf.kredit as kredit,
				tf.saldo
						 from transaksi.transfer tf 
						inner join master.rekening r on r.id=tf.rekeningid
								inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
										inner join master.supplier s on s.id=tf.supplierid
											where jt.id=3 
						union
			select distinct 
				tf.id, 
				s.id as supplierid,
				s.namaperusahaan as supplier,
				cast(tf.tanggal as date) as tanggal,
				jt.nama as jenistransfer,
				cast(tf.debit as char) as bank,
				cast(tf.debit as char) as barang,
				tf.debit as beli,
				tf.debit as harga,
				tf.debit as jual,
				tf.debit as debit,				
				tf.kredit as kredit,
				tf.saldo
						 from transaksi.transfer tf 						
								inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
										left join master.supplier s on s.id=tf.supplierid
											where jt.id=4 order by supplierid,id ASC";	
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
    }
    
    public function rincianPembelianPerSupplier($supplierid)
    {
        $connection=Yii::app()->db;            
           /* $sql="select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date),jt.nama as jenistransfer,r.namabank as bank,b.nama as barang,pb.jumlah as beli,tf.kredit as jual,tf.debit as debit, tf.saldo as harga,tf.kredit,tf.saldo  
				from transaksi.transfer tf 
				inner join transaksi.penerimaanbarang pb on pb.id=tf.penerimaanbarangid 
					left join master.supplier s on tf.supplierid=s.id 
						left join referensi.jenistransfer jt on jt.id=tf.jenistransferid			
								left join master.rekening r on r.id=tf.rekeningid
								left join master.barang b on b.id=pb.barangid
									where jt.id=1 and tf.supplierid=$supplierid
									union
				select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date) as tanggal,jt.nama as jenistransfer,r.namabank as bank,b.nama as barang,tf.debit as beli,pb.jumlah as jual,tf.debit as debit, tf.kredit as harga,tf.kredit,tf.saldo  
								from transaksi.transfer tf inner join transaksi.penjualanbarangkesupplier pb on pb.id=tf.penjualanbarangkesupplierid 
							inner join master.barang b on b.id=pb.barangid
										inner join master.supplier s on tf.supplierid=s.id 
												inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid					
										left join master.rekening r on r.id=tf.rekeningid
											where jt.id=2 and tf.supplierid=$supplierid	
									union
				select distinct s.id as supplierid,s.namaperusahaan as supplier,cast(tf.tanggal as date) as tanggal,jt.nama as jenistransfer,r.namabank as bank,cast(tf.debit as char) as barang,
					tf.debit as beli,tf.debit as jual,tf.debit as debit,tf.kredit as harga,tf.kredit as kredit,tf.saldo
						 from transaksi.transfer tf 
						inner join master.rekening r on r.id=tf.rekeningid
								inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
										inner join master.supplier s on s.id=tf.supplierid
											where jt.id=3 and tf.supplierid=$supplierid";				*/
			$sql ="select distinct 
				tf.id,
				s.id as supplierid,
				s.namaperusahaan as supplier,
				cast(tf.tanggal as date) as tanggal,
				jt.nama as jenistransfer,
				r.namabank as bank,
				b.nama as barang,
				pb.jumlah as beli,
				hb.hargamodal as harga,
				tf.kredit as jual,
				tf.debit as debit, 
				tf.kredit,
				tf.saldo  
				from transaksi.transfer tf 
				inner join transaksi.penerimaanbarang pb on pb.id=tf.penerimaanbarangid 
					left join master.supplier s on tf.supplierid=s.id 
						left join referensi.jenistransfer jt on jt.id=tf.jenistransferid			
								left join master.rekening r on r.id=tf.rekeningid
								left join master.barang b on b.id=pb.barangid
								inner join transaksi.hargabarang hb on hb.barangid=b.id
									where jt.id=1 and tf.supplierid=$supplierid and hb.supplierid=s.id
									union
				select distinct 
				tf.id, 
				s.id as supplierid,
				s.namaperusahaan as supplier,
				cast(tf.tanggal as date) as tanggal,
				jt.nama as jenistransfer,
				r.namabank as bank,
				b.nama as barang,
				tf.debit as beli,
				pb.hargasatuan as harga,
				pb.jumlah as jual,
				tf.debit as debit,
				tf.kredit,
				tf.saldo  
					from transaksi.transfer tf inner join transaksi.penjualanbarangkesupplier pb on pb.id=tf.penjualanbarangkesupplierid 
				inner join master.barang b on b.id=pb.barangid
							inner join master.supplier s on tf.supplierid=s.id 
									inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid					
							left join master.rekening r on r.id=tf.rekeningid
							inner join transaksi.hargabarang hb on hb.barangid=b.id										
								where jt.id=2 and tf.supplierid=$supplierid								
									union
				select distinct 
				tf.id, 
				s.id as supplierid,
				s.namaperusahaan as supplier,
				cast(tf.tanggal as date) as tanggal,
				jt.nama as jenistransfer,
				r.namabank as bank,
				cast(tf.debit as char) as barang,
				tf.debit as beli,
				tf.debit as harga,
				tf.debit as jual,
				tf.debit as debit,				
				tf.kredit as kredit,
				tf.saldo
						 from transaksi.transfer tf 
						inner join master.rekening r on r.id=tf.rekeningid
								inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
										inner join master.supplier s on s.id=tf.supplierid
											where jt.id=3 and tf.supplierid=$supplierid 
											union
				select distinct 
				tf.id, 
				s.id as supplierid,
				s.namaperusahaan as supplier,
				cast(tf.tanggal as date) as tanggal,
				jt.nama as jenistransfer,
				cast(tf.debit as char) as bank,
				cast(tf.debit as char) as barang,
				tf.debit as beli,
				tf.debit as harga,
				tf.debit as jual,
				tf.debit as debit,				
				tf.kredit as kredit,
				tf.saldo
						 from transaksi.transfer tf 						
								inner join referensi.jenistransfer jt on jt.id=tf.jenistransferid
										left join master.supplier s on s.id=tf.supplierid
											where jt.id=4 order by supplierid,id ASC";									
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
    }
    
    public function getSupplier()
    {
        $criteria = new CDbCriteria;
        $criteria->select ="distinct supplierid";                  
        $criteria->order='supplierid asc';
        
        $criteria->condition="supplierid!=0";
        $data = Transfer::model()->findAll($criteria);
        return $data;
    }
	
	public function getSumBeli($supplierid)
	{
			$connection=Yii::app()->db;        
					
			$sql="select sum(pb.jumlah) as totalbeli from transaksi.penerimaanbarang pb inner join transaksi.transfer tf on pb.id=tf.penerimaanbarangid 
			where tf.supplierid=$supplierid and tf.jenistransferid=1 and tf.kredit=0";														
			$data=$connection->createCommand($sql)->queryRow();
			return $data["totalbeli"];		
	}
	
	public function getSumJual($supplierid)
	{
			$connection=Yii::app()->db;        
					
			$sql="select sum(pb.jumlah) as totaljual from transaksi.penjualanbarangkesupplier pb 
			inner join transaksi.transfer tf on pb.id=tf.penjualanbarangkesupplierid 
		where tf.supplierid=$supplierid and tf.jenistransferid=2 and tf.debit=0";														
			$data=$connection->createCommand($sql)->queryRow();
			return $data["totaljual"];		
	}
	
	public function getSumHarga($supplierid)
	{
		$connection=Yii::app()->db;        
					
			$sql1="select sum(pb.hargasatuan) as harga from transaksi.penjualanbarangkesupplier pb 
			inner join transaksi.transfer tf on pb.id=tf.penjualanbarangkesupplierid 
		where tf.supplierid=$supplierid and tf.jenistransferid=2 and tf.debit=0";														
			$data1=$connection->createCommand($sql1)->queryRow();		
			
			$sql2="select * from transaksi.penerimaanbarang pb 
			inner join transaksi.transfer tf on pb.id=tf.penerimaanbarangid 
			where tf.supplierid=$supplierid and tf.jenistransferid=1 and tf.kredit=0";														
			$data2=$connection->createCommand($sql2)->queryAll();
			$pb = array();
			for($i=0;$i<count($data2);$i++)
			{
				$hargaBarang = Hargabarang::model()->find('barangid='.$data2[$i]["barangid"].' AND supplierid='.$data2[$i]["supplierid"])->hargagrosir;
				$pb[] = $data2[$i]["jumlah"]*$hargaBarang;
			}
			
			$totalPb = array_sum($pb);
			
			return $data1["harga"]+$totalPb;
			
	}
	
	
	public function getSumDebit($supplierid)
	{
		$connection=Yii::app()->db;        
					
		$sql="select sum(debit) as debit from transaksi.transfer where supplierid=$supplierid";														
		$data=$connection->createCommand($sql)->queryRow();
		return $data["debit"];	
	}
	
	public function getSumKredit($supplierid)
	{
		$connection=Yii::app()->db;        
					
		$sql="select sum(kredit) as kredit from transaksi.transfer where supplierid=$supplierid";														
		$data=$connection->createCommand($sql)->queryRow();
		return $data["kredit"];	
	}
    
}
