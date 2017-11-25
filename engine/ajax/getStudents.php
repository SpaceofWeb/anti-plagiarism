<?php

require_once '../data/db.php';
require_once '../data/functions.php';


if (!isset($_POST['student'])) die(json_encode(['err'=> 'Параметр "student" не найден']));

$student = checkData($_POST['student'], 'student');



$q = "SELECT P.percent, 
			CONCAT(S.firstName, ' ', SUBSTR(S.middleName, 1, 1), ' ', SUBSTR(S.lastName, 1, 1)) AS name 
		FROM ap_percentage P 
		LEFT JOIN ap_diplomas D ON D.id=P.d2_id 
		LEFT JOIN ap_students S ON S.id=D.student_id 
		WHERE P.d1_id={$student} 
		ORDER BY P.percent DESC 
		LIMIT 10 ";


$res = $db->query($q);
echo $db->error;
if ($res->num_rows > 0) {
	while ($row = $res->fetch_assoc()) {
		$a[] = $row;
	}

	die(json_encode($a));
} else {
	die(json_encode([]));
}



