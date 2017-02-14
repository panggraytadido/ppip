<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OutboxController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Gudang - Outbox';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Outbox('search');
            $model->unsetAttributes();
            if(isset($_GET['Outbox']))
                    $model->attributes=$_GET['Outbox'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
}        