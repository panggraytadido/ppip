<?php

/**
 * This is the model class for table "transaksi.stock".
 *
 * The followings are the available columns in table 'transaksi.stock':
 * @property string $id
 * @property integer $barangid
 * @property integer $jumlah
 * @property string $tanggal
 * @property string $createddate
 * @property integer $userid
 * @property string $updatedate
 * @property boolean $isdeleted
 * @property integer $lokasipenyimpananbarangid
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Lokasipenyimpananbarang $lokasipenyimpananbarang
 */
class Stock extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.stock';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('barangid, jumlah, tanggal', 'required'),
			array('barangid, userid, lokasipenyimpananbarangid', 'numerical', 'integerOnly'=>true),
			array('createddate, updatedate, isdeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, barangid, jumlah, tanggal, createddate, userid, updatedate, isdeleted, lokasipenyimpananbarangid', 'safe', 'on'=>'search'),
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
			'barangid' => 'Barangid',
			'jumlah' => 'Jumlah',
			'tanggal' => 'Tanggal',
			'createddate' => 'Createddate',
			'userid' => 'Userid',
			'updatedate' => 'Updatedate',
			'isdeleted' => 'Isdeleted',
			'lokasipenyimpananbarangid' => 'Lokasipenyimpananbarangid',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('barangid',$this->barangid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('updatedate',$this->updatedate,true);
		$criteria->compare('isdeleted',$this->isdeleted);
		$criteria->compare('lokasipenyimpananbarangid',$this->lokasipenyimpananbarangid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stock the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
