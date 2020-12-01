<?php

/**
 *
 * reset everything
 * 
 * clear all db sessions
 * reset all variables
 * clear session variables
 *ok
 **/


/**
 *
 * delete session
 *
 **/

session_start();

session_unset();
$_SESSION = array(); //clears values in array
session_destroy();

header ("Location: index.php");
exit;
