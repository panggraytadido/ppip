
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
        'type'=>'horizontal',
        'id'=>'search-form',
)); ?>
	

	
        <?php     
            echo $form->dropDownListGroup($model, 'propinsiid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                    'data' => CHtml::listData(Propinsi::model()->findAll(),'id','nama'),
                                    'htmlOptions' => array(
                                            'prompt'=>'Pilih Propinsi',										
                                    )
                            )));              
        ?>
        <?php echo $form->textFieldGroup($model, 'kode',array('wrapperHtmlOptions'=>array('class'=>'col-sm-1'))); ?> 

	<?php echo $form->textFieldGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?>                         
	
	
	<div class="form-actions" style="float:right;">			
		<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType' => 'submit',
			'context'=>'info',
			'label'=>'Search',                      
		)); ?>			
		<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType' => 'submit',
			'context'=>'default',
			'label'=>'Clear',			
		)); ?>
	</div>

<?php $this->endWidget(); ?>

