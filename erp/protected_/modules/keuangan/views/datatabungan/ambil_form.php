<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'form',
                //'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
                'clientOptions'=>array(
                                'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
                )
        )); 
?>
	
        <?php echo $form->errorSummary($model); ?>
        
        
        <div class="form-group">
            <input type="hidden" name="pelangganid" id="pelangganid" value="<?php echo $model->pelangganid ?>" > 
                <label for="Datatabungan_pelangganid" class="col-sm-5 control-label required">Pelanggan</label>                
                <div class="col-sm-4 col-sm-7">
                    <?php echo Pelanggan::model()->findByPk($model->pelangganid)->nama; ?>
                </div>
        </div>

        <div class="form-group">
                <label for="Datatabungan_pelangganid" class="col-sm-5 control-label required">Jumlah Tabungan</label>                
                <div class="col-sm-4 col-sm-7">
                    <?php echo number_format($model->jumlah); ?>
                    <input type="hidden" name="jumlahtabungan" id="jumlahtabungan" value="<?php echo $model->jumlah ?>" > 
                </div>
        </div>

        <div class="form-group">
                <label for="Datatabungan_pelangganid" class="col-sm-5 control-label required">Jumlah  yang diambil</label>                
                <div class="col-sm-4 col-sm-7">
                    <input type="text" id="jumlahambil" onchange="check(this);" name="jumlahambil" class="form-control">
                </div>
                <span id="error-jumlah"></span>
        </div>

      

<span id="loadingProses" style="visibility: hidden; margin-top: -5px;">
        <h5><b>Silahkan Tunggu Proses ...</b></h5>
        <div class="progress progress-striped active">
            <div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar progress-bar-danger">
                <span class="sr-only">Silahkan Tunggu..</span>
            </div>
        </div>
    </span>
           
<div style="float:left">            
    <?php 
        echo CHtml::ajaxSubmitButton('Ambil',CHtml::normalizeUrl(array('datatabungan/ambil','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/keuangan/datatabungan";
                   }
                   else
                   {                                              
                           $("#error-jumlah").text(data.result);                                                    
                           $("#error-jumlah").show();
                   } 
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      document.getElementById("loadingProses").style.visibility = "visible";

                 }'
            ),array('id'=>'btnAmbil','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script>
function check(p)
{    
   var jumlahtabungan  = parseInt($('#jumlahtabungan').val());
   if(parseInt(p.value)>jumlahtabungan)
   {
       $("#error-jumlah").text('Jumlah ambil lebih besar daripada jumlah tabungan');                                                    
       $("#error-jumlah").show();
   }
}
</script>