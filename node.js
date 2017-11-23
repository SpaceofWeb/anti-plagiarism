var {exec} = require('child_process');
var fs = require('fs');


var timer = setInterval(() => {

	var e = exec('php test.php', (err, stdout, sdterr) => {
		if (err) {
			say('err, '+err);
			clearInterval(timer);
		}

		if (stdout == 'repeat') {
			say('rep, '+stdout);
		} else {
			say('clearing, '+stdout);
			clearInterval(timer);
		}
	});

	say(e);
	say('interval++');

}, 2000);





function say(s) {
	var m = new Date().getTime();

	console.log(s);

	fs.appendFile('text.txt', 'stdout: '+s+' : '+m+"\n", (err) => {
		if (err) throw err;
	});
}
