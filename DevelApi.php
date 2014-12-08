<?php

/**
 * Abstract Devel Api Base Class 
 * 
 * Template-Method Pattern
 * 
 * @category   Yii
 * @package    DevelApi
 * 
 * @author Toni Ivanisevic <030toni@googlemail.com>
 * @copyright  Copyright (c) 2012 Toni Ivanisevic <030toni@googlemail.com>
 */
Abstract class DevelApi implements DevelApiInterface
{

	protected $apiUrl = 'http://www.opillion.com/';
	protected $apiUrlResource = '';
	protected $sCacheKey = null;
	protected $requestParameter = array(
		'apiAction' => '',
		'apiCall' => '1',
		'apiLang' => 'EN',
		'getToPost' => '1',
		'outputType' => 'json',
	);
	protected $defaultConnectionOption = array(
		'maxredirects' => 3,
		'timeout' => 30,
		'adapter' => 'EHttpClientAdapterCurl',
	);
	protected $response = null;
	protected $responseSuccessMessage = null;
	protected $responseErrors = null;
	protected $connectError = null;
	protected $cacheTime = null;
	protected $_apiType;
	// curl-object
	protected $_curl = null;
	protected $_curl_response;

	public function __construct()
	{
		
	}

	abstract public function init($setRequestOptions = array());

	final protected function initConnection()
	{
		if ($this->_curl == null) {
			$this->_curl = new EHttpClient(
							$this->apiUrl . $this->apiUrlResource . '/',
							$this->defaultConnectionOption
			);
		}

		$this->_curl->setParameterPost($this->requestParameter);
	}

	/**
	 * Setzt den Resource Typ für den Api-Call 
	 * Beispiel:
	 * für 'http://opillion.com/userLogin/' wäre der zu übergebene Parameter 'userLogin'
	 * 
	 * @param string $resource userLogin
	 * @throws DevelApiException 
	 */
	protected function setApiUrlResource($apiUrlResource = null)
	{
		if ($apiUrlResource == null) {
			/*throw new DevelApiException(
					Yii::t('DevelApi', 'ApiUrlResource is null', $this->requestParameter)
			)*/;
		} else {
			$this->apiUrlResource = $apiUrlResource;
		}
	}

	/**
	 * Setzt eigene Request-Parameter an die default-optionen ran
	 * 
	 * @param type $setOptions 
	 */
	protected function setRequestParameter($setRequestOptions = array())
	{
		$this->requestParameter = array_merge($this->requestParameter, $setRequestOptions);

		if ($this->isCacheActive()) {
			$this->sCacheKey = $this->apiUrlResource . md5(json_encode($this->requestParameter));
		}
	}

	final public function sendRequest()
	{
		
		if(isset(Yii::app()->session['reset_cache']) &&
				Yii::app()->session['reset_cache'] == true) {
			unset($_SESSION['reset_cache']);
			$this->deleteCache();
		}
		if (!Yii::app()->cache->offsetExists($this->sCacheKey)) {

			$this->initConnection();

			try {
				$this->_curl_response = $this->_curl->request(EHttpClient::POST);

				if ($this->_curl_response->isError()) {

					$this->connectError = 'Connection error';

					/*throw new DevelApiException(
							Yii::t('error', 'ApiUrl connection error', $this->requestParameter)
					);*/

					return false;
				} else {

					$this->response = $this->_curl_response->getBody();

					$this->parseRequest();

					if ($this->isCacheActive()) {
						Yii::app()->cache->add($this->sCacheKey, $this->response, $this->cacheTime);
					}
				}
			} catch (EHttpClientException $e) {
				print $e->getMessage();
				return false;
			}
		} else {
			if ($this->isCacheActive()) {
				$this->response = Yii::app()->cache->get($this->sCacheKey);
			}
		}
	}

	/**
	 * Holt den Request und schreibt das ergebnis in $this->response und $this->error
	 *  
	 */
	protected function parseRequest()
	{
		$this->response = json_decode($this->response);
	}

	/**
	 * gibt die api Response zurück ab dem Knoten "apiResponse"
	 * 
	 * @return boolean false wenn der Knoten nicht existiert
	 */
	public function getResponse()
	{
		return (isset($this->response->apiResponse)) ? $this->response->apiResponse : false;
	}

	public function getResponseSuccessMessage()
	{
		return $this->responseSuccessMessage;
	}

	/**
	 * Setzt die Erfolgsnachricht aus dem Request
	 * 
	 * @param type $var 
	 */
	protected function setResponseSuccessMessage($var)
	{
		$this->responseSuccessMessage = $var;
	}

	/**
	 * Gibt ein Array mit den Fehlertexten aus dem Request wieder
	 * 
	 * @return array Fehlermeldungen aus der Api
	 */
	public function getResponseErrors()
	{
		return ($this->responseErrors != null) ? $this->responseErrors : false;
	}

	/**
	 * übergebene Werte (Strings,Arrays, ...) werden in in der  
	 * $this->responseErrors property gespeichert
	 * 
	 * @param mixed $var error string, Array 
	 */
	protected function setResponseErrors($var)
	{
		$this->responseErrors = $var;
	}

	/**
	 * gibt den Error beim Connect wieder wieder
	 * @return type 
	 */
	public function getConnectError()
	{
		return ($this->connectError != null) ? $this->connectError : false;
	}
	
	public function deleteCache() 
	{
		Yii::app()->cache->delete($this->sCacheKey);
	}

	public function setCacheTime($seconds = null)
	{
		$this->cacheTime = $seconds;
	}

	protected function isCacheActive()
	{
		return ($this->cacheTime == null) ? false : true;
	}

}
