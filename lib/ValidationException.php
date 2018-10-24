<?php
/**
 * RequestException
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

Class ValidationException extends Exception
{
	private $errors;
    private $apiResponse;
    
	function __construct($message,$errors,$apiResponse)
	{
		parent::__construct($message, 0);
		$this->errors       = $errors;
		$this->apiResponse  = $apiResponse;
	}
	
	public function getErrors()
	{
		return $this->errors;
    }
    
	public function getResponse(){
		return $this->apiResponse;
	}
}