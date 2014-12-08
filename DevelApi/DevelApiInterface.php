<?php

/**
 * Interfaces für DevelApi
 * 
 * @category   Yii
 * @package    DevelApi
 * 
 * @author Toni Ivanisevic <030toni@googlemail.com>
 * @copyright  Copyright (c) 2012 Toni Ivanisevic <030toni@googlemail.com>
 */
interface DevelApiInterface
{

	public function sendRequest();

	public function getResponse();

	public function getResponseSuccessMessage();

	public function getResponseErrors();

	public function getConnectError();

}
