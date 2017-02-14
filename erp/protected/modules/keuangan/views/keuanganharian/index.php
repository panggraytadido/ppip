<?php
$this->pageTitle = "Laporan - Keuangan Harian";

$this->breadcrumbs=array(
	'Keuangan',
        'Keuangan Harian',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Keuangan Harian</b>
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

                    <form role="form" class="form-horizontal" action="<?php echo Yii::app()->baseurl; ?>/keuangan/keuanganharian/cetak" method="post">

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="tanggal">Tanggal</label>
                        <div class="col-sm-4">
                            <?php
                            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                                'name'=>'tanggal',
                                'options'=>array(
                                    'showAnim'=>'slide',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                                    
                                ),
                                'htmlOptions'=>array(
                                    'id'=>'tanggal',
                                    'class'=>'form-control',
                                    'style'=>'z-index:99999999999999;',
                                ),
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_btnCetak"></label>
                        <div class="col-sm-4">
                            <button type="submit" id="btnCetak" name="btnCetak" class="btn btn-success"><li class="fa fa-print"></li> Cetak</button> 
                        </div>
                    </div>
                        
                    </form>

                </div>
            </div>	              
        </div>
    </div>
</div>


<style type='text/css'>

#ui-datepicker-div {
    z-index: 99999999999999 !important;
}

</style>

<script>
    $('#ui-datepicker-div').load(function() {
        $('#ui-datepicker-div').css('z-index', '99999999999999 !important');
    });
</script>