<?php

require_once 'engine/data/db.php';

// echo 'test.php';
// die();


$q = "SELECT P.id, D.text AS d, D2.text AS d2 FROM ap_percent P 
		LEFT JOIN ap_diplomas1 D ON P.did=D.id 
		LEFT JOIN ap_diplomas1 D2 ON P.d2id=D2.id 
		WHERE P.percent IS NULL 
		ORDER BY P.id LIMIT 1";

$res = $db->query($q);

$p = 0;
if ($res->num_rows == 1) {
	$row = $res->fetch_assoc();

	similar_text($row['d'], $row['d2'], $p);

	$q = "UPDATE ap_percent SET percent='".$p."' WHERE id='".$row['id']."' ";
	if (!$db->query($q)) {
		file_put_contents('text.txt', 'err: '.$db->error.' : '.microtime(true)."\n", FILE_APPEND);
	} else {
		file_put_contents('text.txt', 'added id:'.$row['id'].', '.$p.'% :'.microtime(true)."\n", FILE_APPEND);
	}

	die('repeat');
} else {
	die('norepeat');
}



die();
