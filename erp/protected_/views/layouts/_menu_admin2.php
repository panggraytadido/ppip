<?php
$master = array('negara'=>'Negara',
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

$procedure = array('penerimaanbarang'=>'Penerimaan Barang',
    'penyimpananbarang'=>'Penyimpanan Barang',
    'pengirimanbarang'=>'Pengiriman Barang',
    'statussuratjalan'=>'Status Surat Jalan',
    'stockopname'=>'Stock Opname',
    'retur'=>'Penerimaan Barang Retur');
$management = array(
    'viewsuratjalan'=>'View Surat Jalan',
    'viewscheduledelivery'=>'View Schedule Delivery',
    'manajemenlpd'=>'Manajemen LPD',
    'manajemenlpb'=>'Manajemen LPB',
    'tlugudang'=>'Manajemen Gudang',
    'tluarea'=>'Manajemen Area',
    'tlurack'=>'Manajemen Rack',
    'tluwarnapenyimpanan'=>'Manajemen Identifikasi Warna',
    'tlumutasi'=>'Manajemen Mutasi Barang');

$report = array(
    'laporan/harian'=>'Laporan Harian Penerimaan',
    'laporan/bulanangudang'=>'Peyimpanan Bulanan',
    'laporan/penyimpanganquality'=>'Laporan Penyimpangan',
    'laporan/problemproduk'=>'Laporan Problem Produk',
    'laporan/stockupdate'=>'Laporan Stock Update',

);
$menu = array();
$data = array('master'=>$master, 'procedure'=>$procedure, 'management'=>$management,'report'=>$report);
$controllerid = strtolower(Yii::app()->controller->id);

foreach($data as $k => $v) {
    foreach($v as $k1 => $v1) {
        $menu[$k][] = array('label' => $v1,
            'url' => Yii::app()->baseUrl.'/'.$k1,
            'visible' => Yii::app()->getModule('user')->isAdmin(),
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
            'visible'=>Yii::app()->getModule('user')->isAdmin(),
        ),
        array('label' => '<i class="fa fa-bank fa-fw"></i> Master data <span class="fa arrow"></span>',
            'url' => array(Yii::app()->request->requestUri.'#'),
            'itemOptions' => array('class'=>'sub-menu'),
            'linkOptions'=>array('class'=> in_array($controllerid, array_keys($master)) ? 'active' : ''),
            'items' => $menu['master'],
        ),
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
        array('label' => '<i class="fa fa-exchange"></i><span>Procedure</span>',
            'url' => array(Yii::app()->request->requestUri.'#'),
            'itemOptions' => array('class'=>'sub-menu'),
            'linkOptions'=>array('class'=> in_array($controllerid, array_keys($procedure)) ? 'active' : ''),
            'items' => $menu['procedure'],
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
        /*
        array('label' => '<i class="fa fa-list"></i><span>Report Management</span>',
            'url' => array(Yii::app()->request->requestUri.'#'),
            'itemOptions' => array('class'=>'sub-menu'),
            'items' => array(
                array(
                    'label' => 'Laporan Harian Penerimaan',
                    'url' => Yii::app()->baseUrl.'/Laporan/harian',
                ),
                array(
                    'label' => 'Penyimpanan Bulanan',
                    'url' => Yii::app()->baseUrl.'/Laporan/bulanangudang',
                ),
                array(
                    'label' => 'Laporan Penyimpangan Quality',
                    'url' => Yii::app()->baseUrl.'/Laporan/penyimpanganquality',
                ),
                array(
                    'label' => 'Laporan Problem Produk',
                    'url' => Yii::app()->baseUrl.'/Laporan/problemproduk',
                ),
                array(
                    'label' => 'Laporan Stock Update',
                    'url' => Yii::app()->baseUrl.'/Laporan/stockupdate',
                ),
            ),
            'htmlOptions' => array(
                'class'=>'sub-menu',
            ),
        ),
        */
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