<?php

// Скрипт выбирает данные из базы при переходе по пагинации или поиске на главной странице

require_once '../data/db.php';
require_once '../data/functions.php';


if (!isset($_POST['student'])) die(json_encode(['err'=> 'Параметр "student" не найден']));
if (!isset($_POST['instance'])) die(json_encode(['err'=> 'Параметр "instance" не найден']));

// $student = 1;
// $instance = 'diplomas';
$student = checkData($_POST['student'], 'student', false);
$instance = checkData($_POST['instance'], 'instance');


// Пагинация
$limit = [];

$pagin = (isset($_POST['pagin'])) ? $_POST['pagin'] : ['d'=> 1, 's'=> 1, 'g'=> 1];

foreach ($pagin as $key => $val) {
	$limit[$key] = (isset($pagin[$key])) ? ($pagin[$key]-1)*$cfg['rowsPerPage'] : 0;
}



// Создание запросов
// Поиск дипломных по студенту
if ($instance == 'diplomas') {

	// Выбрать все дипломные по студенту
	$q = "SELECT P.percent,
					CONCAT(S.firstName, ' ', 
						SUBSTR(S.middleName, 1, 1), '. ', 
						SUBSTR(S.lastName, 1, 1), '.') AS name
				FROM {$cfg['dbprefix']}_percentage P
					LEFT JOIN {$cfg['dbprefix']}_diplomas D ON D.id=P.d2_id
					LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id
				WHERE P.d1_id='{$student}' AND P.percent IS NOT NULL
				UNION SELECT P.percent,
					CONCAT(S.firstName, ' ', 
						SUBSTR(S.middleName, 1, 1), '. ', 
						SUBSTR(S.lastName, 1, 1), '.') AS name
				FROM {$cfg['dbprefix']}_percentage P
					LEFT JOIN {$cfg['dbprefix']}_diplomas D ON D.id=P.d1_id
					LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id
				WHERE P.d2_id='{$student}' AND P.percent IS NOT NULL
				ORDER BY percent DESC
				LIMIT ".$limit['d'].','.$cfg['rowsPerPage'];;


	$qCount = "SELECT COUNT(id) AS count
				FROM {$cfg['dbprefix']}_percentage
				WHERE d1_id='{$student}' AND percent IS NOT NULL";

// die($q);
// Поиск по дипломным
} elseif ($instance == 'topDiplomas') {

	// Выбрать топ дипломных по процентам
	$q = "SELECT P.percent,
				CONCAT(S.firstName, ' ', SUBSTR(S.middleName, 1, 1), '. ',
											SUBSTR(S.lastName, 1, 1), '.') AS name,
				CONCAT(S2.firstName, ' ', SUBSTR(S2.middleName, 1, 1), '. ',
											SUBSTR(S2.lastName, 1, 1), '.') AS name2
			FROM {$cfg['dbprefix']}_percentage P
			LEFT JOIN {$cfg['dbprefix']}_diplomas D ON D.id=P.d1_id
			LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id
			LEFT JOIN {$cfg['dbprefix']}_diplomas D2 ON D2.id=P.d2_id
			LEFT JOIN {$cfg['dbprefix']}_students S2 ON S2.id=D2.student_id
			WHERE P.percent IS NOT NULL
			ORDER BY P.percent DESC
			LIMIT ".$limit['t'].','.$cfg['rowsPerPage'];


	$qCount = "SELECT COUNT(id) AS count
				FROM {$cfg['dbprefix']}_percentage
				WHERE percent IS NOT NULL";

}




// Выбрать данные из базы
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
