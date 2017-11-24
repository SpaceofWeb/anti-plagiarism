<?php

require_once 'config.php';

$db = new mysqli($cfg['dbhost'], $cfg['dbuser'], $cfg['dbpass'], $cfg['dbname']);


if ($db->connect_errno)
	die('DB connect error: ' . $db->connect_error);


if (!$db->set_charset('utf8'))
	die('DB set charset error');

