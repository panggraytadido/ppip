
<?php   $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
               // 'action'=>Yii::app()->createAbsoluteUrl("oemmkt/manajemencustomer/Ajaxvalidatecustomerprofile"),//Yii::app()->createUrl($this->route),
                //'method'=>'post',
               'enableAjaxValidation'=>true,
                'type'=>'horizontal',
                'id'=>'form',
                //'htmlOptions' => array('enctype' => 'multipart/form-data'),
        		'enableClientValidation'=>true,
        		'clientOptions'=>array(
        				'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
        				//'afterValidate'=>'js:myAfterValidateFunction'
        		)
        		
        )); ?>
	

	<?php echo $form->errorSummary($modelPenerimaanBarangGudang); ?>

	<div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Divisi</label>
            <div class="col-sm-5 col-sm-9">
                     Gudang                     
                </div>                   
        </div>

        <?php     
                echo $form->datePickerGroup($modelPenerimaanBarangGudang, 'tanggal',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                    ));
              
	?>
        
        
        <?php     
                echo $form->select2Group($modelPenerimaanBarangGudang, 'supplierid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Supplier::model()->findAll(),'id','namaperusahaan'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Supplier',
                                                    'onchange'=>'showHideValidation(this);'
                                            )
                                    )));              
	?>      

        <?php     
        
                $divisiid = Divisi::model()->find("kode='1'")->id;
                $criteria = new CDbCriteria;
                $criteria->condition="divisiid=".$divisiid;
                echo $form->select2Group($modelPenerimaanBarangGudang, 'barangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',
                                                    'onchange'=>'showHideValidation(this);'
                                            )
                                    )));              
	?>

        <?php     
                echo $form->select2Group($modelPenerimaanBarangGudang, 'lokasipenyimpananbarangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Lokasi',
                                                    'onchange'=>'showHideValidation(this);'
                                            )
                                    )));              
	?>

        <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Jumlah</label>
            <div class="col-sm-5 col-sm-9">
                     <?php echo $form->textField($modelPenerimaanBarangGudang,'jumlah',array('size'=>10,'maxlength'=>10,'class'=>'form-control','id'=>'Penerimaanbaranggudang_jumlah','oninput'=>'showHideValidation(this);')); ?>
                    <div style="display:none" id="Penerimaanbaranggudang_jumlah_em_" class="help-block error"></div>                        
                </div>                   
        </div>               

        <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Berat lori</label>
            <div class="col-sm-5 col-sm-9"><input type="text" id="Penerimaanbaranggudang_beratlori" onchange="showHideValidation(this);" oninput="getTotalBarangPenerimaan(this);" name="Penerimaanbaranggudang[beratlori]" placeholder="Berat lori" class="form-control">
                    <div style="display:none" id="Penerimaanbaranggudang_beratlori_em_" class="help-block error"></div>                        
                </div>                   
        </div>

         <?php echo $form->textFieldGroup($modelPenerimaanBarangGudang, 'totalbarang',array(
                'wrapperHtmlOptions'=>array(
                        'class'=>'col-sm-5',
                        'oninput'=>'showHideValidation(this);'
                    )
             )); ?>

        <div class="form-group">
            <label class="col-sm-5 control-label" for="Penerimaanbarang_barangid">Jumlah Karyawan</label>
                <div class="col-sm-1 col-sm-9">
                    <input type="text" class="form-control" id="jumlahkaryawanpenerimaan" oninput="getUpahPenerimaan(this);">                    
                </div>                                                    
                <div class="col-sm-2 col-sm-9">
                    <input type="text" class="form-control" id="upahkaryawanpenerimaan" name="upahkaryawanpenerimaan" placeholder="RP.">
                    <input type="hidden" class="form-control" id="upahkaryawanpenerimaaninput" name="upahkaryawanpenerimaaninput" placeholder="RP.">                    
                </div>                                                    
                <button type="button" class="btn btn-warning" onclick="createPegawai();">Set</button>
        </div>
        
        <div id="tablekaryawanpenerimaan"></div>
       
	<div style="float:left">            
		<?php 
                echo CHtml::ajaxSubmitButton('Tambah',CHtml::normalizeUrl(array('gudang/addpenerimaanbarang')),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                   
                    $("#AjaxLoader").hide();                    
                    if(data.result==="OK")
                    {             
                        window.location.reload();           		             			             			             			             			             			             
                    }
                    else
                    {                        
                        $.each(data, function(key, val) 
                        {
                            $("#form #"+key+"_em_").text(val);                                                    
                            $("#form #"+key+"_em_").show();
                        });
                    }       
                }'               
                 ,                                    
                 'beforeSend'=>'function()
                  {                        
                       $("#AjaxLoader").show();
             			
                  }'
                 ),array('id'=>'btnPenerimaan','class'=>'btn btn-primary'));                                                         
         ?> 
	</div>
        <br>
        
  

<?php $this->endWidget(); ?>

<script>
    function showHideValidation(txt)
    {
       var tmp = txt.id;      
       $('#'+tmp+'_em_').css("display", "none");
    }
    
    function createPegawai()
    {
        var jumlah = $('#jumlahkaryawanpenerimaan').val();
        $('#all-table').append('<tr style="background-color:#cccccc;"><th width="1%">No</th><th></th><th>NIP</th><th>Nama</th></tr>');
        getJsonKaryawan();
        for(var i=1;i<=jumlah;i++)
        {
                                    
            var karyawan ='<select  class="karyawan" id="karyawan_'+i+'" name="karyawan['+i+']"><option value=""></option></select>';
            var rowAdded = '<tr>';											           

            rowAdded += '<td width="2%">';
            rowAdded += '<a class="deleteLink btn btn-danger btn-xs" onclick="deleteRow(this)" style="cursor:pointer;">-</a>';				
            rowAdded += '</td>';

            rowAdded += '<td width="20%">';
            rowAdded += karyawan;				
            rowAdded += '</td>';
            
            rowAdded += '</tr><tr><td></td></tr><br><br><br>';
           

            $('#tablekaryawanpenerimaan').append(''+rowAdded+'');
        }
    }
    
    function getJsonKaryawan()
    {
                $.ajax({
	        url: '<?php echo Yii::app()->baseUrl. '/admin/gudang/getkaryawangudang' ?>',
	        type: 'post',
		dataType:'json',			
	        //data: 'type=manajemen',
	        success: function (row) 
	        {                  
	            	//$('#kodeakun_'+p+' option:nth-child(1)').attr('hidden','hidden');
	            	$.each(row, function () {
	   	             $('.karyawan').append($("<option></option>").val(this['id']).html(this['nama']));
	   	         	});		                						 		
	        }
	   		});		
			return '';
			
    }
    
    function getTotalBarangPenerimaan()
    {     
        var jumlah = $('#Penerimaanbaranggudang_jumlah').val();
        var beratlori = $('#Penerimaanbaranggudang_beratlori').val();
        
        var total = jumlah-beratlori;
        
        $('#Penerimaanbaranggudang_totalbarang').val(total);                
    } 
    
    function getUpahPenerimaan()
    {
        var totalbarang = $('#Penerimaanbaranggudang_totalbarang').val();
        var jumlahKaryawanPenerimaan= $('#jumlahkaryawanpenerimaan').val(); 
        var upah = (totalbarang*20)/jumlahKaryawanPenerimaan;
        $('#upahkaryawanpenerimaan').val(upah);
        $('#upahkaryawanpenerimaaninput').val(upah);
        
    }
       
    
</script>        