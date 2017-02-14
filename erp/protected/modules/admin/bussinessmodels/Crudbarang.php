<?php

class Crudbarang extends Barang
{       
        
    public $divisi;
    public $barang;
    public $supplierid;
    public $hargamodal;
    public $hargagrosir;
    public $hargaeceran;
    
    
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
    
    public function listDataBarang()
    {
            $criteria=new CDbCriteria;

            $criteria->select = 'b.kode,b.nama as barang,d.nama as divisi,b.id';

            $criteria->alias = 'b';
            $criteria->join = 'INNER JOIN master.divisi AS d ON d.id=b.divisiid ';         
            $criteria->condition = "d.kode !='4'";
            $criteria->order = 'd.nama,b.id desc';

            if(isset($_GET['Crudbarang']))
            {                                        
                if($_GET['Crudbarang']['barang']!='')                                            
                 $criteria->compare('upper(b.nama)',  strtoupper ($_GET['Crudbarang']['barang']),true);          
                
                if($_GET['Crudbarang']['divisi']!='')                                            
                 $criteria->addCondition("d.id=".$_GET['Crudbarang']['divisi']);
            }

            $sort = new CSort();
            $sort->attributes = array(
                'defaultOrder'=>'b.barangid desc',                                              

            );

            return new CActiveDataProvider('Crudbarang', array(
                    'criteria'=>$criteria,
                    'sort'=>$sort,
                    'pagination'=>array(
                        'pageSize'=>10,
                    ),
            ));
    }
        
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
}
