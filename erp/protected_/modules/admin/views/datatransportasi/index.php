<br><?php

$this->pageTitle = 'Admin - Data Transportasi';

$this->breadcrumbs=array(
	'Admin',
        'Data Transportasi'
);
?>



<div class="row">
    <div class="col-lg-12">
    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab-1" data-toggle="tab" aria-expanded="true"> Data Kota</a>
            </li>
            <li class="">
                <a href="#tab-2" data-toggle="tab" aria-expanded="false">Data Mobil</a>
            </li>
            <li class="">
                <a href="#tab-3" data-toggle="tab" aria-expanded="false">Data Biaya</a>
            </li>
            <li class="">
                <a href="#tab-4" data-toggle="tab" aria-expanded="false">Data Perjalanan</a>
            </li>
        </ul>
        
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                <?php $this->widget('booster.widgets.TbGridView', array(
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$modelKota->search(),
                            'filter'=>$modelKota,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),                                                                        
                                    array('name'=>'kode','value'=>'$data->kode','header'=>'Kode'),                           
                                    array('name'=>'nama','value'=>'$data->nama','header'=>'Kota'),                                        
                                    array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                         'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("admin/kota/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            									                                                            									                                                            								                                                        
							                                                            		$("#modal-kota .modal-body").html(data); 
							                                                            		$("#modal-kota").modal();
							                                                            		
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
    <!-- popup kota -->                
    <div class="modal fade bs-example-modal-sm" id="modal-kota" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Update Data Kota</h4>
            </div>
            <div class="modal-body">
               <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/gentelala/images/ajax-loader.gif"></img></div>
               <br>
             
            </div>
            <div class="modal-footer">
              <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
              <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>

          </div>
        </div>
  </div>
            </div>                      
        </div>
    <div id="tab-2" class="tab-pane">
        <div class="panel-body">
           <?php $this->widget('booster.widgets.TbGridView', array(
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$modelKendaraan->search(),
                            'filter'=>$modelKendaraan,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),                                                                        
                                    array('name'=>'jenis','value'=>'$data->jenis','header'=>'Jenis'),                           
                                    array('name'=>'nama','value'=>'$data->nama','header'=>'Kota'),                                        
                                    array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("keuangan/dataperjalanan/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            									                                                            									                                                            								                                                        
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
                    )); ?>
        </div>
        
        
       <div class="panel-body">                      
            <?php     
                  
            ?>
            
            </div>
        
    </div>
    <div id="tab-3" class="tab-pane">
        <div class="panel-body">
            <?php $this->widget('booster.widgets.TbGridView', array(
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$modelBiaya->search(),
                            'filter'=>$modelBiaya,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),                                                               
                                    array('name'=>'kendaraanid','value'=>function($data,$row){
                                        return Kendaraan::model()->findByPk($data->kendaraanid)->nama;
                                    },'header'=>'Kendaraan'),
                                    array('name'=>'upah','value'=>'number_format($data->upah)','header'=>'Jenis'),                           
                                    array('name'=>'nama','value'=>'$data->nama','header'=>'Kendaraan'),                                        
                                    array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("keuangan/dataperjalanan/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            									                                                            									                                                            								                                                        
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
                    )); ?>
        </div>
    </div>
        
    <div id="tab-4" class="tab-pane">
        <div class="panel-body">            
             <?php $this->widget('booster.widgets.TbGridView', array(
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$modelDataPerjalanan->listPerjalanan(),
                            'filter'=>$modelDataPerjalanan,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),                                     
                                    array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal Perjalanan',
                                            'filter'=>  $this->widget(
                                                'booster.widgets.TbDatePicker',
                                                array(
                                                    'name' => 'Dataperjalanan[tanggal]',
                                                    'attribute' => 'tanggal',
                                                    'htmlOptions' => array(
                                                                        'id' => 'Dataperjalanan_tanggal_index',
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
                                    array('name'=>'karyawan','value'=>'$data->karyawan','header'=>'Karyawan'),                           
                                    array('name'=>'nik','value'=>'$data->nik','header'=>'NIK'),     
                                    array('name'=>'tujuan','value'=>'$data->tujuan','header'=>'Tujuan'),     
                                    array('name'=>'kendaraan','value'=>'$data->kendaraan','header'=>'Kendaraan'),     
                                    array('name'=>'bbm','value'=>'number_format($data->bbm)','header'=>'BBM'),     
                                    array('name'=>'upah','value'=>'number_format($data->upah)','header'=>'Upah'),     		
                                    array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("keuangan/dataperjalanan/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            									                                                            									                                                            								                                                        
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
                    )); ?>  
        </div>
    </div>    
    </div>
    </div>
</div>
<div class="col-lg-6">
</div>
</div>