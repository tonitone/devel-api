<?php

/**
 * Item Api
 * 
 * @category   Yii
 * @package    DevelApi
 * 
 * @author Toni Ivanisevic <030toni@googlemail.com>
 * @copyright  Copyright (c) 2012 Toni Ivanisevic <030toni@googlemail.com>
 */
class DevelApiItem extends DevelApi
{

	public function init($setRequestOptions = array())
	{
		$this->setCacheTime(240);

		$this->setApiUrlResource('itemApi');

		$this->setRequestParameter(array(
			'apiAction' => 'item',
			'itemAction' => 'list',
		));

		$this->setRequestParameter($setRequestOptions);
	}

}