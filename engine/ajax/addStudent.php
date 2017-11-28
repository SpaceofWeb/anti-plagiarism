<?php

require_once '../data/db.php';
require_once '../data/functions.php';


if (isset($_SESSION['token']) && (int)$_SESSION['token'] > time())
	die(json_encode(['err'=> 'Токен не доступен, подождите '.((int)$_SESSION['token']-time()).' секунды']));

$_SESSION['token'] = time()+2;




if (!isset($_POST['firstName'])) die(json_encode(['err'=> 'Parameter "first name" not found']));
if (!isset($_POST['middleName'])) die(json_encode(['err'=> 'Parameter "middle name" not found']));
if (!isset($_POST['lastName'])) die(json_encode(['err'=> 'Parameter "last name" not found']));
if (!isset($_POST['group'])) die(json_encode(['err'=> 'Parameter "group" not found']));


$firstName = checkData($_POST['firstName'], 'first name');
$middleName = checkData($_POST['middleName'], 'middle name');
$lastName = checkData($_POST['lastName'], 'last name');
$group = checkData($_POST['group'], 'group');



$q = "INSERT INTO {$cfg['dbprefix']}_students (firstName, middleName, lastName, group_id) 
		VALUES('{$firstName}', '{$middleName}', '{$lastName}', '{$group}')";


if ($db->query($q))
	die(json_encode(['success'=> 'Student added successful']));
else
	die(json_encode(['err'=> 'Student doesn`t added: ' . $db->error]));

