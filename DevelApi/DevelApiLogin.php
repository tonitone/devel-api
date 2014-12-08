<?php

/**
 * Login Api 
 * 
 * @category   Yii
 * @package    DevelApi
 * 
 * @author Toni Ivanisevic <030toni@googlemail.com>
 * @copyright  Copyright (c) 2012 Toni Ivanisevic <030toni@googlemail.com>
 */
class DevelApiLogin extends DevelApi
{

	public function init($setRequestOptions = array())
	{
		$this->setApiUrlResource('registerApi');

		$this->setRequestParameter(array(
			'login' => '1',
			'apiAction' => 'login',
		));

		$this->setRequestParameter($setRequestOptions);
	}

}