<?php
$management = array(
    'helm/viewsuratjalan'=>'View Surat Jalan',
    'helm/viewscheduledelivery'=>'View Schedule Delivery',
    'helm/manajemenlpd'=>'Manajemen LPD',
    'helm/lpb'=>'Manajemen LPB',
    'helm/tlugudang'=>'Manajemen Gudang',
    'helm/tluarea'=>'Manajemen Area',
    'helm/tlurack'=>'Manajemen Rack',
    'helm/tluwarnapenyimpanan'=>'Manajemen Identifikasi Warna',
    'helm/tlumutasi'=>'Manajemen Mutasi Barang');

$report = array(
    'helm/laporan/harian'=>'Laporan Harian Penerimaan',
    'helm/laporan/bulanangudang'=>'Peyimpanan Bulanan',
    'helm/laporan/penyimpanganquality'=>'Laporan Penyimpangan',
    'helm/laporan/problemproduk'=>'Laporan Problem Produk',
    'helm/laporan/stockupdate'=>'Laporan Stock Update',

);
$menu = array();
$data = array('management'=>$management,'report'=>$report);

foreach($data as $k => $v) {
    foreach($v as $k1 => $v1) {
        $menu[$k][] = array('label' => $v1,
            'url' => Yii::app()->baseUrl.'/'.$k1,
            'active' => Yii::app()->controller->id==$k1,
        );
    }
}

$this->widget('zii.widgets.CMenu', array(
    'id' => 'nav-accordion',
    'items' => array(
        array('label' => '<i class="fa fa-dashboard"></i><span>Dashboard</span>',
            'url' => Yii::app()->baseUrl.'/main/index',
            'linkOptions'=>array('class'=> Yii::app()->controller->id =='main' ? 'active' : ''),),
        array('label' => '', array('class' => 'divider')),
        array('label' => '<i class="fa fa-bars"></i><span>Main Management</span>',
            'url' => array(Yii::app()->request->requestUri.'#'),
            'itemOptions' => array('class'=>'sub-menu'),
            'linkOptions'=>array('class'=> in_array(Yii::app()->controller->id, array_keys($management)) ? 'active' : ''),
            'items' => $menu['management'],
            'htmlOptions' => array(
                'class'=>'sub-menu',
            ),
        ),
        array('label' => '<i class="fa fa-exchange"></i><span>Report Management</span>',
            'url' => array(Yii::app()->request->requestUri.'#'),
            'itemOptions' => array('class'=>'sub-menu'),
            'linkOptions'=>array('class'=> in_array(Yii::app()->controller->id, array_keys($report)) ? 'active' : ''),
            'items' => $menu['report'],
            'htmlOptions' => array(
                'class'=>'sub-menu',
            ),
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