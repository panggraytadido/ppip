<?php
$this->pageTitle = "View Stock - Besek";

$this->breadcrumbs=array(
	'Besek',
        'View Stock',        
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Besek - View Stock</b>
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

                    <div class="col-lg-12">

                        <form class="form-horizontal">

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Supplier</label>
                                <div class="col-sm-7">
                                    <input class="form-control" disabled="disabled" value="<?php echo Supplier::model()->findByPk($supplierid)->namaperusahaan; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Barang</label>
                                <div class="col-sm-7">
                                    <input class="form-control" disabled="disabled" value="<?php echo Barang::model()->findByPk($barangid)->nama; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Lokasi</label>
                                <div class="col-sm-7">
                                    <input class="form-control" disabled="disabled" value="<?php echo Lokasipenyimpananbarang::model()->findByPk(Yii::app()->session['lokasiid'])->nama; ?>">
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Jumlah</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $jumlah ?>" />
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