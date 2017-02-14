<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';
        

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            /* @var $cs CClientScript */
            $cs = Yii::app()->clientScript;
            /* @var $theme CTheme */
            $theme = Yii::app()->theme;
            
            $cs->registerCssFile($theme->getBaseUrl() . '/font-awesome/css/font-awesome.css');
            $cs->registerCssFile($theme->getBaseUrl() . '/css/bootstrap.min.css');
            $cs->registerCssFile($theme->getBaseUrl() . '/css/animate.css');
            $cs->registerCssFile($theme->getBaseUrl() . '/css/style.css');

            //$cs->registerScriptFile($theme->getBaseUrl() . '/js/login/jquery.backstretch.min.js', CClientScript::POS_END);
            //$cs->registerScriptFile($theme->getBaseUrl() . '/js/login/login.js', CClientScript::POS_END);

            return true;
        }

        return false;
    }

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
            if (Yii::app()->user->isGuest) 
            {
                //use diferent layout
                $this->layout = '//layouts/login';
                $model=new UserLogin;
                // collect user input data
                if(isset($_POST['UserLogin']))
                {
                    $model->attributes=$_POST['UserLogin'];
                    // validate user input and redirect to previous page if valid
                    if($model->validate())
                    {
                        $this->lastViset();                        
                        Yii::app()->session['roles'] = $this->getRoles();
                        $this->getUserInfo();
                                                
                        //get menu and submenus
                        $menus = Menu::model()->getAuthParentMenu();
                        $items = array();
                        foreach($menus as $k => &$v) {
                            $submenu = Menu::model()->getAuthSubMenu($v->id);
                            $subItems = array();
                            foreach($submenu as $sub){
                                $subItems[] = array(
                                    'label' => $sub->label,
                                    'url' => $sub->url,
                                    'linkOptions'=> array('data-original-title'=>$sub->label,'title'=>'','data-toggle'=>'tooltip')
                                );
                            }
                            $items[] = array(
                                'label' => $v->label,
                                'url' => $v->url,
                                'items' => $subItems
                            );
                        }
                        Yii::app()->session['menus'] = $items;

                        if (Yii::app()->user->returnUrl=='/index.php')
                            $this->redirect(Yii::app()->controller->module->returnUrl);
                        else
                            $this->redirect(Yii::app()->user->returnUrl);
                    }
                }
                
                // display the login form
                $this->render('/user/login',array('model'=>$model));
            }
            else
                $this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit_at = date("Y-m-d H:i:s", time());
		$lastVisit->save();
	}
        
        private function getRoles()
        {
            $tmp = "";
            $data = UserAuthassignment::model()->find("userid='".Yii::app()->user->id."'");
            if($data!=null)
            {
                $tmp = strtolower(str_replace(" ", "", $data->itemname));
            }
            
            return $tmp;
        }
        
        private function getUserInfo()
        {
            $tmp = "";
            $dataRole = UserAuthassignment::model()->find("userid='".Yii::app()->user->id."'");
            if($dataRole!=null)
            {
                $tmp = strtolower(str_replace(" ", "", $dataRole->itemname));
                if($tmp!='supplier')
                {
                    $data = Userskaryawan::model()->with('divisi')->find("usersid=".Yii::app()->user->id);
                    if($data!=null)
                    {
                        Yii::app()->session['karyawanid'] = $data->karyawanid;
                        Yii::app()->session['divisiid'] = $data->divisiid;
                        Yii::app()->session['namadivisi'] = $data->divisi->nama;
                        Yii::app()->session['lokasiid'] = $data->lokasipenyimpananbarangid;
                    }
                    else
                    {
                        Yii::app()->session['karyawanid'] = '';
                        Yii::app()->session['divisiid'] = '';
                        Yii::app()->session['namadivisi'] = '';
                        Yii::app()->session['lokasiid'] = '';
                    }
                }
                else
                {
                    $data = Userskaryawan::model()->find("usersid=".Yii::app()->user->id);
                    if($data!=null)
                    {
                        Yii::app()->session['supplierid'] = $data->supplierid;                       
                    }
                    else
                    {
                        Yii::app()->session['supplierid'] = '';                        
                    }
                }
            }                                
        }

}