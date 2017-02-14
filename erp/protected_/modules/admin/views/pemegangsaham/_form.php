
<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'form',
                'enableAjaxValidation'=>false,
                'clientOptions'=>array(
                                'validateOnChange'=>true,
                )
        )); 
?>
	
        <?php //echo $form->errorSummary($modelPenerimaan); ?>
        <?php //echo $form->errorSummary($modelBongkarmuat); ?>

        <?php echo $form->datePickerGroup($model, 'tanggal', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenerimaanbarang_tanggal_input',
                                                                'readonly'=>'readonly',                                                                
                                                            )
                                                )
                        ) 
                ); 
        ?>

        <?php 
				$criteria = new  CDbCriteria;
				$criteria->condition = 'ispemegangsaham=true';
				$criteria->order='nama asc';
                echo $form->dropDownListGroup($model, 'nama',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Anggota::model()->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'-- Pilih Pemegang Saham --',
                                                    'ajax' => array(
                                                        'type'=>'POST', 
                                                        'dataType'=>'json', 
                                                        'url'=>Yii::app()->createUrl('admin/pemegangsaham/getjumlahsaham'),
                                                        'success' => "function(data){ 
                                                                $(\"#jumlah_saham\").html(data.jumlah);                                                               
                                                        }",
                                                        'error' => "function () {
                                                            alert(\"Error occured. Please try again (getSupplierBarang)\");
                                                        }",
                                                        'data'=>array('anggotaid'=>'js:this.value'),
                                                      )
                                            )
                                    )));
									
        ?>
		
		 <?php 
                echo $form->textFieldGroup($model, 'jumlahsaham',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'htmlOptions' => array(
                                                    'onchange'=>'checkTotalSaham(this);',
                                                    'value'=>'0'
                                            )
                                    ),
                                    'hint' => '<p id="error_jumlah" style="color:red !important;"></p>',
                            )
                ); 
        ?>
		
		<div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Harga Saham Per Lembar</label>
            <div class="col-sm-4 col-sm-7">
				<input type="text" name="hargasaham" id="hargasaham" value="<?php echo number_format($hargaSaham);?>" disabled="disabled" class="form-control">
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>
		
		
		<div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Total Saham</label>
            <div class="col-sm-4 col-sm-7">
				<input type="text" name="totalsaham" id="totalsaham" disabled="disabled" class="form-control">
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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('pemegangsaham/create','render'=>true)),
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
            ),array('id'=>'btnSavePemegangSaham','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">
function checkTotalSaham(p)
{
	if(jumlah!='')
	{
		var jumlah = p.value;
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