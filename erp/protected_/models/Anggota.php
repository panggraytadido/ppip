<?php

/**
 * This is the model class for table "master.anggota".
 *
 * The followings are the available columns in table 'master.anggota':
 * @property string $id
 * @property string $nama
 * @property integer $jeniskelamin
 * @property string $alamat
 * @property integer $hp
 * @property integer $telepon
 * @property string $noktp
 * @property string $photoktp
 * @property string $photo
 * @property integer $status
 * @property string $tanggalbermitra
 * @property string $pendidikan
 * @property string $kode
 */
class Anggota extends CActiveRecord
{
	public $jumlahsaham;
	public $totalsaham;
	public $pemegangsaham;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'master.anggota';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nama, jeniskelamin, noktp, kode', 'required'),
			array('jeniskelamin, hp, telepon, status', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>255),
			array('noktp, pendidikan', 'length', 'max'=>30),
			//array('photoktp, photo', 'length', 'max'=>50),
			array('kode', 'length', 'max'=>10),
			array('alamat, tanggalbermitra', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nama, jeniskelamin, alamat, hp, telepon, noktp, photoktp, photo, status, tanggalbermitra, pendidikan, kode', 'safe', 'on'=>'search'),
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
			'nama' => 'Nama',
			'jeniskelamin' => 'Jenis Kelamin',
			'alamat' => 'Alamat',
			'hp' => 'Hp',
			'telepon' => 'Telepon',
			'noktp' => 'No KTP',
			'photoktp' => 'Photo KTP',
			'photo' => 'Photo',
			'status' => 'Status',
			'tanggalbermitra' => 'Tanggal Bermitra',
			'pendidikan' => 'Pendidikan',
			'kode' => 'Kode',
			'ispemegangsaham' => 'Apakah Anggota diatas sebagai Pemegang Saham, jika ya centang checkbox berikut',
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
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('jeniskelamin',$this->jeniskelamin);
		$criteria->compare('alamat',$this->alamat,true);
		$criteria->compare('hp',$this->hp);
		$criteria->compare('telepon',$this->telepon);
		$criteria->compare('noktp',$this->noktp,true);
		$criteria->compare('photoktp',$this->photoktp,true);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('tanggalbermitra',$this->tanggalbermitra,true);
		$criteria->compare('pendidikan',$this->pendidikan,true);
		$criteria->compare('kode',$this->kode,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Anggota the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
