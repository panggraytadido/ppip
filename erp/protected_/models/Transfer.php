<?php

/**
 * This is the model class for table "transaksi.transfer".
 *
 * The followings are the available columns in table 'transaksi.transfer':
 * @property string $id
 * @property integer $jenistransferid
 * @property integer $rekeningid
 * @property integer $supplierpembeliid
 * @property integer $debit
 * @property integer $kredit
 * @property integer $saldo
 * @property string $tanggal
 * @property integer $supplierid
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 */
class Transfer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.transfer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('jenistransferid, rekeningid, debit, kredit, saldo, supplierid, userid', 'numerical', 'integerOnly'=>true),
			array('tanggal, createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, jenistransferid, rekeningid, debit, kredit, saldo, tanggal, supplierid, createddate, updateddate, userid', 'safe', 'on'=>'search'),
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
			'jenistransferid' => 'Jenistransferid',
			'rekeningid' => 'Rekeningid',			
			'debit' => 'Debit',
			'kredit' => 'Kredit',
			'saldo' => 'Saldo',
			'tanggal' => 'Tanggal',
			'supplierid' => 'Supplierid',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('jenistransferid',$this->jenistransferid);
		$criteria->compare('rekeningid',$this->rekeningid);		
		$criteria->compare('debit',$this->debit);
		$criteria->compare('kredit',$this->kredit);
		$criteria->compare('saldo',$this->saldo);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('supplierid',$this->supplierid);
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
	 * @return Transfer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
