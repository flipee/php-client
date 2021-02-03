<?php
	
namespace EnotasGw\Api\Media\Formatters;

abstract class FormatterBase {
	abstract public function encode($objData, &$contentType);
	abstract public function decode($encodedData);
}
