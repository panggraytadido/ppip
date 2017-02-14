<?php

class Jualkesupplier extends Penjualanbarangkesupplier
{            
        public $barang;
        public $supplier;
        public $supplierid;


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

			array('kode, nama, hargamodal,supplierid,hargagrosir, hargaeceran,divisiid', 'required'),
			//array('kode, nama, hargagrosir, hargaeceran, hargamodal', 'integerOnly'=>true),			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('kode, nama, hargagrosir, hargaeceran, hargamodal', 'safe', 'on'=>'search'),
		);
	}    
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kode' => 'Kode Barang',
			'nama' => 'Nama Barang',
			'divisiid' => 'Divisi',	
                        'supplierid' => 'Supplier',	
                        'hargamodal'=>'Harga Modal',
                        'hargaeceran'=>'Harga Eceran',
                        'hargagrosir'=>'Harga Grosir'
                    
		);
	}
        
        
	public function listData()
	{
            $criteria=new CDbCriteria;

                $criteria->select = "cast(p.tanggal as date) as tanggal,sup.namaperusahaan as supplier, cast(p.tanggal as date)|| '' ||p.supplierpembeliid as id ";
                $criteria->alias = "p";
                $criteria->join = "INNER JOIN master.supplier AS sup ON sup.id=p.supplierpembeliid";             
                $criteria->group="supplierpembeliid,cast(tanggal as date),sup.namaperusahaan";
                $criteria->condition = "issendtokasir=true and statuspenjualan=false";
                //$criteria->condition = "issendtokasir=true AND p.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
                $criteria->order="cast(p.tanggal as date) desc";
                
                if(isset($_GET['Jualkesupplier']))
                {                                        
                    if($_GET['Jualkesupplier']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Jualkesupplier']['tanggal'])));
                    
                    if($_GET['Jualkesupplier']['supplier']!='')
                        $criteria->compare('upper(sup.namaperusahaan)',  strtoupper ($_GET['Jualkesupplier']['supplier']),true);                                
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'p.id desc',
                    'p.id',                  
                    'tanggal'=>array(
                                      'asc'=>'tanggal',
                                      'desc'=>'tanggal desc',
                                    ),                  
                    'supplier'=>array(
                                      'asc'=>'supplier',
                                      'desc'=>'supplier desc',
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
        
        public function listDetail($supplierpembeliid,$tanggal)
        {
            $sql ="select d.nama as divisi,b.nama as barang,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,s.namaperusahaan as supplier,pb.id as id
                    from transaksi.penjualanbarangkesupplier pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                        inner join master.supplier s on s.id=pb.supplierid
                            where pb.supplierpembeliid=$supplierpembeliid and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            
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
        
        public function detailFormFaktur($supplierpembeliid,$tanggal)
        {
            $connection=Yii::app()->db;
            $sql ="select d.nama as divisi,b.nama as barang,pb.box,pb.jumlah,pb.hargatotal,pb.hargasatuan,pb.kategori,pb.id as id,b.id as barangid
                    from transaksi.penjualanbarangkesupplier pb inner join master.divisi d on d.id=pb.divisiid
                        inner join master.barang b on b.id=pb.barangid
                            where pb.supplierpembeliid=$supplierpembeliid and cast(pb.tanggal as date)='$tanggal' and pb.lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
        }        
        
        public function updateStatusPenjualan($supplierpembeliid,$tanggal)
        {
            $connection =Yii::app()->db;
            $sql = "update transaksi.penjualanbarangkesupplier set statuspenjualan=TRUE "
                    . "     where statuspenjualan=FALSE and  supplierpembeliid=$supplierpembeliid "
                    . "         and cast(tanggal as date)='$tanggal' and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
            $command = $connection->createCommand($sql);
            $command->execute();
        }
                
}
