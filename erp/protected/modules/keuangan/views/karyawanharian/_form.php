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
        
        
        <?php                     
                echo $form->dropDownListGroup($model, 'karyawanid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Karyawan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Karyawan',
													'onchange'=>'checkexist(this);'	
                                            )
                                    )));
        ?>

        <?php                     
                echo $form->dropDownListGroup($model, 'jabatanid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Jabatan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Jabatan',
                                            )
                                    )));
        ?>       
        
       <?php echo $form->textFieldGroup($model, 'gaji', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                //'readonly'=>'readonly',
                                                                'id'=>'Dataperjalanan_jumlah',                                                                
                                                                //'oninput'=>'hitungTotal(this);'
                                                            )
                                                )
                                        )
            ); ?>

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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('karyawanharian/create','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/keuangan/karyawanharian";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#form #"+key+"_em_").text(val);                                                    
                           $("#form #"+key+"_em_").show();
                       });
                   } 
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      document.getElementById("loadingProses").style.visibility = "visible";

                 }'
            ),array('id'=>'btnSave','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script>
function checkexist(p)
{
	var karyawanid = p.value;
	if(karyawanid!="")
	{
		$.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo Yii::app()->baseurl; ?>/keuangan/karyawanharian/checkexist',
                    data: { karyawanid : karyawanid},
                    success: function (data) {
                         if(data.result==1)
						 {
							alert('data gaji karyawan harian sudah ada');								
						 }								 
                    },
                    error: function () {
                        alert("Error occured. Please try again (getkaryawanabsen)");
                    }
            });
	}
	
}
</script>