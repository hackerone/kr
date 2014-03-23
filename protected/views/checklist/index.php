<?php
/* @var $this UserController */

$this->breadcrumbs=array(
  'Check List'=>['checklist/index']
);


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
    'itemsCssClass' => 'table',
    'columns' => [
      'name',
      'created_date',
      [
        'class' => 'CButtonColumn',
        'template' => '{edit} {del}',
        'buttons' => [
          'edit' => [
            'label' => 'Edit',
            'url' => '["checklist/edit", "id" => $data->id]'
          ],
          'del' => [
            'label' => 'Delete',
            'url' => '["checklist/delete", "id" => $data->id]'
          ]
        ]
      ]
    ]
));
echo CHtml::link('Add Checklist', ['checklist/add'], ['class' => 'btn btn-primary']);
?>