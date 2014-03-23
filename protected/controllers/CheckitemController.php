<?php

class CheckitemController extends Controller
{

  public function filters()
  {
      return [
        'userOnly + index, add, edit, delete',
        'adminOnly + index, add, edit, delete'
      ];
  }

  public function filterUserOnly($filterChain)
  {
    if(Yii::app()->user->isGuest)
      Yii::app()->user->loginRequired();
    else
      $filterChain->run();
  }


  public function filterAdminOnly($filterChain)
  {
    if(Yii::app()->user->isAdmin)
      $filterChain->run();
    else{
      $this->forward('user/unauth');
    }
  }

  public function actionAdd($list)
  {
    
    $model = new Checkitem('Add');
    $model->list_id = $list;

    $this->_renderForm($model);
  }

  public function actionEdit($id)
  { 
    $model = Checkitem::model()->findByPk($id); // gets user from table
    $this->_renderForm($model);
  }

  public function actionDelete($id)
  {
    Checkitem::model()->updateByPk($id, ['status' => 0]);
    $this->redirect(['checkitem/index']);
  }

  public function _renderForm($model)
  {
      
      $form = new GForm('application.forms.checkitem', $model);

      if(isset($_POST['Checkitem'])){
        $model->attributes = $_POST['Checkitem'];

        if($model->save()){
          Yii::app()->user->setFlash('success', "Saved successfully");
          $this->redirect(['checkitem/edit', 'id' => $model->id]);
        }else{
          Yii::app()->user->setFlash('danger', "Error saving Form");
        }
      }

      $this->render('edit', ['model' => $model, 'form' => $form ]);
  }

  public function actionIndex()
  {
    $model = new Checkitem('search');

    if(isset($_POST['Checkitem'])){
      $model->attributes = $_POST['Checkitem'];
    }

    $this->render('index', ['model' => $model]);
  }

}