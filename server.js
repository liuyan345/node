// var http = require("http");

// http.createServer(function(request,response){
// 	response.writeHead(200,{'Content-Type':'text/plain'});
// 	response.end("Hello World");
// }).listen(8888);



var http = require("http");

var url = require("url");

function start(route){
	function onRequest(request,response) {
		var pathname = url.parse(request.url).pathname;
		console.log(pathname);
		
		route(pathname);

		response.writeHead(200,{"Content-Type":"text/plain"});
		response.write("Hello World "+pathname);
		response.end();
	}

	http.createServer(onRequest).listen(8888);
	console.log("Server has started.");
}

exports.start = start;


