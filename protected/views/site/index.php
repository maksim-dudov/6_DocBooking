<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php
foreach($doctors as $item) {
	echo $item->fio.' '.$item->speciality->title.'<br />';
}

?>