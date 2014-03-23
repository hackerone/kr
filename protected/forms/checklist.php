<?php

$form = [
  'title' => ($this->model->id ? 'Edit' : 'Add') .' Checklist',
  'showErrors' => true,
  'elements' => [
    'name' => ['type' => 'text'],
    'content' => ['type' => 'text'],
    'status' => ['type' => 'dropdownlist', 'items' => $this->model->states ]
  ],

   'buttons'=>array(
        'submit'=>array(
            'type'=>'submit',
            'label'=>'Save',
            'class' => 'btn btn-success'
        ),
    ),
];

if($this->model->id){
  $form['buttons']['addItem'] = CHtml::link('Add Item', ['checkitem/add', 'list' => $this->model->id], ['class' => 'btn btn-primary']);
}
  

return $form;