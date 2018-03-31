<?php

// die('norepeat');

// Написать в лог
function setLog($f, $s) {
	file_put_contents($f, $s."\n", FILE_APPEND);
}



require_once '../data/db.php';

$logFile = '../bgproc/logs/'.date("Ym").'.log';

// Выбрать из базы две дипломные работы
$q = "SELECT P.id, D.text AS d, D2.text AS d2
		FROM {$cfg['dbprefix']}_percentage P
		LEFT JOIN {$cfg['dbprefix']}_diplomas D ON P.d1_id=D.id
		LEFT JOIN {$cfg['dbprefix']}_diplomas D2 ON P.d2_id=D2.id
		WHERE P.percent IS NULL
		ORDER BY P.id LIMIT 1";

$res = $db->query($q);

$p = 0;
if ($res->num_rows == 1) {
	$row = $res->fetch_assoc();
	// Сравнение
	similar_text($row['d'], $row['d2'], $p);

	$p = (int)$p;
	// Запись в базу результатов сравнения
	$q = "UPDATE {$cfg['dbprefix']}_percentage SET percent='".$p."' WHERE id='".$row['id']."' ";
	if ($db->query($q)) {
		setLog($logFile, 'added id: '.$row['id'].', '.$p.'%');
	} else {
		setLog($logFile, 'err: '.$db->error);
	}


	die('repeat');
} else {
	die('norepeat');
}
