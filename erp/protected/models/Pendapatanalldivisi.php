<?php

/**
 * This is the model class for table "report.pendapatanalldivisi".
 *
 * The followings are the available columns in table 'report.pendapatanalldivisi':
 * @property string $id
 * @property integer $totalpenjualan
 * @property integer $totalmodal
 * @property integer $totallaba
 * @property integer $totaldiskon
 * @property integer $totalpengeluaran
 * @property integer $totalpiutang
 * @property integer $labarugi
 * @property string $tanggal
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 */
class Pendapatanalldivisi extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report.pendapatanalldivisi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('totalpenjualan, totalmodal, totallaba, totaldiskon, totalpengeluaran, totalpiutang, totallabarugi, userid', 'numerical', 'integerOnly'=>true),
			array('tanggal, createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, totalpenjualan, totalmodal, totallaba, totaldiskon, totalpengeluaran, totalpiutang, totallabarugi, tanggal, createddate, updateddate, userid', 'safe', 'on'=>'search'),
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
			'totalpenjualan' => 'Totalpenjualan',
			'totalmodal' => 'Totalmodal',
			'totallaba' => 'Totallaba',
			'totaldiskon' => 'Totaldiskon',
			'totalpengeluaran' => 'Totalpengeluaran',
			'totalpiutang' => 'Totalpiutang',
			'totallabarugi' => 'Labarugi',
			'tanggal' => 'Tanggal',
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
		$criteria->compare('totalpenjualan',$this->totalpenjualan);
		$criteria->compare('totalmodal',$this->totalmodal);
		$criteria->compare('totallaba',$this->totallaba);
		$criteria->compare('totaldiskon',$this->totaldiskon);
		$criteria->compare('totalpengeluaran',$this->totalpengeluaran);
		$criteria->compare('totalpiutang',$this->totalpiutang);
		$criteria->compare('totallabarugi',$this->totallabarugi);
		$criteria->compare('tanggal',$this->tanggal,true);
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
	 * @return Pendapatanalldivisi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
