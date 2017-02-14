<?php

/**
 * This is the model class for table "transaksi.stocksupplier".
 *
 * The followings are the available columns in table 'transaksi.stocksupplier':
 * @property integer $id
 * @property integer $supplierid
 * @property integer $barangid
 * @property integer $lokasipenyimpananbarangid
 * @property integer $jumlah
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 *
 * The followings are the available model relations:
 * @property Supplier $supplier
 * @property Barang $barang
 * @property Lokasipenyimpananbarang $lokasipenyimpananbarang
 */
class Stocksupplier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.stocksupplier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplierid, barangid, lokasipenyimpananbarangid, userid', 'numerical', 'integerOnly'=>true),
			array('createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplierid, barangid, lokasipenyimpananbarangid, jumlah, createddate, updateddate, userid', 'safe', 'on'=>'search'),
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
			'supplier' => array(self::BELONGS_TO, 'Supplier', 'supplierid'),
			'barang' => array(self::BELONGS_TO, 'Barang', 'barangid'),
			'lokasipenyimpananbarang' => array(self::BELONGS_TO, 'Lokasipenyimpananbarang', 'lokasipenyimpananbarangid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'supplierid' => 'Supplierid',
			'barangid' => 'Barangid',
			'lokasipenyimpananbarangid' => 'Lokasipenyimpananbarangid',
			'jumlah' => 'Jumlah',
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
		$criteria->compare('supplierid',$this->supplierid);
		$criteria->compare('barangid',$this->barangid);
		$criteria->compare('lokasipenyimpananbarangid',$this->lokasipenyimpananbarangid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stocksupplier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
