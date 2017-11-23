<?php

require_once 'engine/data/db.php';
require_once 'engine/static/head.php';
require_once 'engine/static/header.php';



// Pagination
$limit = [];

foreach (['d'=> 0, 's'=> 0, 'g'=> 0] as $key => $val) {
	$limit[$key] = (isset($_GET['p'][$key])) ? ($_GET['p'][$key]-1)*$cfg['rowsPerPage'] : 0;
	$pag[$key] = (isset($_GET['p'][$key])) ? $_GET['p'][$key] : 1;
}


?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="jumbotron pb-md-1">
				<h4>Изменить диплом</h4><br>

				
				<form id="dForm" class="form-inline mt-2 mt-md-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="dSearch" class="form-control" placeholder="Search">
							</div>
						</div>
					</div>
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Диплом студента</th>
							<th class="right">Действия</th>
						</tr>
					</thead>
					<tbody id="tableDiplomas">

<?php

// Get diplomas count
$q = "SELECT COUNT(D.id) AS count 
		FROM {$cfg['dbprefix']}_diplomas D 
		LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id ";

$resCount = $db->query($q);
if ($resCount->num_rows == 1) {
	$count = $resCount->fetch_assoc()['count'];
} else {
	$count = 0;
}

$all = ceil($count/$cfg['rowsPerPage']);


// Get diplomas
$q = "SELECT D.id, CONCAT(S.firstName, ' ', SUBSTR(S.middleName, 1, 1), '. ', SUBSTR(S.lastName, 1, 1), '.') AS name 
		FROM {$cfg['dbprefix']}_diplomas D 
		LEFT JOIN {$cfg['dbprefix']}_students S ON S.id=D.student_id 
		ORDER BY S.firstName, S.middleName, S.lastName 
		LIMIT ".$limit['d'].','.$cfg['rowsPerPage'];


$res = $db->query($q);
if ($res->num_rows > 0) {

	while ($row = $res->fetch_assoc()) {

?>

<tr>
	<td><?=$row['name']; ?></td>
	<td class="right">
		<button type="button" class="btn btn-warning btn-sm" 
				data-toggle="modal" data-target="#diplomas" data-id="<?=$row['id']; ?>">♻</button>
		<button type="button" class="btn btn-danger btn-sm btnDelete" data-id="<?=$row['id']; ?>">X</button>
	</td>
</tr>

<?php

	}

} else {
	echo '<tr><td colspan="2"><font color="red">Ни одной записи не найдено</font></td></tr>';
}


?>

					</tbody>
				</table>
				<nav aria-label="Diplomas search results">
					<ul id="diplomasPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first<?=($pag['d'] <= 1) ? ' disabled' : ''; ?>">
							<a class="page-link first" href="#" aria-label="Previous" 
									data-page="<?=($pag['d'] <= 1) ? 1 : $pag['d']-1; ?>">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link">
								<?=$pag['d']; ?>/<?=$all; ?>
							</span>
						</li>
						<li class="page-item last<?=($pag['d'] >= $all) ? ' disabled' : ''; ?>">
							<a class="page-link last" href="#" aria-label="Next" 
									data-page="<?=($pag['d'] >= $all) ? $all : $pag['d']+1; ?>">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<div class="col-md-4">
			<div class="jumbotron pb-md-1">
				<h4>Изменить студента</h4><br>

				
				<form id="sForm" class="form-inline mt-2 mt-md-0 was-validated">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="sSearch" class="form-control is-invalid" placeholder="Search">
							</div>
						</div>
					</div>
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Студент</th>
							<th class="right">Действия</th>
						</tr>
					</thead>
					<tbody id="tableStudents">

<?php

// Get students count
$q = "SELECT COUNT(id) AS count FROM {$cfg['dbprefix']}_students ";

$resCount = $db->query($q);
if ($resCount->num_rows == 1) {
	$count = $resCount->fetch_assoc()['count'];
} else {
	$count = 0;
}

$all = ceil($count/$cfg['rowsPerPage']);


// Get students
$q = "SELECT id, CONCAT(firstName, ' ', SUBSTR(middleName, 1, 1), '. ', SUBSTR(lastName, 1, 1), '.') AS name 
		FROM {$cfg['dbprefix']}_students 
		ORDER BY name LIMIT ".$limit['s'].','.$cfg['rowsPerPage'];


