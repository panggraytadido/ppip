<?php
$this->pageTitle = "Data Pendapatan - Admin";

$this->breadcrumbs=array(
	'Admin',
        'Data Pendapatan' => array('penjualanbarang/index'),        
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                        <?php 
                            if($pilihan==1)
                            {
                        ?>
                        <b>Admin - Data Pendapatan Per Divisi</b>
                        <?php 
                            }
                            else
                            {   
                        ?>
                             <b>Admin - Data Pendapatan All Divisi</b>
                        <?php 
                            }
                        ?>
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

                            <?php 
                                if($pilihan==1)
                                {
                            ?>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Divisi</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo Divisi::model()->findByPk($divisi)->nama; ?>" />
                                    <input type="hidden" name="divisi" class="form-control" value="<?php echo Divisi::model()->findByPk($divisi)->nama; ?>" />
                                </div>
                            </div>
                            <?php 
                                }
                            ?>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Tanggal</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo date("d-m-Y", strtotime($tanggal)); ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Penjualan</label>
                                <div class="col-sm-7">
                                    <?php 
                                        if($pilihan==1)
                                        {
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
                                        }         
                                        elseif($pilihan==2)
                                        {
                                            $data = Datapendapatan::getTotalPenjualanAllDivisi($tanggal);
                                            if(count($data)!=0)
                                            {
                                    ?>
                                                <input type="hidden" name="totalpenjualan" class="form-control" value=<?php echo $data; ?> />
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                    <?php
                                            }
                                            else
                                            {
                                    ?>
                                                <input type="hidden" name="totalpenjualan" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php            
                                                
                                            }
                                        }
                                    ?>                                    
                                </div>
                            </div>
                            
                            <div class="form-group">
                            <label class="col-sm-5 control-label">Total Modal</label>
                            <div class="col-sm-7">
                                <?php 
                                    if($pilihan==1)
                                    {
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
                                    }         
                                    elseif($pilihan==2)
                                    {
                                        $data = Datapendapatan::getTotalModalAllDivisi($tanggal);
                                        if(count($data)!=0)
                                        {
                                ?>
                                            <input type="hidden" name="totalmodal" class="form-control" value=<?php echo $data; ?> />
                                            <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                <?php
                                        }
                                        else
                                        {
                                ?>
                                            <input type="hidden" name="totalmodal" class="form-control" value="0" />  
                                            <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                <?php            

                                        }
                                    }
                                ?>
                            </div>
                        </div>       
                            
                                                                                                             
                            
                            <button class="btn btn-primary" onclick="simpan();">Simpan</button>
                            
                        </form>

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
                                                //echo number_format($data);
                                    ?>
                                                <input type="hidden" name="totallaba" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php                                                            
                                            }
                                        }         
                                        elseif($pilihan==2)
                                        {
                                            $data = Datapendapatan::getTotalLabaAllDivisi($tanggal);
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
                                        if($pilihan==1)
                                        {
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
                                                //echo number_format($data);
                                    ?>
                                                <input type="hidden" name="totalpengeluaran" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php                                                            
                                            }
                                        }         
                                        elseif($pilihan==2)
                                        {
                                            $data = Datapendapatan::getTotalPengeluaranAllDivisi($tanggal);
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
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <?php 
                                if($pilihan==2)
                                {                                    
                            ?>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Discont</label>
                                <div class="col-sm-7">
                                    <?php 
                                        if($pilihan==1)
                                        {
                                            $data = Datapendapatan::model()->getTotalDiskonPerDivisi($tanggal,$divisi);                                                 
                                            if(count($data)!=0)
                                            {
                                                
                                    ?>
                                                <input type="hidden" name="totaldiskon" class="form-control" value=<?php echo $data; ?> />
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                    <?php
                                            }
                                            else
                                            {
                                               
                                    ?>
                                                <input type="hidden" name="totaldiskon" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php                                                            
                                            }
                                        }         
                                        elseif($pilihan==2)
                                        {
                                            $data = Datapendapatan::getTotalDiskonAllDivisi($tanggal);
                                            if(count($data)!=0)
                                            {
                                    ?>
                                                <input type="hidden" name="totaldiskon" class="form-control" value=<?php echo $data; ?> />
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                    <?php
                                            }
                                            else
                                            {
                                    ?>
                                                <input type="hidden" name="totaldiskon" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php            
                                                
                                            }
                                        }
                                    ?>
                                </div>
                                
                                
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Piutang</label>
                                <div class="col-sm-7">
                                    <?php 
                                        if($pilihan==1)
                                        {
                                            $data = Datapendapatan::model()->getTotalPiutangPerDivisi($tanggal,$divisi);                                                 
                                            if(count($data)!=0)
                                            {
                                                
                                    ?>
                                                <input type="hidden" name="totalpiutang" class="form-control" value=<?php echo $data; ?> />
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                    <?php
                                            }
                                            else
                                            {
                                                //echo number_format($data);
                                    ?>
                                                <input type="hidden" name="totalpiutang" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php                                                            
                                            }
                                        }         
                                        elseif($pilihan==2)
                                        {
                                            $data = Datapendapatan::getTotalPiutangAllDivisi($tanggal);
                                            if(count($data)!=0)
                                            {
                                    ?>
                                                <input type="hidden" name="totalpiutang" class="form-control" value=<?php echo $data; ?> />
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo number_format($data); ?> />
                                    <?php
                                            }
                                            else
                                            {
                                    ?>
                                                <input type="hidden" name="totalpiutang" class="form-control" value="0" />  
                                                <input type="text" class="form-control" readonly="readonly" value=<?php echo '-'; ?> />
                                    <?php            
                                                
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php 
                                }
                            ?>
                            
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
                        <?php 
                            if($pilihan==1)
                            {
                        ?>
                        <b>Admin - Data Pendapatan Per Divisi</b>
                        <?php 
                            }
                            else
                            {   
                        ?>
                             <b>Admin - Data Pendapatan All Divisi</b>
                        <?php 
                            }
                        ?>
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
                                if($pilihan==1)
                                {
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
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("admin/crudbarang/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            									                                                            									                                                            								                                                        
							                                                            		$("#modal-update-bagian .modal-body").html(data); 
							                                                            		$("#modal-update-bagian").modal();
							                                                            		
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

                   
                                }
                                else
                                {
                                    
                                }
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
     alert(data);
    }
</script>    
