<?php

class AdminController extends Controller
{
    public $defaultAction = 'admin';
    public $layout='//layouts/column2';

    private $_model;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return CMap::mergeArray(parent::filters(),array(
            'accessControl', // perform access control for CRUD operations
        ));
    }
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete','create','update','view'),
                'users'=>UserModule::getAdmins(),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $this->layout='//layouts/column2_1';
        $model=new User('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['User']))
            $model->attributes=$_GET['User'];

        $this->render('index',array(
            'model'=>$model,
        ));
        /*$dataProvider=new CActiveDataProvider('User', array(
            'pagination'=>array(
                'pageSize'=>Yii::app()->controller->module->user_page_size,
            ),
        ));

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));//*/
    }


    /**
     * Displays a particular model.
     */
    public function actionView()
    {
        $this->layout='//layouts/column2';
        $model = $this->loadModel();
        $this->render('view',array(
            'model'=>$model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new User;
        $profile=new Profile;
        $userskaryawan=new Userskaryawan;
        
        $this->performAjaxValidation($model,$profile,$userskaryawan);

        if(isset($_POST['User']) && isset($_POST['Profile']))
        {
            $model->attributes=$_POST['User'];
            $model->activkey=Yii::app()->controller->module->encrypting(microtime().$model->password);

            $profile->attributes=$_POST['Profile'];
            $profile->user_id=0;
            
            $userskaryawan->attributes=$_POST['Userskaryawan'];
            $userskaryawan->usersid = 0;
            
            if($model->validate() && $profile->validate() && $userskaryawan->validate())
            {
                $model->password=Yii::app()->controller->module->encrypting($model->password);

                if($model->save()) 
                {
                    $profile->user_id=$model->id;
                    $profile->save();

                    $userskaryawan->usersid = $model->id;
                    $userskaryawan->save();

                    echo CJSON::encode(array
                            (
                                'result'=>'OK',
                                'id'=>$model->id
                            ));
                    Yii::app ()->user->setFlash ( 'success', "Data User Baru Berhasil Ditambah.");
                    Yii::app()->end();
                }
            }
            else
            {
                $error = CActiveForm::validate(array($model, $profile, $userskaryawan));
                if($error!='[]')
                {
                    echo $error;
                }
                Yii::app()->end();
            }
        }

        $this->render('create',array(
            'model'=>$model,
            'profile'=>$profile,
            'userskaryawan'=>$userskaryawan
        ));
        
        Yii::app()->end();
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $model=$this->loadModel();
        $profile = $model->profile;
        $userskaryawan = $model->userskaryawan;
        
        $this->performAjaxValidation($model,$profile,$userskaryawan);

        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            $profile->attributes=$_POST['Profile'];
            $userskaryawan->attributes=$_POST['Userskaryawan'];

            if($model->validate() && $profile->validate() && $userskaryawan->validate())
            {
                $old_password = User::model()->notsafe()->findByPk($model->id);
                if ($old_password->password!=$model->password) 
                {
                    $model->password=Yii::app()->controller->module->encrypting($model->password);
                    $model->activkey=Yii::app()->controller->module->encrypting(microtime().$model->password);
                }
                
                $model->save();
                $profile->save();
                $userskaryawan->save();

                echo CJSON::encode(array
                                    (
                                        'result'=>'OK',
                                        'id'=>$model->id
                                    ));
                            Yii::app ()->user->setFlash ( 'success', "Data User Baru Berhasil Diubah.");
                            Yii::app()->end();
                            
                $this->redirect(array('view','id'=>$model->id));
            }
            else
            {
                $error = CActiveForm::validate(array($model, $profile, $userskaryawan));
                if($error!='[]')
                {
                    echo $error;
                }
                Yii::app()->end();
            }
        }

        $this->render('update',array(
            'model'=>$model,
            'profile'=>$profile,
            'userskaryawan'=>$userskaryawan
        ));
        
        Yii::app()->end();
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $model = $this->loadModel();
            $profile = Profile::model()->findByPk($model->id);
            $profile->delete();
            
            $karyawan = Userskaryawan::model()->find('usersid='.$model->id);
            $karyawan->delete();
            
            $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_POST['ajax']))
                $this->redirect(array('/user/admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model1, $model2, $model3)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
        {
            echo CActiveForm::validate(array($model1, $model2, $model3));
            Yii::app()->end();
        }
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel()
    {
        if($this->_model===null)
        {
            if(isset($_GET['id']))
                $this->_model=User::model()->notsafe()->findbyPk($_GET['id']);
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }

}