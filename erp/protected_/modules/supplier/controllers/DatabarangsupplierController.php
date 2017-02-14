<?php

class DatabarangsupplierController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			
		);
	}
        
        /**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Databarangpersupplier('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Databarangpersupplier']))
			$model->attributes=$_GET['Databarangpersupplier'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
        
        public function actionDetail()
        {
           $id = Yii::app()->getRequest()->getParam('id');           
           
           $child = Databarangpersupplier::model()->listDetail($id);	
            // partially rendering "_relational" view
            $this->renderPartial('detail', array(
                            'id' => Yii::app()->getRequest()->getParam('id'),
                            'child' => $child,
            ));
        }
}        