$res = $db->query($q);
if ($res->num_rows > 0) {

	while ($row = $res->fetch_assoc()) {

?>

<tr>
	<td><?=$row['name']; ?></td>
	<td class="right">
		<button type="button" class="btn btn-warning btn-sm" 
				data-toggle="modal" data-target="#students" data-id="<?=$row['id']; ?>">♻</button>
		<button type="button" class="btn btn-danger btn-sm btnDelete" data-id="<?=$row['id']; ?>">X</button>
	</td>
</tr>

<?php

	}

} else {
	echo '<tr><td colspan="2"><font color="red">Ни одной записи не найдено</font></td></tr>';
}


?>

					</tbody>
				</table>
				<nav aria-label="Students search results">
					<ul id="studentsPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first <?=($pag['s'] <= 1) ? ' disabled' : ''; ?>">
							<a class="page-link first" href="#" aria-label="Previous" 
									data-page="<?=($pag['s'] <= 1) ? 1 : $pag['s']-1; ?>">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link">
								<?=$pag['s']; ?>/<?=$all; ?>
							</span>
						</li>
						<li class="page-item last<?=($pag['s'] >= $all) ? ' disabled' : ''; ?>">
							<a class="page-link last" href="#" aria-label="Next" 
									data-page="<?=($pag['s'] >= $all) ? $all : $pag['s']+1; ?>">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Next</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<div class="col-md-4">
			<div class="jumbotron pb-md-1">
				<h4>Изменить группу</h4><br>

				<form id="gForm" class="form-inline mt-2 mt-md-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="input-group">
								<input type="search" id="gSearch" class="form-control" placeholder="Search">
							</div>
						</div>
					</div>
				</form>
				<table class="table">
					<thead>
						<tr>
							<th>Группа</th>
							<th class="right">Действия</th>
						</tr>
					</thead>
					<tbody id="tableGroups">

<?php

// Get groups count
$q = "SELECT COUNT(id) AS count FROM {$cfg['dbprefix']}_groups ";

$resCount = $db->query($q);
if ($resCount->num_rows == 1) {
	$count = $resCount->fetch_assoc()['count'];
} else {
	$count = 0;
}

$all = ceil($count/$cfg['rowsPerPage']);


// Get groups
$q = "SELECT id, name FROM {$cfg['dbprefix']}_groups 
		ORDER BY name LIMIT ".$limit['g'].','.$cfg['rowsPerPage'];


$res = $db->query($q);
if ($res->num_rows > 0) {

	while ($row = $res->fetch_assoc()) {

?>

<tr>
	<td><?=$row['name']; ?></td>
	<td class="right">
		<button type="button" class="btn btn-warning btn-sm" 
				data-toggle="modal" data-target="#groups" data-id="<?=$row['id']; ?>">♻</button>
		<button type="button" class="btn btn-danger btn-sm btnDelete" data-id="<?=$row['id']; ?>">X</button>
	</td>
</tr>

<?php

	}

} else {
	echo '<tr><td colspan="2"><font color="red">Ни одной записи не найдено</font></td></tr>';
}


?>

					</tbody>
				</table>
				<nav aria-label="Groups search results">
					<ul id="groupsPag" class="pagination pagination-sm justify-content-center">
						<li class="page-item first<?=($pag['g'] <= 1) ? ' disabled' : ''; ?>">
							<a class="page-link first" href="#" aria-label="Previous" 
									data-page="<?=($pag['g'] <= 1) ? 1 : $pag['g']-1; ?>">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Previous</span>
							</a>
						</li>
						<li class="page-item disabled">
							<span class="page-link">
								<?=$pag['g']; ?>/<?=ceil($all); ?>
							</span>
						</li>
						<li class="page-item last<?=($pag['g'] >= $all) ? ' disabled' : ''; ?>">
							<a class="page-link last" href="#" aria-label="Next" 
									data-page="<?=($pag['g'] >= $count) ? $count : $pag['g']+1; ?>">
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








<div class="modal fade" id="diplomas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Изменить диплом</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Студент</span>
						<select class="form-control" name="student">
							<option>-</option>
							<option>Хадзиев Герман</option>
							<option>firstName middleName</option>
						</select>
					</div><br>

					<div class="input-group">
						<input type="file" name="doc" class="custom-file-input">
						<span class="custom-file-control"></span>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Год защиты</span>
						<input type="text" class="form-control" name="year" value="<?=date("Y"); ?>">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success">Сохранить</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="students" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Изменить студента</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Фамилия</span>
						<input type="text" class="form-control" name="firstName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Имя</span>
						<input type="text" class="form-control" name="middleName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Отчество</span>
						<input type="text" class="form-control" name="lastName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Грппа</span>
						<select class="form-control" name="group">
							<option>-</option>
							<option>group</option>
							<option>group</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success">Сохранить</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Change Group</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Название группы</span>
						<input type="text" class="form-control" name="group">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success">Сохранить</button>
			</div>
		</div>
	</div>
