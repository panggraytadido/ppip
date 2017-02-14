<?php

/**
 * This is the model class for table "report.pendapatan".
 *
 * The followings are the available columns in table 'report.pendapatan':
 * @property string $id
 * @property integer $divisid
 * @property string $namadivisi
 * @property integer $totalpenjualan
 * @property integer $totalmodal
 * @property integer $totallaba
 * @property integer $totalpengeluaran
 * @property integer $labarugi
 * @property string $tanggal
 */
class Reportpendapatan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report.pendapatanperdivisi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('divisid, totalpenjualan, totalmodal, totallaba, totalpengeluaran, labarugi', 'numerical', 'integerOnly'=>true),
			array('namadivisi', 'length', 'max'=>30),
			array('tanggal', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, divisid, namadivisi, totalpenjualan, totalmodal, totallaba, totalpengeluaran, labarugi, tanggal', 'safe', 'on'=>'search'),
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
			'divisid' => 'Divisid',
			'namadivisi' => 'Namadivisi',
			'totalpenjualan' => 'Totalpenjualan',
			'totalmodal' => 'Totalmodal',
			'totallaba' => 'Totallaba',
			'totalpengeluaran' => 'Totalpengeluaran',
			'labarugi' => 'Labarugi',
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
		$criteria->compare('divisid',$this->divisid);
		$criteria->compare('namadivisi',$this->namadivisi,true);
		$criteria->compare('totalpenjualan',$this->totalpenjualan);
		$criteria->compare('totalmodal',$this->totalmodal);
		$criteria->compare('totallaba',$this->totallaba);
		$criteria->compare('totalpengeluaran',$this->totalpengeluaran);
		$criteria->compare('labarugi',$this->labarugi);
		$criteria->compare('tanggal',$this->tanggal,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Reportpendapatan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
