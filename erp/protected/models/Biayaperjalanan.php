<?php

/**
 * This is the model class for table "transaksi.biayaperjalanan".
 *
 * The followings are the available columns in table 'transaksi.biayaperjalanan':
 * @property integer $upah
 * @property string $nama
 * @property integer $kotaid
 * @property integer $propinsiid
 * @property integer $id
 * @property integer $kendaraanid
 */
class Biayaperjalanan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.biayaperjalanan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nama', 'required'),
			array('upah, kotaid, propinsiid, kendaraanid', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('upah, nama, kotaid, propinsiid, id, kendaraanid', 'safe', 'on'=>'search'),
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
			'upah' => 'Upah',
			'nama' => 'Tujuan',
			'kotaid' => 'Kotaid',
			'propinsiid' => 'Propinsiid',
			'id' => 'ID',
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

		$criteria->compare('upah',$this->upah);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('kotaid',$this->kotaid);
		$criteria->compare('propinsiid',$this->propinsiid);
		$criteria->compare('id',$this->id);
		$criteria->compare('kendaraanid',$this->kendaraanid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Biayaperjalanan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
