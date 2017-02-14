<?php

/**
 * This is the model class for table "transaksi.bongkarmuat".
 *
 * The followings are the available columns in table 'transaksi.bongkarmuat':
 * @property integer $id
 * @property integer $karyawanid
 * @property integer $penjualanbarangid
 * @property integer $upah
 * @property string $tanggal
 * @property string $createddate
 * @property integer $userid
 * @property integer $penerimaanbarangid
 * @property string $updatedate
 * @property boolean $isdeleted
 * @property integer $divisiid
 *
 * The followings are the available model relations:
 * @property Karyawan $karyawan
 */
class Bongkarmuat extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.bongkarmuat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('karyawanid, penjualanbarangid, userid, penerimaanbarangid, divisiid', 'numerical', 'integerOnly'=>true),
			array('tanggal, createddate, updatedate, isdeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, karyawanid, penjualanbarangid, upah, tanggal, createddate, userid, penerimaanbarangid, updatedate, isdeleted, divisiid', 'safe', 'on'=>'search'),
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
			'karyawan' => array(self::BELONGS_TO, 'Karyawan', 'karyawanid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'karyawanid' => 'Karyawanid',
			'penjualanbarangid' => 'Penjualanbarangid',
			'upah' => 'Upah',
			'tanggal' => 'Tanggal',
			'createddate' => 'Createddate',
			'userid' => 'Userid',
			'penerimaanbarangid' => 'Penerimaanbarangid',
			'updatedate' => 'updatedate',
			'isdeleted' => 'Isdeleted',
			'divisiid' => 'Divisiid',
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
		$criteria->compare('karyawanid',$this->karyawanid);
		$criteria->compare('penjualanbarangid',$this->penjualanbarangid);
		$criteria->compare('upah',$this->upah);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('penerimaanbarangid',$this->penerimaanbarangid);
		$criteria->compare('updatedate',$this->updatedate,true);
		$criteria->compare('isdeleted',$this->isdeleted);
		$criteria->compare('divisiid',$this->divisiid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Bongkarmuat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
