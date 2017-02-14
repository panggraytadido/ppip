<?php

class Hargabarangreport extends Barang
{       
        
   
    public $barang;
    public $supplierid;
	public $supplier;
    public $hargamodal;
    public $hargagrosir;
    public $hargaeceran;
	public $divisi;
    
    
    /**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(

                    array('kode,nama,divisiid', 'required'),		
                    array('kode, nama,supplierid', 'safe', 'on'=>'search'),
            );
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kode' => 'Kode',
                        'divisiid' => 'Divisi',
			'nama' => 'Nama',
			'supplierid' => 'Supplier',
			'hargamodal' => 'Harga Modal',
			'hargaeceran' => 'Harga Eceran',
			'hargagrosir' => 'Harga Grosir',		
		);
	}
    
    public function listHargaBarang()
    {
            $criteria=new CDbCriteria;

            $criteria->select = 'b.kode,b.nama as barang,s.namaperusahaan as supplier,d.nama as divisi,b.id,hb.hargamodal,hb.hargaeceran,hb.hargagrosir';

            $criteria->alias = 'b';
            $criteria->join = 'INNER JOIN master.divisi AS d ON d.id=b.divisiid ';
			$criteria->join .= ' INNER JOIN transaksi.hargabarang AS hb ON hb.barangid=b.id';	
			$criteria->join .= ' INNER JOIN master.supplier AS s ON s.id=hb.supplierid';	
            //$criteria->condition = "b.divisiid=$divisiid";
            $criteria->order = 's.namaperusahaan asc';

            if(isset($_GET['Hargabarang']))
            {                                        
                if($_GET['Hargabarang']['supplier']!='')                                            
                 $criteria->compare('upper(s.namaperusahaan)',  strtoupper ($_GET['Hargabarang']['supplier']),true);       

				if($_GET['Hargabarang']['barang']!='')                                            
                 $criteria->compare('upper(b.nama)',  strtoupper ($_GET['Hargabarang']['barang']),true);       
                               
            }

            $sort = new CSort();
            $sort->attributes = array(
                'defaultOrder'=>'b.id desc',                                              

            );

            return new CActiveDataProvider('Hargabarang', array(
                    'criteria'=>$criteria,
                    'sort'=>$sort,
                    'pagination'=>array(
                        'pageSize'=>10,
                    ),
            ));
    }
	
	public function reportHargaBarang()
	{
		$connection=Yii::app()->db;
            $sql ="select b.kode,b.nama as barang,s.namaperusahaan as supplier,d.nama as divisi,b.id,hb.hargamodal,hb.hargaeceran,hb.hargagrosir
				from master.barang b INNER JOIN master.divisi AS d ON d.id=b.divisiid 
				INNER JOIN transaksi.hargabarang AS hb ON hb.barangid=b.id
				INNER JOIN master.supplier AS s ON s.id=hb.supplierid order by s.namaperusahaan asc";            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
	}
        
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
}
