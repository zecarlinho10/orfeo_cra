<?php 

class OrfeoException extends Exception{
	public function OrfeoException($message, $code = 0, Exception $previous = null){
		parent::__construct($message, $code,$previous);
	}
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

}

?>