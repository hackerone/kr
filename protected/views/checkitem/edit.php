<?php
/* @var $this ChecklistController */

$this->breadcrumbs=array(
  'Checklist'=>array('/checklist'),
  $model->list->name => array('checklist/edit', 'id' => $model->list_id),
  'Item',
);
?>
<?= $form ?>