<?php

class HomeController extends Controller
{
	public $layout='//layouts/column2';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model = Karyawan::model()->with(array('jabatan','jeniskaryawan'))->findByPk(Yii::app()->session['karyawanid']);
            $this->render('index',array(
                    'model'=>$model,
            ));
        }
 
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='home-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}