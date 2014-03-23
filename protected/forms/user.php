<?php

$form = [
  'title' => ($this->model->id ? 'Edit' : 'Add') .' User',
  'showErrors' => true,
  'elements' => [
    'username' => ['type' => 'text'],
    'password' => ['type' => 'password'],
    'rights' => ['type' => 'dropdownlist', 'items' => $this->model->types ]
  ],

   'buttons'=>array(
        'submit'=>array(
            'type'=>'submit',
            'label'=>'Save',
            'class' => 'btn btn-success'
        ),
    ),
];

if($this->model->id)
  $form['elements']['username']['readonly'] = true;

return $form;