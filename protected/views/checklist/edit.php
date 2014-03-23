<?php
/* @var $this ChecklistController */

$this->breadcrumbs=array(
	'Checklist'=>array('/checklist'),
	'Edit',
);
?>
<?= $form ?>

<?php
if($model->id):
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->itemModel->search(),
    'itemsCssClass' => 'table',
    'columns' => [
      'content',
      [
        'class' => 'CButtonColumn',
        'template' => '{edit} {del}',
        'buttons' => [
          'edit' => [
            'label' => 'Edit',
            'url' => '["checkitem/edit", "id" => $data->id]'
          ],
          'del' => [
            'label' => 'Delete',
            'url' => '["checkitem/delete", "id" => $data->id]'
          ]
        ]
      ]
    ]
));
endif;

?>