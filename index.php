<?php


require_once 'engine/data/db.php';
require_once 'engine/static/head.php';
require_once 'engine/static/header.php';



// Пагинация
$limit = [];

foreach (['t'=> 0] as $key => $val) {
	$limit[$key] = (isset($_GET['p'][$key])) ? ($_GET['p'][$key]-1)*$cfg['rowsPerPage'] : 0;
	$pag[$key] = (isset($_GET['p'][$key])) ? $_GET['p'][$key] : 1;
}


?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="jumbotron pb-md-2">
				<h4>Search diplomas</h4><br>
					<form class="form-inline">
						<div class="input-group mb-2 mr-sm-2 mb-sm-0">
							<div class="input-group-addon">Student1</div>
							<input type="text" class="form-control" id="tS1" placeholder="FML">
							<input type="hidden" id="tS1h">
						</div>

						<div class="input-group mb-2 mr-sm-2 mb-sm-0">
							<div class="input-group-addon">Student2</div>
							<input type="text" class="form-control" id="tS2" placeholder="FML">
							<input type="hidden" id="tS2h">
						</div>
					</form>

				<table class="table table-stripped">
					<thead>
						<tr>
							<th>Student</th>
							<th>Student2</th>
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



		<div class="col-md-5">
			<div class="jumbotron pb-md-2">
				<h4>Сортировка по студенту</h4><br>

				<div class="form-group row">
					<div class="col-md-12">
						<div class="input-group">
							<span class="input-group-addon bgcolor">Студент</span>
							<input type="text" id="search" class="form-control">
							<input type="hidden" id="studentHidden">
						</div>
					</div>
				</div>

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

// Выбрать количество дипломных
$q = "SELECT COUNT(id) AS count
		FROM {$cfg['dbprefix']}_percentage
		WHERE percent IS NOT NULL";

$resCount = $db->query($q);

if ($resCount->num_rows == 1) {
	$count = $resCount->fetch_assoc()['count'];
} else {
	$count = 0;
}

$all = ceil($count/$cfg['rowsPerPage']);
$all = ($all == 0) ? 1 : $all;


// Выбрать топ дипломных
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
		WHERE P.percent IS NOT NULL
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
			<td colspan="3"><font color="red">Ни одной записи не найдено</font></td>
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

<link href="styles/css/jquery-ui.css" rel="stylesheet">
<script src="styles/js/jquery-ui.js"></script>

<script>

$(document).ready(() => {

var rowsPerPage = <?=$cfg['rowsPerPage']; ?>,
	diplomas = $('#diplomas'),
	topDiplomas = $('#topDiplomas');


// Автодополнение для поиска
// $('#search').autocomplete({
// 	source: 'engine/ajax/getOptions.php',
// 	select: (event, ui) => {
// 		$('#studentHidden').val(ui.item.id);
// 		getStudents(ui.item.id, 'diplomas');
// 	}
// });

$('#tS1').autocomplete({
	source: 'engine/ajax/getOptions.php',
	select: (event, ui) => {
		$('#tS1h').val(ui.item.id);
		getStudents(ui.item.id, 'diplomas');
	}
});

$('#tS2').autocomplete({
	source: 'engine/ajax/getOptions.php',
	select: (event, ui) => {
		$('#tS2h').val(ui.item.id);
		getStudents(ui.item.id, 'diplomas');
	}
});


// Пагинация дипломных по студенту
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


// Пагинация дипломных
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





// Выбрать список студентов
function getStudents(id, instance) {
	$.ajax({
		url: 'engine/ajax/searchMain.php',
		type: 'POST',
		data: {student: $('#tS1h').val(), student2: $('#tS2h').val(), instance: instance},
		success: (data) => {
			try {
				data = JSON.parse(data);
			} catch(e) {}

			// $('#tS1h').val('');
			// $('#tS2h').val('');

			var html = '';
			for (var i in data.data) {
				html += '<tr>\
							<td>'+data.data[i].name1+'</td>\
							<td>'+data.data[i].name2+'</td>\
							<td class="right">'+data.data[i].percent+'</td>\
						</tr>';
			}

			diplomas.val('');
			diplomas.html(html);

			setPagination(1, data.count, 'diplomas');
		}
	});
}


// Поиск
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


// Создание и установка пагинации
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


// Выбрать индекс страницы для пагинации
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


// Массив в строку запроса
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
