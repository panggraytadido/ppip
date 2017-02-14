<?php

/**
 * This is the model class for table "transaksi.jumlahsaham".
 *
 * The followings are the available columns in table 'transaksi.jumlahsaham':
 * @property integer $id
 * @property integer $anggotaid
 * @property integer $sahamid
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 */
class Jumlahsaham extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Jumlahsaham the static model class
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
		return 'master.jumlahsaham';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		array('anggotaid, tanggal,kode, sahamid,rekeningid, hargasaham, jumlahsaham, totalsaham', 'required'),
			array('jumlahsaham', 'rekeningid','numerical', 'integerOnly'=>true),
			array('createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, anggotaid,rekeningid, sahamid, createddate, updateddate, userid', 'safe', 'on'=>'search'),
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
			'anggota' => array(self::BELONGS_TO, 'Anggota', 'anggotaid'),
			'saham' => array(self::BELONGS_TO, 'Saham', 'sahamid'),
			'rekening' => array(self::BELONGS_TO, 'Rekening', 'rekeningid'),
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'anggotaid' => 'Anggota',
			'sahamid' => 'Saham',
			'rekeningid' => 'Rekening',
			'jumlahsaham' => 'Jumlah Saham',
			'totalsaham' => 'Total Saham',
			'hargasaham' => 'Harga Per Lembar Saham',
			'dokumen' => 'Dokumen',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
			'kode' => 'No Saham',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('kode',$this->kode,true);
		$criteria->compare('anggotaid',$this->anggotaid);
		$criteria->compare('rekeningid',$this->rekeningid);
		$criteria->compare('sahamid',$this->sahamid);
		$criteria->compare('jumlahsaham',$this->jumlahsaham);
		$criteria->compare('totalsaham',$this->totalsaham);
		$criteria->compare('hargasaham',$this->hargasaham);
		$criteria->compare('dokumen',$this->dokumen,true);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);
                
                $criteria->condition='isdeleted=false';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}