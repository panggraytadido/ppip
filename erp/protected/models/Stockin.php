<?php

/**
 * This is the model class for table "histori.stockin".
 *
 * The followings are the available columns in table 'histori.stockin':
 * @property string $id
 * @property integer $barangid
 * @property integer $jumlah
 * @property string $tanggal
 * @property string $createddate
 * @property integer $userid
 * @property integer $penerimaanbarangid
 */
class Stockin extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Stockin the static model class
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
		return 'histori.stockin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('barangid, jumlah', 'required'),
			array('barangid, userid, penerimaanbarangid', 'numerical', 'integerOnly'=>true),
			array('tanggal, createddate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, barangid, jumlah, tanggal, createddate, userid, penerimaanbarangid', 'safe', 'on'=>'search'),
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
			'barangid' => 'Barangid',
			'jumlah' => 'Jumlah',
			'tanggal' => 'Tanggal',
			'createddate' => 'Createddate',
			'userid' => 'Userid',
			'penerimaanbarangid' => 'Penerimaanbarangid',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('barangid',$this->barangid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('penerimaanbarangid',$this->penerimaanbarangid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}