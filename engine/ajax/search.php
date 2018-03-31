<?php

// Скрипт выбирает данные из базы при переходе по пагинации или поиске на странице изменения

require_once '../data/db.php';
require_once '../data/functions.php';


if (!isset($_POST['s'])) die(json_encode(['err'=> 'Параметр "search" не найден']));
if (!isset($_POST['instance'])) die(json_encode(['err'=> 'Параметр "instance" не найден']));


$s = checkData($_POST['s'], 'search', false);
$instance = checkData($_POST['instance'], 'instance');


// Пагинация
$limit = [];

$pagin = (isset($_POST['pagin'])) ? $_POST['pagin'] : ['d'=> 1, 's'=> 1, 'g'=> 1];

foreach ($pagin as $key => $val) {
	$limit[$key] = (isset($pagin[$key])) ? ($pagin[$key]-1)*$cfg['rowsPerPage'] : 0;
}




// Создание запросов
// Поиск дипломных
if ($instance == 'diplomas') {

	$where = ($s == '') ? '' : "WHERE CONCAT(S.firstName, ' ', S.middleName, ' ', S.lastName) LIKE '%{$s}%' ";

	$q = "SELECT D.id, CONCAT(S.firstName, ' ', SUBSTR(S.middleName, 1, 1), '. ', SUBSTR(S.lastName, 1, 1), '.') AS name
			FROM {$cfg['dbprefix']}_diplomas D
			LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id
			".$where."
			ORDER BY S.firstName, S.middleName, S.lastName LIMIT ".$limit['d'].','.$cfg['rowsPerPage'];


	$qCount = "SELECT COUNT(D.id) AS count
				FROM {$cfg['dbprefix']}_diplomas D
				LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id
				".$where;


// Поиск студентов
} elseif ($instance == 'students') {

	$where = ($s == '') ? '' : "WHERE CONCAT(firstName, ' ', middleName, ' ', lastName) LIKE '%{$s}%' ";

	$q = "SELECT id, CONCAT(firstName, ' ', SUBSTR(middleName, 1, 1), '. ', SUBSTR(lastName, 1, 1), '.') AS name
			FROM {$cfg['dbprefix']}_students
			".$where."
			ORDER BY firstName, middleName, lastName LIMIT ".$limit['s'].','.$cfg['rowsPerPage'];


	$qCount = "SELECT COUNT(id) AS count FROM {$cfg['dbprefix']}_students ".$where;


// Поиск групп
} elseif ($instance == 'groups') {

	$where = ($s == '') ? '' : "WHERE name LIKE '%{$s}%' ";

	$q = "SELECT id, name FROM {$cfg['dbprefix']}_groups
			".$where."
			ORDER BY name LIMIT ".$limit['g'].','.$cfg['rowsPerPage'];


	$qCount = "SELECT COUNT(id) AS count FROM {$cfg['dbprefix']}_groups ".$where;

}




// Выборка данных
$res = $db->query($q);
if ($res->num_rows > 0) {
	while ($row = $res->fetch_assoc()) {

		$a[] = $row;

	}

	$resCount = $db->query($qCount);
	if ($resCount->num_rows == 1) {
		$count = $resCount->fetch_assoc()['count'];
	} else {
		$count = 0;
	}

	die(json_encode(['data'=> $a, 'count'=> $count]));
} else {

	$resCount = $db->query($qCount);
	if ($resCount->num_rows == 1) {
		$count = $resCount->fetch_assoc()['count'];
	} else {
		$count = 0;
	}

	// die(json_encode(['warn'=> 'Записей не найдено']));
	die(json_encode(['data'=> [], 'count'=> $count]));
}
