<?php

/**
 * Register Api
 * 
 * @category   Yii
 * @package    DevelApi
 * 
 * @author Toni Ivanisevic <030toni@googlemail.com>
 * @copyright  Copyright (c) 2012 Toni Ivanisevic <030toni@googlemail.com>
 */
class DevelApiRegister extends DevelApi
{

	public function init($setRequestOptions = array())
	{

		$this->setApiUrlResource('registerApi');

		$this->setRequestParameter(array(
			'apiAction' => 'register',
		));

		$this->setRequestParameter($setRequestOptions);
	}

	protected function parseRequest()
	{
		parent::parseRequest();

		$response = $this->getResponse();

		if (isset($response->validation)) {
			$validation = $response->validation;
			// zähle die Anzahl der validation rückgabe
			$iValidationCount = count((array) $validation);

			if ($iValidationCount > 1) {
				$aErrors = array();
				foreach ($validation as $k => $v) {
					if (isset($v->fieldState) && $v->fieldState == 'not_valid') {
						$aErrors[$k] = utf8_decode(urldecode($v->errorMessage));
					}
				}
				$this->setResponseErrors($aErrors);
			} else {
				$this->setResponseSuccessMessage(utf8_decode(urldecode($response->headLineInfo)));
			}
		}
		return true;
	}

}