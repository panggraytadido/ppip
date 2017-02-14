
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
	

	<div class="alert alert-danger fade in" id="stockMessage">
            <button type="button" class="close close-sm" data-dismiss="alert">
                <i class="fa fa-times"></i>
            </button>
            Stock Barang Tidak Mencukupi
        </div>    

	<div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Divisi</label>
            <div class="col-sm-2 col-sm-9"><b>Gudang</b>
                    <div style="display:none" id="Penerimaanbarang_beratlori_em_" class="help-block error"></div>                        
                </div>                
        </div>

        <?php     
                echo $form->datePickerGroup($modelPenjualanBarang, 'tanggal',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                    ));
              
	?>
        
        
        <?php     
                echo $form->select2Group($modelPenjualanBarang, 'pelangganid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Pelanggan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pelanggan',
                                                    'onchange'=>'showHideValidation(this);'
                                            )
                                    )));              
	?>

        <?php     
                $divisiid = Divisi::model()->find("kode='1'")->id;
                $criteria = new CDbCriteria;
                $criteria->condition="divisiid=".$divisiid;
                echo $form->select2Group($modelPenjualanBarang, 'barangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',
                                                   //'onchange'=>'checkStockPenjualanBarang(this);'
                                            )
                                    )));              
	?>

        <?php     
                echo $form->select2Group($modelPenjualanBarang, 'lokasipenyimpananbarangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Lokasi',
                                                    'onchange'=>'checkStockPenjualanBarang(this);'
                                            )
                                    )));              
	?>

        <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Jumlah(Kg)</label>
            <div class="col-sm-2 col-sm-9"><input type="text" id="Penjualanbaranggudang_jumlah"  name="Penjualanbaranggudang[jumlah]" placeholder="Jumlah(Kg)" class="form-control">
                    <div style="display:none" id="Penerimaanbarang_beratlori_em_" class="help-block error"></div>                        
                </div> 
             <div class="col-sm-2 col-sm-9">
                    <input type="text" class="form-control" id="box" name="Penjualanbaranggudang[box]" placeholder="Box">                    
                </div>         
        </div>

        <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Kategori</label>
                <div class="col-sm-2 col-sm-9">
                    <input type="radio" name="kategori" onclick="getHarga();" id="kategori" value="1">&nbsp;Eceran <input type="radio" name="kategori" onclick="getHarga();" id="kategori" value="2">&nbsp;Grosir 
                    <div style="display:none" id="Penjualannbaranggudang_beratlori_em_" class="help-block error"></div>                        
                </div> 
             <div class="col-sm-3 col-sm-9">
                    <input type="text" class="form-control" id="harga" placeholder="Harga">                    
                    <input type="hidden" name="hargainput" class="form-control" id="hargainput">                    
             </div>         
        </div>

        <?php echo $form->textFieldGroup($modelPenjualanBarang, 'hargatotal',array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'))); ?>
        <input type="hidden" name="Penjualanbaranggudang[hargatotalinput]" id="Penjualanbaranggudang_hargatotalinput" >
        
        
        <div class="form-group">
            <label class="col-sm-5 control-label" for="Penerimaanbarang_barangid">Jumlah Karyawan</label>
                <div class="col-sm-1 col-sm-9">
                    <input type="text" class="form-control" id="jumlahkaryawanpenjualan" oninput="getUpahPenjualan(this);">                    
                </div>                                                    
                <div class="col-sm-2 col-sm-9">
                    <input type="text" class="form-control" id="upahkaryawanpenjualan" name="upahkaryawanpenjualan" placeholder="RP.">      
                    <input type="hidden" class="form-control" id="upahkaryawanpenjualaninput" name="upahkaryawanpenjualaninput" placeholder="RP.">      
                </div>                                                    
                <button type="button" class="btn btn-warning" onclick="createPegawaipenjualan();">Set</button>
        </div>

        <div id="tablekaryawanpenjualan"></div>
        
	<div style="float:left">            
           
		<?php 
                echo CHtml::ajaxSubmitButton('Tambah',CHtml::normalizeUrl(array('gudang/addpenjualanbarang')),
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
                    else if(data.result==="NotOK")
                    {
                         $("#stockMessage").show();
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
                 ),array('id'=>'btnPenjualan','class'=>'btn btn-primary'));                                                         
         ?> 
	</div>
        <br>
  

<?php $this->endWidget(); ?>

<script>
    $('#stockMessage').hide();
    
    function checkStockPenjualanBarang()
    {
        var barangid = $('#Penjualanbaranggudang_barangid option:selected').val();
        var lokasipenyimpananbarangid = $('#Penjualanbaranggudang_lokasipenyimpananbarangid option:selected').val();
                
                
         $.ajax({
	        url: '<?php echo Yii::app()->baseUrl. '/admin/gudang/checkstockbarang' ?>',
	        type: 'post',
		dataType:'json',			
	        data: 'barangid='+barangid+'&lokasipenyimpananbarangid='+lokasipenyimpananbarangid,
	        success: function (row) 
	        {         
                    if(row.stockPending==null)
                    {
                        alert('Stock Tersedia : '+row.stock+', Pending Stock 0');                						 		
                    }    
                    else
                    {
                        alert('Stock Tersedia : '+row.stock+', Pending Stock '+row.stockPending);        
                    }    
	            
	        }
            });
                        
    }
    
    function createPegawaipenjualan()
    {
        var jumlah = $('#jumlahkaryawanpenjualan').val();
       // $('#tablekaryawanpenjualan').append('<tr style="background-color:#cccccc;"><th width="1%">No</th><th></th><th>NIP</th><th>Nama</th></tr>');
        getJsonKaryawan();
        for(var i=1;i<=jumlah;i++)
        {
                                    
            var karyawan ='<select class="karyawan form-control"  id="karyawan_'+i+'" name="karyawan['+i+']"><option value=""></option></select>';
            var rowAdded = '<tr>';											           

            rowAdded += '<td width="2%">';
            rowAdded += '<a class="deleteLink btn btn-danger btn-xs" onclick="deleteRow(this)" style="cursor:pointer;">-</a>';				
            rowAdded += '</td>';

            rowAdded += '<td width="20%">';
            rowAdded += karyawan;				
            rowAdded += '</td>';
            
            rowAdded += '</tr>';
            
            rowAdded +='<br><br>';

            $('#tablekaryawanpenjualan').append(''+rowAdded+'');
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
			
    }
    
    function getTotalBarang()
    {
        var jumlah = $('#Penjualanbaranggudang_jumlah').val();
        var hargasatuan = $('#hargainput').val();
        
        var total = jumlah-beratlori;
        
        $('#Penerimaanbarang_totalbarang').val(total);                
    } 
    
    function getHarga()
    {
        var barangid = $('#Penjualanbaranggudang_barangid :selected').val();
        if(barangid!='')
        {
            var kategori =  $('input[name="kategori"]:checked').val();      
            var jumlah = $('#Penjualanbaranggudang_jumlah').val();

            $.ajax({
                    url: '<?php echo Yii::app()->baseUrl. '/admin/gudang/getharga'?>',
                    type: 'post',
                    dataType:'json',			
                    data: 'barangid='+barangid+'&kategori='+kategori,
                    success: function (row) 
                    {                      
                        var harga=formatCurrency(row);
                        $('#harga').val(harga);
                        $('#hargainput').val(row);
                        var hargatotal = jumlah* $('#hargainput').val();
                        $('#Penjualanbaranggudang_hargatotal').val(formatCurrency(hargatotal));
                        $('#Penjualanbaranggudang_hargatotalinput').val(hargatotal);
                        
                    }
               });
        }
        else 
        {
            alert('Pilih Barang Terlebih dahulu');
        }
        
    }    
    
    function getUpahPenjualan()
    {
        var jumlah = $('#Penjualanbaranggudang_jumlah').val();
        var jumlahKaryawanPenjualan= $('#jumlahkaryawanpenjualan').val(); 
        var upah = (jumlah*30)/jumlahKaryawanPenjualan;
        $('#upahkaryawanpenjualan').val(upah);
        $('#upahkaryawanpenjualaninput').val(upah);
        
    }    
    
    function formatCurrency(total) {
    return total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");;
}
</script>        