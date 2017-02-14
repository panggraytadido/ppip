


<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    // 'action'=>Yii::app()->createAbsoluteUrl("oemmkt/manajemencustomer/Ajaxvalidatecustomerprofile"),//Yii::app()->createUrl($this->route),
    //'method'=>'post',
    //'enableAjaxValidation'=>true,
    'type' => 'horizontal',
    'id' => 'jumlahsaham-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
    )
        ));
?>


<?php echo $form->errorSummary($model); ?>

<?php
echo $form->textFieldGroup($model, 'kode', array(
    'wrapperHtmlOptions' => array('class' => 'col-sm-3'),
    'widgetOptions' => array(
        'htmlOptions' => array(
            //'readonly'=>'readonly',
            'id' => 'Jumlahsaham_kode',
        )
    )
        )
);
?>
<?php
echo $form->datePickerGroup($model, 'tanggal', array(
    'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
    'wrapperHtmlOptions' => array('class' => 'col-sm-4'),
    'widgetOptions' => array(
        'htmlOptions' => array(
            /*  'id'=>'Jumlahsaham_tanggal', */
            'readonly' => 'readonly',
        )
    )
        )
);
?>


<?php
$criteria = new CDbCriteria;
$criteria->condition = 'ispemegangsaham = 1';
$criteria->order = 'nama asc';
echo $form->dropDownListGroup($model, 'anggotaid', array(
    'wrapperHtmlOptions' => array('class' => 'col-sm-4'),
    'widgetOptions' => array(
        'data' => CHtml::listData(Anggota::model()->findAll($criteria), 'id', 'nama'),
        'htmlOptions' => array(
            'prompt' => '-- Pilih Anggota --',
        )
    )
        )
);
?>

<?php
$criteria = new CDbCriteria;
$criteria->condition = 'id = 1';
$criteria->order = 'nama asc';
echo $form->dropDownListGroup($model, 'sahamid', array(
    'wrapperHtmlOptions' => array('class' => 'col-sm-4'),
    'widgetOptions' => array(
        'data' => CHtml::listData(Saham::model()->findAll($criteria), 'id', 'nama'),
        'htmlOptions' => array(
            /* 'prompt'=>'-- Pilih Saham --', */
            'readonly' => 'readonly',
            'id' => 'Jumlahsaham_sahamid',
            'onchange' => 'hitungHargaSaham();'
        )
    )
        )
);
?>

<?php
echo $form->textFieldGroup($model, 'jumlahsaham', array(
    'wrapperHtmlOptions' => array('class' => 'col-sm-3'),
    'widgetOptions' => array(
        'htmlOptions' => array(
            //'readonly'=>'readonly',
            'id' => 'Jumlahsaham_jumlahsaham_input',
            'onchange' => 'hitungHargaTotalSaham();',
        )
    )
        )
);
?>

<?php
echo $form->textFieldGroup($model, 'hargasaham', array(
    'wrapperHtmlOptions' => array('class' => 'col-sm-4'),
    'widgetOptions' => array(
        'htmlOptions' => array(
            'readonly' => 'readonly',
            'id' => 'Jumlahsaham_hargasaham_input',
            'value' => '20000000',
        )
    )
        )
);
?> 
<?php
echo $form->textFieldGroup($model, 'totalsaham', array(
    'wrapperHtmlOptions' => array('class' => 'col-sm-4'),
    'widgetOptions' => array(
        'htmlOptions' => array(
            'readonly' => 'readonly',
            'id' => 'Jumlahsaham_totalsaham_input',
            'value' => '0',
        )
    )
));
?>  

<?php
echo $form->dropDownListGroup($model, 'rekeningid', array(
    'wrapperHtmlOptions' => array('class' => 'col-sm-4'),
    'widgetOptions' => array(
        'data' => CHtml::listData(Rekening::model()->findAll(), 'id', 'namabank'),
        'htmlOptions' => array(
            'prompt' => 'Pilih Rekening',
            'onchange' => 'getNorek()'
        )
)));
?>

