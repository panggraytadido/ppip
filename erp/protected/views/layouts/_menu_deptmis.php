<?php
$management = array(
    'manajemenlpd'=>'Manajemen LPD',
    'manajemenlpb'=>'Manajemen LPB',);

$master = array('tlunegara'=>'Negara',
    'tlupropinsi'=>'Propinsi',
    'tlukabupatenkota'=>'Kabupaten/Kota',
    'tlukecamatandesa'=>'Kecamatan/Desa',
    'tluperusahaan'=>'Perusahaan',
    'tludivisi'=>'Divisi',
    'tludepartemen'=>'Departemen',
    'tlusop'=>'SOP',
    'tlusatuan'=>'Satuan',
    'tlusupplier'=>'Supplier',
    'tlukategoriakun'=>'Kategori Akun',
    'tlukategoricustomer'=>'Kategori Customer',
    'tlukategorigudang'=>'Kategori Gudang',);

$menu = array();
$data = array('master'=>$master);
$controllerid = strtolower(Yii::app()->controller->id);

foreach($data as $k => $v) {
    foreach($v as $k1 => $v1) {
        $menu[$k][] = array('label' => $v1,
            'url' => Yii::app()->baseUrl.'/'.$k1,
            'active' => $controllerid==$k1,
        );
    }
}

$this->widget('zii.widgets.CMenu', array(
    'id' => 'nav-accordion',
    'items' => array(
        array('label' => '<i class="fa fa-dashboard"></i><span>Dashboard</span>',
            'url' => Yii::app()->baseUrl.'/main/index',
            'linkOptions'=>array('class'=> $controllerid =='main' ? 'active' : ''),),
        array('url'=>'/user/admin',
            'linkOptions'=>array('class'=> isset(Yii::app()->controller->module->id)=='user' ? 'active' : ''),
            'label'=>'<i class="fa fa-lg fa-fw fa-users"></i> '.Yii::app()->getModule('user')->t("Manage Users"),
        ),
        array('label' => '', array('class' => 'divider')),
        array('label' => '<i class="fa fa-bank fa-fw"></i> Master data <span class="fa arrow"></span>',
            'url' => array(Yii::app()->request->requestUri.'#'),
            'itemOptions' => array('class'=>'sub-menu'),
            'linkOptions'=>array('class'=> in_array($controllerid, array_keys($master)) ? 'active' : ''),
            'items' => $menu['master'],
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