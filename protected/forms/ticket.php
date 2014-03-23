<?php

$form = [
  'title' => ($this->model->id ? 'Edit' : 'Assign') .' Ticket',
  'showErrors' => true,
  'elements' => [
    'external_id' => ['type' => 'text'],
    'status' => ['type' => 'dropdownlist', 'items' => $this->model->states , 'readonly' => 'readonly'],
    'checklist_id' => ['type' => 'dropdownlist', 'items' => Checklist::model()->listAll(), 'prompt' => 'select checklist', 'ajax' => [
      'type' => 'POST',
      'url' => ['ticket/list'],
      'update' => '.field_ticketChecks'
    ]],
    'ticketChecks' => ['type' => 'checkboxlist', 'items' => $this->model->listChecks()],
  ],

   'buttons'=>array(
        'submit'=>array(
            'type'=>'submit',
            'label'=>'Save',
            'class' => 'btn btn-success'
        ),
    ),
];



return $form;