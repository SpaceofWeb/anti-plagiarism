// Скрипт на node.js рекурсивно запускает php скрипт для сравнения дипломных

var {exec} = require('child_process');
var fs = require('fs');




// Проверяем запущен ли уже таймер
fs.readFile(__dirname+'/process.txt', (err, data) => {
	if (err) throw err;

	if (data == 1) {
		die();
	} else if (data == 0) {
		fs.writeFile(__dirname+'/process.txt', '1', (err) => {
			if (err) throw err;

			repeat();
		});
	}
});



// Инициализация логов
var d = new Date();
var logFile = __dirname+'/logs/'+d.getFullYear()+(d.getMonth()+1)+'.log';


if (!fs.existsSync(__dirname+'/logs/')) {
	fs.mkdirSync(__dirname+'/logs/');
}

fs.appendFileSync(logFile, "start session\n");


// Выход
function exit() {
	fs.appendFile(logFile, 'exit process'+"\n", (err) => {
		if (err) throw err;

		fs.writeFile(__dirname+'/process.txt', '0', (err) => {
			if (err) throw err;

			process.exit();
		});
	});
}


// Умирание процесса
function die() {
	fs.appendFile(logFile, 'die process'+"\n", (err) => {
		if (err) throw err;

		process.exit();
	});
}


// Написать в лог
function say(s, cb) {
	var m = new Date().getTime();

	fs.appendFile(logFile, s+"\n", (err) => {
		if (err) throw err;

		cb();
	});
}


// Рекурсия
function repeat() {
	var e = exec('php '+__dirname+'/compare.php', (err, stdout, sdterr) => {
		if (err) {
			say('err: '+err, () => {exit();});
		}

		if (stdout == 'repeat') {
			// say('repeat', () => {});
			repeat();
		} else {
			say('stop, stdout: '+stdout, () => {exit();});
		}
	});

}
