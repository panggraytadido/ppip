<?php
$this->pageTitle = "Form Set Stock dan Supplier - Admin";

$this->breadcrumbs=array(
	'Admin',
        'Form Set Harga per Supplier',
);

?>



<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                                       
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Admin - Form Harga Per Supplier</b>
                    </div>   
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>
                
                                
                <input type="hidden" name="barangid" value="<?php echo $barangid ?>" id="barangid">
                <div class="ibox-content">
                    
                    
                   <div class="form-group">
                            <label class="col-sm-5 control-label">Barang</label>
                            <div class="col-lg-3">
                                <input class="form-control" disabled="disabled" value="<?php echo Barang::model()->findByPk($barangid)->nama; ?>">
                            </div>
                   </div>
                    <br>
                    <br>
                    <div class="pull-right">
                        <div id="error" style="color:red;font-weight:bold;"></div>
                    </div>
                    <table class="table table-bordered" id="table-harga-barang">
                        <tr>
                            <th>No</th><th>Supplier</th><th>Harga Modal</th><th>Harga Eceran</th><th>Harga Grosir</th><th></th>
                        </tr>
                        <tr>
                            <td>1</td>    
                            <td><?php
                                    $connection=Yii::app()->db;
                                    $sql ="SELECT id,namaperusahaan FROM master.supplier";

                                    $data=$connection->createCommand($sql)->queryAll();
                                    //print("<pre>".print_r($data,true)."</pre>");
                                    
                                    echo '<select name="supplier[1]" class="form-control no" id="1" class=form-control col-md-4"><option value="">Pilih Supplier</option>';
					for($i=0;$i<count($data);$i++)
                                        {
                                          $val = $data[$i]["namaperusahaan"];  
                                          $key = $data[$i]["id"];  
                                          echo "<option value=$key>$val</option>";
                                        }
                                    echo '</select';	                                    
                            ?></td>
                            <td>
                                <input type="hidden" name="cekhargamodal" id="cekhargamodal_1">
                                <input class="form-control" id="hargamodal_1" name="hargamodal[1]" value='0' oninput="insertToHargaModal(this);">
                            </td>
                            <td>
                                <input type="hidden" name="cekhargaeceran" id="cekhargaeceran_1">
                                <input class="form-control" id="hargaeceran_1" name="hargaeceran[1]" value='0' oninput="validateHargaEceran(this);">
                                <div id="messhargaeceran_1" style="color:red;font-weight:bold;display: none;">Harga Eceran Harus Lebih Besar dari Harga Modal</div>
                            </td>
                            <td>
                                <input type="hidden" name="cekhargagrosir" id="cekhargagrosir_1">
                                <input class="form-control" id="hargagrosir_1" name="hargagrosir[1]" value='0' oninput="validateHargaGrosir(this);">
                                <div id="messhargagrosir_1" style="color:red;font-weight:bold;display: none;">Harga Grosir Harus Lebih Besar dari Harga Modal</div>
                            </td>                
                            <td><button class="btn btn-primary" onclick="addrow();">+</button></td>
                        </tr>                        
                    </table>
                     <span id="loadingProses" style="visibility: hidden; margin-top: -5px;">
                        <h5><b>Silahkan Tunggu Proses ...</b></h5>
                        <div class="progress progress-striped active">
                            <div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar progress-bar-danger">
                                <span class="sr-only">Silahkan Tunggu..</span>
                            </div>
                        </div>
                    </span>    
                    <button class="btn btn-primary" onclick="simpan();" id="bntSimpan">Simpan</button>
                </div> 
            </div>	              
        </div>
    </div>
</div>

<script>
$('#error').hide();
function insertToHargaModal(p)
{
    var tmp = p.id.split("_");
    var id =tmp[1];
    $('#cekhargamodal_'+id+'').val(p.value);    
}

function insertToHargaEceran(p)
{
    var tmp = p.id.split("_");
    var id =tmp[1];
    $('#cekhargaeceran_'+id+'').val(p.value);    
}

function insertToHargaGrosir(p)
{
    var tmp = p.id.split("_");
    var id =tmp[1];
    $('#cekhargagrosir_'+id+'').val(p.value);    
}

function simpan()
{
    var hargamodal = $('#Crudbarang_hargamodal').val();
    var hargaeceran = $('#Crudbarang_hargaeceran').val();
    var hargagrosir = $('#Crudbarang_hargagrosir').val();
    
    //if(hargamodal!='' && hargaeceran!='' && hargagrosir!='')
    //{
        var data = $('#table-harga-barang :input').serialize();
        //alert(data);
        
        $.ajax({
                    dataType:'json',
                    type: 'POST',      
                    beforeSend: function() {
                        // setting a timeout
                        document.getElementById("loadingProses").style.visibility = "visible";
                    },
                    url: '<?php echo Yii::app()->baseurl; ?>/admin/crudbarang/sethargabarang',
                    data: data+'&barangid='+$('#barangid').val(),//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                    success: function (data) {
                            document.getElementById("loadingProses").style.visibility = "hidden";     
                            if(data.result==='Failed')
                            {
                                $('#error').html('Isikan Seluruh Isian Form');
                                $('#error').show();
                                $('#error').fadeOut(3000);

                            }    
                            if(data.result=='OK')
                            {
                                location.href='<?php echo Yii::app()->baseurl ?>/admin/crudbarang';    
                            }    
                            //location.href='<?php echo Yii::app()->baseurl ?>/admin/crudbarang';                                                                                
                    },
                    error: function () {
                        //alert("Error occured. Please try again (hitungHargaBarang)");
                    }
            });
        }   
       
    //}
