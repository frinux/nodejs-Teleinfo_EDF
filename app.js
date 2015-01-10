var teleinfo = require('teleinfo');
var util = require('util');
var mysql = require('mysql');
var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

var port = 8080;

//Database connection
function db() {
  var connection = mysql.createConnection({user: 'root', password: 'root', host: 'localhost', port: 3306, database: 'teleinfo'});
  return connection;
}

//Use EJS rather then Jade templating
app.set('view engine', 'ejs');

//Routing
app.get('/', function (req, res) {
  res.render('live');
})

app.get('/graph_daily', function (req, res) {
  res.render('graph_daily');
})

app.get('/graph_hourly', function (req, res) {
  res.render('graph_hourly');
})

app.post('/graph_daily', function (req, res) {

	var connection = db();
	connection.query(' \
		SELECT UNIX_TIMESTAMP(CONCAT(YEAR, "-", MONTH, "-", DAY, " ", "23:59:59"))*1000 AS x, \
		SUM_HCHC_DELTA AS y1, \
		SUM_HCHP_DELTA AS y2, \
		IF(DAYOFWEEK(CONCAT(YEAR, "-", MONTH, "-", DAY))=1 OR DAYOFWEEK(CONCAT(YEAR, "-", MONTH, "-", DAY))=7,"wetrue","wefalse") AS isweekend \
		FROM daily_consumption', function(err, rows){

			if(err)
				console.log("Error Selecting : %s ",err );

			res.json(rows);
	});
})

app.post('/graph_hourly', function (req, res) {

	var connection = db();
	connection.query(' \
		SELECT UNIX_TIMESTAMP(CONCAT(YEAR, "-", MONTH, "-", DAY, " ", HOUR, ":59:59"))*1000 AS x, \
		SUM_HCHC_DELTA AS y1, \
		SUM_HCHP_DELTA AS y2, \
		IF(DAYOFWEEK(CONCAT(YEAR, "-", MONTH, "-", DAY))=1 OR DAYOFWEEK(CONCAT(YEAR, "-", MONTH, "-", DAY))=7,"wetrue","wefalse") AS isweekend \
		FROM hourly_consumption', function(err, rows){

			if(err)
				console.log("Error Selecting : %s ",err );

			res.json(rows);
	});
})

server.listen(port);

//Socket IO listening : live update

var trameEvents = teleinfo('/dev/ttyAMA0');
trameEvents.on('tramedecodee', function (data) {

	//calcul de la puissance instantanée
	data.pinst = data.IINST * 230;

	//send data to socket.io clients
	io.sockets.emit('message', data);
});

//log errors
trameEvents.on('error', function (err) {
	console.log(util.inspect(err));
});

console.log('Server listening on port: '.concat(port));
