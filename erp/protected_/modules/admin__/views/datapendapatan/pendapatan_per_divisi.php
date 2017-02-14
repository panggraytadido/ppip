<?php
$this->pageTitle = "Data Pendapatan - Admin";

$this->breadcrumbs=array(
	'Admin',
        'Data Pendapatan' => array('penjualanbarang/index'),        
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
                       
                        <b>Admin - Data Pendapatan Per Divisi</b>                       
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
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
                                <label class="col-sm-5 control-label">Divisi</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo Divisi::model()->findByPk($divisi)->nama; ?>" />
                                    <input type="hidden" name="divisi" class="form-control" value="<?php echo Divisi::model()->findByPk($divisi)->nama; ?>" />
                                    <input type="hidden" name="divisiid" class="form-control" value="<?php echo Divisi::model()->findByPk($divisi)->id; ?>" />
                                </div>
                            </div>
                           
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Tanggal</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo date("d-m-Y", strtotime($tanggal)); ?>" />
                                    <input type="hidden" name="tanggal" class="form-control" value=<?php echo $tanggal; ?> />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Penjualan</label>
                                <div class="col-sm-7">
                                    <?php 
                                       
                                            $data = Datapendapatan::model()->getTotalPenjualanPerDivisi($tanggal,$divisi);                                                 
                                            if(count($data)!=0)
                                            {
                                                
                                    ?>
                                                <input type="hidden" name="totalpenjualan" class="form-control" value=<?php echo $data; ?> />
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                    <?php
                                            }
                                            else
                                            {
                                                //echo number_format($data);
                                    ?>
                                                <input type="hidden" name="totalpenjualan" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php                                                            
                                            }
                                                 
                                    ?>                                
                                </div>
                            </div>
                            
                            <div class="form-group">
                            <label class="col-sm-5 control-label">Total Modal</label>
                            <div class="col-sm-7">
                                <?php 
                                    
                                        $data = Datapendapatan::model()->getTotalModalPerDivisi($tanggal,$divisi);                                                 
                                        if(count($data)!=0)
                                        {

                                ?>
                                            <input type="hidden" name="totalmodal" class="form-control" value=<?php echo $data; ?> />
                                            <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                <?php
                                        }
                                        else
                                        {
                                            //echo number_format($data);
                                ?>
                                            <input type="hidden" name="totalmodal" class="form-control" value="0" />  
                                            <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                <?php                                                            
                                        }
                                 ?>
                            </div>
                        </div>                                                                                                                                                                                                        
                            
                        </form>
                        <span id="loadingProses" style="visibility: hidden; margin-top: -5px;">
                            <h5><b>Silahkan Tunggu Proses ...</b></h5>
                            <div class="progress progress-striped active">
                                <div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar progress-bar-danger">
                                    <span class="sr-only">Silahkan Tunggu..</span>
                                </div>
                            </div>
                        </span>    
                        <button class="btn btn-primary" onclick="simpan();">Simpan</button>

                    </div>
                    
                    <div class="col-lg-6">                                  
                        <form class="form-horizontal" id="form2">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Laba</label>
                                <div class="col-sm-7">
                                    <?php 
                                        if($pilihan==1)
                                        {
                                            $data = Datapendapatan::model()->getTotalLabaPerDivisi($tanggal,$divisi);                                                 
                                            if(count($data)!=0)
                                            {
                                                
                                    ?>
                                                <input type="hidden" name="totallaba" class="form-control" value=<?php echo $data; ?> />
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                    <?php
                                            }
                                            else
                                            {                                                
                                    ?>
                                                <input type="hidden" name="totallaba" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php                                                            
                                            }
                                        }                                                 
                                    ?>
                                                
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Pengeluaran</label>                                
                                <div class="col-sm-7">
                                    <?php                                         
                                            $data = Datapendapatan::model()->getTotalPengeluaranPerDivisi($tanggal,$divisi);                                                 
                                            if(count($data)!=0)
                                            {
                                                
                                    ?>
                                                <input type="hidden" name="totalpengeluaran" class="form-control" value=<?php echo $data; ?> />
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                    <?php
                                            }
                                            else
                                            {                                                
                                    ?>
                                                <input type="hidden" name="totalpengeluaran" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php                                                            
                                            }
                                       
                                    ?>                                              
                                </div>
                            </div>                                                       
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Laba/Rugi</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo '0';//echo date("d-m-Y", strtotime($data->tanggal)); ?>" />
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
                       
                        <b>Admin - Data Pendapatan Per Divisi</b>
                       
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
                                    'dataProvider'=>$model->search(),
                                    'filter'=>$model,                                    
                                    'columns'=>array(
                                            array(
                                                'header'=>'No',
                                                'class'=>'IndexColumn',
                                                'htmlOptions'=>array('width'=>'5px'),
                                            ),                                                                                                                                                      
                                            array('name'=>'namadivisi','value'=>'$data->namadivisi','header'=>'Divisi'), 
                                            array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal','filter'=>FALSE),
                                            array('name'=>'totalpenjualan','value'=>'number_format($data->totalpenjualan)','header'=>'Total Penjualan','filter'=>FALSE),
                                            array('name'=>'totalmodal','value'=>'number_format($data->totalmodal)','header'=>'Total Modal','filter'=>FALSE),
                                            array('name'=>'totallaba','value'=>'number_format($data->totallaba)','header'=>'Total Laba','filter'=>FALSE),
                                            array('name'=>'totalpengeluaran','value'=>'number_format($data->totallaba)','header'=>'Total Pengeluaran','filter'=>FALSE),
                                        array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{delete}',
							                                    'buttons' => array(
							                                       
                                                                                                'delete' => array
                                                                                                            (
                                                                                                                'url'=>'Yii::app()->createUrl("datapendapatan/deleteperdivisi", array("id"=>$data->id))',
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
        //alert(data);
        
        $.ajax({
                    dataType:'json',
                    type: 'POST',      
                    beforeSend: function() {
                        // setting a timeout
                        document.getElementById("loadingProses").style.visibility = "visible";
                    },
                    url: '<?php echo Yii::app()->baseurl; ?>/admin/datapendapatan/simpanperdivisi',
                    data: data,//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                    success: function (data) {
                            document.getElementById("loadingProses").style.visibility = "hidden";                                
                            if(data.result=='OK')
                            {
                                location.href="<?php echo Yii::app()->baseurl ?>/admin/datapendapatan/view/pilihan/<?php echo $pilihan ?>/divisi/<?php echo $divisi ?>/tanggal/<?php echo $tanggal ?>";    
                            }    
                            //location.href='<?php echo Yii::app()->baseurl ?>/admin/crudbarang';                                                                                
                    },
                    error: function () {
                        //alert("Error occured. Please try again (hitungHargaBarang)");
                    }
            });
            
    }
</script>    
