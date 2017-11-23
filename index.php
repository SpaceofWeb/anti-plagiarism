<?php


require_once 'engine/data/db.php';
require_once 'engine/static/head.php';
require_once 'engine/static/header.php';

$start = microtime(true);


// exec('#!/usr/bin/php test.php > /dev/null &');
// exec('/usr/bin/php test.php', $o);
// exec('node node.js > /dev/null &');
// exec('node node.js', $o);
// print_r($o);

echo '';

// $q = "SELECT id, text FROM ap_diplomas1 WHERE id=1 ";
// $res = $db->query($q);
// $d = $res->fetch_assoc();



// $q = "SELECT id, text FROM ap_diplomas1 WHERE id<>1 LIMIT 1 ";
// $res = $db->query($q);

// $vals = '';
// $p = 0;

// if ($res->num_rows > 0) {
// 	while ($row = $res->fetch_assoc()) {

// 		$s = microtime();
// 		similar_text($d['text'], $row['text'], $p);
// 		echo 'func time: ', microtime() - $s, '<br>';

// 		$vals .= '(\''.$d['id'].'\', \''.$row['id'].'\', \''.$p.'\'),';
// 	}

// 	$vals = substr($vals, 0, -1);
// 	echo$q = "INSERT INTO ap_percent (did, d2id, percent) VALUES".$vals;
// 	// $db->query($q);
// }

// =============================================

// $q = "SELECT id FROM ap_diplomas1";
// $res = $db->query($q);

// if ($res->num_rows > 0) {
// 	while ($row = $res->fetch_assoc()) {
// 		$r[] = $row['id'];
// 	}


// 	$n = 0;
// 	$vals = '';
// 	$count = count($r);

// 	for ($i=0; $i < $count; $i++) {
// 		for ($j=0; $j < $count; $j++) {
// 			if ($i == $j) continue;

// 			$vals .= '(\''.$r[$i].'\', \''.$r[$j].'\'),';

// 			++$n;
// 			// if (++$n >= 90902) {
// 			// 	break;
// 			// 	break;
// 			// }
// 		}
// 	}


// 	$vals = substr($vals, 0, -1);

// 	echo '<pre1>', $n;

// 	$q = "INSERT INTO ap_percent (did, d2id) VALUES".$vals;
// 	// if (!$db->query($q)) echo $db->error;

// 	echo '</pre1>';
// }

// =============================================

// $l = '';
// $q = "INSERT INTO ap_diplomas1 (text, addDate, file, sid) VALUES('".$l."', '2017', '', 1) ";

// for ($i=0; $i < 150; $i++) { 
// 	$db->query($q);
// }


// echo '<br><br>Exec time: ', microtime(true) - $start;

// die();
?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="jumbotron">
				<h4>Сортировка по студенту</h4><br>

				<!-- <form class="form-inline"> -->
					<div class="form-group row">
						<!-- <label>Example select</label> -->
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon bgcolor">Студент</span>
								<select class="form-control">
									<option>-</option>
									<option>Хадзиев Герман</option>
									<option>Хадзиев Герман</option>
								</select>
							</div>
						</div>
					</div>
				<!-- </form><br> -->

				<table class="table table-stripped">
					<thead>
						<tr>
							<th>Имя</th>
							<th>Проценты</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Гезалов Бахрам И.</td>
							<td>84</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="col-md-6">
			<div class="jumbotron">
				<h4>Топ по процентам</h4><br>

				<table class="table">
					<thead>
						<tr>
							<th>Студент</th>
							<th>Студент</th>
							<th>Проценты</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Гезалов Бахрам И.</td>
							<td>Хадзиев Герман А.</td>
							<td>84</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<?php

require_once 'engine/static/footer.php';












