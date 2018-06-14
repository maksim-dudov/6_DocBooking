<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$doctors = new Doctors();

		if (isset($_POST['Orders'])==false) {
			$step=1;
			$prepareData = array();
			$available_time_raw=array();
			$order = new Orders();

		}
		elseif($_POST['Orders']['step'] == 1) {
			$step=2;
			$prepareData = $_POST;
			$available_time=Dashboard::getWindowsByDoctorAndDate(
				$_POST['Orders']['doctor_id'],
				new DateTime($_POST['Orders']['datetime_app'])
			);
			foreach($available_time as $time){
				$available_time_raw[$time->format('d.m.Y H:i:s')]=$time->format('H:i');
			}
			$order = new Orders();
			$order->attributes=$_POST['Orders'];
		}
		elseif($_POST['Orders']['step'] == 2) {

		$order = new Orders();
			$result = $order->makeOrder(
				$_POST['Orders']['doctor_id'],
				new DateTime(),
				$_POST['Orders']['app_type_id']
			);

			$step=3;
			$prepareData = array();
			$available_time_raw=array();
		}

		$this->render('index',
			array(
				'doctors'=>$doctors->findAll(),
				'model'=>$order,
				'step'=>$step,
				'prepareData'=>$prepareData,
				'available_time'=>$available_time_raw,
				'step'=>$step,
			)
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Displays the contact page
	 */
	public function actionTestPage()
	{
		$dashboard = new Dashboard();
		$data = $dashboard->checkDayIsOpen(2,'2018-06-14');
		$this->render('test',array('data'=>$data));
	}
}