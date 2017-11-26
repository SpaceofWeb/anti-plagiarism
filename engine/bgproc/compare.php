<?php

// die('norepeat');


function setLog($f, $s) {
	file_put_contents($f, $s."\n", FILE_APPEND);
}



require_once '../data/db.php';

$logFile = '../bgproc/logs/'.date("Ym").'.log';


$q = "SELECT P.id, D.text AS d, D2.text AS d2 
		FROM ap_percentage1 P 
		LEFT JOIN ap_diplomas1 D ON P.d1_id=D.id 
		LEFT JOIN ap_diplomas1 D2 ON P.d2_id=D2.id 
		WHERE P.percent IS NULL 
		ORDER BY P.id LIMIT 1";

$res = $db->query($q);

$p = 0;
if ($res->num_rows == 1) {
	$row = $res->fetch_assoc();

	similar_text($row['d'], $row['d2'], $p);

	$p = (int)$p;

	$q = "UPDATE ap_percentage1 SET percent='".$p."' WHERE id='".$row['id']."' ";
	if ($db->query($q)) {
		setLog($logFile, 'added id: '.$row['id'].', '.$p.'%');
	} else {
		setLog($logFile, 'err: '.$db->error);
	}


	die('repeat');
} else {
	die('norepeat');
}






