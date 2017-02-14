
<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'form',
                'enableAjaxValidation'=>true,
                'clientOptions'=>array(
                                'validateOnChange'=>true,
                )
        )); 
?>
	        
         <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Pemegang Saham</label>
            <div class="col-sm-4">
				<?php echo $model->nama; ?>
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>
		
		
		<?php 
                echo $form->textFieldGroup($model, 'jumlahsaham',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'htmlOptions' => array(
                                                    'onchange'=>'checkTotalSaham(this);',
                                                    'value'=>$jumlahSaham
                                            )
                                    ),
                                    'hint' => '<p id="error_jumlah" style="color:red !important;"></p>',
                            )
                ); 
        ?>
		
		<div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Total Saham</label>
            <div class="col-sm-4 col-sm-7">
				<input type="text" name="totalsaham" id="totalsaham" value="<?php echo $totalSaham; ?>" disabled="disabled" class="form-control">
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
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
        echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('pemegangsaham/update','id'=>$model->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/admin/pemegangsaham";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#gudangpenerimaanbarang-form #"+key+"_em_").text(val);                                                    
                           $("#gudangpenerimaanbarang-form #"+key+"_em_").show();
                       });
                   }       
                   
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      document.getElementById("loadingProses").style.visibility = "visible";
                 }'
            ),array('id'=>'btnUpdatePemegangSaham','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">
function checkTotalSaham(p)
{
	var jumlah = p.value;
	if(jumlah!='')
	{
		
		var hargaSaham ="<?php echo $hargaSaham; ?>";

		var totalsaham = parseInt(jumlah)*parseInt(hargaSaham);
		$('#totalsaham').val(formatCurrency(totalsaham));	
	}
	else
	{
		alert('MASUKAN JUMLAH SAHAM');
	}	
}

function formatCurrency(total) {
    return total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");;
}
</script>