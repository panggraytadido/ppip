<?php
$this->pageTitle = "Penerimaan Barang - Gudang";

$this->breadcrumbs=array(
	'Gudang',
        'Penerimaan Barang' => array('penerimaanbarang/index'),
        'Detail',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Gudang - Penerimaan Barang</b>
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
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
                            <label class="col-lg-4 control-label">Divisi</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo Divisi::model()->findByPk($modelPenerimaan->divisiid)->nama; ?>" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Tanggal</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo date("d-m-Y", strtotime($modelPenerimaan->tanggal)); ?>" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Barang</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenerimaan->barang->nama; ?>" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Supplier</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenerimaan->supplier->namaperusahaan; ?>" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Lokasi Barang</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenerimaan->lokasipenyimpananbarang->nama; ?>" />
                            </div>
                        </div>
                        
                        </form>
                        
                    </div>
                    
                    <div class="col-lg-6">
                        
                        <form class="form-horizontal">
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Jumlah</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenerimaan->jumlah; ?>" />
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-4 control-label">Berat Lori</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenerimaan->beratlori; ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label">Total Barang</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenerimaan->totalbarang; ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label required" for="Bongkarmuat_jumlahkaryawan">Jumlah Karyawan</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo count($modelBongkarmuat); ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label required" for="Bongkarmuat_upah">Upah</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelBongkarmuat[0]->upah; ?>" />
                            </div>
                        </div>

                        <?php for($i=0;$i<count($modelBongkarmuat);$i++) { ?>

                            <div id="inputKaryawan_<?php echo $i; ?>">
                                <div class="form-group">
                                    <label class="col-lg-4 control-label required" for="Bongkarmuat_karyawanid">Karyawan <?=($i+1)?></label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelBongkarmuat[$i]->karyawan->nama; ?>" />
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                            
                        </form>
                        
                    </div>
                    
                    <div class="clearfix"></div>
                    
                </div>
                
            </div>
        </div>
        
    </div>
</div>