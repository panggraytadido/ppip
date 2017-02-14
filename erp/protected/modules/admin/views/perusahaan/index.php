<?php
/* @var $this BagianController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Admin - Perusahaan';

$this->breadcrumbs=array(
	'Persahaan',
);

Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('divisi-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

?>

<br>
<?php if(Yii::app()->user->hasFlash('success')):?>
        <div class="alert alert-success fade in">
                          <button type="button" class="close close-sm" data-dismiss="alert">
                              <i class="fa fa-times"></i>
                          </button>
                          <?php echo Yii::app()->user->getFlash('success'); ?>
                      </div>    
<?php endif; ?>


<div class="col-lg-12">
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-content">      


                    
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
                         
                            <?php echo $form->textFieldGroup($model, 'nama', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
                            ); ?>
                    
                            <?php echo $form->textFieldGroup($model, 'owner', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
                            ); ?>

                            <?php echo $form->textAreaGroup($model, 'titleperusahaan', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
                            ); ?> 
                            
                            <?php echo $form->textAreaGroup($model, 'alamat', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                //'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
                            ); ?>      
                    
                            
                            <?php echo $form->textFieldGroup($model, 'telepon', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-2'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
                            ); ?> 
                    
                            <?php 
                                if(count($kontak)>0)
                                {  
                                    echo '<div id="kontak">';
                                    echo '<button class="btn btn-primary" onclick="addrow();">+</button>';
                                    $no=1;
                                    for($i=0;$i<count($kontak);$i++)
                                    {
                            ?>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Kontak <?php echo $no++ ?></label>
                                            <div class="col-sm-2 col-sm-7">
                                                <input type="text" id="<?php echo $i ?>"  class="form-control nokontak" name="Kontak[<?php echo $i ?>]" value="<?php echo $kontak[$i]["nomor"]; ?>" />
                                            </div>
                                        </div>
                            <?php 
                                    }
                                    echo '</div>';
                                }
                                else
                                {
                            ?>
                                
                            <?php
                                }
                            ?>
                            
                                                          
                               <?php echo $form->datePickerGroup($model, 'tanggalberdiri', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenerimaanbarang_tanggal_input',
                                                                'readonly'=>'readonly',
                                                                'onchange'=>'addKaryawan();'
                                                            )
                                                )
                        ) 
                ); 
        ?>
                           
                            
                        <div class="form-group">
                            <input type="hidden" name="karyawanidhidden" id="karyawanidhidden"/>;
                            <input type="hidden" name="ManajemencustomerBM[logoajax]" id="logoajax" />
                            <label class="col-sm-5 control-label" for="Karyawan_tmtmasuk">Photo</label>
                                <div class="col-sm-5 col-sm-9">
                                    <div data-provides="fileupload" class="fileupload fileupload-new">
                                            <div style="width: 200px; height: 150px;" class="fileupload-new thumbnail">
                                                <?php 
                                                
                                                ?>
                                                <img id="previewFile" alt="Upload Image" src="<?php echo Yii::app()->request->baseUrl."/../upload/".$model->logo;  ?>">
                                            </div>                                
                                        </div>
                                    <div>
                                            <span class="btn btn-white btn- file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                            <?php echo $form->fileField($model,'logo',array('size'=>60,'maxlength'=>200,'onchange'=>'readURL(this);')); ?>                                           
                                            </span>
                                            <a name="hold"></a>
                                            <a onclick="removeURL();" data-dismiss="fileupload" class="btn btn-danger fileupload-exists" href="#hold"><i class="fa fa-trash"></i> Remove</a>
                                             <span class="label label-danger">NOTE!</span>

                                            <span>
                                                Ukuran maksimum file yang diperkenankan adalah 500KB.
                                            </span>
                                            <br />
                                            <?php echo $form->error($model,'logo'); ?>
                                        </div>
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
                        
                  <?php 
                echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('perusahaan/simpan','id'=>1)),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                   
                    $("#AjaxLoader").hide();                    
                    if(data.result==="OK")
                    {                           
                        alert("data berhasil diupdate");
                        upload_file();                      	             			             			             			             			             			             
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
                            
                    <?php $this->endWidget(); ?>
				 </div>
				</div>	              
                </div>
            </div>
</div>

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

	var anggotaid = 1;
	if(anggotaid!='')
	{
            var fd = new FormData();  
            var e = document.getElementById("Perusahaan_logo");    	   	

            var file = fd.append( "Perusahaan[logo]", $(e)[0].files[0]);           
                       
            if($(e)[0].files[0].length===undefined)
            {
                $.ajax({
                        url: '<?php echo Yii::app()->createAbsoluteUrl("admin/perusahaan/uploadlogo"); ?>',
                        type: 'POST',
                        cache: false,
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function (data) 
                        { 
                            if(data.result==="success")
                            {
                                window.location.reload();     		
                            }                                
                        },
                        error: function () {
                            alert("Upload Error");
                        }
                });
            }           
            window.location.reload();     
	}
	else 
	{
		//alert('isikan customer profile before upload image');
		//$('#notifUploadBeforeInsertCustomerProfile').modal('show'); 
	}
        
}

function addrow()
{    
        var max = 0;
	$('.nokontak').each(function() {
    	max = Math.max(this.id, max);
	});
        
        var maxNo =max+1;    
    
        var rowAdded = '<div class="form-group">';
        rowAdded    += '<label class="col-sm-5 control-label">Kontak '+maxNo+' </label>';
        rowAdded    += '<div class="col-sm-2 col-sm-7">';
        rowAdded    += '<input type="text" id=""  class="form-control nokontak" name="Kontak[]"/>';
        rowAdded    += '</div>';
        rowAdded    += '</div>';										        

	$('#kontak').after(rowAdded);
                
        return true;
        
}
</script>