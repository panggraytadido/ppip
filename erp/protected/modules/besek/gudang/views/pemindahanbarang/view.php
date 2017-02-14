<?php
$this->pageTitle = "Pemindahan Barang - Gudang";

$this->breadcrumbs=array(
	'Gudang',
        'Pemindahan Barang' => array('pemindahanbarang/index'),
        'Detail',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Gudang - Pemindahan Barang</b>
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
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo date("d-m-Y", strtotime($modelPemindahan->tanggal)); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Barang</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo Barang::model()->findByPk($modelPemindahan->barangid)->nama; ?>" />
                                </div>
                            </div>

                            <?php for($i=0;$i<count($modelDetail);$i++) { ?>

                                <div class="form-group">
                                    <label class="col-sm-5 control-label" for="karyawanid">Supplier</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="supplierid[]" class="form-control" value="<?php echo $modelDetail[$i]->supplier->namaperusahaan; ?>" readonly="readonly" />
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" name="jumlah[]" class="form-control" value="<?php echo $modelDetail[$i]->jumlah; ?>" readonly="readonly" />
                                    </div>
                                </div>
                                
                            <?php } ?>
                            
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Lokasi Barang Tujuan</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPemindahan->lokasipenyimpananbarangtujuan->nama; ?>" />
                                </div>
                            </div>
                            
                        </form>

                    </div>
                    
                    <div class="col-lg-6">
        
                        <form class="form-horizontal">

                            <div class="form-group">
                                <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Jumlah Karyawan</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo count($modelBongkarmuat); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label required" for="Bongkarmuat_upah">Upah</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelBongkarmuat[0]->upah; ?>" />
                                </div>
                            </div>

                            <?php for($i=0;$i<count($modelBongkarmuat);$i++) { ?>

                                <div id="inputKaryawan_<?php echo $i; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label required" for="Bongkarmuat_karyawanid">Karyawan <?=($i+1)?></label>
                                        <div class="col-sm-7">
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