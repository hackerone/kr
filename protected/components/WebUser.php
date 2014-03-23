<?php

/**
* WebUser
* @id
*/
class WebUser extends CWebUser
{

  private $_model;

  public function model()
  {
    if(!$this->_model)
      $this->_model = User::model()->findByAttributes(['username' => $this->id]);
    return $this->_model;
  }

  public function getRight()
  {
    $user = $this->model();
    return @$user->rightName;
  }

  public function getProfile()
  {
    $user = $this->model();
    return @['user/edit', 'id' => $user->id];
  }

  public function getIsAdmin()
  {
    $user = $this->model();

    return $user && $user->rights == 1;
  }

  public function getModelId()
  {

    $user = $this->model();

    return $user ? $user->id : null;
    
  }
}