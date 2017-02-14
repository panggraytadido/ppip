<?php

//echo "Test";

$menus = isset(Yii::app()->session['menus']) ? Yii::app()->session['menus'] : array();

foreach($menus as $k => &$v) {
    //$isactiveparent = isActiveMenus($v['url']);
    if(count($v['items'])>0)
    {
        $v['htmloptions'] = array('class'=>'sub-menu');
        $v['itemOptions'] = array('class'=>'sub-menu');
        foreach($v['items'] as &$sub){
            //$isactive = isActiveMenus($sub['url']);;
            $sub['url'] = Yii::app()->baseUrl.'/'.$sub['url'];
            $sub['active'] = $isactive;
            if($isactive==true)
            {
                
            }
                //$isactiveparent = $isactive;
        }
    }
    $v['url'] = Yii::app()->baseUrl.'/'.$v['url'];

    ///$v['linkOptions']=array('class'=> $isactiveparent);
}

/*
$this->widget('zii.widgets.CMenu', array(
    'id' => 'nav-accordion',
    'items' => $menus,
    'activeCssClass' => 'active',
    'htmlOptions' => array(
        'class'=>'sidebar-menu',
		'title'
    ),
    'submenuHtmlOptions' => array(
        'class' => 'sub',
    ),
    'encodeLabel' => false,
));
 * 
 */