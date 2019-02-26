jsdiff = require('string-similarity');
mysql = require('mysql');




var con = mysql.createConnection({
	host: 'localhost',
	user: 'root',
	password: 'root',
	database: 'ap'
});

con.connect(function(err) {
	if (err) throw err;
	console.log("Connected!");

	var q = 'SELECT P.id, D.text AS d, D2.text AS d2\
		FROM ap_percentage P\
		LEFT JOIN ap_diplomas D ON P.d1_id=D.id\
		LEFT JOIN ap_diplomas D2 ON P.d2_id=D2.id\
		WHERE P.id = 14\
		ORDER BY P.id LIMIT 1';

	con.query(q, function (err, res) {
		if (err) throw err;
		console.log('Result: ', res[0].id);

		var s1 = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci itaque, accusantium dolores voluptatum eaque nulla numquam officia dolore maiores veritatis! Impedit commodi quod unde adipisci, minima delectus nihil iusto. Odio.'
		var s2 = 'Lorem ipsum dolor sit аmet, consectetur adipisicing elit. Adipisci itaque, accusantium dolores voluptatum eaque nulla numquam officia dolore maiores veritatis! Impedit commodi quod unde adipisci, minima delectus nihil iusto. Odio.'

		// var p = jsdiff.compareTwoStrings(res[0].d, res[0].d2);
		var p = jsdiff.compareTwoStrings(s1, s2);
		console.log('percent: ', p);
	});

});

