<?php

class ChecklistController extends Controller
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

	public function actionAdd()
	{
		
		$model = new Checklist('Add');
		$this->_renderForm($model);
	}

	public function actionEdit($id)
	{
		
		$model = Checklist::model()->findByPk($id); // gets user from table
		$this->_renderForm($model);
	}

	public function actionDelete($id)
	{
		Checklist::model()->updateByPk($id, ['status' => 0]);
		$this->redirect(['checklist/index']);
	}

	public function _renderForm($model)
	{
			
			$form = new GForm('application.forms.checklist', $model);

			if(isset($_POST['Checklist'])){
				$model->attributes = $_POST['Checklist'];

				if($model->save()){
					Yii::app()->user->setFlash('success', "Saved successfully");
					$this->redirect(['checklist/edit', 'id' => $model->id]);
				}else{
					Yii::app()->user->setFlash('danger', "Error saving Form");
				}
			}

			$this->render('edit', ['model' => $model, 'form' => $form ]);
	}

	public function actionIndex()
	{
		$model = new Checklist('search');

		if(isset($_POST['Checklist'])){
			$model->attributes = $_POST['Checklist'];
		}

		$this->render('index', ['model' => $model]);
	}

}