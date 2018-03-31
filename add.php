<?php

require_once 'engine/data/db.php';
require_once 'engine/static/head.php';
require_once 'engine/static/header.php';

?>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="jumbotron" id="drop-area-div">
				<h4>Добавить диплом</h4><br>

				<form method="POST" id="formAddDiploma" enctype="multipart/form-data">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Студент</span>
						<select class="form-control" name="student" id="student">
							<option value="0">-</option>
<?php

// Выбрать список студентов
$q = "SELECT id, CONCAT(firstName, ' ', middleName, ' ', SUBSTR(lastName, 1, 1), '.') AS name
		FROM {$cfg['dbprefix']}_students ORDER BY firstName, middleName";

$res = $db->query($q);

if ($res->num_rows > 0) {
	while ($row = $res->fetch_assoc()) {
		echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
	}
}

?>
						</select>
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Год сдачи</span>
						<input type="text" class="form-control" name="year" id="year" value="<?=date("Y"); ?>">
					</div><br>

					<div class="input-group">
						<input type="file" name="files" class="custom-file-input1">
						<!-- <span class="custom-file-control"></span> -->
					</div><br>

					<div id="progress" class="progress">
						<div class="progress-bar progress-bar-striped" role="progressbar" style="width:0%"
								aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div><br>

					<div class="right">
						<input type="submit" name="addDiploma" class="btn btn-success" value="Добавить">
					</div>
				</form>
			</div>
		</div>

		<div class="col-md-4">
			<div class="jumbotron">
				<h4>Добавить студента</h4><br>

				<form method="POST" id="formAddStudent">
					<div class="input-group">
						<span class="input-group-addon bgcolor" required>Фамилия</span>
						<input type="text" class="form-control" name="firstName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor" required>Имя</span>
						<input type="text" class="form-control" name="middleName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor" required>Отчество</span>
						<input type="text" class="form-control" name="lastName">
					</div><br>

					<div class="input-group">
						<span class="input-group-addon bgcolor">Группа</span>
						<select class="form-control" name="group" id="groupSelect">
							<option value="0">-</option>
<?php

// Выбрать список групп
$q = "SELECT * FROM {$cfg['dbprefix']}_groups ORDER BY name DESC";
$res = $db->query($q);

if ($res->num_rows > 0) {
	while ($row = $res->fetch_assoc()) {
		echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
	}
}

?>
						</select>
					</div><br>

					<div class="right">
						<input type="submit" name="addStudent" class="btn btn-success" value="Добавить">
					</div>
				</form>
			</div>
		</div>

		<div class="col-md-4">
			<div class="jumbotron">
				<h4>Добавить группу</h4><br>

				<form method="POST" id="formAddGroup" name="form">
					<div class="input-group">
						<span class="input-group-addon bgcolor">Имя группы</span>
						<input type="text" class="form-control" id="group" name="group">
					</div><br>

					<div class="right">
						<input type="submit" name="addStudent" class="btn btn-success" value="Добавить">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="styles/js/jq-upload.js"></script>


<script>

$(document).ready(() => {


var formAddDiploma = $('#formAddDiploma'),
	formAddStudent = $('#formAddStudent'),
	formAddGroup = $('#formAddGroup');



// Выбрать список студентов, групп
function getOptions(instance) {
	$.ajax({
		url: 'engine/ajax/getOptions.php',
		type: 'POST',
		data: {instance: instance},
		success: (data) => {
			try {
				data = JSON.parse(data);
			} catch(e) {}


			if (data.err) {
				$.notify(data.err, 'error');
			} else {
				if (instance == 'students') {
					$('#student').html(data.data);
				} else if (instance == 'groups') {
					$('#groupSelect').html(data.data);
				}
			}
		}
	});
}



// Добавить группу в базу
formAddGroup.on('submit', (e) => {
	e.preventDefault();

	$.ajax({
		url: 'engine/ajax/addGroup.php',
		type: 'POST',
		data: formAddGroup.serialize(),
		success: (data) => {
			try {
				data = JSON.parse(data);
			} catch(e) {}


			if (data.err) {
				$.notify(data.err, 'error');
			} else {
				$.notify(data.success, 'success');
				formAddGroup[0].reset();
				getOptions('groups');
			}
		}
	});
});



// Добавить студента в базу
formAddStudent.on('submit', (e) => {
	e.preventDefault();

	$.ajax({
		url: 'engine/ajax/addStudent.php',
		type: 'POST',
		data: formAddStudent.serialize(),
		success: (data) => {
			try {
				data = JSON.parse(data);
			} catch(e) {}


			if (data.err) {
				$.notify(data.err, 'error');
			} else {
				$.notify(data.success, 'success');
				formAddStudent[0].reset();
				getOptions('students');
			}
		}
	});
});



// Загрузить дипломную и добавить в базу
formAddDiploma.jqUpload({
	url: 'engine/ajax/addDiplomas.php',
	dataType: 'json',
	// dataType: 'text',
	allowedTypes: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	extFilter: 'docx',
	getData: () => {
		return {
			student: $('#student').val(),
			year: $('#year').val()
		}
	},
	onBeforeUpload: function() {
		$.notify('Загрузка начата', 'info');
	},
	onUploadProgress: function(percent) {
		$('div.progress-bar').width(percent + '%');
	},
	onUploadSuccess: function(data) {
		try {
			data = JSON.parse(data);
		} catch(e) {}

		console.log("Server Response: \n", data);

		if (data.err) {
			$.notify(data.err, 'error');
			$('div.progress-bar').width('0%');
		} else {
			$.notify('Дипломная работа успешно сохранена', 'success');

			$('div.progress-bar').width('100%');
			formAddDiploma[0].reset();

			setTimeout(() => {
				$('div.progress-bar').width('0%');
			}, 2000);
		}
	},
	onHaventFile: () => {
		$.notify('Вы не выбрали файл', 'warn');
	},
	onUploadError: function(message) {
		console.log(message);
		$.notify('Ошибка загрузки файла: ' + message, 'error');
	},
	onFileTypeError: function(file) {
		$.notify('Файл \'' + file.name + '\' должен быть с расширением ".docx"', 'error');
	},
	onFallbackMode: function(message) {
		$.notify('Браузер не поддерживается: ' + message, 'error');
	}
});


});

</script>

<?php

require_once 'engine/static/footer.php';
