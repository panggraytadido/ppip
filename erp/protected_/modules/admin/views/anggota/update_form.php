
<?php   $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
               // 'action'=>Yii::app()->createAbsoluteUrl("oemmkt/manajemencustomer/Ajaxvalidatecustomerprofile"),//Yii::app()->createUrl($this->route),
                //'method'=>'post',
                //'enableAjaxValidation'=>true,
                'type'=>'horizontal',
                'id'=>'karyawan-form',
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
        		'enableClientValidation'=>true,
        		'clientOptions'=>array(
        				//'validateOnSubmit'=>true,
        				'validateOnChange'=>true,
        				//'afterValidate'=>'js:myAfterValidateFunction'
        		)
        		
        )); ?>
	

	<?php echo $form->errorSummary($model); ?>
	
	<?php
		$criteria = new CDbCriteria;
		$criteria->order = 'id DESC';
		$row = Anggota::model()->find($criteria);
		if(count($row)>0)
		{
			$maxId = $row->id;
			$kode = Anggota::model()->findByPk($maxId)->kode;            
			$kode = explode("-",$kode);
			$kode = $kode[1]+1;   
			$kode = "AG"."-".$kode;       
		}
		else
		{
			$kode = "AG"."-"."001";      
		}
		
	?>

	<?php echo $form->textFieldGroup($model, 'kode',
                array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'),
                         'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenjualanbarang_tanggal_input',
                                                                'readonly'=>'readonly',
                                                                'value'=>$kode
                                                            )
                                                )
	)); ?> 

	<?php echo $form->textFieldGroup($model, 'noktp',array('wrapperHtmlOptions'=>array('class'=>'col-sm-6'))); ?> 					

	<?php echo $form->textFieldGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

        <?php     
                echo $form->dropDownListGroup($model, 'jeniskelamin',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => array('1'=>'Pria','2'=>'Wanita'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Jenis Kelamin',										
                                            )
                                    )));
        ?>        
       
	   
	   <?php     
                echo $form->dropDownListGroup($model, 'pendidikan',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => array('1'=>'SD','2'=>'SMP','3'=>'SLTA','4'=>'S1','5'=>'S2','6'=>'S3'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pendidikan',										
                                            )
                                    )));
       ?>
       
       <?php echo $form->textAreaGroup($model, 'alamat',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

      <?php echo $form->datePickerGroup($model, 'tanggalbermitra',array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'))); ?>
       
	
       <?php echo $form->textFieldGroup($model, 'hp',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'))); ?> 
	   
	   <?php echo $form->textFieldGroup($model, 'telepon',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'))); ?> 
       
                     

        <div class="form-group">
        <input type="hidden" name="anggotaidhidden" id="anggotaidhidden" value="<?php echo $model->id; ?>"/>;
             <input type="hidden" name="ManajemencustomerBM[logoajax]" id="logoajax" />
            <label class="col-sm-5 control-label" for="Karyawan_tmtmasuk">Photo KTP</label>
                <div class="col-sm-5 col-sm-9">
                    <div data-provides="fileupload" class="fileupload fileupload-new">
                            <div style="width: 200px; height: 150px;" class="fileupload-new thumbnail">
                                <!--<img id="previewFile" alt="Upload Image" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=No+Image">-->
								 <?php echo CHtml::image(Yii::app()->request->baseUrl.'/../upload/'.$model->photoktp, 'DORE',array(
										'id'=>'previewFile'
									)); ?>

                            </div>                                
                        </div>
                    <div>
                            <span class="btn btn-white btn- file">
                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                            <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                            <?php echo $form->fileField($model,'photoktp',array('size'=>60,'maxlength'=>200,'onchange'=>'readURL(this);')); ?>
                            <!-- <input type="file" class="default" name="file" id="file" onchange="readURL(this);" />-->
                            </span>
                            <a name="hold"></a>
                            <a onclick="removeURL();" data-dismiss="fileupload" class="btn btn-danger fileupload-exists" href="#hold"><i class="fa fa-trash"></i> Remove</a>
                             <span class="label label-danger">NOTE!</span>
                            <span>
                                Ukuran maksimum file yang diperkenankan adalah 500KB.
                            </span>
                            <br />
                            <?php echo $form->error($model,'photoktp'); ?>
                        </div>
                </div>
    </div>
	
	<?php echo $form->checkboxGroup($model,'ispemegangsaham',array('value' => true, 'uncheckValue'=>false)); ?>

        <div style="float:left">            
		<?php 
                echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('anggota/update','id'=>$model->id)),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                   
                    $("#AjaxLoader").hide();                    
                    if(data.result==="OK")
                    {             
                       var anggotaid =  $("#anggotaidhidden").val();
                       if(anggotaid)
                       {
                            upload_file();
                       }
                        window.location.reload();           		             			             			             			             			             			             
                    }
                    else
                    {                        
                        $.each(data, function(key, val) 
                        {
                            $("#karyawan-form #"+key+"_em_").text(val);                                                    
                            $("#karyawan-form #"+key+"_em_").show();
                        });
                    }       
                }'               
                 ,                                    
                 'beforeSend'=>'function()
                  {                        
                       $("#AjaxLoader").show();
             			
                  }'
                 ),array('id'=>'btnSaveAnggota','class'=>'btn btn-primary'));                                                         
         ?> 
	</div>
<br>
<br>
<br>        
<br>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewFile').src=e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeURL() {
        document.getElementById('previewFile').src='';     
    }
    
    function upload_file()
    {

	var anggotaid = $('#anggotaidhidden').val();
	if(anggotaid!='')
	{
            var fd = new FormData();  
            var e = document.getElementById("Anggota_photoktp");    	   	

            fd.append( "Anggota[photoktp]", $(e)[0].files[0]);
            fd.append("anggotaidhidden",$("#anggotaidhidden").val());
	    
	    $.ajax({
	        url: '<?php echo Yii::app()->createAbsoluteUrl("admin/anggota/updateuploadfile"); ?>',
	        type: 'POST',
	        cache: false,
	        data: fd,
	        processData: false,
	        contentType: false,
	        success: function (data) 
	        { 
                   // window.location.reload();     		
	        },
	        error: function () {
	            //alert("Upload Error");
	        }
	    });
	}
	else 
	{
		//alert('isikan customer profile before upload image');
		//$('#notifUploadBeforeInsertCustomerProfile').modal('show'); 
	}				          
}
</script>