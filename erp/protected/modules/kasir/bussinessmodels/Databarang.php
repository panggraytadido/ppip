<?php

class Databarang extends Stocksupplier
{            
        public $barang;
        public $hargamodal;
        public $hargagrosir;
        public $hargaeceran;
        public $totalstock;
        public $harga;
        public $total;
        public $divisiid;
        public $kode;
        public  $nama;
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
        
        
        public function listDataBarang()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'hb.barangid as id';
                
                $criteria->alias = 's';
                $criteria->join = 'INNER JOIN transaksi.hargabarang AS hb ON hb.barangid=s.barangid ';         
                $criteria->join .= ' INNER JOIN master.barang AS b ON b.id=hb.barangid';
                $criteria->group= 'hb.barangid';
                $criteria->condition = 's.barangid=s.barangid';
                $criteria->order ='hb.barangid asc';
                                        
                if(isset($_GET['Databarang']))
                {                                        
                    if($_GET['Databarang']['barang']!='')                                            
                     $criteria->compare('upper(b.nama)',  strtoupper ($_GET['Databarang']['barang']),true);                     
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'b.barangid desc',                                              
                    
                );
                
		return new CActiveDataProvider('Databarang', array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
                        'pagination'=>array(
							'pageSize'=>10,
					),
		));
	}        
      
	
}
