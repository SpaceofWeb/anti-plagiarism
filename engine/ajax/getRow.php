<?php

require_once '../data/db.php';
require_once '../data/functions.php';



// if we searching from change page
if (!isset($_POST['id'])) die(json_encode(['err'=> 'Параметр "id" не найден']));
if (!isset($_POST['instance'])) die(json_encode(['err'=> 'Параметр "instance" не найден']));

$id = checkData($_POST['id'], 'id');
$instance = checkData($_POST['instance'], 'instance');



// Get rows
if ($instance == 'diplomas') {


	$q = "SELECT id, year, student_id FROM {$cfg['dbprefix']}_diplomas WHERE id='{$id}' ";
	$res = $db->query($q);

	if ($res->num_rows == 1) {
		$row = $res->fetch_assoc();

		// get groups list
		$q = "SELECT id, CONCAT(firstName, ' ', SUBSTR(middleName, 1, 1), '. ', SUBSTR(lastName, 1, 1), '.') AS name 
				FROM {$cfg['dbprefix']}_students ORDER BY name";

		$res = $db->query($q);

		$html = '<option value="0">-</option>';
		while ($studentsRow = $res->fetch_assoc()) {
			$selected = ($row['student_id'] == $studentsRow['id']) ? ' selected' : '';
			$html .= '<option value="'.$studentsRow['id'].'"'.$selected.'>'.$studentsRow['name'].'</option>';
		}

		$row['students'] = $html;


		die(json_encode(['data'=> $row]));
	} else {
		die(json_encode(['err'=> 'Запись не найдена']));
	}


} elseif ($instance == 'students') {


	$q = "SELECT * FROM {$cfg['dbprefix']}_students WHERE id='{$id}' ";
	$res = $db->query($q);

	if ($res->num_rows == 1) {
		$row = $res->fetch_assoc();

		// get groups list
		$q = "SELECT id, name FROM {$cfg['dbprefix']}_groups ORDER BY name";
		$res = $db->query($q);

		$html = '<option value="0">-</option>';
		while ($groupsRow = $res->fetch_assoc()) {
			$selected = ($row['group_id'] == $groupsRow['id']) ? ' selected' : '';
			$html .= '<option value="'.$groupsRow['id'].'"'.$selected.'>'.$groupsRow['name'].'</option>';
		}

		$row['groups'] = $html;


		die(json_encode(['data'=> $row]));
	} else {
		die(json_encode(['err'=> 'Запись не найдена']));
	}


} elseif ($instance == 'groups') {


	$q = "SELECT id, name FROM {$cfg['dbprefix']}_groups WHERE id='{$id}' ";

	$res = $db->query($q);

	if ($res->num_rows == 1) {
		$row = $res->fetch_assoc();

		die(json_encode(['data'=> $row]));
	} else {
		die(json_encode(['err'=> 'Запись не найдена']));
	}

}


