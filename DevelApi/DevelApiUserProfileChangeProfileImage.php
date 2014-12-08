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

/* http://opillion.com/userApi/
 * apiCall=1&
 * getToPost=1&
 * userId=4bee8d4ff46140db5f2de64a7b6785ba&
 * userLogin=f21a76231d6fb319a593959910ecd88a&
 * apiAction=userProfile&
 * itemAction=setUserImage&
 * itemId=7295895240021c021eb94c6788d6d425&
 * apiLang=EN&
 * outputType=json
 */

class DevelApiUserProfileChangeProfileImage extends DevelApi
{

	public function init($setRequestOptions = array())
	{
		$this->setCacheTime(240);

		$this->setApiUrlResource('userApi');

		$this->setRequestParameter(array(
			'apiAction' => 'userProfile',
			'userId' => md5(Yii::app()->user->id), // email:
			'userLogin' => md5(Yii::app()->user->id . '|' . Yii::app()->session['password']), // email und pw mit pipe
			'itemAction' => 'setUserImage',
		));

		$this->setRequestParameter($setRequestOptions);
	}

}