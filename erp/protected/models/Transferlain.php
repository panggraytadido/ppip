<?php

/**
 * This is the model class for table "transaksi.transferlain".
 *
 * The followings are the available columns in table 'transaksi.transferlain':
 * @property integer $id
 * @property integer $rekeningid
 * @property string $nama
 * @property integer $jumlah
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 * @property string $tanggal
 *
 * The followings are the available model relations:
 * @property Rekening $rekening
 */
class Transferlain extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.transferlain';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('rekeningid, nama, jumlah, tanggal', 'required'),
			array('rekeningid, jumlah, userid', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>255),
			array('createddate, updateddate, tanggal', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rekeningid, nama, jumlah, createddate, updateddate, userid, tanggal', 'safe', 'on'=>'search'),
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
			'rekeningid' => 'Rekening',
			'nama' => 'Nama',
			'jumlah' => 'Jumlah',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
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

                if(isset($_GET['Transferlain']))
                {                                        
                    if($_GET['Transferlain']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Transferlain']['tanggal'])));

                    
                }    

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Transferlain the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
