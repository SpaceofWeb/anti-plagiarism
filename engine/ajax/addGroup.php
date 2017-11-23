<?php

ini_set('display_errors', 1);


require_once '../data/db.php';
require_once '../data/functions.php';


$group = checkData($_POST['group'], 'group');


$q = "INSERT INTO {$cfg['dbprefix']}_groups (name) VALUES('{$group}')";


if (!$db->query($q))
	die(json_encode(['err'=> 1, 'msg'=> 'Group doesn`t added: ' . $db->error]));
else
	die(json_encode(['err'=> 0, 'msg'=> 'Group added successful']));

