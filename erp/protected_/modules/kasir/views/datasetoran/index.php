<?php
$this->pageTitle = "Data Setoran - Kasir";

$this->breadcrumbs=array(
	'Kasir','Data Setoran'
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
                        <b>Kasir - Data Setoran</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                <div class="ibox-content">      
        <?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->listDataSetoran(),
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
            				'url' =>$this->createUrl("datasetoran/childdatasetoran"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
            }'
            		),
                array('name'=>'nofaktur','value'=>function($data,$row)
                    {
                        $pelangganid = substr($data->id, 10,4);                               
                        $tanggal = substr($data->id, 0,10);                                                  
                        
                        $cekFaktur = Faktur::model()->find("tanggal='$tanggal' AND pelangganid=".$pelangganid);
						 if(count($cekFaktur)!=0)
                        {
                            return $cekFaktur->nofaktur;
                        }
                        else
                        {
                            return '-';
                        }
						
                    },'header'=>'No Faktur','filter'=>false),	        
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Datasetoran[tanggal]',
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
                    'value'=>function($data,$row){
                        
                        $pelangganid = substr($data->id, 10,4);                               
                        $tanggal = substr($data->id, 0,10);                                                  
                        
                        $cekFaktur = Faktur::model()->find("tanggal='$tanggal' AND pelangganid=".$pelangganid." AND jenispembayaran='kredit'");
                        if(count($cekFaktur)!=0)
                        {
                            return number_format($cekFaktur->hargatotal);
                        }
                        else
                        {
                            return 0;
                        }                        
                    },
                    'filter'=>false
                ),
                array(
                    'header'=>'Total Bayar',                   
                    'name'=>'totalbayar',
                    'value'=>function($data,$row){
                        
                        $pelangganid = substr($data->id, 10,4);                               
                        $tanggal = substr($data->id, 0,10);                                                  
                        
                        $cekFaktur = Faktur::model()->find("tanggal='$tanggal' AND pelangganid=".$pelangganid." AND jenispembayaran='kredit'");
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
                        $cekFaktur = Faktur::model()->find("tanggal='$tanggal' AND pelangganid=".$pelangganid." AND jenispembayaran='kredit'");
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
                    'header'=>'Diskon',                   
                    'name'=>'diskon',
                    'value'=>function($data,$row){
                        
                        $pelangganid = substr($data->id, 10,4);                               
                        $tanggal = substr($data->id, 0,10);                           
                        $cekFaktur = Faktur::model()->find("tanggal='$tanggal' AND pelangganid=".$pelangganid." AND jenispembayaran='kredit'");
                         if(count($cekFaktur)!=0)
                         {
                             return number_format($cekFaktur->diskon);
                         }
                         else 
                         {
                             return '0';
                         }    
                    },
                    'filter'=>false
                ),        
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{setor} {print} {delete}',
							                                    'buttons' => array(
							                                        'setor' =>   array(
							                                                       'label'=>'Setor',
                                                                                                               'icon'=>'fa fa-pencil',
                                                                                                                'url'=>'Yii::app()->createUrl("kasir/datasetoran/popupsetor", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
																							'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
							                                                                'success'=>'function(data) {
							                                                            		$("#AjaxLoader").hide();							                                                            									                                                            								                                                        
							                                                            		$("#modal-setoran .modal-body").html(data); 
							                                                            		$("#modal-setoran").modal();
							                                                            		
																								}'
							                                                            ),
							                                                        ),
							                                                    ),
                                                                                                'print' =>   array(
							                                                       'label'=>'Print Faktur',
                                                                                                               'icon'=>'fa fa-print',
                                                                                                               // 'url'=>'Yii::app()->createUrl("kasir/datasetoran/cetak", array("id"=>$data->id))', 
                                                                                                               'url'=>'Yii::app()->createUrl("kasir/datasetoran/checkformfaktur", array("id"=>$data->id))', 
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',							                                                           
                                                                                                                    //'onclick'=>'cetakFaktur(this);',
                                                                                                                    'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
                                                                                                                        'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
							                                                                'success'=>'js:function(data) {
                                                                                                                            $("#AjaxLoader").hide();
                                                                                                                            if(data.status=="true")
                                                                                                                            {
                                                                                                                                //location.href="'.Yii::app()->baseurl.'/kasir/datasetoran/printfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal+"/pembelianke/" +data.pembelianke;
																																window.open("'.Yii::app()->baseurl.'/kasir/datasetoran/printfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal+"/pembelianke/" +data.pembelianke, "_blank");  
                                                                                                                            }
                                                                                                                            else 
                                                                                                                            {
                                                                                                                                location.href="'.Yii::app()->baseurl.'/kasir/datasetoran/formfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal+"/pembelianke/" +data.pembelianke;
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
        </div>

      </div>
    </div>
  </div>
<!--  -->

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Datasetoran_tanggal').datepicker();
}
");
?>