//}

function addrow()
{    
        var max = 0;
	$('.no').each(function() {
    	max = Math.max(this.id, max);
	});
        
        var maxNo =max+1;    
    
        var rowAdded = '<tr>';					
					
        rowAdded += '<td>';
	rowAdded += maxNo;	
	rowAdded += '</td>';
                                        
	rowAdded += '<td>';
	rowAdded += '<select name="supplier['+maxNo+']" id="'+maxNo+'" class="form-control no supplier"><option value="0">Pilih Supplier</option></select>';	
	rowAdded += '</td>';
	
	rowAdded += '<td>';
        rowAdded += '<input type="hidden" class="form-control" id="cekhargamodal_'+maxNo+'">';
	rowAdded += '<input class="form-control" oninput="insertToHargaModal(this);"  id="hargamodal_'+maxNo+'" name="hargamodal['+maxNo+']" value="0">';				
	rowAdded += '</td>';
		
	rowAdded += '<td>';
        rowAdded += '<input type="hidden" class="form-control" id="cekhargaeceran_'+maxNo+'">';
	rowAdded += '<input class="form-control" id="hargaeceran_'+maxNo+'" oninput="validateHargaEceran(this);"  name="hargaeceran['+maxNo+']" value="0">';
        rowAdded += '<div id="messhargaeceran_'+maxNo+'" style="color:red;font-weight:bold;display: none;">Harga Eceran Harus Lebih Besar dari Harga Modal</div>';        
	rowAdded += '</td>';
	
	rowAdded += '<td>';
        rowAdded += '<input type="hidden" class="form-control" id="cekhargagrosir_'+maxNo+'">';
	rowAdded += '<input class="form-control" id="hargagrosir_'+maxNo+'" oninput="validateHargaGrosir(this);" name="hargagrosir['+maxNo+']" value="0">';	
        rowAdded += '<div id="messhargagrosir_'+maxNo+'" style="color:red;font-weight:bold;display: none;">Harga Grosir Harus Lebih Besar dari Harga Modal</div>';
	rowAdded += '</td>';
        
        rowAdded += '<td>';
	rowAdded += '<button class="btn btn-danger deleteLink" onclick="deleteRow(this)">-</button>';				
	rowAdded += '</td>';
	

	$('#table-harga-barang tr:last').after(rowAdded);
        
         $.ajax({
                url: '<?php echo Yii::app()->baseurl; ?>/admin/crudbarang/getsupplier',
                type: 'post',
                dataType:'json',			
               // data: 'type=manajemen',
                success: function (row) 
                {                       
                        $.each(row, function () {
                             $('.supplier').append($("<option></option>").val(this['id']).html(this['nama']));
                            });		                						 		
                }
          });	
          
          return false;
        
}    

function deleteRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("table-harga-barang").deleteRow(i);
}


function validateNumber(evt) {
    var e = evt || window.event;
    var key = e.keyCode || e.which;

    if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
    // numbers   
    key >= 48 && key <= 57 ||
    // Numeric keypad
    key >= 96 && key <= 105 ||
    // Backspace and Tab and Enter
    key == 8 || key == 9 || key == 13 ||
    // Home and End
    key == 35 || key == 36 ||
    // left and right arrows
    key == 37 || key == 39 ||
    // Del and Ins
    key == 46 || key == 45) {
        // input is VALID
    }
    else {
        // input is INVALID
        e.returnValue = false;
        if (e.preventDefault) e.preventDefault();
    }
}

function validateHargaEceran(p)
{
    var tmp = p.id.split("_");
    var id =tmp[1];
    var hargamodal = $('#cekhargamodal_'+id+'').val();
    
    insertToHargaEceran(p);
    var hargaeceran = $('#cekhargaeceran_'+id+'').val();
    
    //alert(hargamodal);
    if(hargaeceran>hargamodal)
    {
        $('#messhargaeceran_'+id+'').hide();
        $('#messhargaerceran_'+id+'').html('');                
        $('#bntSimpan').attr("disabled", false);
    }    
    else
    {        
        $('#hargaeceran_'+id+'').addClass('.error');          
        $('#messhargaeceran_'+id+'').show();    
        $('#bntSimpan').attr("disabled", true);
    }    
}

function validateHargaGrosir(p)
{
    var tmp = p.id.split("_");
    var id =tmp[1];
    var hargamodal = $('#cekhargamodal_'+id+'').val();
    
    insertToHargaGrosir(p);
    var hargagrosir = $('#cekhargagrosir_'+id+'').val();
    
    if(hargagrosir>hargamodal)
    {
        $('#messhargagrosir_'+id+'').hide();
        $('#messhargagrosir_'+id+'').html('');          
        $('#bntSimpan').attr("disabled", false);
    }    
    else
    {        
        $('#hargagrosir_'+id+'').addClass('.error');          
        $('#messhargagrosir_'+id+'').show();    
        $('#bntSimpan').attr("disabled", true);
        //$(":submit").attr("disabled", true);
    }    
}
</script>    