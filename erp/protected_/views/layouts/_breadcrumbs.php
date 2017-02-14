
<?php 
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>$this->breadcrumbs,
    'tagName'=>'ol',
    'activeLinkTemplate'=>'<li><a href="{url}">{label}</a></li>', // will generate the clickable breadcrumb links
    'inactiveLinkTemplate'=>'<li>{label}</li>',
    'homeLink'=>'<li><a href="/main"><i class="fa fa-home"></i> Home</a></li>' ,
    'htmlOptions'=>array('class'=>'breadcrumb'),
    'separator'=>''
)); ?>
