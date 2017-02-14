<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Console',

	// preloading 'log' component
	'preload'=>array('log'),
           // autoloading model and component classes
        'import'=>array(
         'application.models.*',
         'application.components.*',
        ),

	// application components
	'components'=>array(
                

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/_db.php'),               
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),

	),
);
