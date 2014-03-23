<?php

class UserController extends Controller
{

	public function filters()
	{
			return [
				'userOnly + index, add, edit, delete',
				'adminOnly + index, add,  delete',
				'selfOnly + edit',
			];
	}

	public function filterUserOnly($filterChain)
	{
		if(Yii::app()->user->isGuest)
			Yii::app()->user->loginRequired();
		else
			$filterChain->run();
	}

	public function filterSelfOnly($filterChain)
	{
		if(Yii::app()->user->isAdmin)
			$filterChain->run();
		else if (Yii::app()->user->modelId == $_GET['id'])
			$filterChain->run();
		else
			$this->forward('user/unauth');

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
		
		$model = new User('Register');
		$this->_renderForm($model);
	}

	public function actionEdit($id)
	{
		
		$model = User::model()->findByPk($id); // gets user from table
		$this->_renderForm($model);
	}

	public function actionDelete($id)
	{
		$user = User::model()->findByPk($id);
		$user->rights = -1;
		$user->save();
		$this->redirect(['user/index']);
	}

	public function _renderForm($model)
	{
			
			$form = new GForm('application.forms.user', $model);

			if(isset($_POST['User'])){
				$model->attributes = $_POST['User'];

				if($model->save()){
					Yii::app()->user->setFlash('success', "Saved successfully");
					$this->redirect(['user/edit', 'id' => $model->id]);
				}else{
					Yii::app()->user->setFlash('danger', "Error saving Form");
				}
			}

			$this->render('edit', ['model' => $model, 'form' => $form ]);
	}

	public function actionIndex()
	{
		$model = new User('search');

		if(isset($_POST['User'])){
			$model->attributes = $_POST['User'];
		}

		$this->render('index', ['model' => $model]);
	}

	public function actionLogin()
	{

		$model = new User('login');
		$form = new GForm('application.forms.login', $model);

		if(isset($_POST['User'])){
			$model->attributes = $_POST['User'];
			if($model->login()){
				Yii::app()->user->setFlash('success', "Welcome ". $model->username);
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}

		$this->render('login', ['model' => $model, 'form' => $form]);
	}

	public function actionError()
	{
		$this->render('error');
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();

		$this->redirect(['user/login']);
	}

	public function actionUnauth()
	{
		$this->render('unauth');
	}

}