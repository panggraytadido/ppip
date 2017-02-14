<?php
$this->pageTitle = "Karyawan Gudang - Keuangan";

$this->breadcrumbs=array(
	'Keuangan',
        'Karyawan Gudang',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Keuangan - Penerimaan Barang</b>
                    </div>                   
                </div>
                
                <div class="ibox-content">
                       <?php $this->renderPartial('grid_penerimaan',
                                    array(                                                            
                                        'modelPenerimaan'=>$modelPenerimaan,
                                    )
                                ); 
                        ?>              
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Keuangan - Penjualan Barang</b>
                    </div>                   
                </div>
                
                <div class="ibox-content">
                    <?php $this->renderPartial('grid_penjualan',
                                    array(                                                            
                                        'modelPenjualan'=>$modelPenjualan,
                                    )
                                ); 
                        ?>        
                </div>
            </div>
        </div>
    </div>
</div>
