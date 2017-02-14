<?php

/**
 * This is the model class for table "master.barang".
 *
 * The followings are the available columns in table 'master.barang':
 * @property integer $id
 * @property integer $divisiid
 * @property integer $supplierid
 * @property string $kode
 * @property string $nama
 * @property integer $hargamodal
 * @property integer $hargaeceran
 * @property integer $hargagrosir
 */
class Barang extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Barang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'master.barang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('divisiid, kode, nama', 'required'),
			array('divisiid', 'numerical', 'integerOnly'=>true),
			array('kode', 'length', 'max'=>20),
			array('nama', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, divisiid, kode, nama', 'safe', 'on'=>'search'),
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
                    'divisi' => array(self::BELONGS_TO, 'Divisi', 'divisiid'),
                    'stock' => array(self::HAS_MANY, 'Stock', 'barangid'),
                    'stocksupplier' => array(self::HAS_MANY, 'Stocksupplier', 'barangid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'divisiid' => 'Divisiid',
			'supplierid' => 'Supplierid',
			'kode' => 'Kode',
			'nama' => 'Nama',
			'hargamodal' => 'Hargamodal',
			'hargaeceran' => 'Hargaeceran',
			'hargagrosir' => 'Hargagrosir',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('divisiid',$this->divisiid);
		$criteria->compare('supplierid',$this->supplierid);
		$criteria->compare('kode',$this->kode,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('hargamodal',$this->hargamodal);
		$criteria->compare('hargaeceran',$this->hargaeceran);
		$criteria->compare('hargagrosir',$this->hargagrosir);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}