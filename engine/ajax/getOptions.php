<?php

require_once '../data/db.php';
require_once '../data/functions.php';


if (!isset($_POST['instance'])) die(json_encode(['err'=> 'Параметр "instance" не найден']));

$instance = checkData($_POST['instance'], 'instance');


if ($instance == 'students') {

	$q = "SELECT id, CONCAT(firstName, ' ', middleName, ' ', SUBSTR(lastName, 1, 1), '.') AS name 
			FROM {$cfg['dbprefix']}_students ORDER BY firstName, middleName";

	$res = $db->query($q);

	$html = '';
	if ($res->num_rows > 0) {
		while ($row = $res->fetch_assoc()) {
			// GEN HTML
			$a[] = $row;
		}

		die(json_encode(['data'=> $a]));
	} else {
		die(json_encode(['err'=> 'Записей не найдено']));
	}

} elseif ($instance == 'groups') {

	$q = "SELECT * FROM {$cfg['dbprefix']}_groups ORDER BY name DESC";
	$res = $db->query($q);

	if ($res->num_rows > 0) {
		while ($row = $res->fetch_assoc()) {
			$a[] = $row;
		}

		die(json_encode(['data'=> $a]));
	} else {
		die(json_encode(['err'=> 'Записей не найдено']));
	}

}




