<?php

class GajikaryawangudangController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Penggajian - Karyawan Gudang';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Karyawangudang('search');
            $model->unsetAttributes();
            if(isset($_GET['Karyawangudang']))
                    $model->attributes=$_GET['Karyawangudang'];
            
            $this->render('index',array(
                    'model'=>$model,
            ));
        }
 
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='karyawangudang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}