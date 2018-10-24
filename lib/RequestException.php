<?php
/**
 * RequestException
 *
 */
Class RequestException extends Exception
{
    private $object;
    
	function __construct($message, $curlObject)
	{
		parent::__construct($message, 0);
		$this->object = $curlObject;
	}
	
	public function __toString()
	{
		# will return curl object from curl.php in string manner.
		return "ERROR at Processing cURL request" . PHP_EOL . (string)$this->object;
	}
	
}