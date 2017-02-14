<?php

/**
 * This is the model class for table "transaksi.hargabarang".
 *
 * The followings are the available columns in table 'transaksi.hargabarang':
 * @property integer $id
 * @property integer $barangid
 * @property integer $supplierid
 * @property integer $hargamodal
 * @property integer $hargaeceran
 * @property integer $hargagrosir
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Supplier $supplier
 */
class Hargabarang extends CActiveRecord
{
	public $divisi;
	public $supplier;
	public $barang;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.hargabarang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('barangid, supplierid, hargamodal, hargaeceran, hargagrosir, userid', 'numerical', 'integerOnly'=>true),
			array('createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, barangid, supplierid, hargamodal, hargaeceran, hargagrosir, createddate, updateddate, userid', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'barang' => array(self::BELONGS_TO, 'Barang', 'barangid'),
			'supplier' => array(self::BELONGS_TO, 'Supplier', 'supplierid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'barangid' => 'Barangid',
			'supplierid' => 'Supplierid',
			'hargamodal' => 'Hargamodal',
			'hargaeceran' => 'Hargaeceran',
			'hargagrosir' => 'Hargagrosir',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('barangid',$this->barangid);
		$criteria->compare('supplierid',$this->supplierid);
		$criteria->compare('hargamodal',$this->hargamodal);
		$criteria->compare('hargaeceran',$this->hargaeceran);
		$criteria->compare('hargagrosir',$this->hargagrosir);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listHargaBarang()
	{
		$criteria=new CDbCriteria;

            $criteria->select = 'b.kode,s.namaperusahaan as supplier,b.nama as barang,d.nama as divisi,b.id,hb.hargamodal,hb.hargaeceran,hb.hargagrosir';

            $criteria->alias = 'hb';
            $criteria->join .= ' INNER JOIN master.barang AS b ON b.id=hb.barangid ';
			$criteria->join .= ' INNER JOIN master.supplier AS s ON s.id=hb.supplierid ';
			$criteria->join .= ' INNER JOIN master.divisi AS d on d.id=b.divisiid';	
            $criteria->order = 'd.nama,b.id desc';

            if(isset($_GET['Hargabarang']))
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
            $sql ="SELECT b.kode,s.namaperusahaan as supplier,b.nama as barang,b.id,d.nama as divisi,hb.hargaeceran,hb.hargagrosir,hb.hargamodal FROM transaksi.hargabarang hb
						INNER JOIN master.barang AS b ON b.id=hb.barangid 
						INNER JOIN master.supplier AS s ON s.id=hb.supplierid 
						INNER JOIN master.divisi as d ON d.id=b.divisiid
						ORDER BY b.nama,b.id";            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
	}
	
	public function reportHargaBarangPerDivisi($divisiid)
	{
		$connection=Yii::app()->db;
            $sql ="SELECT b.kode,s.namaperusahaan as supplier,b.nama as barang,b.id,d.nama as divisi,hb.hargaeceran,hb.hargagrosir,hb.hargamodal FROM transaksi.hargabarang hb
						INNER JOIN master.barang AS b ON b.id=hb.barangid 
						INNER JOIN master.supplier AS s ON s.id=hb.supplierid 
						INNER JOIN master.divisi as d ON d.id=b.divisiid where d.id=$divisiid
						ORDER BY b.nama,b.id";            
            $data=$connection->createCommand($sql)->queryAll();
            return $data;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hargabarang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