</div>



<script>

$(document).ready(() => {


var rowsPerPage = <?=$cfg['rowsPerPage']; ?>,
	dSearch = $('#dSearch'),
	sSearch = $('#sSearch'),
	gSearch = $('#gSearch'),
	tableDiplomas  = $('#tableDiplomas'),
	tableStudents  = $('#tableStudents'),
	tableGroups    = $('#tableGroups');



// When we searching
$('#dForm').on('input', (e) => {
	e.preventDefault();

	var p = getPage();
	p.d = 1;

	search(dSearch.val(), 'diplomas', p, (data, count) => {
		setResults(data, 'diplomas');
		setPagination(p.d, count, 'diplomas');

		history.pushState({}, '', '?'+toQueryString(p));
	});
});


$('#sForm').on('input', (e) => {
	e.preventDefault();

	var p = getPage();
	p.s = 1;

	search(sSearch.val(), 'students', p, (data, count) => {
		setResults(data, 'students');
		setPagination(p.s, count, 'students');

		history.pushState({}, '', '?'+toQueryString(p));
	});
});


$('#gForm').on('input', (e) => {
	e.preventDefault();

	var p = getPage();
	p.g = 1;

	search(gSearch.val(), 'groups', p, (data, count) => {
		setResults(data, 'groups');
		setPagination(p.g, count, 'groups');

		history.pushState({}, '', '?'+toQueryString(p));
	});
});




// if we changing pages (pagination)
$('#diplomasPag a').on('click', (e) => {
	e.preventDefault();

	var p = getPage();
	p.d = e.currentTarget.dataset.page;

	search(dSearch.val(), 'diplomas', p, (data, count) => {
		setResults(data, 'diplomas');
		setPagination(p.d, count, 'diplomas');

		history.pushState({}, '', '?'+toQueryString(p));
	});
});


$('#studentsPag a').on('click', (e) => {
	e.preventDefault();

	var p = getPage();
	p.s = e.currentTarget.dataset.page;

	search(sSearch.val(), 'students', p, (data, count) => {
		setResults(data, 'students');
		setPagination(p.s, count, 'students');

		history.pushState({}, '', '?'+toQueryString(p));
	});
});


$('#groupsPag a').on('click', (e) => {
	e.preventDefault();

	var p = getPage();
	p.g = e.currentTarget.dataset.page;

	search(gSearch.val(), 'groups', p, (data, count) => {
		setResults(data, 'groups');
		setPagination(p.g, count, 'groups');

		history.pushState({}, '', '?'+toQueryString(p));
	});
});











// Searching
function search(s, instance, pagin, cb) {
	// console.log(pagin);
	$.ajax({
		url: 'engine/ajax/search.php',
		type: 'POST',
		cache: false,
		data: {'s': s, 'instance': instance, 'pagin': pagin},
		success: (data) => {
			console.log(data);
			try {
				data = JSON.parse(data);
			} catch(e) {}

			// if error
			if (data.err) {
				$.notify(data.err, 'error');
				return;
			}

			// if warn
			// if (data.warn) {
				// $.notify(data.warn, 'warn');
				// return;
			// }


			cb(data.data, data.count);
		}
	});
}



// Set search results
function setResults(data, instance) {
	var html = '';

	for (var i in data) {
		html += '<tr>\
					<td>'+data[i].name+'</td>\
					<td class="right">\
						<button type="button" class="btn btn-warning btn-sm" \
								data-toggle="modal" data-target="#'+instance+'" data-id="'+data[i].id+'">♻</button>\
						<button type="button" class="btn btn-danger btn-sm btnDelete" data-id="'+data[i].id+'">X</button>\
					</td>\
				</tr>';
	}


	switch(instance) {
		case 'diplomas':
			tableDiplomas.val('');
			tableDiplomas.html(html);
		break;
		case 'students':
			tableStudents.val('');
			tableStudents.html(html);
		break;
		case 'groups':
			tableGroups.val('');
			tableGroups.html(html);
		break;
	}
}



// Build and set pagination for results
function setPagination(current, count, instance) {
	current = parseInt(current);
	count = parseInt(count);

	var all = Math.ceil(count/rowsPerPage);
	all = (all == 0) ? 1 : all;

	console.log(current, count, instance);

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




