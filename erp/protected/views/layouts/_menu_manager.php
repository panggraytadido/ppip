<?php
$management = array(
                    'manajemenlpd'=>'Manajemen LPD',
                    'manajemenlpb'=>'Manajemen LPB',);
					
$report = array(
					'laporan/harian'=>'Laporan Harian Penerimaan',
					'laporan/bulanangudang'=>'Peyimpanan Bulanan',
					'laporan/penyimpanganquality'=>'Laporan Penyimpangan',
					'laporan/problemproduk'=>'Laporan Problem Produk',
					'laporan/stockupdate'=>'Laporan Stock Update',

					);					
$menu = array();
$data = array('management'=>$management,'report'=>$report);
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
		array('label' => '', array('class' => 'divider')),
		array('label' => '<i class="fa fa-bars"></i><span>Main Management</span>',
            'url' => array(Yii::app()->request->requestUri.'#'),
			'itemOptions' => array('class'=>'sub-menu'),
            'linkOptions'=>array('class'=> in_array($controllerid, array_keys($management)) ? 'active' : ''),
            'items' => $menu['management'],
			'htmlOptions' => array(
				'class'=>'sub-menu',
			),
        ),
		array('label' => '<i class="fa fa-exchange"></i><span>Report Management</span>',
            'url' => array(Yii::app()->request->requestUri.'#'),
			'itemOptions' => array('class'=>'sub-menu'),
			'linkOptions'=>array('class'=> in_array($controllerid, array_keys($report)) ? 'active' : ''),
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