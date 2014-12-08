<?php

/**
 * User Profile 
 * 
 * @category   Yii
 * @package    DevelApi
 * 
 * @author Toni Ivanisevic <030toni@googlemail.com>
 * @copyright  Copyright (c) 2012 Toni Ivanisevic <030toni@googlemail.com>
 */
//require_once dirname(__FILE__) . '/DevelApiDecorator.php';

class DevelApiUserProfile extends DevelApi
{

	public function init($setRequestOptions = array())
	{
		$this->setCacheTime(240);

		$this->setApiUrlResource('userApi');

		$this->setRequestParameter(array(
			'apiAction' => 'userProfile',
			'userId' => md5(Yii::app()->user->id), // email:
			'userLogin' => md5(Yii::app()->user->id . '|' . Yii::app()->session['password']), // email und pw mit pipe
		));

		$this->setRequestParameter($setRequestOptions);
	}

}