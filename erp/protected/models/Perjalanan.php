<?php

/**
 * This is the model class for table "transaksi.perjalanan".
 *
 * The followings are the available columns in table 'transaksi.perjalanan':
 * @property string $id
 * @property string $tanggal
 * @property integer $karyawanid
 * @property integer $biayaperjalananid
 * @property integer $bbm
 * @property integer $upah
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 * @property integer $kendaraanid
 */
class Perjalanan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.perjalanan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal, karyawanid', 'required'),
			array('karyawanid, biayaperjalananid, bbm, upah, userid, kendaraanid', 'numerical', 'integerOnly'=>true),
			array('updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tanggal, karyawanid, biayaperjalananid, bbm, upah, createddate, updateddate, userid, kendaraanid', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tanggal' => 'Tanggal',
			'karyawanid' => 'Karyawan',
			'biayaperjalananid' => 'Biayaperjalananid',
			'bbm' => 'Bbm',
			'upah' => 'Upah',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
			'kendaraanid' => 'Kendaraanid',
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
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('karyawanid',$this->karyawanid);
		$criteria->compare('biayaperjalananid',$this->biayaperjalananid);
		$criteria->compare('bbm',$this->bbm);
		$criteria->compare('upah',$this->upah);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('kendaraanid',$this->kendaraanid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Perjalanan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
