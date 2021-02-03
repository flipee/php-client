<?php
	
namespace EnotasGw\Api\Exceptions;

class UnauthorizedException extends ApiException {	
	public function __construct($httpCode, $errors) {
		parent::__construct($httpCode, $errors);
	}
}
