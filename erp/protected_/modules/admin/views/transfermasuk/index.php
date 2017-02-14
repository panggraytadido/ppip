<?php
$this->pageTitle = "Transfer Masuk - Admin";

$this->breadcrumbs=array(
	'Admin',
        'Transfer Masuk',        
);

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

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                       
                             <b>Admin - Transfer Masuk</b>                      
                    </div>
                    <div class="pull-right">                                             
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>                    
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('../admin/datatransfer'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>

                <div class="ibox-content">
                    <?php if(Yii::app()->user->hasFlash('success')):?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close close-sm" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>    
                    <?php endif; ?>
                    
                    <div class="col-lg-6">

                        <form class="form-horizontal" id="form1">
                         
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Tanggal</label>
                                <div class="col-sm-7">                                   
                                    <?php
                            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                                'name'=>'tanggal',
                                'options'=>array(
                                    'showAnim'=>'slide',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                                    
                                ),
                                'htmlOptions'=>array(
                                    'id'=>'tanggal',
                                    'class'=>'form-control',
                                    'style'=>'z-index:99999999999999;',
                                ),
                            ));
                            ?>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Bank</label>
                                <div class="col-sm-7">
                                    <?php 
                                        echo CHtml::dropDownList('rekening', 'rekening', 
                                                    CHtml::listData(Rekening::model()->findAll(),
                                                    'id', 'namabank'),
                                                    array('class'=>'form-control','onchange'=>'getNoRekening(this);','prompt'=>'Pilih Rekening')
                                                                                      );
                                    ?>
                                </div>
                            </div>                            
                            
                            <div class="form-group">
                            <label class="col-sm-5 control-label">No. REK</label>
                            <div class="col-sm-7">                               
                               <input type="text" class="form-control" readonly="readonly" id="norekening"/>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Nama Penyetor</label>
                            <div class="col-sm-7">                                   
                                    <?php 
										$criteria = new  CDbCriteria;										
										$criteria->order='nama asc';
										
										$this->widget(
											'booster.widgets.TbSelect2',
											array(
												'name' => 'pelanggan',
												'id'=>'pelanggan',
												'data' => CHtml::listData(Pelanggan::model()->findAll($criteria),'id','nama'),
												'options' => array(
													'placeholder' => 'Pilih Penyetor',
													'width' => '100%',
												)
											)
										);
										/*
                                        echo CHtml::dropDownList('pelanggan', 'pelanggan', 
                                                CHtml::listData(Pelanggan::model()->findAll(),
                                                'id', 'nama'),
                                                array('class'=>'form-control','prompt'=>'Pilih Penyetor')
                                        );
										*/
                                    ?>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Pilih Faktur</label>
                            <div class="col-sm-7">                                   
                                <div class="btn btn-warning"><a href="#" onclick="popUpNoFaktur();">+ Pilih</a></div>
                                <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div> 
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
                            
                    </form>
                                           
                    <button class="btn btn-primary" onclick="simpanTransfer();">Simpan</button>
                        
                    </div>
                    
                    <div class="col-lg-6">

                        <form class="form-horizontal" id="form2">
                         
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Nilai Transaksi</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="nilaitransaksiinput" class="form-control" id="nilaitransaksiinput" />
                                    <input type="text" disabled="disabled" class="form-control" name="nilaitransaksi" id="nilaitransaksi" oninput="nilaiTransaksi(this);"/>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Sisa Transaksi</label>
                                <div class="col-sm-7">                                  
                                    <input type="hidden" name="sisatransaksiinput" class="form-control" id="sisatransaksiinput" />
                                    <input type="text" disabled="disabled" class="form-control" id="sisatransaksi" name="sisatransaksi" />                      
                                </div>
                            </div>
                            
                            <div class="form-group">
                            <label class="col-sm-5 control-label">Jumlah Saldo</label>
                            <div class="col-sm-7">                           
                                    <input type="hidden" name="saldoinput" class="form-control" id="saldoinput" value="0"/>
                                    <input type="text" class="form-control" readonly="readonly" id="saldo" />
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Total Saldo</label>
                            <div class="col-sm-7">                                   
                                    <input type="hidden" name="totalsaldoinput" class="form-control" id="totalsaldoinput" />
                                    <input type="text" class="form-control" readonly="readonly" id="totalsaldo" />
                            </div>
                        </div>                                                                                                                                                                                                                                
                    </form>                                                
                        
                    </div>
                 
                    <div class="clearfix"></div>
                    
                </div>
                
                
                
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">                    
                             <b>Admin - Data Transfer Masuk</b>                      
                    </div>
                    <div class="pull-right">                                              
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>                    
                    </div>
                </div>

                <div class="ibox-content">

                    <?php if(Yii::app()->user->hasFlash('success')):?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close close-sm" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>    
                    <?php endif; ?>

                    <div class="col-lg-12">                       
                            <?php $this->widget('booster.widgets.TbGridView', array(
		'id'=>'grid',
        'type' => 'striped bordered hover',
		'dataProvider'=>$model->listTransferMasuk(),
		'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker',     
		'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
                array(
            				'class'=>'booster.widgets.TbRelationalColumn',
            				//'name' => 'firstLetter',
            				'header'=>'Detail Barang',
            				'url' =>$this->createUrl("transfermasuk/childdatasetoran"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
            }'
            		),
                array('name'=>'nofaktur','value'=>'$data->nofaktur','header'=>'No Faktur'),	        
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Transfermasuk[tanggal]',
                                'attribute' => 'tanggal',
                                'htmlOptions' => array(
                                                    'class'=>'form-control ct-form-control',
                                                ),
                                'options' => array(
                                    'format' => 'yyyy-mm-dd',
                                    'language' => 'en',
                                    'class'=>'form-controls form-control-inline input-medium default-date-picker',
                                    'size'=>"16"
                                )
                            ),true
                        )
                ),		
                array('name'=>'pelanggan','value'=>'$data->pelanggan','header'=>'Pelanggan'),	
                array(
                    'header'=>'Total Belanja',                   
                    'name'=>'totalbelanja',
                    'value'=>'number_format($data->hargatotal)',
                    'filter'=>false
                ),
                array(
                    'header'=>'Total Bayar',                   
                    'name'=>'totalbayar',
                    'value'=>function($data,$row){
                        
                        $pelangganid = substr($data->id, 10,4);                               
                        $tanggal = substr($data->id, 0,10);                                                  
                        
                        $cekFaktur = Faktur::model()->findByPk($data->id);
                        if(count($cekFaktur)!=0)
                        {
                            return number_format($cekFaktur->bayar);
                        }
                        else
                        {
                            return 0;
                        }
                        //return Datasetoran::model()->checkDataFakturTotalBayar($pelangganid,$tanggal);
                    },
                    'filter'=>false
                ),
                array(
                    'header'=>'Sisa Tagihan',                   
                    'name'=>'sisatagihan',
                    'value'=>function($data,$row){
                        
                        $pelangganid = substr($data->id, 10,4);                               
                        $tanggal = substr($data->id, 0,10);                            
                        $cekFaktur = Faktur::model()->findByPk($data->id);
                         if(count($cekFaktur)!=0)
                         {
                             return number_format($cekFaktur->sisa);
                         }
                         else 
                         {                             
                             return number_format(Datasetoran::model()->getTotalBelanja($pelangganid,$tanggal));
                         }    
                    },
                    'filter'=>false        
                ),               
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{} {delete}',
							                                    'buttons' => array(							                                        
																				'' =>   array(
							                                                      //'label'=>'Print Faktur',
																				   //'icon'=>'fa fa-print',     																					
																				   //'url'=>'Yii::app()->createUrl("kasir/datasetoran/checkformfaktur", array("id"=>$data->id))', 
							                                                        'options'=>array(
																						'visible'=>false,
																						//'class'=>'btn btn-success btn-xs',							                                                           
																						//'onclick'=>'cetakFaktur(this);',
																						'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'js:function(data) {
																							if(data.result==="OK")
																							{
																								$("#nilaitransaksi").val(data.nilaitransaksi);
																								$("#sisatransaksi").val(data.sisatransaksi);
																								$("#saldo").val(data.saldo);
																								$("#tanggal").val(data.tanggal);
																								$("#rekening").prop("selectedIndex", 1);
																								$("#pelanggan").attr("selectedIndex", 5);
																								getNorekUpdate(1);
																							}																											
																						}'                                                                                                                                                                                                                                                                                                                                                                                                              
							                                                            ),
                                                                                                                     
							                                                        ),
							                                                    ),
                                                                                                'delete' => array
                                                                                                            (
                                                                                                                'label'=>'Hapus',
                                                                                                                'icon'=>'fa fa-trash-o',
                                                                                                                'options'=>array(
                                                                                                                    'class'=>'btn btn-danger btn-xs',
                                                                                                                ),
                                                                                                            ),
							                                    ),
							            ),
	),
)); ?>
<!-- content -->
                    </div>                                        
                    
                    <div class="clearfix"></div>
                    
                </div>
                
                
                
            </div>
        </div>
        
    </div>
