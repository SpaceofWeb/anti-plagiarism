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

		$html = '<option value="0">-</option>';

		while ($row = $res->fetch_assoc()) {
			$html .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}

		die(json_encode(['data'=> $html]));
	} else {
		die(json_encode(['err'=> 'Записей не найдено']));
	}

} elseif ($instance == 'groups') {

	$q = "SELECT * FROM {$cfg['dbprefix']}_groups ORDER BY name DESC";
	$res = $db->query($q);

	if ($res->num_rows > 0) {

		$html = '<option value="0">-</option>';

		while ($row = $res->fetch_assoc()) {
			$html .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}

		die(json_encode(['data'=> $html]));
	} else {
		die(json_encode(['err'=> 'Записей не найдено']));
	}

}




