// var http = require("http");

// http.createServer(function(request,response){
// 	response.writeHead(200,{'Content-Type':'text/plain'});
// 	response.end("Hello World");
// }).listen(8888);



// var http = require("http");

// var url = require("url");

// function start(route){
// 	function onRequest(request,response) {
// 		var pathname = url.parse(request.url).pathname;
// 		console.log(pathname);
		
// 		route(pathname);

// 		response.writeHead(200,{"Content-Type":"text/plain"});
// 		response.write("Hello World "+pathname);
// 		response.end();
// 	}

// 	http.createServer(onRequest).listen(8888);
// 	console.log("Server has started.");
// }

// exports.start = start;



var http = require("http");
var fs = require("fs");
var url = require("url");

http.createServer(function(require,response){
	var pathname = url.parse(request.url).pathname;
	console.log(pathname);
	console.log(pathname.substr(1));
	fs.readFile(pathname.substr(1),function(err,data){
		if(err){
			console.log(err);
			response.writeHead(404,{'Content-Type':'text/html'});
		}else{
			response.writeHead(200,{'Content-Type':'text/html'});
			response.write(data.toString());
		}
		response.end();
	})

}).listen("8888");

