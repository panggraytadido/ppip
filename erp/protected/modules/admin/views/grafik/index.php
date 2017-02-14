<?php                                                             
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
                        'series' => array(
                            array('name'=>'tokyo','data'=>array(1,2,3)),
                            array('name'=>'Jakarta','data'=>array(3,2,3)),
                        ),
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