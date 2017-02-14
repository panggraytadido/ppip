<?php

/**
 * This is the model class for table "transaksi.faktursupplier".
 *
 * The followings are the available columns in table 'transaksi.faktursupplier':
 * @property string $id
 * @property string $nofaktur
 * @property string $createddate
 * @property integer $userid
 * @property string $updateddate
 * @property integer $hargatotal
 * @property integer $bayar
 * @property integer $sisa
 * @property integer $diskon
 * @property integer $supplierpembeliid
 * @property integer $pembelianke
 * @property string $tanggal
 *
 * The followings are the available model relations:
 * @property Supplier $supplierpembeli
 * @property Faktursupplierpenjualanbarang[] $faktursupplierpenjualanbarangs
 */
class Faktursupplier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.faktursupplier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userid, hargatotal, bayar, sisa, diskon, supplierpembeliid, pembelianke', 'numerical', 'integerOnly'=>true),
			array('nofaktur', 'length', 'max'=>25),
			array('createddate, updateddate, tanggal', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nofaktur, createddate, userid, updateddate, hargatotal, bayar, sisa, diskon, supplierpembeliid, pembelianke, tanggal', 'safe', 'on'=>'search'),
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
			'supplierpembeli' => array(self::BELONGS_TO, 'Supplier', 'supplierpembeliid'),
			'faktursupplierpenjualanbarangs' => array(self::HAS_MANY, 'Faktursupplierpenjualanbarang', 'faktursupplierid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nofaktur' => 'Nofaktur',
			'createddate' => 'Createddate',
			'userid' => 'Userid',
			'updateddate' => 'Updateddate',
			'hargatotal' => 'Hargatotal',
			'bayar' => 'Bayar',
			'sisa' => 'Sisa',
			'diskon' => 'Diskon',
			'supplierpembeliid' => 'Supplierpembeliid',
			'pembelianke' => 'Pembelianke',
			'tanggal' => 'Tanggal',
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
		$criteria->compare('nofaktur',$this->nofaktur,true);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('hargatotal',$this->hargatotal);
		$criteria->compare('bayar',$this->bayar);
		$criteria->compare('sisa',$this->sisa);
		$criteria->compare('diskon',$this->diskon);
		$criteria->compare('supplierpembeliid',$this->supplierpembeliid);
		$criteria->compare('pembelianke',$this->pembelianke);
		$criteria->compare('tanggal',$this->tanggal,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Faktursupplier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
