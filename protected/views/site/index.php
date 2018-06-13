<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
	<h1>Запись ко врачу</h1>

<?php
if($step==1) {
	$disable_field=false;
	$disable_time=false;
}
elseif($step==2) {
	$disable_field=false;
	$disable_time=false;
}
elseif($step==3) {
	$disable_field=true;
	$disable_time=true;
}
?>

<div class="form">
	<?php $form = $this->beginWidget(
		'CActiveForm',
		array(
			'id'=>'Orders',
			'enableAjaxValidation'=>false,
			'htmlOptions'=>array(
				'enctype'=>'multipart/form-data',
			),
		)
	); ?>

	<div class="row">
		<?php echo $form->dropDownList(
			$model,
			'doctor_id',
			CHtml::listData(Doctors::getList(), 'id', 'fio'),
			array('empty' => 'Выберите врача','disabled' => $disable_field)
		);
		?>
	</div>

	<div class="row">
		<?php echo $form->dropDownList(
			$model,
			'app_type_id',
			CHtml::listData(AppTypes::getList(), 'id', 'title'),
			array('empty' => 'Выберите тип приёма','disabled' => $disable_field)
		);
		?>
	</div>

	<div class="input-append">
		<?php
		if ($step==1) {
			echo CHtml::activeLabel($model,'datetime_app',array('label'=>'Дата приёма:'));
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name' => 'datetime_app',
				'model' => $model,
				'attribute' => 'datetime_app',
				'language' => 'ru',
				'options' => array('disabled' => $disable_field)
			));
		}
		elseif($step==2) {
			echo CHtml::activeLabel($model,'datetime_app',array('label'=>'Дата приёма: '.$model->datetime_app));
			echo $form->dropDownList(
				$model,
				'datetime_app',
				$available_time,
				array('empty' => 'Выберите доступное время','disabled' => $disable_time)
			);
		}
		if($step==3) {
			echo 'Вы записаны!';
		}
		?>
	</div>

	<?php
	echo CHtml::hiddenField('Orders[step]',$step);
	?>


	<div class="row submit">
		<?php echo CHtml::submitButton('Отправить'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->