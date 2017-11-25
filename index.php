<?php


require_once 'engine/data/db.php';
require_once 'engine/static/head.php';
require_once 'engine/static/header.php';

// $start = microtime(true);


// exec('#!/usr/bin/php test.php > /dev/null &');
// exec('/usr/bin/php test.php', $o);
// exec('node node.js > /dev/null &');
// exec('node node.js', $o);
// print_r($o);

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


// Pagination
$limit = [];

foreach (['t'=> 0] as $key => $val) {
	$limit[$key] = (isset($_GET['p'][$key])) ? ($_GET['p'][$key]-1)*$cfg['rowsPerPage'] : 0;
	$pag[$key] = (isset($_GET['p'][$key])) ? $_GET['p'][$key] : 1;
}


?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-5">
			<div class="jumbotron pb-md-2">
				<h4>Сортировка по студенту</h4><br>

				<!-- <form class="form-inline"> -->
					<div class="form-group row">
						<!-- <label>Example select</label> -->
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon bgcolor">Студент</span>
								<input type="text" id="search" class="form-control">
								<input type="hidden" id="studentHidden">
								<!-- <select class="form-control">
									<option>-</option>
									<option>Хадзиев Герман</option>
									<option>Хадзиев Герман</option>
								</select> -->
							</div>
						</div>
					</div>
				<!-- </form><br> -->

				<table class="table table-stripped">
					<thead>
						<tr>
							<th>Имя</th>
							<th class="right">%</th>
						</tr>
					</thead>
					<tbody id="diplomas"></tbody>
				</table>
				<nav aria-label="Diplomas search results">
					<ul id="diplomasPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first disabled">
							<a class="page-link first" href="#" aria-label="Previous" 
									data-page="1">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link">
								1/1
							</span>
						</li>
						<li class="page-item last disabled">
							<a class="page-link last" href="#" aria-label="Next" 
									data-page="2">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<div class="col-md-7">
			<div class="jumbotron pb-md-2">
				<h4>Топ по процентам</h4><br>

				<table class="table">
					<thead>
						<tr>
							<th>Студент</th>
							<th>Студент</th>
							<th class="right">%</th>
						</tr>
					</thead>
					<tbody id="topDiplomas">

<?php

// Get top diplomas count
$q = "SELECT COUNT(P.id) AS count FROM {$cfg['dbprefix']}_percentage P ";
$resCount = $db->query($q);

if ($resCount->num_rows == 1) {
	$count = $resCount->fetch_assoc()['count'];
} else {
	$count = 0;
}

$all = ceil($count/$cfg['rowsPerPage']);


// Get top diplomas
$q = "SELECT P.percent, 
			CONCAT(S.firstName, ' ', SUBSTR(S.middleName, 1, 1), '. ', 
									SUBSTR(S.lastName, 1, 1), '.') AS name, 
			CONCAT(S2.firstName, ' ', SUBSTR(S2.middleName, 1, 1), '. ', 
									SUBSTR(S2.lastName, 1, 1), '.') AS name2 
		FROM ap_percentage P 
		LEFT JOIN ap_diplomas D ON D.id=P.d1_id 
		LEFT JOIN ap_students S ON S.id=D.student_id 
		LEFT JOIN ap_diplomas D2 ON D2.id=P.d2_id 
		LEFT JOIN ap_students S2 ON S2.id=D2.student_id 
		ORDER BY P.percent DESC 
		LIMIT ".$limit['t'].','.$cfg['rowsPerPage'];

$res = $db->query($q);
if ($res->num_rows > 0) {
	while ($row = $res->fetch_assoc()) {
		echo '<tr>
				<td>'.$row['name'].'</td>
				<td>'.$row['name2'].'</td>
				<td class="right">'.$row['percent'].'</td>
			</tr>';
	}
} else {
	echo '<tr>
			<td colspan="3"><font color="red">Not found</font></td>
		</tr>';
}

?>

					</tbody>
				</table>
				<nav aria-label="Top diplomas search results">
					<ul id="topDiplomasPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first<?=($pag['t'] <= 1) ? ' disabled' : ''; ?>">
							<a class="page-link first" href="#" aria-label="Previous" 
									data-page="<?=($pag['t'] <= 1) ? 1 : $pag['t']-1; ?>">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link">
								<?=$pag['t']; ?>/<?=ceil($all); ?>
							</span>
						</li>
						<li class="page-item last<?=($pag['t'] >= $all) ? ' disabled' : ''; ?>">
							<a class="page-link last" href="#" aria-label="Next" 
									data-page="<?=($pag['t'] >= $count) ? $count : $pag['t']+1; ?>">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

