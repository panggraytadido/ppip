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
	
        <?php echo $form->errorSummary($modelDataPerjalanan); ?>

        <?php echo $form->datePickerGroup($modelDataPerjalanan, 'tanggal', array(
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

        <?php     
                $jabatanid = Jabatan::model()->find("kode='JB-09'")->id;
                echo $form->dropDownListGroup($modelDataPerjalanan, 'karyawanid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Karyawan::model()->findAll("jabatanid=$jabatanid"),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Karyawan',
                                            )
                                    )));
        ?>

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Tujuan</label>
            <div class="col-sm-4">
                 <?php 				                 
                        $data = Biayaperjalanan::model()->findAll();
                        echo '<select name="Biayaperjalanan[nama]" class="form-control">';
                        foreach($data as $row)
                        {
                                $id = $row["id"];
                                $value=$row["nama"];
                                if($row["id"]==$modelDataPerjalanan->biayaperjalananid)
                                {
                                       
                                        echo "<option value=$id selected>$value</option>";
                                }
                                else
                                {
                                     echo "<option value=$id selected>$value</option>";
                                }
                        }
                        echo '</select';					
                ?>       
            </div>
        </div> 

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Kendaraan</label>
            <div class="col-sm-4">
                 <?php 				                 
                        $data = Kendaraan::model()->findAll();
                        echo '<select name="Kendaraan[nama]" class="form-control">';
                        foreach($data as $row)
                        {
                                $id = $row["id"];
                                $value=$row["nama"];
                                if($row["id"]==$modelDataPerjalanan->kendaraanid)
                                {
                                       
                                        echo "<option value=$id selected>$value</option>";
                                }
                                else
                                {
                                     echo "<option value=$id selected>$value</option>";
                                }
                        }
                        echo '</select';					
                ?>       
            </div>
        </div> 
       
        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Biaya</label>
            <div class="col-sm-4">
                <input type="text" name="biaya" id="biaya" disabled="disabled" value="<?php echo Biayaperjalanan::model()->findByPk($modelDataPerjalanan->biayaperjalananid)->upah; ?>">
                <input type="hidden" name="biayainput" id="biayainput" value="<?php echo Biayaperjalanan::model()->findByPk($modelDataPerjalanan->biayaperjalananid)->upah; ?>">
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">BBM</label>
            <div class="col-sm-4">
                <input type="text" name="bbm" id="bbm" oninput="upah(this);" value="<?php echo $modelDataPerjalanan->bbm ?>">
                <input type="hidden" name="bbminput" id="bbminput" value="<?php echo $modelDataPerjalanan->bbm ?>">
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Gaji</label>
            <div class="col-sm-4">
                <input type="text" name="gaji" disabled="disabled" id="gaji" value="<?php echo $modelDataPerjalanan->upah ?>">
                <input type="hidden" name="gajiinput" id="gajiinput" value="<?php echo $modelDataPerjalanan->upah ?>">
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>
        
<div style="float:left">            
    <?php 
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('dataperjalanan/update','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/keuangan/dataperjalanan/view/id/" + data.id;
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#dataperjalanan-form #"+key+"_em_").text(val);                                                    
                           $("#dataperjalanan-form #"+key+"_em_").show();
                       });
                   }       
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      $("#AjaxLoader").show();

                 }'
            ),array('id'=>'btnSave','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script>
function hitungBiaya()
{
    var tujuan = $('#Biayaperjalanan_nama option:selected').val();
    var kendaraan = $('#Kendaraan_nama option:selected').val();
    
    var data = 'tujuan='+tujuan+'&kendaraan='+kendaraan;
    
    $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/keuangan/dataperjalanan/hitungbiaya',
            data: data,
            success: function (data) {                                                      
                $('#biaya').val(data.upah);
                $('#biayainput').val(data.upah);
            },
            error: function () {
                alert("Error occured. Please try again (getLokasiBarang)");
            }
    });
}

function upah(p)
{
    var biaya = $('#biayainput').val();
    var bbm = p.value;
    
    $('#bbminput').val(bbm);
    
    var upah=(biaya-bbm)*30/100;
    $('#gaji').val(upah);
    $('#gajiinput').val(upah);
    
}
</script>