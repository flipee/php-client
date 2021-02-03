<?php
	
namespace EnotasGw\Api; 

use Exception;
use EnotasGw\Api\EmpresaApi;
use EnotasGw\Api\NfeApi;
use EnotasGw\Api\PrefeituraApi;
use EnotasGw\Api\ServicosMunicipaisApi;
use EnotasGw\Api\Proxy\CurlProxy;
use EnotasGw\Api\Media\Formatters\JsonFormatter;
use EnotasGw\Api\Media\Formatters\FormDataFormatter;

class EnotasGw {
	private static $_apiKey;
	private static $_defaultContentType = 'application/json';
	private static $_baseUrl = 'http://api.enotasgw.com.br';
	private static $_version = '1';
	private static $_versionedBaseUrl;
	private static $_proxy;
	private static $_formmaters;
	private static $_trustedCAListPath;

	public static $EmpresaApi;
	public static $NFeApi;
	public static $PrefeituraApi;
	public static $ServicosMunicipaisApi;

	public static function configure($config) {
		$config = (object)$config;

		self::$_formmaters = array(
			'application/json' => new JsonFormatter(),
			'multipart/form-data' => new FormDataFormatter()
		);

		if(!isset($config->apiKey)) {
			throw new Exception('A api key deve ser definida no método configure.');
		}

		self::$_apiKey = $config->apiKey;

		if(isset($config->baseUrl)) {
			self::$_baseUrl = $config->baseUrl;
		}

		if(isset($config->version)) {
			self::$_version = $config->version;
		}

		if(isset($config->defaultContentType)) {
			self::$_defaultContentType = $config->defaultContentType;
		}
		
		if(isset($config->_trustedCAListPath)) {
			self::$_trustedCAListPath = $config->_trustedCAListPath;
		}
		else {
			self::$_trustedCAListPath = dirname(__FILE__) . '/files/ca-bundle.crt';
		}

		self::$_versionedBaseUrl = self::$_baseUrl . '/v' . self::$_version;

		self::init();
	}

	public static function getMediaFormatter($contentType) {
		$contentType = explode(';', $contentType);
		
		return self::$_formmaters[$contentType[0]];
	}

	private static function init() {
		self::$_proxy = self::createProxy();
		self::$NFeApi = new NfeApi(self::$_proxy);
		self::$EmpresaApi = new EmpresaApi(self::$_proxy);
		self::$PrefeituraApi = new PrefeituraApi(self::$_proxy);
		self::$ServicosMunicipaisApi = new ServicosMunicipaisApi(self::$_proxy);
	}

	private static function createProxy() {
		return new CurlProxy(array(
			'baseUrl' => self::$_versionedBaseUrl,
			'apiKey' => self::$_apiKey,
			'defaultContentType' => self::$_defaultContentType,
			'trustedCAListPath' => self::$_trustedCAListPath
		));
	}
}
