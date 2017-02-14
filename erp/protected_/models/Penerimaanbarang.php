<?php

/**
 * This is the model class for table "transaksi.penerimaanbarang".
 *
 * The followings are the available columns in table 'transaksi.penerimaanbarang':
 * @property string $id
 * @property integer $barangid
 * @property integer $supplierid
 * @property integer $divisiid
 * @property string $tanggal
 * @property integer $jumlah
 * @property integer $beratlori
 * @property integer $totalbarang
 * @property string $createddate
 * @property integer $userid
 * @property string $updatedate
 * @property boolean $isdeleted
 * @property integer $lokasipenyimpananbarangid
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Divisi $divisi
 * @property Supplier $supplier
 * @property Lokasipenyimpananbarang $lokasipenyimpananbarang
 */
class Penerimaanbarang extends CActiveRecord
{        
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.penerimaanbarang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('barangid, supplierid, divisiid, tanggal, jumlah, beratlori, totalbarang, lokasipenyimpananbarangid', 'required'),
			array('barangid, supplierid, divisiid, beratlori, userid, lokasipenyimpananbarangid', 'numerical', 'integerOnly'=>true),
			array('createddate, updateddate, isdeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, barangid, supplierid, divisiid, tanggal, jumlah, beratlori, totalbarang, createddate, userid, updatedate, isdeleted, lokasipenyimpananbarangid', 'safe', 'on'=>'search'),
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
			'barang' => array(self::BELONGS_TO, 'Barang', 'barangid'),
			'divisi' => array(self::BELONGS_TO, 'Divisi', 'divisiid'),
			'supplier' => array(self::BELONGS_TO, 'Supplier', 'supplierid'),
			'lokasipenyimpananbarang' => array(self::BELONGS_TO, 'Lokasipenyimpananbarang', 'lokasipenyimpananbarangid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'barangid' => 'Barang',
			'supplierid' => 'Supplier',
			'divisiid' => 'Divisi',
			'tanggal' => 'Tanggal',
			'jumlah' => 'Jumlah',
			'beratlori' => 'Berat Lori',
			'totalbarang' => 'Total Barang',
			'createddate' => 'Created date',
			'userid' => 'User',
//			'updatedate' => 'Update date',
                        'updateddate' => 'Update date',
			'isdeleted' => 'Is deleted',
			'lokasipenyimpananbarangid' => 'Lokasi Barang',
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
		$criteria->compare('barangid',$this->barangid);
		$criteria->compare('supplierid',$this->supplierid);
		$criteria->compare('divisiid',$this->divisiid);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('beratlori',$this->beratlori);
		$criteria->compare('totalbarang',$this->totalbarang);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('updatedate',$this->updatedate,true);
		$criteria->compare('isdeleted',$this->isdeleted);
		$criteria->compare('lokasipenyimpananbarangid',$this->lokasipenyimpananbarangid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Penerimaanbarang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
