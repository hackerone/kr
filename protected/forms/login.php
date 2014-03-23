<?php

return [
  'title' => 'Login',
  'showErrors' => true,
  'elements' => [
    'username' => ['type' => 'text'],
    'password' => ['type' => 'password'],
  ],

   'buttons'=>array(
        'submit'=>array(
            'type'=>'submit',
            'label'=>'Login',
            'class' => 'btn btn-success'
        ),
    ),
];