<div class="form-group">
    <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">No. Rekening</label>
    <div class="col-sm-4">
        <input type="text" name="norekening" id="norekening" disabled="disabled" class="form-control">                
        <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Nama Pemilik</label>
    <div class="col-sm-4">
        <input type="text" name="namapemilik" id="namapemilik" disabled="disabled" class="form-control">                
        <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Saldo</label>
    <div class="col-sm-4">
        <input type="text" name="saldo" id="saldo" disabled="disabled" class="form-control">                
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
    echo CHtml::ajaxSubmitButton('Simpan', CHtml::normalizeUrl(array('pemegangsaham/create', 'render' => true)), array(
        'dataType' => 'json',
        'type' => 'POST',
        'success' => 'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="' . Yii::app()->baseurl . '/admin/pemegangsaham";
                   }
				   else if(data.result==="FALSE")
				   {
					   alert("SALDO 0");
				   }
                   else
                   {          
					  $("#btnSavePemegangSaham").prop( "disabled", false);
                       $.each(data, function(key, val) 
                       {
                           $("#jumlahsaham-form #"+key+"_em_").text(val);                                                    
                           $("#jumlahsaham-form #"+key+"_em_").show();
                       });
                       document.getElementById("loadingProses").style.visibility = "hidden";
                   }       
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'
        ,
        'beforeSend' => 'function()
                 {      
					  $("#btnSavePemegangSaham").prop( "disabled", true);	
					  //$("body").undelegate("#btnSavePemegangSaham","click");
                      document.getElementById("loadingProses").style.visibility = "visible";

                 }'
            ), array('id' => 'btnSavePemegangSaham', 'class' => 'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewFile').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeURL() {
        document.getElementById('previewFile').src = '';
    }

    function upload_file()
    {

        var sahamid = $('#jumlahsahamidhidden').val();
        if (sahamid != '')
        {
            var fd = new FormData();
            var e = document.getElementById("Jumlahsaham_dokumen");

            fd.append("Jumlahsaham[dokumen]", $(e)[0].files[0]);
            fd.append("jumlahsahamidhidden", $("#jumlahsahamidhidden").val());

            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl("admin/pemegangsaham/uploadfile"); ?>',
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
        } else
        {
            //alert('isikan customer profile before upload image');
            $('#notifUploadBeforeInsertCustomerProfile').modal('show');
        }
    }
</script>
<script type="text/javascript">

    function hitungHargaSaham()
    {
        if (document.getElementById("Jumlahsaham_sahamid").value != '')
        {
            hargaBarang = 0;



            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->baseurl; ?>/admin/pemegangsaham/gethargasaham',
                data: {sahamid: document.getElementById("Jumlahsaham_sahamid").value},
                success: function (data) {
                    document.getElementById("Jumlahsaham_hargasaham_input").value = data;

                    hitungHargaTotalSaham();
                },
                error: function () {
                    alert("Error occured. Please try again (hitungHargaSaham)");
                }
            });
        } else
        {
            document.getElementById("Jumlahsaham_hargasaham_input").value = '0';
            hitungHargaTotalSaham();
        }
    }
    function hitungHargaTotalSaham()
    {
        rp = 0;
        rp = document.getElementById("Jumlahsaham_jumlahsaham_input").value * document.getElementById("Jumlahsaham_hargasaham_input").value;
        document.getElementById("Jumlahsaham_totalsaham_input").value = rp;
    }

    function getNorek()
    {
        var rekeningid = $('#Jumlahsaham_rekeningid option:selected').val();

        var data = 'rekeningid=' + rekeningid;

        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/admin/pemegangsaham/getnorek',
            data: data,
            success: function (row) {

                $('#norekening').val(row.data.norekening);
                $('#namapemilik').val(row.data.namapemilik);
                $('#saldo').val(row.saldo);
                if (row.saldo == 0)
                {
                    alert('SALDO BANK 0');
                    $('#btnSavePemegangSaham').prop("disabled", true);
                } else
                {
                    $('#btnSavePemegangSaham').prop("disabled", false);
                }
            },
            error: function () {
                alert("Error occured. Please try again (getLokasiBarang)");
            }
        });
    }


</script>


