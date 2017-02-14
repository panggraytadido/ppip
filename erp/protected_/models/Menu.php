<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $posisi
 * @property integer $parentmenu
 */
class Menu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label, url', 'required'),
			array('posisi, parentmenu', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>50),
			array('url', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, label, url, posisi, parentmenu', 'safe', 'on'=>'search'),
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
			'label' => 'Label',
			'url' => 'Url',
			'posisi' => 'Posisi',
			'parentmenu' => 'Parentmenu',
		);
	}


    public function getAuthParentMenu(){
        $criteria = new CDbCriteria;
        $criteria->select = 'm.id, m.label, m.url, m.posisi';
        $criteria->alias = 'm';
        $criteria->join = 'INNER JOIN "public"."authmenu" AS am ON am.menuid = m.id ';
        $criteria->condition = 'm.parentmenu is NULL AND am.role = :role';
        $criteria->params = array(':role'=>Yii::app()->session['roles']);
        $criteria->order = 'm.posisi ASC';

        return $this::model()->findAll($criteria);
    }

    public function getAuthSubMenu($parentid){
        $criteria = new CDbCriteria;
        $criteria->select = 'm.id, m.label, m.url, m.posisi';
        $criteria->alias = 'm';
        $criteria->join = 'INNER JOIN "public"."authmenu" AS am ON am.menuid = m.id ';
        $criteria->condition = 'm.parentmenu = :parentid AND am.role = :role';
        $criteria->params = array(':parentid'=>$parentid, ':role'=>Yii::app()->session['roles']);
        $criteria->order = 'm.posisi ASC';

        return $this::model()->findAll($criteria);
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

		$criteria->compare('id',$this->id);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('posisi',$this->posisi);
		$criteria->compare('parentmenu',$this->parentmenu);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
=======
    public function getAllRole()
    {
        $userauthitems = UserAuthitem::model()->findAll("type=2");

        foreach($userauthitems as $k => $v){
            $data[$k] = array('id'=>strtolower(str_replace(" ", "", $v->name)),'nama'=>$v->description);
        }

        return $data;
    }

    public function getMenuName($id)
    {

        $menu = isset($id) ? $this::model()->findByAttributes(array('id'=>$id,'deptid'=>Yii::app()->session['deptid']))->label : '';

        return $menu;
    }

    public function getDept()
    {
        $dept = TluDepartemen::model()->findAll();

        foreach($dept as $k => $v){
            $data[$k] = array('id'=>$v->id,'text'=>$v->nama,'group'=>$v->divisi->nama);
        }

        return $data;
    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
        }
        
        //function untuk menampilkan menu
        public function getMenu() 
	 {
            //$roles = Yii::app()->session['roles']; 
            $roles = Rights::getAssignedRoles(1); // checkfor single role
            print("<pre>".print_r($roles,true)."</pre>");
            //$roles = Yii::app()->user->Id; // checkfor single role
            //echo $roles;
            //echo Yii::app()->user->Id;
            //die();
            //echo Yii::app()->user->Id;                        
//            foreach ($roles as $role)
//            {
//                $arrRoles[] = "'". $role->name. "'";
//            }

            die();
            $criteria = new CDbCriteria;
            $criteria->condition = 'parentmenu is null and id in (select menuid from authmenu where rolle in ('.$roles.'))';
            $criteria->order = 'posisi ASC';
            $mainMenus = Menu::model()->findAll($criteria);
            print("<pre>".print_r($mainMenus,true)."</pre>");
            
            
                     die();
            foreach ($mainMenus as $mainMenu) 
            {
                $criteria = new CDbCriteria;
                $criteria->condition = 'parentmenu = ' . $mainMenu->id.'and id in (select menuid from authmenu where rolle in('.implode(",",$arrRoles).'))';
                $criteria->order = 'posisi ASC';
                $subMenus = Menu::model()->findAll($criteria);
                    $subItems=null;
                    foreach ($subMenus as $subMenu) 
                    {
                        $subItems[] = array(
                            'label' => $subMenu->label,
                            'url' => array($subMenu->url),
                        );
                    }
                    
                $items[] = array(
                    'label' => $mainMenu->label,
                    'url' => array($mainMenu->url),
                    'items' => $subItems,
                );
        }
             
            return $items;
    }
}
