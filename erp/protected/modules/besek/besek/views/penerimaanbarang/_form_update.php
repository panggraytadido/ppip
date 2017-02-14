<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'besekpenerimaanbarang-form',
                'enableAjaxValidation'=>true,
                'clientOptions'=>array(
                                'validateOnChange'=>true,
                )
        )); 
?>
	
<?php echo $form->errorSummary($modelPenerimaan); ?>

<input type="hidden" name="waktuinsert" value="<?php echo date("Y-m-d H:", time()); ?>" />
<input type="hidden" name="waktuupdate" value="<?php echo date("Y-m-d H:", time()); ?>" />

        <?php echo $form->datePickerGroup($modelPenerimaan, 'tanggal', array(
                                            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'onchange'=>'deleteAllKaryawan();'
                                                            )
                                                )
                                        )
                ); ?>

        <?php     
                echo $form->dropDownListGroup($modelPenerimaan, 'barangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->with('stocksupplier')->findAll('divisiid='.Yii::app()->session['divisiid']." and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'-- Pilih Barang --',
                                                    'ajax' => array(
                                                        'type'=>'POST', 
                                                        'dataType'=>'json', 
                                                        'url'=>Yii::app()->createUrl('besek/penerimaanbarang/getsupplierbarang'),
                                                        'success' => "function(data){ 
                                                                $(\"#Besekpenerimaanbarang_supplierid\").html(data.cmbSupplier);
                                                                $(\"#Besekpenerimaanbarang_lokasipenyimpananbarangid\").html(data.cmbLokasi);
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
                                            'data' => CHtml::listData(Supplier::model()->with('stocksupplier')->findAll('status=1 and barangid='.$modelPenerimaan->barangid),'id','namaperusahaan'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'-- Pilih Supplier --',
                                                    'ajax' => array(
                                                        'type'=>'POST', 
                                                        'url'=>Yii::app()->createUrl('besek/penerimaanbarang/getlokasibarang'),
                                                        'update'=>'#Besekpenerimaanbarang_lokasipenyimpananbarangid',
                                                        'error' => "function () {
                                                            alert(\"Error occured. Please try again (getlokasibarang)\");
                                                        }",
                                                        'data'=>array('barangid'=>'js:document.getElementById("Besekpenerimaanbarang_barangid").value','supplierid'=>'js:this.value'),
                                                      )
                                            )
                                    )));
        ?>

        <?php     
                echo $form->dropDownListGroup($modelPenerimaan, 'lokasipenyimpananbarangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->with('stocksupplier')->findAll('barangid='.$modelPenerimaan->barangid.' and supplierid='.$modelPenerimaan->supplierid.' and lokasipenyimpananbarangid='.$modelPenerimaan->lokasipenyimpananbarangid),'id','nama'),
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
        echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('penerimaanbarang/update','id'=>$modelPenerimaan->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {  
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->createUrl("besek/penerimaanbarang/view", array("id"=>$modelPenerimaan->id)).'";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#besekpenerimaanbarang-form #"+key+"_em_").text(val);                                                    
                           $("#besekpenerimaanbarang-form #"+key+"_em_").show();
                       });
                   }
                   
                    document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      document.getElementById("loadingProses").style.visibility = "visible";
                 }'
            ),array('id'=>'btnUpdate','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">

    
    
    function cekJumlahAbsen()
    {
        var tgl = document.getElementById("Besekpenerimaanbarang_tanggal").value;
        var jumlahAbsen = 0;

        if(tgl!='')
        {
            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/besek/penerimaanbarang/getjumlahabsen',
                    data: { tanggal : tgl},
                    success: function (jumlah) {

                        if(jumlah>0)
                        {
                            jumlahAbsen = jumlah;
                            if(counter < jumlahAbsen)
                                tambahKaryawan();
                        }
                        else
                            document.getElementById("pesanKaryawan").innerHTML = '<br /><div class="label label-warning">Tidak Ada Karyawan yang Hadir</div>';

                    },
                    error: function () {
                        alert("Error occured. Please try again (cekJumlahAbsen)");
                    }
            });
        }
        else
        {
            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Pilih Tanggal Dahulu</div><br /><br />';
        }
    }
    
    function tambahKaryawan()
    {
        var tgl = document.getElementById("Besekpenerimaanbarang_tanggal").value;
        
        if(tgl!='')
        {
            $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo Yii::app()->baseurl; ?>/besek/penerimaanbarang/getkaryawanabsen',
                    data: { tanggal : tgl},
                    success: function (data) {

                        if(data.option!='')
                        {
                            counterNext = counter + 1;
                            
                            content = '<div class="form-group"><label class="col-sm-5 control-label required" for="karyawanid">Karyawan</label><div class="col-sm-4">';
                            content = content + '<select name="karyawanid[]" id="karyawanid" class="form-control">' + data.option + '</select>';
                            content = content + '</div></div><div id="inputKaryawan_' + counterNext + '"></div>';
                            
                            document.getElementById("inputKaryawan_"+counter).innerHTML = content;
                            counter++;
                            
                            // ubah nilai jumlah karyawan
                            document.getElementById("Bongkarmuat_jumlahkaryawan").value = counter;
                            hitungRupiah();
                            
                            document.getElementById("pesanKaryawan").innerHTML = '';
                        }
                        else
                            document.getElementById("pesanKaryawan").innerHTML = '<br /><div class="label label-warning">Tidak Ada Karyawan yang Hadir</div>';
                    },
                    error: function () {
                        alert("Error occured. Please try again (tambahKaryawan)");
                    }
            });
        }
        else
        {
            document.getElementById("pesanKaryawan").innerHTML = '<br /><div class="label label-warning">Pilih Tanggal Dahulu</div><br /><br />';
        }
    }

    function deleteKaryawan()
    {
        no=counter-1;
        document.getElementById("inputKaryawan_"+no).innerHTML = "&nbsp;";

        counter--;
        
        // ubah nilai jumlah karyawan
        document.getElementById("Bongkarmuat_jumlahkaryawan").value = counter;
        hitungRupiah();
    }
    
    function deleteAllKaryawan()
    {
        while(counter>0)
        {
            no=counter-1;
            document.getElementById("inputKaryawan_"+no).innerHTML = "&nbsp;";

            counter--;

            // ubah nilai jumlah karyawan
            document.getElementById("Bongkarmuat_jumlahkaryawan").value = counter;
            //hitungRupiah();
        }
    }
    
    function hitungTotalBarang()
    {
        totalBarang = 0;
        totalBarang = (document.getElementById("Besekpenerimaanbarang_jumlah").value) - (document.getElementById("Besekpenerimaanbarang_beratlori").value);
        document.getElementById("Besekpenerimaanbarang_totalbarang").value = totalBarang;
        
        //hitungRupiah();
    }
    
    function hitungRupiah()
    {
        rp = 0;
        rp = (document.getElementById("Besekpenerimaanbarang_totalbarang").value * 20) / document.getElementById("Bongkarmuat_jumlahkaryawan").value;
        document.getElementById("Bongkarmuat_upah").value = rp;
    }

</script>