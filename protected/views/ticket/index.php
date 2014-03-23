<?php
/* @var $this UserController */

$this->breadcrumbs=array(
  'Ticket'=>['ticket/index']
);


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
    'itemsCssClass' => 'table',
    'columns' => [
      'external_id',
      'assigned_date',
      'status',
      [
        'class' => 'CButtonColumn',
        'template' => '{edit}',
        'buttons' => [
          'edit' => [
            'label' => 'Edit',
            'url' => '["ticket/edit", "id" => $data->id]'
          ]
        ]
      ]
    ]
));
echo CHtml::link('Assign Ticket', ['ticket/add'], ['class' => 'btn btn-primary']);
?>