<br><?php

$this->pageTitle = 'Admin - Divisi Gudang';

$this->breadcrumbs=array(
	'Gudang','Divisi Gudang'
);
?>



<div class="row">
    <div class="col-lg-12">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab-1" data-toggle="tab" aria-expanded="true"> Penerimaan Barang</a>
            </li>
            <li class="">
                <a href="#tab-2" data-toggle="tab" aria-expanded="false">Penjualan Barang</a>
            </li>
            <li class="">
                <a href="#tab-3" data-toggle="tab" aria-expanded="false">Stock Update</a>
            </li>    
			<li class="">
                <a href="#tab-4" data-toggle="tab" aria-expanded="false">Harga Barang</a>
            </li>    	
        </ul>
        
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                
                <?php $this->renderPartial('penerimaanbarang/grid_penerimaan_barang',
                                    array(                                                            
                                        'modelPenerimaanBarangGudang'=>$modelPenerimaanBarangGudang
                                    )
                                ); 
                        ?>
            </div>
            
            <div class="panel-body">                      
            <?php                        
                /*
                $this->Widget('ext.highcharts.HighchartsWidget', array(
                    'options' => array(
                        'chart'=> array('defaultSeriesType'=>'column',
                               'options3d'=> array(
                                    'enabled'=>true,                                
                               )                                                           
                            ), 
                        'title' => array('text' => 'Grafik Penerimaan Barang'),
                        'xAxis' => array(
                           'categories' => array('')
                        ),
                        'yAxis' => array(
                           'title' => array('text' => 'Data Penerimaan Barang')
                        ),
                        'series' => $dataChartPenerimaanBarang,
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
                 * 
                 */   
            ?>
            
            </div>
            
            
            
        </div>
    <div id="tab-2" class="tab-pane">
        <div class="panel-body">
            <?php $this->renderPartial('penjualanbarang/grid_penjualan_barang',
                                    array(
                                       'modelPenjualanBarangGudang'=>$modelPenjualanBarangGudang,
                                    )
                                ); 
                        ?>
        </div>
        
        
       <div class="panel-body">                      
            <?php     
                /*
                $this->Widget('ext.highcharts.HighchartsWidget', array(
                    'options' => array(
                        'chart'=> array('defaultSeriesType'=>'column',
                               'options3d'=> array(
                                    'enabled'=>true,                                
                               )                                                           
                            ), 
                        'title' => array('text' => 'Grafik Penjualan Barang'),
                        'xAxis' => array(
                           'categories' => array('')
                        ),
                        'yAxis' => array(
                           'title' => array('text' => 'Data Penjualan Barang')
                        ),
                        'series' => $dataChartPenjualanBarang,
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
                 * 
                 */       
            ?>
            
            </div>
        
    </div>
    <div id="tab-3" class="tab-pane">
        <div class="panel-body">
            <?php $this->renderPartial('grid_stock_update',
                                    array(
                                        'modelStockupdate'=>$modelStockupdate,                                             
                                    )
                                ); 
                        ?>
        </div>
    </div>
    
	<div id="tab-4" class="tab-pane">
        <div class="panel-body">
            <?php $this->renderPartial('form_harga_barang',
                                    array(
                                        //'modelStockupdate'=>$modelStockupdate,                                             
                                    )
                                ); 
                        ?>
        </div>
    </div>	
    
    </div>
    </div>
</div>
<div class="col-lg-6">
</div>
</div>