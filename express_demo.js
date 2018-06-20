var express = require("express");

var app = express();

app.get('/',function(require,response){
	response.send("this is express web");
})

app.get('/test',function(require,response){
	response.send("this is express test page");
})


var server = app.listen(8888,function(){
	var host = server.address().address;
	var port = server.address().port;

	console.log("http://%s:%s",host,port);
})