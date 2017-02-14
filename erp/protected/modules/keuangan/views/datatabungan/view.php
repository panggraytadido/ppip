<?php
$this->pageTitle = "Data Pengeluaran - Keuangan";

$this->breadcrumbs=array(
	'Keuangan',
        'View' => array('datapengeluaran/index'),        
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Keuangan - Data Pengeluaran</b>
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
                                <label class="col-sm-5 control-label">Divisi</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo divisi::model()->findByPk($model->divisiid)->nama ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Tanggal</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo date("d-m-Y", strtotime($model->tanggal)); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Barang</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $model->nama; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Harga Satuan</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $model->hargasatuan ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Jumlah</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $model->jumlah ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $model->total ?>" />
                                </div>
                            </div>

                            
                         
                        </form>

                    </div>
                                        
                    
                    <div class="clearfix"></div>
                    
                </div>
                
            </div>
        </div>
    </div>
    
    
    <!-- -->
    <?php
        //$id = $model->id;
        $dataBayar = Datakasbon::model()->getTotalBayar($model->id); 
        if($dataBayar!='')
        {
    ?>
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Keuangan - Data Pembayaran Kasbon</b>
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
                        <table class="table table-advance">   
                            <tr>
                                <th>Tanggal Pembayaran</th>
                                <th>Jumlah</th>
                            </tr>
                        <?php 
                            $dataBayar = Pembayarankasbon::model()->findAll('kasbonid='.$model->id);
                            for($i=0;$i<count($dataBayar);$i++)
                            {
                        ?>
                            <tr>
                                <td><?php echo date("d-m-Y", strtotime($dataBayar[$i]["tanggalbayar"])); ?></td>
                                <td><?php echo number_format($dataBayar[$i]["jumlah"]) ?></td>
                            </tr>                       
                        <?php
                            }
                        ?>
                        </table>
                    </div>
                                                            
                    <div class="clearfix"></div>
                    
                </div>
                
            </div>
        </div>
    </div>
    <?php 
        }
        else
        {
            echo '';
        }    
    ?>
</div>