$(document).ready(() => {

var rowsPerPage = <?=$cfg['rowsPerPage']; ?>,
	diplomas = $('#diplomas'),
	topDiplomas = $('#topDiplomas');



$('#search').autocomplete({
	source: 'engine/ajax/getOptions.php',
	select: (event, ui) => {
		$('#studentHidden').val(ui.item.id);
		getStudents(ui.item.id, 'diplomas');
	}
});



$('#diplomasPag a').on('click', (e) => {
	e.preventDefault();

	var p = getPage();
	p.d = e.currentTarget.dataset.page;

	search('', 'diplomas', p, (data, count) => {
		var html = '';

		for (var i in data) {
			html += '<tr>\
						<td>'+data[i].name+'</td>\
						<td class="right">'+data[i].percent+'</td>\
					</tr>';
		}

		diplomas.val('');
		diplomas.html(html);

		setPagination(p.d, count, 'diplomas');
	});
});


$('#topDiplomasPag a').on('click', (e) => {
	e.preventDefault();

	var p = getPage();
	p.t = e.currentTarget.dataset.page;

	search('', 'topDiplomas', p, (data, count) => {
		var html = '';

		for (var i in data) {
			html += '<tr>\
						<td>'+data[i].name+'</td>\
						<td>'+data[i].name2+'</td>\
						<td class="right">'+data[i].percent+'</td>\
					</tr>';
		}

		topDiplomas.val('');
		topDiplomas.html(html);

		setPagination(p.t, count, 'topDiplomas');

		history.pushState({}, '', '?'+toQueryString(p));
	});
});





// Get students list
function getStudents(id, instance) {
	$.ajax({
		url: 'engine/ajax/searchMain.php',
		type: 'POST',
		data: {student: id, instance: instance},
		success: (data) => {
			try {
				data = JSON.parse(data);
			} catch(e) {}

			var html = '';
			for (var i in data.data) {
				html += '<tr>\
							<td>'+data.data[i].name+'</td>\
							<td class="right">'+data.data[i].percent+'</td>\
						</tr>';
			}

			diplomas.val('');
			diplomas.html(html);

			setPagination(1, data.count, 'diplomas');
		}
	});
}


// Searching
function search(s, instance, pagin, cb) {
	$.ajax({
		url: 'engine/ajax/searchMain.php',
		type: 'POST',
		cache: false,
		data: {'student': $('#studentHidden').val(), 'instance': instance, 'pagin': pagin},
		success: (data) => {
			try {
				data = JSON.parse(data);
			} catch(e) {}

			// if error
			if (data.err) {
				$.notify(data.err, 'error');
				return;
			}

			cb(data.data, data.count);
		}
	});
}


// Build and set pagination for results
function setPagination(current, count, instance) {
	current = (parseInt(current)) ? parseInt(current) : 1;
	count = parseInt(count);

	var all = Math.ceil(count/rowsPerPage);
	all = (all == 0) ? 1 : all;

	if (current <= 1) {
		$('#'+instance+'Pag').find('li.first').addClass('disabled');
		$('#'+instance+'Pag').find('li.first>a').attr('data-page', 1);
	} else {
		$('#'+instance+'Pag').find('li.first').removeClass('disabled');
		$('#'+instance+'Pag').find('li.first>a').attr('data-page', current-1);
	}


	$('#'+instance+'Pag').find('li>span').text(current+'/'+all);


	if (current >= all) {
		$('#'+instance+'Pag').find('li.last').addClass('disabled');
		$('#'+instance+'Pag').find('li.last>a').attr('data-page', count);
	} else {
		$('#'+instance+'Pag').find('li.last').removeClass('disabled');
		$('#'+instance+'Pag').find('li.last>a').attr('data-page', current+1);
	}

}


// Get page indexes
function getPage() {
	var s = window.location.search.substring(1).split('&');
	var p = {};

	for (i in s) {
		if (/p\[(\w+)\]=(\d*)/.test(s[i])) {
			var m = /p\[(\w+)\]=(\d*)/.exec(s[i]);
			p[m[1]] = m[2];
		}
	}

	return p;
}


// Arrray to querystring
function toQueryString(a) {
	var out = [];

	for(key in a) {
		out.push('p[' + key + ']=' + encodeURIComponent(a[key]));
	}

	return out.join('&');
}



});

</script>

<?php

require_once 'engine/static/footer.php';












