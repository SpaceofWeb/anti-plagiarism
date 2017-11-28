<?php

require_once '../data/db.php';
require_once '../data/functions.php';


if (isset($_SESSION['token']) && (int)$_SESSION['token'] > time())
	die(json_encode(['err'=> 'Токен не доступен, подождите '.((int)$_SESSION['token']-time()).' секунды']));

$_SESSION['token'] = time()+2;




if (!isset($_POST['group'])) die(json_encode(['err'=> 'Parameter "group" not found']));

$group = checkData($_POST['group'], 'group');



$q = "INSERT INTO {$cfg['dbprefix']}_groups (name) VALUES('{$group}')";


if ($db->query($q))
	die(json_encode(['success'=> 'Group added successful']));
else
	die(json_encode(['err'=> 'Group doesn`t added: ' . $db->error]));

