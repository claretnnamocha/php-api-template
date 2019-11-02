<?php

function session_set($key, $value)
{
	$_SESSION[$key] = $value;
}

function session_get($key, $default=false)
{
	return $_SESSION[$key] ? $_SESSION[$key] : $default ;
}