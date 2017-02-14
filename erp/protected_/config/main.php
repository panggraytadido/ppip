<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.


//require_once( dirname(__FILE__) . '/../components/helpers.php');

return array(
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'timeZone' => 'Asia/Jakarta',
    'language' => 'id',
    'name' => 'PPIP',    
    'theme' => 'inspinia',
    //'defaultController' => 'main',
    // preloading 'log' component
    'preload'=>array('log','booster'),

    // autoloading model and component classes
    'import'=>array(        
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',       
        'application.modules.user.components.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',        
        'application.extensions.*',
        'application.modules.admin.bussinessmodels.*',
        'application.modules.kasir.bussinessmodels.*',
        'application.modules.keuangan.bussinessmodels.*',
        'application.modules.supplier.bussinessmodel.*',
    ),

    'modules'=>array(      
        'admin',
        'gudang',
        'kasir',
        'keuangan',            
		'anggota',	
        'pegawai',
        'supplier',
        // uncomment the following to enable the Gii tool
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'ppip',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
            'generatorPaths' => array(
                    'booster.gii'
                ),        
        ), 
        
        'user'=>array(
            'tableUsers' => 'users',
            'tableProfiles' => 'profiles',
            'tableProfileFields' => 'profiles_fields',
            # encrypting method (php hash function)
            'hash' => 'md5',
            # send activation email
            'sendActivationMail' => false,
            # allow access for non-activated users
            'loginNotActiv' => false,
            # activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => true,
            # automatically login from registration
            'autoLogin' => true,
            # registration path
            #'registrationUrl' => array('/user/registration'),
            # recovery password path
            #'recoveryUrl' => array('/user/recovery'),
            # login form path
            'loginUrl' => array('/user/login'),
            # page after login
            'returnUrl' => array('/user/profile'),

            # page after logout
            'returnLogoutUrl' => array('/user/login'),
            ),
         

           //Modules Rights        
        
        'rights'=>array(
            'superuserName'=>'Admin', // Name of the role with super user privileges. 
            'authenticatedName'=>'Authenticated',  // Name of the authenticated user role. 
            'userIdColumn'=>'id', // Name of the user id column in the database. 
            'userNameColumn'=>'username',  // Name of the user name column in the database. 
            'enableBizRule'=>true,  // Whether to enable authorization item business rules. 
            'enableBizRuleData'=>true,   // Whether to enable data for business rules. 
            'displayDescription'=>true,  // Whether to use item description instead of name. 
            'flashSuccessKey'=>'RightsSuccess', // Key to use for setting success flash messages. 
            'flashErrorKey'=>'RightsError', // Key to use for setting error flash messages.
            'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested. 
            #'layout'=>'rights.views.layouts.main',  // Layout to use for displaying Rights. 
            //'appLayout'=>'application.views.layouts.column2', // Application layout. 
//            'cssFile'=>'themes/flatlab/',
            #'cssFile'=>'rights.css', // Style sheet file to use for Rights. 
            #'install'=>false,  // Whether to enable installer. 
            'debug'=>false, 
        ),
        
         
    ),

    // application components
    'components'=>array(
            'Grafik' => array('class' => 'Grafik'),//dido
            'DateConvert' => array('class' => 'DateConvert'),//dido
            'Menus' => array('class' => 'Menus'),//dido
            'Report'=>array('class'=>'Report'),
            'IndexColumn'=>array('class'=>'IndexColumn'),//dido    		
         // dido   
         'phpxml'=>array(
			'class' => 'application.extensions.phpjasperxml.classes.PHPJasperXML',
			'db_host' => 'localhost',
			'db_user' => 'postgres',
			'db_pass'=>'dido',
			'db_or_dsn_name'=>'cindygroup',
			'cndriver'=>'psql'
		),	        
        
        //dido
        'excel'=>array(
			'class' => 'application.extensions.phpjasperxml.classes.PHPExcel.PHPExcel_IOFactory',                        
		),
        
        'user'=>array(
            'class'=>'RWebUser',            
            'allowAutoLogin'=>true,
            'loginUrl'=>array('/user/login'),
        ),
		
		
        'authManager'=>array(
            'class'=>'RDbAuthManager',
            'connectionID'=>'db',
            'itemTable'=>'user_authitem',
            'itemChildTable'=>'user_authitemchild',
            'assignmentTable'=>'user_authassignment',
            'rightsTable'=>'user_rights',
        ),        
         
        'booster' => array(
                        'class' => 'ext.bootstrap.components.Booster',
                        'responsiveCss' => true,
						'bootstrapCss' => false,
						'coreCss'=>false,
						'enableBootboxJS'=>false,
						'enableNotifierJS'=>false,
                ),
        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'caseSensitive'=>false
        ),
        'clientScript' => array(
            'packages'=>array(
//                'jquery'=>array(                             // set the new jquery
//                    'baseUrl'=>'',
//                    'js'=>array('js/jquery.js'),
//                ),
                'bootstrap'=>array(                       //set others js libraries
                    //'baseUrl'=>'themes/gentelala/',
                    'js'=>array(
                        //'js/bootstrap.min.js',
                        /*'js/jquery.js',
                        'js/jquery.dcjqaccordion.2.7.js',
                        'js/jquery.scrollTo.min.js',
                        'js/slidebars.min.js',
                        'js/jquery.nicescroll.js',
                        'js/respond.min.js',
                        'js/common-scripts.js',
                        'js/jquery.validate.min.js',
                        'js/daterangepicker.js',
                        'js/date.js',
                        'js/moment.min.js',
                    	'js/jquery.print.js',*/
                        
					),
                    'css'=>array(                        // and css
                       //'css/bootstrap.min.css',
                        //'css/bootstrap-reset.css',
                       //'font-awesome/css/font-awesome.min.css',
                       // 'css/custom.css',
                        //'css/style.css',
                       // 'css/style-responsive.css',
                        //'css/daterangepicker-bs3.css',
                        
                    ),
                    //'depends'=>array('jquery'), // cause load jquery before load this.
                ),
            ),
        ),
        'db'=>require ('_db.php'),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'cache' => array( 
            'class' => 'CApcCache'
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
		'pathUpload'=> dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'upload',
		'pageSize'=>10,    	
               
                'divisiid'=>Yii::app()->session['divisiid']
    ),
);