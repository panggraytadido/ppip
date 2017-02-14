<?php
$this->pageTitle = "Admin - Data Setoran";

$this->breadcrumbs=array(
	'Admin',
        'Data Setoran',        
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

<div class="col-lg-12">
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">                
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">                    
                             <b>Admin - Data Setoran</b>                      
                    </div>
                    <div class="pull-right">
                        
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
                            <?php
                                
                                   $this->widget('booster.widgets.TbGridView', array(
                                    'id'=>'grid',
                                    'type' => 'striped bordered hover',
                                    'dataProvider'=>$model->listDataSetoran(),
                                    'filter'=>$model,                                    
                                    'columns'=>array(
                                            array(
                                                'header'=>'No',
                                                'class'=>'IndexColumn',
                                                'htmlOptions'=>array('width'=>'5px'),
                                            ),
                                            array('name'=>'pelanggan','value'=>'$data->pelanggan','header'=>'Pelanggan'),
                                            array('name'=>'nofaktur','value'=>'$data->nofaktur','header'=>'No Faktur'),    
                                            array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal','filter'=>FALSE),                                                                                                                            
                                            array('name'=>'hargatotal','value'=>'number_format($data->hargatotal)','header'=>'Total Belanja','filter'=>FALSE),
                                            array('name'=>'bayar','value'=>'number_format($data->bayar)','header'=>'Total Bayar','filter'=>FALSE),
                                            array('name'=>'sisa','value'=>'number_format($data->sisa)','header'=>'Sisa Tagihan','filter'=>FALSE),                                            
                                            array('name'=>'diskon','value'=>'number_format($data->diskon)','header'=>'Diskon','filter'=>FALSE),                                              
                                        array(
                                                'header'=>'Action',
                                                'class'=>'booster.widgets.TbButtonColumn',
                                                'template'=>'{update} {delete}',
                                                                'buttons' => array(	
                                                                    'update' =>   array(
							                                                       'label'=>'Setor',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("keuangan/datakasbon/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
                                                                                                                        'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
							                                                                'success'=>'function(data) {
							                                                            		$("#AjaxLoader").hide();							                                                            									                                                            								                                                        
							                                                            		$("#modal-update .modal-body").html(data); 
							                                                            		$("#modal-update").modal();
							                                                            		
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
                                    
    
                    )); 
                            ?>
                    </div>                                        
                    
                    <div class="clearfix"></div>
                    
                </div>
                
                
                
            </div>
        </div>
        
    </div>
</div>


<script>
    function simpan()
    {
        var data = $('#form1,#form2').serialize();   
        $.ajax({
                    dataType:'json',
                    type: 'POST',      
                    beforeSend: function() {
                        // setting a timeout
                        document.getElementById("loadingProses").style.visibility = "visible";
                    },
                    url: '<?php echo Yii::app()->baseurl; ?>/admin/datapendapatan/simpanalldivisi',
                    data: data,//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                    success: function (data) {
                            document.getElementById("loadingProses").style.visibility = "hidden";                                
                            
                            //location.href='<?php echo Yii::app()->baseurl ?>/admin/crudbarang';                                                                                
                    },
                    error: function () {
                        //alert("Error occured. Please try again (hitungHargaBarang)");
                    }
            });
        
    }
</script>    
