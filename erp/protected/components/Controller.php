<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController
//class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to 'column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='//layouts/main';

    private $_assetsUrl;
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
    
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            
            $this->pageTitle = ucfirst(Yii::app()->controller->action->id.' '.Yii::app()->controller->id);$cs = Yii::app()->clientScript;
            /* @var $theme CTheme */
            $cs = Yii::app()->clientScript;
            $theme = Yii::app()->theme;
            /*
			$cs->registerCssFile($theme->getBaseUrl() . '/css/bootstrap.min.css');
			$cs->registerCssFile($theme->getBaseUrl() . '/css/bootstrap-reset.css');
            $cs->registerCssFile($theme->getBaseUrl() . '/font-awesome/css/font-awesome.min.css');
            $cs->registerCssFile($theme->getBaseUrl() . '/css/slidebars.css');
            //$cs->registerCssFile($theme->getBaseUrl() . '/css/style.css');
            $cs->registerCssFile($theme->getBaseUrl() . '/css/style-responsive.css');

            $cs->registerScriptFile('/js/jquery.js', CClientScript::POS_END);
            $cs->registerScriptFile($theme->getBaseUrl() . '/js/jquery.dcjqaccordion.2.7.js', CClientScript::POS_END);
            $cs->registerScriptFile($theme->getBaseUrl() . '/js/jquery.scrollTo.min.js', CClientScript::POS_END);
            $cs->registerScriptFile($theme->getBaseUrl() . '/js/slidebars.min.js', CClientScript::POS_END);
            $cs->registerScriptFile($theme->getBaseUrl() . '/js/jquery.nicescroll.js', CClientScript::POS_END);
            $cs->registerScriptFile($theme->getBaseUrl() . '/js/respond.min.js', CClientScript::POS_END);
            $cs->registerScriptFile($theme->getBaseUrl() . '/js/common-scripts.js', CClientScript::POS_END);
            */
            return true;
        }

        return false;
   }
   
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl===null) {
            $assetsPath = Yii::getPathOfAlias('webroot.themes').DIRECTORY_SEPARATOR.Yii::app()->theme->name.DIRECTORY_SEPARATOR.'assets';
            
            // We need to republish the assets if debug mode is enabled.
            if (YII_DEBUG===true) {
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
            } else {
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
            }
        }
        return $this->_assetsUrl;
    }
	
	public function filters()
   {
       return array(
           'rights - login, user.logout, site.error', // perform access control for CRUD operations
        );
   }
         
}