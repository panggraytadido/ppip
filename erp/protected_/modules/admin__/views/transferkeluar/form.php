<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'transfer-form',
                'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
                'clientOptions'=>array(
                                'validateOnSubmit'=>true,
        				'validateOnChange'=>true,
                )
        )); 
?>
	
        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->datePickerGroup($model, 'tanggal', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',     
                                                                'id'=>'Transferkasir_Tanggal_index'
                                                            )
                                                )
                        ) 
                ); 
        ?>

        <?php                     
		
				$criteria = new CDbCriteria;
				$criteria->order='namaperusahaan asc';
                echo $form->dropDownListGroup($model, 'supplier',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Supplier::model()->findAll($criteria),'id','namaperusahaan'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Supplier',                                                    
                                            )
                                    )));
        ?>

        <?php                    
                $criteria = new CDbCriteria;
                $criteria->condition='ID=3 OR ID=4';
                echo $form->dropDownListGroup($model, 'jenisTransfer',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Jenistransfer::model()->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih ',          
													'onchange'=>'jenisTransfer(this);'	
                                            )
                                    )));
        ?>

		<div id="transfer">
        <?php                     
                echo $form->dropDownListGroup($model, 'rekening',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Rekening::model()->findAll(),'id','namabank'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih ',             
													'onchange'=>'getJumlahNoRek();'
                                            )
                                    )));
        ?>

		<div class="form-group">
			<label class="col-sm-5 control-label" for="Transferkeluar_jumlah">Jumlah Rekening</label>
			<div class="col-sm-3 col-sm-7">
				<input class="form-control"  id="jumlah_rekening" type="text" disabled="disabled">
			</div>
		</div>
		</div>

        <?php echo $form->textFieldGroup($model, 'jumlah',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
					 'widgetOptions' => array(                                            
                                            'htmlOptions' => array(
                                                    //'oninput'=>'checkSaldo(this);'                                             
                                            )
                                    )
			)); ?> 
       
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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('transferkeluar/create')),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/admin/transferkeluar";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#transfer-form #"+key+"_em_").text(val);                                                    
                           $("#transfer-form #"+key+"_em_").show();
                       });
                   }       
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                     document.getElementById("loadingProses").style.visibility = "visible";

                 }'
            ),array('id'=>'btnSaveTransfer','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script>
$("#transfer").hide();

function jenisTransfer(p)
{
	var jenistransfer = p.value;
	if(jenistransfer==3)
	{
		$("#transfer").show();		
	}
	else
	{
		$("#transfer").hide();
	}
}

function getJumlahNoRek()
{
    var rekeningid = $('#Transferkeluar_rekening option:selected').val();    
    
    var data = 'rekeningid='+rekeningid;
    
    $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/admin/transferkeluar/getsaldo',
            data: data,
            success: function (row) {                                                      
                
                $('#jumlah_rekening').val(row.saldo);            
				if(row.saldo==0)
				{
					$('#btnSave').prop( "disabled", true);
				}
					
            },
            error: function () {
               // alert("Error occured. Please try again (getLokasiBarang)");
            }
    });
}

function checkSaldo(p)
{
	var jumlah_tf = p.value; 
	var jumlah_saldo = $('#jumlah_rekening').val();
	if(jumlah_tf>jumlah_saldo)
	{
		alert('jumlah transfer tidak boleh lebih besar dari saldo');
		$('#btnSave').prop( "disabled", true);
	}	
	else
	{
		$('#btnSave').prop( "disabled", false);
	}		
	
}

</script>