<?php
/* @var $this UserController */

$this->breadcrumbs=array(
  'User'=>'User'
);


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
    'itemsCssClass' => 'table',
    'columns' => [
      'username',
      'rightName',
      [
        'class' => 'CButtonColumn',
        'template' => '{edit} {del}',
        'buttons' => [
          'edit' => [
            'label' => 'Edit',
            'url' => '["user/edit", "id" => $data->id]'
          ],
          'del' => [
            'label' => 'Delete',
            'url' => '["user/delete", "id" => $data->id]'
          ]
        ]
      ]
    ]
));
echo CHtml::link('Add User', ['user/add'], ['class' => 'btn btn-primary']);
?>