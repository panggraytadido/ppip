
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
	

	<?php echo $form->textFieldGroup($model, 'nik',array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'))); ?> 

	<?php echo $form->textFieldGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?>  

        <?php     
                echo $form->dropDownListGroup($model, 'jeniskelamin',array('wrapperHtmlOptions'=>array('class'=>'col-sm-2'),'widgetOptions' => array(
                                            'data' => array('1'=>'Pria','2'=>'Wanita'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Jenis Kelamin',										
                                            )
                                    )));
        ?>        

       <?php echo $form->datePickerGroup($model, 'tanggallahir', array(
                                            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenerimaanbarang_tanggallahir_input',
                                                                'readonly'=>'readonly',                                                                
                                                            )
                                                )
                                        )
		); ?>  
       
       <?php echo $form->textAreaGroup($model, 'alamat',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

       <?php     
                echo $form->dropDownListGroup($model, 'jeniskaryawanid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Jeniskaryawan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Jenis Karyawan',										
                                            )
                                    )));
       ?> 

       <?php echo $form->textFieldGroup($model, 'tempatlahir',array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'))); ?> 
	
       <?php echo $form->textFieldGroup($model, 'hp',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

       <?php     
                echo $form->dropDownListGroup($model, 'pendidikan',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => array('1'=>'SD','2'=>'SMP','3'=>'SLTA','4'=>'S1','5'=>'S2','6'=>'S3'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pendidikan',										
                                            )
                                    )));
       ?> 
       
        <?php     
                echo $form->dropDownListGroup($model, 'jabatanid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Jabatan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Jabatan',										
                                            )
                                    )));
        ?>        

        <?php echo $form->datePickerGroup($model, 'tmtmasuk', array(
                                            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenerimaanbarang_tmtmasuk_input',
                                                                'readonly'=>'readonly',                                                                
                                                            )
                                                )
                                        )
		); ?>
        
        <div class="form-group">
                <input type="hidden" name="karyawanidhiddenupdate" id="karyawanidhiddenupdate" value="<?php echo $model->id; ?>" />
                 <input type="hidden" name="ManajemencustomerBM[logoajax]" id="logoajax" />
                <label class="col-sm-5 control-label" for="Karyawan_tmtmasuk">Photo</label>
                    <div class="col-sm-5 col-sm-9">
                        <div data-provides="fileupload" class="fileupload fileupload-new">
                                <div style="width: 200px; height: 150px;" class="fileupload-new thumbnail">                                    
                                    <?php echo CHtml::image(Yii::app()->request->baseUrl."/../upload/".$model->photo, "LOGO"); ?>
                                    <img id="previewFileUpdate" alt="Upload Image" src="<?php //echo CHtml::image(Yii::app()->request->baseUrl.'/erp/upload/'.$model->photo, 'DORE'); ?>" />                                    
                                </div>                                
                            </div>
                        <div>
                                <span class="btn btn-white btn- file">
                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                <?php echo $form->fileField($model,'photo',array('size'=>60,'maxlength'=>200,'onchange'=>'readURL1(this);')); ?>
                                <!-- <input type="file" class="default" name="file" id="file" onchange="readURL(this);" />-->
                                </span>
                                <a name="hold"></a>
                                <a onclick="removeURL1();" data-dismiss="fileupload" class="btn btn-danger fileupload-exists" href="#hold"><i class="fa fa-trash"></i> Remove</a>
                                 <span class="label label-danger">NOTE!</span>
                                <span>
                                    Ukuran maksimum file yang diperkenankan adalah 500KB.
                                </span>
                                <br />
                                <?php echo $form->error($model,'photo'); ?>
                            </div>
                    </div>
        </div>

        

	<div style="float:left">            
		<?php 
                echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('karyawan/update','id'=>$model->id)),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                   
                    //$("#AjaxLoader").hide();                    
                    if(data.result==="OK")
                    {      
                        if(upload_file())
                        {
                            window.location.reload();       
                        }
                        
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
                 ),array('id'=>'btnUpdates','class'=>'btn btn-warning'));                                                         
         ?> 
	</div>
        <br>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    function readURL1(input) {        
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewFileUpdate').src=e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
        
    }
    
    function removeURL1() {
        document.getElementById('previewFileUpdate').src='';
        //document.getElementById('namafile').value='';
    }
    
    function upload_file()
    {

	var karyawanid = $('#karyawanidhiddenupdate').val();
	if(karyawanid!='')
	{
            var fd = new FormData();  
            var e = document.getElementById("Karyawan_photo");    	   	

            fd.append( "Karyawan[photo]", $(e)[0].files[0]);
            fd.append("karyawanidhiddenupdate",$("#karyawanidhiddenupdate").val());
	    
	    $.ajax({
	        url: '<?php echo Yii::app()->createAbsoluteUrl("admin/karyawan/updateuploadfile"); ?>',
	        type: 'POST',
	        cache: false,
	        data: fd,
	        processData: false,
	        contentType: false,
	        success: function (data) 
	        { 
                    window.location.reload();     		
	        },
	        error: function () {
	            alert("Upload Error");
	        }
	    });
	}
	else 
	{
		alert('isikan customer profile before upload image');
		//$('#notifUploadBeforeInsertCustomerProfile').modal('show'); 
	}				          
}
</script>