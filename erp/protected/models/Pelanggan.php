<?php

/**
 * This is the model class for table "master.pelanggan".
 *
 * The followings are the available columns in table 'master.pelanggan':
 * @property integer $id
 * @property string $kode
 * @property string $nama
 * @property integer $status
 * @property string $alamat
 * @property integer $propinsiid
 * @property integer $kotaid
 *
 * The followings are the available model relations:
 * @property Propinsi $propinsi
 * @property Kota $kota
 */
class Pelanggan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'master.pelanggan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('kode, nama', 'required'),
			array('status, propinsiid, kotaid', 'numerical', 'integerOnly'=>true),
			array('kode', 'length', 'max'=>20),
			array('nama', 'length', 'max'=>150),
			array('alamat', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, kode, nama, status, alamat, propinsiid, kotaid', 'safe', 'on'=>'search'),
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
			'propinsi' => array(self::BELONGS_TO, 'Propinsi', 'propinsiid'),
			'kota' => array(self::BELONGS_TO, 'Kota', 'kotaid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kode' => 'Kode',
			'nama' => 'Nama',
			'status' => 'Status',
			'alamat' => 'Alamat',
			'propinsiid' => 'Propinsi',
			'kotaid' => 'Kota',
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
                if(isset($_GET['Pelanggan']))
                {
                
                    $criteria->compare('id',$this->id);
                    $criteria->compare('upper(kode)',  strtoupper($_GET['Pelanggan']['kode']),true);
                    $criteria->compare('upper(nama)',  strtoupper($_GET['Pelanggan']['nama']),true);
                    $criteria->compare('status',$this->status);
                    $criteria->compare('alamat',$this->alamat,true);
                    $criteria->compare('propinsiid',$this->propinsiid);
                    $criteria->compare('kotaid',$this->kotaid);                  
                }    
                
                 return new CActiveDataProvider($this, array(
                            'criteria'=>$criteria,
                            'pagination'=>array(
                                'pageSize'=>10,
                            ),
                    ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pelanggan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
