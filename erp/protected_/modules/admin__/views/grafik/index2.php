<?php
$this->pageTitle = "Data Grafik Total - Admin";

$this->breadcrumbs=array(
    'Admin',
    'Data Grafik Total',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Admin - Data Grafik</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>                       
                    </div>
                </div>
                
                <div class="ibox-content">
                        <?php     
$tahun = date('Y');
                $this->Widget('ext.highcharts.HighchartsWidget', array(
                    'options' => array(
                        'chart'=> array('defaultSeriesType'=>'column',
                               'options3d'=> array(
                                    'enabled'=>true,                                
                               )                                                           
                            ), 
                        'title' => array('text' => 'Grafik Total Tahun : '.$tahun),
                        'xAxis' => array(
                           'categories' => $bulan
                        ),
                        'yAxis' => array(
                           'title' => array('text' => 'Data Penerimaan Barang,Penjualan Barang dan Keuntungan')
                        ),
                        'series' => $data,
                         'legend'=> array(
                              'layout'=> 'vertical',
                              'align'=> 'right',
                              'verticalAlign'=>'top',
                              'x'=> -40,
                              'y'=> 80,
                              'floating'=> true,
                              'borderWidth'=> 1,
                              //'backgroundColor'=>array((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                              'shadow'=>true
                      ),
                      'plotOptions'=>array(
                                  'column'=>array(
                                     // 'colorByPoint'=>true,
                                      'dataLabels'=>array(
                                            'enabled'=>true,
                                            //'rotation'=> 90,
                                            //'y'=>25,
                                            //'color'=> '#000',
                                            
                                      )                                                                                                                        
                                  )
                             )                                                                                            
                    )
                ));          
            ?>

                </div>
            </div>
        </div>
    </div>
</div>
