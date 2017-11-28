<?php

require_once '../data/db.php';
require_once '../data/functions.php';


// if we searching students from main page
if (isset($_GET['term'])) {
	$s = checkData($_GET['term'], 'term', false);

	$q = "SELECT D.id, 
				CONCAT(S.firstName, ' ', SUBSTR(S.middleName, 1, 1), '. ', SUBSTR(S.lastName, 1, 1), '.') AS label 
			FROM {$cfg['dbprefix']}_diplomas D 
			LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id 
			WHERE CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE '%{$s}%' 
			ORDER BY S.firstName, S.middleName, S.lastName LIMIT 10 ";

	$res = $db->query($q);
	if ($res->num_rows > 0) {
		while ($row = $res->fetch_assoc()) {
			$a[] = $row;
		}

		die(json_encode($a));
	} else {
		die(json_encode([]));
	}
}



// if we searching from change page
if (!isset($_POST['instance'])) die(json_encode(['err'=> 'Параметр "instance" не найден']));

$instance = checkData($_POST['instance'], 'instance');


if ($instance == 'students') {

	$q = "SELECT id, CONCAT(firstName, ' ', middleName, ' ', SUBSTR(lastName, 1, 1), '.') AS name 
			FROM {$cfg['dbprefix']}_students 
			ORDER BY firstName, middleName";

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




