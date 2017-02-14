<?php
$this->pageTitle = "Penjualan Barang ke Supplier- Hygienis";

$this->breadcrumbs=array(
	'Hygienis',
        'Penjualan Barang ke Supplier' => array('penjualanbarangkesupplier/index'),
        'Detail',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Hygienis - Penjualan Barang ke Supplier</b>
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>

                <div class="ibox-content">

                    <?php if(Yii::app()->user->hasFlash('success')):?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close close-sm" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>    
                    <?php endif; ?>

                    <div class="col-lg-6">

                        <form class="form-horizontal">

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Tanggal</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo date("d-m-Y", strtotime($modelPenjualan->tanggal)); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Supplier Pembeli</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo Supplier::model()->findByPk($modelPenjualan->supplierpembeliid)->namaperusahaan; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Barang</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenjualan->barang->nama; ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Supplier</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenjualan->supplier->namaperusahaan; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Lokasi Barang</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenjualan->lokasipenyimpananbarang->nama; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Jumlah</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenjualan->jumlah; ?>" />
                                </div>
                            </div>

                           

                        </form>

                    </div>

                    <div class="col-lg-6">
        
                        <form class="form-horizontal">
                          
							<div class="form-group">
                                <label class="col-sm-5 control-label">Kategori</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo ($modelPenjualan->kategori==1) ? "Eceran" : "Grosir"; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Harga Satuan</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenjualan->hargasatuan; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Harga Total</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenjualan->hargatotal; ?>" />
                                </div>
                            </div>
                        </form>  

                    </div>
                    
                    <div class="clearfix"></div>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>