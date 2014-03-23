<?php

class TicketController extends Controller
{

  public function filters()
  {
      return [
        'userOnly + index, add, edit, delete',
      ];
  }

  public function filterUserOnly($filterChain)
  {
    if(Yii::app()->user->isGuest)
      Yii::app()->user->loginRequired();
    else
      $filterChain->run();
  }

  public function actionAdd()
  {
    $model = new Ticket('Add');
    $this->_renderForm($model);
  }

  public function actionEdit($id)
  {
    
    $model = Ticket::model()->findByPk($id); // gets user from table
    $this->_renderForm($model);
  }

  public function actionDelete($id)
  {
    Ticket::model()->updateByPk($id, ['status' => 0]);
    $this->redirect(['ticket/index']);
  }

  public function _renderForm($model)
  {
      
      $form = new GForm('application.forms.ticket', $model);

      if(isset($_POST['Ticket'])){
        $model->attributes = $_POST['Ticket'];

        if($model->save()){
          Yii::app()->user->setFlash('success', "Saved successfully");
          $this->redirect(['ticket/edit', 'id' => $model->id]);
        }else{
          Yii::app()->user->setFlash('danger', "Error saving Form");
        }
      }

      $this->render('edit', ['model' => $model, 'form' => $form ]);
  }

  public function actionIndex()
  {
    $model = new Ticket('search');

    if(isset($_POST['Ticket'])){
      $model->attributes = $_POST['Ticket'];
    }

    $this->render('index', ['model' => $model]);
  }

  public function actionList()
  {
     $ticket = new Ticket;
     $ticket->attributes = $_POST['Ticket'];
     echo CHtml::activeCheckBoxList($ticket, "ticketChecks", $ticket->listChecks());
  }

}