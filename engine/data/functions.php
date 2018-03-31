<?php

// Экранирование строк
function escapeString($s, $db=null, array $opt=null) {
	if (!is_null($opt)) {

		if (in_array('trim', $opt)) $s = trim($s);
		if (in_array('stripTags', $opt)) $s = strip_tags($s);
		if (in_array('html', $opt)) $s = htmlspecialchars($s);
		if (in_array('int', $opt)) $s = intval($s);

		if (!is_null($db)) {
			return $db->real_escape_string($s);
		}

		return $s;
	}

	if (is_null($db)) {
		return htmlspecialchars(strip_tags(trim($s)));
	} else {
		return $db->real_escape_string(htmlspecialchars(strip_tags(trim($s))));
	}
}


// Проверка данных на экранирование
function checkData($val, $name, bool $void=true) {
	$val = escapeString($val);

	if ($void)
		if ($val == '')
			die(json_encode(['err'=> 'Параметр "'.$name.'" не валиден']));


	return $val;
}
