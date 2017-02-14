<?php
$this->pageTitle = "Penerimaan Barang - Gudang";

$this->breadcrumbs=array(
	'Besek',
        'Penjualan Barang' => array('penjualanbarang/index'),
        'Detail',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Besek - Penjualan Barang</b>
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
                        <?php 
                        
                            if($modelPenjualan->issendtokasir!=1)
                                {
                                    echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('penjualanbarang/update','id'=>$modelPenjualan->id)),
                                array(                
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    
                                       $("#modal-update #body-update").html(data); 
                                       $("#modal-update").modal({backdrop: "static", keyboard: false});
                                   }'               
                                    ,                                    
                                    'beforeSend'=>'function()
                                     {                        

                                     }'
                                ),array('id'=>'btnSave','class'=>'btn btn-primary btn-xs'));      
                       
                                echo '&nbsp;';
                                    
                                echo CHtml::ajaxSubmitButton('Kirim',CHtml::normalizeUrl(array('penjualanbarang/sendtokasir','id'=>$modelPenjualan->id)),
                            array(
                                'dataType'=>'json',
                                'type'=>'POST',                      
                                'success'=>'js:function(data) 
                                 {    

                                   if(data.result==="OK")
                                   {             
                                      location.href="'.Yii::app()->baseurl.'/besek/penjualanbarang/index";
                                   }                  
                               }'               
                                ,                                    
                                'beforeSend'=>'function()
                                 {                        

                                 }'
                            ),array('id'=>'btnKirim','class'=>'btn btn-warning btn-xs'));      
                        }
                        else
                        {
                            echo '<button class="btn btn-warning btn-xs">Sudah Dikirim Kekasir</button>';
                        }    
                    ?>                        
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
                                <label class="col-sm-5 control-label">Pelanggan</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo $modelPenjualan->pelanggan->nama; ?>" />
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

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Kategori</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo ($modelPenjualan->kategori==1) ? "Eceran" : "Grosir"; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Harga Satuan</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo number_format($modelPenjualan->hargasatuan); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Harga Total</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo number_format($modelPenjualan->hargatotal); ?>" />
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

<!-- modal update -->
<div class="modal fade bs-example-modal-sm" id="modal-update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Update Data Penjualan Barang</h4>
        </div>
        <div class="modal-body" id="body-update">
            
        </div>
        <div class="modal-footer">
          &nbsp;
        </div>

      </div>
    </div>
</div>