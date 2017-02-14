<?php
class DefaultController extends Controller
{
	public function actionIndex()
	{
		//$this->render('index');
                echo Yii::app()->Report->Export("databarang.jrxml","DataBarang",array(1),array("id"=>"id"),"pdf");
	}
}