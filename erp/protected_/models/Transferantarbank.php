<?php

/**
 * This is the model class for table "transaksi.transferantarbank".
 *
 * The followings are the available columns in table 'transaksi.transferantarbank':
 * @property integer $id
 * @property integer $rekeningid
 * @property integer $rekeningtujuanid
 * @property string $tanggal
 * @property integer $jumlah
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 */
class Transferantarbank extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.transferantarbank';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rekeningid, rekeningtujuanid, jumlah','required' ),
			array('tanggal, createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rekeningid, rekeningtujuanid, tanggal, jumlah, createddate, updateddate, userid', 'safe', 'on'=>'search'),
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
			'rekeningid' => 'Rekening',
			'rekeningtujuanid' => 'Rekening Tujuan',
			'tanggal' => 'Tanggal',
			'jumlah' => 'Jumlah',
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

                if(isset($_GET['Transferantarbank']))
                {                                        
                    if($_GET['Transferantarbank']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Transferantarbank']['tanggal'])));

                    
                }   
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Transferantarbank the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
