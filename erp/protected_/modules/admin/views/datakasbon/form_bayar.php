<?php
$this->pageTitle = "Data Kasbon - Keuangan";

$this->breadcrumbs=array(
	'Keuangan',
        'View' => array('datakasbon/index'),        
);

?>


  <?php   
            $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                    'type'=>'horizontal',
                    'id'=>'dataperjalanan-form',
                    //'enableAjaxValidation'=>true,
                'enableClientValidation'=>true,
                    'clientOptions'=>array(
                                    'validateOnSubmit'=>true,
                                            'validateOnChange'=>false,
                    )
            )); 
    ?>

    <div class="form-group">
        <label class="col-sm-5 control-label">Tanggal</label>
        <div class="col-sm-7">
            <input type="text" class="form-control" readonly="readonly" value="<?php echo date("d-m-Y", strtotime($model->tanggal)); ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-5 control-label">Karyawan</label>
        <div class="col-sm-7">
            <input type="text" class="form-control" readonly="readonly" value="<?php echo Karyawan::model()->findByPk($model->karyawanid)->nama; ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-5 control-label">Jumlah</label>
        <div class="col-sm-7">
            <input type="text" class="form-control" readonly="readonly" value="<?php echo number_format($model->jumlah); ?>" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-5 control-label">Keterangan</label>
        <div class="col-sm-7">
            <input type="text" class="form-control" readonly="readonly" value="<?php echo $model->keterangan ?>" />
        </div>
    </div>  
    
    <div class="form-group">
        <label class="col-sm-5 control-label">Total Bayar</label>
        <div class="col-sm-7">
            <input type="text" class="form-control" readonly="readonly" value="<?php 
                $dataKasbon = Datakasbon::model()->getTotalBayar($model->id); 
                    if($dataKasbon!='')
                    {
                        echo number_format($dataKasbon);
                    }                        
                    else {
                        echo '0';
                    }                                        
                        
             ?>" />
        </div>
    </div>  
      
    <?php echo $form->datePickerGroup($modelPembayaranKasbon, 'tanggalbayar', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',                                                                
                                                            )
                                                )
                        ) 
                ); 
        ?>
    
    <div class="form-group">
        <label class="col-sm-5 control-label">Bayar</label>
        <div class="col-sm-7">
            <input type="hidden" name="bayarinput" id="bayarinput"/>
            <input type="text" class="form-control" name="bayar" oninput="input(this);" id="bayar" />
        </div>
    </div>
    
    <?php 
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('datakasbon/bayar','id'=>$model->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/keuangan/datakasbon/view/id/" + data.id;
                   } 
                   if(data.result==="LUNAS")
                   {
                        location.href="'.Yii::app()->baseurl.'/keuangan/datakasbon";
                   }
                   if(data.result==="salah")
                   {
                        alert("Masukan Tanggal Bayar dan Besaran");
                   }
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      $("#AjaxLoader").show();

                 }'
            ),array('id'=>'btnBayar','class'=>'btn btn-primary'));
    ?> 
    

</form><?php $this->endWidget(); ?>
<script>
function input(p)
{
    $('#bayarinput').val(p.value);
}
</script>