</div>

<!-- modal update -->
<div class="modal fade" id="modal-setoran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Setoran</h4>
        </div>
        <div class="modal-body">        
           <br>    
         
        </div>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>-->
        </div>

      </div>
    </div>
  </div>
<!--  -->

<!-- modal update -->
<div class="modal fade" id="modal-faktur" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Transfer Faktur</h4>
        </div>
        <div class="modal-body">        
           <br>    
         
        </div>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>-->
        </div>

      </div>
    </div>
  </div>
<!--  -->

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Transfermasuk_tanggal').datepicker();
}
");
?>

<script>
    
    function popUpNoFaktur()
    {
        
        var pelanggan = $('#pelanggan option:selected').val();
        if(pelanggan!="")
        {
                $.ajax({
                        //dataType:'json',
                        type: 'POST',      
                        beforeSend: function() {
                           $("#AjaxLoader").show();
                        },
                        url: '<?php echo Yii::app()->baseurl; ?>/admin/transfermasuk/popupfaktur',
                        data: 'pelangganid='+pelanggan,//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                        success: function (data) {
                                $("#AjaxLoader").hide();                                                            
							   $("#AjaxLoader").hide();							                                                            									                                                            								                                                        
								$("#modal-faktur .modal-body").html(data); 
								$("#modal-faktur").modal();                                                                                                                               
                        },
                        error: function () {
                            //alert("Error occured. Please try again (hitungHargaBarang)");
                        }

                });
        }
        else
        {
            alert('isikan penyetor');
        }                
    }
    
    function getNoRekening(txt)
    {
        $.ajax({
                    dataType:'json',
                    type: 'POST',      
                    beforeSend: function() {
                        // setting a timeout
                        //document.getElementById("loadingProses").style.visibility = "visible";
                    },
                    url: '<?php echo Yii::app()->baseurl; ?>/admin/transfermasuk/getnorekening',
                    data: 'id='+txt.value,//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                    success: function (data) {
                            //document.getElementById("loadingProses").style.visibility = "hidden";                                
                            if(data.result=='OK')
                            {
                                $('#norekening').val(data.rekening);
                                if(data.saldo==null)
                                {
                                    $('#saldo').val(0);
                                    $('#saldoinput').val(0);
                                }       
                                else
                                {
                                    $('#saldo').val(data.saldo);
                                    $('#saldoinput').val(data.saldo);
                                }    
                                
                            }    
                            //location.href='<?php //echo Yii::app()->baseurl ?>/admin/crudbarang';                                                                                
                    },
                    error: function () {
                        //alert("Error occured. Please try again (hitungHargaBarang)");
                    }
        
        });
    }
    
    function nilaiTransaksi(txt)
    {
        var total = txt.value;
        $('#nilaitransaksiinput').val(total);
        $('#sisatransaksi').val(total);
        $('#sisatransaksiinput').val(total);
        
        if($('#saldoinput').val()!="")
        {
            var totalAll = parseInt($('#saldoinput').val())+parseInt(total);
        }    
        else
        {
            var totalAll = parseInt(0)+parseInt(total);
        }    
        
        
        $('#totalsaldo').val(totalAll);
        $('#totalsaldoinput').val(totalAll);
    }    
	
	function getNorekUpdate(txt)
	{
		$.ajax({
                    dataType:'json',
                    type: 'POST',                        
                    url: '<?php echo Yii::app()->baseurl; ?>/admin/transfermasuk/getnorekening',
                    data: 'id='+txt,//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                    success: function (data) {
                            //document.getElementById("loadingProses").style.visibility = "hidden";                                
                            if(data.result=='OK')
                            {
                                $('#norekening').val(data.rekening);
                                if(data.saldo==null)
                                {
                                    $('#saldo').val(0);
                                    $('#saldoinput').val(0);
                                }       
                                else
                                {
                                    $('#saldo').val(data.saldo);
                                    $('#saldoinput').val(data.saldo);
                                }    
                                
                            }    
                            //location.href='<?php //echo Yii::app()->baseurl ?>/admin/crudbarang';                                                                                
                    },
                    error: function () {
                        //alert("Error occured. Please try again (hitungHargaBarang)");
                    }
        
        });
	}
    
    function simpanTransfer()
    {
        if($('#tanggal').val()!="" && $("#pelanggan").val()!="" && $("#nilaitransaksi").val()!="")
        {            
            
            var data =$("#form1, #form2").serialize();
            $.ajax({
                    dataType:'json',
                    type: 'POST',      
                    beforeSend: function() {
                        // setting a timeout
                        document.getElementById("loadingProses").style.visibility = "visible";
                    },
                    url: '<?php echo Yii::app()->baseurl; ?>/admin/transfermasuk/simpan',
                    data: data,//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                    success: function (data) {                            
                            document.getElementById("loadingProses").style.visibility = "hidden";                                
                           if(data.result=='OK')
                           {
                               location.href='<?php echo Yii::app()->baseurl ?>/admin/transfermasuk';                                                                                
                           }                                
                    },
                    error: function () {
                        //alert("Error occured. Please try again (hitungHargaBarang)");
                    }
        
            });
            
        }    
        else
        {
            alert("Isikan Semua Isian Form");
        }    
    }    
</script>    
