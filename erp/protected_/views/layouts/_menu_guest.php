<?php
$this->widget('zii.widgets.CMenu', array(
    'id' => 'nav-accordion',
    'items' => array(
        array('label' => '<i class="fa fa-dashboard"></i><span>Dashboard</span>',
            'url' => Yii::app()->baseUrl.'/main/index',
            'linkOptions'=>array('class'=> Yii::app()->controller->id =='main' ? 'active' : ''),
        ),
    ),
    'activeCssClass' => 'active',
    'htmlOptions' => array(
        'class'=>'sidebar-menu',
    ),
    'submenuHtmlOptions' => array(
        'class' => 'sub',
    ),
    'encodeLabel' => false,
));?>