<?php

class HargabarangController extends Controller
{
	public function actionIndex()
	{
		$model=new Hargabarang('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Hargabarang']))
			$model->attributes=$_GET['Hargabarang'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
        
	public function actionSubmit()
	{
		if (Yii::app ()->request->isAjaxRequest) {
				
			$model = new Hargabarang;		
			if(isset($_POST['divisi']))
			{								
				$divisiid = intval($_POST['divisi']);
			}			
			
			$this->layout = 'a';         
			$this->render('grid_hargabarang', array(                            
							'model'=>$model,
							'divisiid'=>$divisiid	
            ));
		}
	}
	
	public function actionPrint()
	{
		$this->render('report', array(                            
							
            ));	
	}

}