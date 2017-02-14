<?php
$this->pageTitle = "Pemindahan Barang - Hygienis";

$this->breadcrumbs=array(
	'Hygienis',
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
                        <b>Hygienis - Pemindahan Barang</b>
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
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPemindahan->barang->nama; ?>" />
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
                         
                        </form>  

                    </div>
                    
                    <div class="clearfix"></div>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>