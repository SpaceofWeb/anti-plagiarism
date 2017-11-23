<?php

ini_set('display_errors', 1);


require_once '../data/db.php';
require_once '../data/functions.php';


// if (!isset($_POST['firstName'])) die(json_encode(['err'=> 1, 'msg'=> 'Parameter "firstName" not found']));
// if (!isset($_POST['middleName'])) die(json_encode(['err'=> 1, 'msg'=> 'Parameter "middleName" not found']));
// if (!isset($_POST['lastName'])) die(json_encode(['err'=> 1, 'msg'=> 'Parameter "lastName" not found']));
// if (!isset($_POST['group'])) die(json_encode(['err'=> 1, 'msg'=> 'Parameter "group" not found']));


$firstName = checkData($_POST['firstName'], 'first name');
$middleName = checkData($_POST['middleName'], 'middle name');
$lastName = checkData($_POST['lastName'], 'last name');
$group = checkData($_POST['group'], 'group id');


// if ($firstName == '') die(json_encode(['err'=> 1, 'msg'=> 'Parameter "firstName" not valid']));
// if ($middleName == '') die(json_encode(['err'=> 1, 'msg'=> 'Parameter "middleName" not valid']));
// if ($lastName == '') die(json_encode(['err'=> 1, 'msg'=> 'Parameter "lastName" not valid']));
// if ($group == '') die(json_encode(['err'=> 1, 'msg'=> 'Parameter "group" not valid']));





$q = "INSERT INTO {$cfg['dbprefix']}_students (firstName, middleName, lastName, group_id) 
		VALUES('{$firstName}', '{$middleName}', '{$lastName}', '{$group}')";


if (!$db->query($q))
	die(json_encode(['err'=> 1, 'msg'=> 'Student doesn`t added: ' . $db->error]));
else
	die(json_encode(['err'=> 0, 'msg'=> 'Student added successful']));

