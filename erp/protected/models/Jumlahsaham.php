<?php

/**
 * This is the model class for table "master.jumlahsaham".
 *
 * The followings are the available columns in table 'master.jumlahsaham':
 * @property integer $id
 * @property integer $anggotaid
 * @property integer $sahamid
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 * @property string $tanggal
 * @property integer $jumlahsaham
 * @property integer $totalsaham
 * @property integer $hargasaham
 * @property string $dokumen
 * @property string $kode
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
			array('jumlahsaham, totalsaham', 'required'),
			array('anggotaid, sahamid, userid, jumlahsaham, totalsaham, hargasaham', 'numerical', 'integerOnly'=>true),			
			array('dokumen', 'length', 'max'=>50),
			array('kode', 'length', 'max'=>20),
			array('createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, anggotaid, sahamid, createddate, updateddate, userid, tanggal, jumlahsaham, totalsaham, hargasaham, dokumen, kode', 'safe', 'on'=>'search'),
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
			'anggotaid' => 'Anggota',
                        'rekeningid' => 'Rekening',
			'sahamid' => 'Saham',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
			'tanggal' => 'Tanggal',
			'jumlahsaham' => 'Jumlahsaham',
			'totalsaham' => 'Totalsaham',
			'hargasaham' => 'Hargasaham',
			'dokumen' => 'Dokumen',
			'kode' => 'Kode',
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
		$criteria->compare('anggotaid',$this->anggotaid);
		$criteria->compare('sahamid',$this->sahamid);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('jumlahsaham',$this->jumlahsaham);
		$criteria->compare('totalsaham',$this->totalsaham);
		$criteria->compare('hargasaham',$this->hargasaham);
		$criteria->compare('dokumen',$this->dokumen,true);
		$criteria->compare('kode',$this->kode,true);

                
                $criteria->condition='isdeleted=false';
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}