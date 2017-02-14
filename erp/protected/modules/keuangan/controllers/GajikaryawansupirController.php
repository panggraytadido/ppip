<?php

class GajikaryawansupirController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Penggajian - Karyawan Supir';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Karyawansupir('search');
            $model->unsetAttributes();
            if(isset($_GET['Karyawansupir']))
                    $model->attributes=$_GET['Karyawansupir'];
            
            $this->render('index',array(
                    'model'=>$model,
            ));
        }
 
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='karyawansupir-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}