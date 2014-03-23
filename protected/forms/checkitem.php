<?php

$form = [
  'title' => ($this->model->id ? 'Edit' : 'Add') .' CheckItem',
  'showErrors' => true,
  'elements' => [
    'content' => ['type' => 'text'],
    'status' => ['type' => 'dropdownlist', 'items' => $this->model->states ]
  ],

   'buttons'=>array(
        'back' => CHtml::link('Go Back', ['checklist/edit', 'id' => $this->model->list_id], ['class' => 'btn btn-primary']),
        'submit'=>array(
            'type'=>'submit',
            'label'=>'Save',
            'class' => 'btn btn-success'
        ),
    ),
];
  

return $form;