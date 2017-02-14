<?php

class SaldobankController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $pageTitle = 'Admin - Saldo Bank';

	/**
	 * @return array action filters
	
	public function filters()
	{
            /*
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
            
	}
         */
        
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{		
		$rek = Rekening::model()->findAll();
		
		for($i=0;$i<count($rek);$i++)
		{
			$saldo[] = array(
				'id'=>$rek[$i]['id'],
				'nama'=>$rek[$i]['namabank'],
				'norek'=>$rek[$i]['norekening'],
				'saldo'=>Saldobank::model()->listSaldo($rek[$i]['id'])
			);
			
		}
		
		$dataProvider=new CArrayDataProvider($saldo, array(
            'totalItemCount' => 4,
			'keyField' => 'id',
            //'keys'=>array('id', 'nama', 'saldo'),
            'sort'=>array(
                'attributes'=>array(
                    'id', 'nama', 'saldo','norek'
                ),
            ),
            'pagination'=>array(
                'pageSize'=>15,
            ),
        ));
		//print("<pre>".print_r($saldo,true)."</pre>");	
		//die;
		
		$this->render('index',array(    
			'saldo'=>$dataProvider
        ));
		
	}
             
}
