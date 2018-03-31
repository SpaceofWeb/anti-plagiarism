<?php

// Скрипт сохраняет изменения дипломных, студентов и групп в базу

require_once '../data/db.php';
require_once '../data/functions.php';
require_once '../data/docx2text.php';

// die(json_encode([$_POST, $_FILES]));


if (!isset($_POST['id'])) die(json_encode(['err'=> 'Параметр "id" не найден']));
if (!isset($_POST['instance'])) die(json_encode(['err'=> 'Параметр "instance" не найден']));

$id = checkData($_POST['id'], 'id');
$instance = checkData($_POST['instance'], 'instance');

$newFile = false;


// Создание запроса
// Дипломная
if ($instance == 'diplomas') {

	if (!isset($_POST['year'])) die(json_encode(['err'=> 'Параметр "year" не найден']));
	if (!isset($_POST['student'])) die(json_encode(['err'=> 'Параметр "student" не найден']));

	$year = checkData($_POST['year'], 'year');
	$student = checkData($_POST['student'], 'student');
	$doc = '';

	// Сохранение дипломной работы
	if (isset($_FILES['file'])) {
		$file = 'diplomas/'.$year.'/';

		if (!file_exists($cfg['uploadDir'].$file)) mkdir($cfg['uploadDir'].$file, 0777, true);


		$addDate = mktime();
		$file .= 'id'.$student.'-'.$addDate.'.docx';

		if (!move_uploaded_file($_FILES['file']['tmp_name'], $cfg['uploadDir'].$file)) {
			die(json_encode(['err'=> 'Не удалось сохранить файл']));
		}

		$newFile = true;

		// Парсинг дипломной работы
		$d2t = new DocumentParser();
		$text = $d2t->parseFromFile($cfg['uploadDir'].$file, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

		$text = escapeString($text, $db, ['trim', 'stripTags']);

		$doc = ",text='{$text}', file='{$file}' ";
	}

	$q = "UPDATE {$cfg['dbprefix']}_diplomas
			SET year = '{$year}',
				student_id = '{$student}'
				{$doc}
			WHERE id='{$id}' ";

// Студент
} elseif ($instance == 'students') {

	if (!isset($_POST['firstName'])) die(json_encode(['err'=> 'Параметр "firstName" не найден']));
	if (!isset($_POST['middleName'])) die(json_encode(['err'=> 'Параметр "middleName" не найден']));
	if (!isset($_POST['lastName'])) die(json_encode(['err'=> 'Параметр "lastName" не найден']));
	if (!isset($_POST['group'])) die(json_encode(['err'=> 'Параметр "group" не найден']));

	$firstName = checkData($_POST['firstName'], 'firstName');
	$middleName = checkData($_POST['middleName'], 'middleName');
	$lastName = checkData($_POST['lastName'], 'lastName');
	$group = checkData($_POST['group'], 'group');


	$q = "UPDATE {$cfg['dbprefix']}_students
			SET firstName = '{$firstName}',
				middleName = '{$middleName}',
				lastName = '{$lastName}',
				group_id = '{$group}'
			WHERE id='{$id}' ";

// Группа
} elseif ($instance == 'groups') {

	if (!isset($_POST['group'])) die(json_encode(['err'=> 'Параметр "group" не найден']));

	$group = checkData($_POST['group'], 'group');


	$q = "UPDATE {$cfg['dbprefix']}_groups SET name='{$group}' WHERE id='{$id}' ";

}



// Выполнение запроса
if ($db->query($q)) {
	if ($newFile) {
		$q = "UPDATE {$cfg['dbprefix']}_percentage SET percent=NULL WHERE d1_id='{$id}' OR d2_id='{$id}' ";
		if (!$db->query($q))
			die(json_encode(['err'=> 'Ошибка обнуления процентов дипломной: '.$db->error]));

		exec('node ../bgproc/timer.js > /dev/null &');
	}

	die(json_encode(['success'=> 'Успешно сохранено', 'instance'=> $instance]));
} else {
	die(json_encode(['err'=> 'Произошла ошибка: '.$db->error]));
}
