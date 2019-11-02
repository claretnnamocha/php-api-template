<?php

use Volnix\CSRF\CSRF;

function post($param='')
{
	if ($param === '') {
		return $_POST;
	}
	try {
		return $_POST[$param];
	} catch (Exception $e) {
		throw new Exception("POST field '{$param}' not defined");		
	}
}

function get($param='')
{
	if ($param === '') {
		return $_GET;
	}
	try {
		return $_GET[$param];
	} catch (Exception $e) {
		throw new Exception("GET field '{$param}' not defined");		
	}
}

function method($method='')
{
	if ($method === '') {
		return $_SERVER['REQUEST_METHOD'];
	}
	return (strtolower($method) === strtolower($_SERVER['REQUEST_METHOD']));
}

function get_csrf($csrf_name, $html=false)
{
	return $value ? CSRF::getHiddenInputString($csrf_name) : CSRF::getToken($csrf_name);	
}

function validate_csrf($csrf_name)
{
	return CSRF::validate(post(), $csrf_name);
}

function check_params($params, $method='post', $validations = [])
{
	$output = [];
	$params = (strtolower($method) === 'post') ? $_POST : $_GET;

	foreach ($params as $param) {
		if ((!array_key_exists($param, $params)) or (empty($params[$param]) and trim($params[$param]) == '' )) {
			return [ 
				'status' => false,
				'message' => ucwords(str_replace('_', ' ', $param))." is required!"
			];
		}
		if (array_key_exists($param,$validations)) {
			$valid = validate_param($params[$param],$validations[$param]);
			if (!$valid) {
				$type = in_array(substr($validations[$param], 0, 1), explode(',', 'a,e,i,o,u')) ? 'an ' : 'a ';
				$type .= $validations[$param] ;
				return [
					'status' => false,
					'message' => ucwords(str_replace('_', ' ', $param))." must be ". (str_replace('_', ' ', $type)) 
				];
			}
		}
		$output[$param] = $params[$param];
	}
	return [ 'status' => true, 'params' => $output];
}

function validate_param($value, $validation)
{
	switch ($validation) {
		case 'email':
			return filter_var($value,FILTER_VALIDATE_EMAIL);
			break;
		
		case 'int':
			$value = intval($value);
			if ($value == 0) {
				return true;
			}
			return filter_var($value,FILTER_VALIDATE_INT);
			break;
		case 'supported_crypto_currency':
			return in_array($value, CryptoController::CRYPTO_CURRENCIES);
			break;
	}
}