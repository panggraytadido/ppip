<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
    UserModule::t("Profile")=>array('profile'),
    UserModule::t("Edit"),
);
$this->menu=array(
    ((UserModule::isAdmin())
        ?array('label'=>UserModule::t('Manage Users'), 'url'=>array('/user/admin'))
        :array()),
    array('label'=>UserModule::t('List User'), 'url'=>array('/user')),
    array('label'=>UserModule::t('Profile'), 'url'=>array('/user/profile')),
    array('label'=>UserModule::t('Change password'), 'url'=>array('changepassword')),
    array('label'=>UserModule::t('Logout'), 'url'=>array('/user/logout')),
);
?>
<!--
<h1><?php echo UserModule::t('Edit profile'); ?></h1>
-->
<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
    </div>
<?php endif; ?>

<aside class="profile-nav col-lg-3">
    <section class="panel">
        <div class="user-heading round">
            <a href="#">
                <?php $this->widget('ext.yiiuserimg.YiiUserImg', array(
                    'htmlOptions'=>array(
                        'style' => 'width: 80px; height: 80px;',//you have other options too; look in the php file.
                    )
                )); ?>
            </a>
            <h1><?php echo CHtml::encode($model->username); ?></h1>
            <p><?php echo CHtml::encode($model->email); ?></p>
        </div>

    </section>
</aside>
<div class="form">
    <?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'=>'profile-form',
        'enableAjaxValidation'=>true,
        'type' => 'horizontal',
        'htmlOptions' => array('enctype'=>'multipart/form-data'),
    )); ?>

    <aside class="profile-info col-lg-9">
        <section class="panel"><?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
                <div class="panel-body">
                <div class="alert alert-success alert-block fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                    <h4>
                        <i class="fa fa-ok-sign"></i>
                        Success!
                    </h4>
                    <p><?php echo Yii::app()->user->getFlash('profileMessage'); ?></p>
                </div>
                </div><?php endif; ?>
            <div class="bio-graph-heading">
                Welcome
            </div>
            <div class="panel-body bio-graph-info">
                <h1>User Profile</h1>
                <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

                <?php echo $form->errorSummary(array($model,$profile)); ?>

                <?php echo $form->textFieldGroup($model,'email',array('wrapperHtmlOptions' => array('class' => 'col-sm-5'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-sm-2','maxlength'=>20)))); ?>
                <?php
                $profileFields=$profile->getFields();
                if ($profileFields) {
                    foreach($profileFields as $field) {
                        ?>
                        <!--<div class="row">-->
                        <?php //echo $form->labelEx($profile,$field->varname);

                        if ($widgetEdit = $field->widgetEdit($profile)) {
                            echo $widgetEdit;
                        } elseif ($field->range) {
                            echo $form->dropDownListGroup($profile,$field->varname,array('wrapperHtmlOptions' => array('class' => 'col-sm-2'),'widgetOptions'=>array('data'=>Profile::range($field->range),'htmlOptions'=>array('class'=>'col-sm-2'))));
                            //echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
                        } elseif ($field->field_type=="TEXT") {
                            echo $form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
                        } else {
                            echo $form->textFieldGroup($profile,$field->varname,array('wrapperHtmlOptions' => array('class' => 'col-sm-7'), 'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-lg-2'))));
                            //echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
                        }
                        echo $form->error($profile,$field->varname); ?>
                        <!--</div>	-->
                    <?php
                    }
                }
                ?>
                <?php echo $form->fileFieldGroup($model,'filename',array('wrapperHtmlOptions' => array('class' => 'col-sm-5'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'')))); ?>
                <div class="form-actions col-lg-offset-2 col-lg-10">
                    <?php echo CHtml::link('Back',array('/user/profile/'), array('class'=>'btn btn-primary')); ?>
                    <?php $this->widget('booster.widgets.TbButton', array(
                        'buttonType'=>'submit',
                        'context'=>'primary',
                        'label'=>$model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'),
                    )); ?>
                </div>
                <!--
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save')); ?>
	</div>
	-->
            </div>
        </section>
    </aside>

    <?php $this->endWidget(); ?>

</div><!-- form -->
