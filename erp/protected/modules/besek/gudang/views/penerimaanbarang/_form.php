<?php  Yii::app()->clientScript->registerCoreScript('yiiactiveform') ?>
<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'gudangpenerimaanbarang-form',
                'enableAjaxValidation'=>true,
                'clientOptions'=>array(
                                'validateOnChange'=>true,
                )
        )); 
?>
	
        <?php echo $form->errorSummary($modelPenerimaan); ?>
        <?php echo $form->errorSummary($modelBongkarmuat); ?>

        <?php echo $form->datePickerGroup($modelPenerimaan, 'tanggal', array(
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

        <?php 
		
			$criteria = new  CDbCriteria;
			$criteria->condition = 'divisiid='.Yii::app()->session["divisiid"].' and lokasipenyimpananbarangid='.Yii::app()->session["lokasiid"];
			$criteria->order='nama asc';
						  
                echo $form->dropDownListGroup($modelPenerimaan, 'barangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->with('stock')->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'-- Pilih Barang --',
                                                    'ajax' => array(
                                                        'type'=>'POST', 
                                                        'dataType'=>'json', 
                                                        'url'=>Yii::app()->createUrl('gudang/penerimaanbarang/getsupplierbarang'),
                                                        'success' => "function(data){ 
                                                                $(\"#Gudangpenerimaanbarang_supplierid\").html(data.cmbSupplier);
                                                                $(\"#Gudangpenerimaanbarang_lokasipenyimpananbarangid\").html(data.cmbLokasi);
                                                        }",
                                                        'error' => "function () {
                                                            alert(\"Error occured. Please try again (getSupplierBarang)\");
                                                        }",
                                                        'data'=>array('barangid'=>'js:this.value'),
                                                      )
                                            )
                                    )));
									
        ?>
		

	<?php     
                echo $form->dropDownListGroup($modelPenerimaan, 'supplierid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => array(),
                                            'htmlOptions' => array(
                                                    'prompt'=>'-- Pilih Supplier --',
                                                    'ajax' => array(
                                                        'type'=>'POST', 
                                                        'url'=>Yii::app()->createUrl('gudang/penerimaanbarang/getlokasibarang'),
                                                        'update'=>'#Gudangpenerimaanbarang_lokasipenyimpananbarangid',
                                                        'error' => "function () {
                                                            alert(\"Error occured. Please try again (getlokasibarang)\");
                                                        }",
                                                        'data'=>array('barangid'=>'js:document.getElementById("Gudangpenerimaanbarang_barangid").value','supplierid'=>'js:this.value'),
                                                      )
                                            )
                                    )));
        ?>
        
        <?php     
                echo $form->dropDownListGroup($modelPenerimaan, 'lokasipenyimpananbarangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => array(),
                                    )));
        ?>

       <?php echo $form->textFieldGroup($modelPenerimaan, 'jumlah', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
            ); ?> 
	
       <?php echo $form->textFieldGroup($modelPenerimaan, 'beratlori', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
            ); ?> 

       <?php echo $form->textFieldGroup($modelPenerimaan, 'totalbarang', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly'
                                                            )
                                                )
                                        )
            ); ?>  
    
<br /><br />

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Jumlah Karyawan</label>
            <div class="col-sm-4">
                <select name="Bongkarmuat[jumlahkaryawan]" id="Bongkarmuat_jumlahkaryawan" onchange="hitungRupiah()" class="form-control">
                    <option value="">-- Pilih Tanggal Dahulu --</option>
                </select>
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>

        <?php echo $form->textFieldGroup($modelBongkarmuat, 'upah', array(
                                            'append' => '<input id="btnSet" class="btn btn-info" name="btnSet" value="Set" type="button" onclick="addKaryawan()" />',
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'value'=>'0',
                                                                'readonly'=>'readonly'
                                                            )
                                                )
                                        )
            ); ?>  
    

    <div id="inputKaryawan">
        &nbsp;
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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('penerimaanbarang/create','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/gudang/penerimaanbarang/view/id/" + data.penerimaanid;
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
            ),array('id'=>'btnSavePenerimaanBarang','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">

    function hitungTotalBarang()
    {
        totalBarang = 0;
        totalBarang = (document.getElementById("Gudangpenerimaanbarang_jumlah").value) - (document.getElementById("Gudangpenerimaanbarang_beratlori").value);
        document.getElementById("Gudangpenerimaanbarang_totalbarang").value = totalBarang;
        
        hitungRupiah();
    }
    
    function hitungRupiah()
    {
        rp = 0;
        rp = (document.getElementById("Gudangpenerimaanbarang_totalbarang").value * 20) / document.getElementById("Bongkarmuat_jumlahkaryawan").value;
        document.getElementById("Bongkarmuat_upah").value = rp;
    }
    
    function addKaryawan()
    {
        var tgl = document.getElementById("Gudangpenerimaanbarang_tanggal_input").value;
        
        if(tgl!='')
        {
            $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penerimaanbarang/getkaryawanabsen',
                    data: { tanggal : tgl},
                    success: function (data) {
                        
                        if(data.option!='')
                        {
                            var jmlInput = document.getElementById("Bongkarmuat_jumlahkaryawan").value;
                            var content = '';
                            for(i=1;i<=jmlInput;i++)
                            {
                                content = content + '<div class="form-group"><label class="col-sm-5 control-label" for="karyawanid">Karyawan</label><div class="col-sm-4"><select name="karyawanid[]" id="karyawanid_' + i + '" class="form-control">' + data.option + '</select></div></div>';
                            }
                            document.getElementById("inputKaryawan").innerHTML = content;
                        }
                        else
                        {
                            $('#Bongkarmuat_jumlahkaryawan option[value!=""]').remove();
                            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Tidak Ada Karyawan yang Hadir</div><br /><br />';
                        }

                    },
                    error: function () {
                        alert("Error occured. Please try again (getkaryawanabsen)");
                    }
            });
			
			addJumlahDropdownKaryawan();
        }
        else
        {
            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Pilih Tanggal Dahulu</div><br /><br />';
        }
    }
    
    function addJumlahDropdownKaryawan()
    {
        var tgl = document.getElementById("Gudangpenerimaanbarang_tanggal_input").value;
        var jmlItem = $('#Bongkarmuat_jumlahkaryawan').children('option').length;
        
        if(tgl!='' && jmlItem <= 1)
        {
            $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penerimaanbarang/getkaryawanabsen',
                    data: { tanggal : tgl},
                    success: function (data) {
                        
                        if(data.option!='')
                        {
                            var x = document.getElementById("Bongkarmuat_jumlahkaryawan");
                            $('#Bongkarmuat_jumlahkaryawan option[value!=""]').remove();
                            for(i=0;i<data.jml;i++)
                            {
                                var option = document.createElement("option");
                                option.value = (i+1);
                                option.text = (i+1);
                                x.add(option);
                            }
                        }
                        else
                        {
                            $('#Bongkarmuat_jumlahkaryawan option[value!=""]').remove();
                            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Tidak Ada Karyawan yang Hadir.</div><br /><br />';
                        }

                    },
                    error: function () {
                        alert("Error occured. Please try again (addJumlahDropdownKaryawan)");
                    }
            });
        }
        else
        {
            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Pilih Tanggal Dahulu</div><br /><br />';
        }
    }
</script>