<?php

/**
 * This is the model class for table "transaksi.gajiperjabatan".
 *
 * The followings are the available columns in table 'transaksi.gajiperjabatan':
 * @property integer $id
 * @property integer $jabatanid
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 * @property integer $gaji
 * @property integer $jeniskaryawanid
 * @property integer $karyawanid
 *
 * The followings are the available model relations:
 * @property Jabatan $jabatan
 * @property Jeniskaryawan $jeniskaryawan
 * @property Karyawan $karyawan
 */
class Gajiperjabatan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.gajiperjabatan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('jabatanid, userid, gaji, jeniskaryawanid, karyawanid', 'numerical', 'integerOnly'=>true),
			array('createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, jabatanid, createddate, updateddate, userid, gaji, jeniskaryawanid, karyawanid', 'safe', 'on'=>'search'),
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
			'jabatan' => array(self::BELONGS_TO, 'Jabatan', 'jabatanid'),
			'jeniskaryawan' => array(self::BELONGS_TO, 'Jeniskaryawan', 'jeniskaryawanid'),
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
			'jabatanid' => 'Jabatanid',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
			'gaji' => 'Gaji',
			'jeniskaryawanid' => 'Jeniskaryawanid',
			'karyawanid' => 'Karyawanid',
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
		$criteria->compare('jabatanid',$this->jabatanid);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('gaji',$this->gaji);
		$criteria->compare('jeniskaryawanid',$this->jeniskaryawanid);
		$criteria->compare('karyawanid',$this->karyawanid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gajiperjabatan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
