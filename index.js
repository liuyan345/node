'use strict';

var express = require("express");
var path = require("path");

var admin = require("./controller/admin");
// var common = require("./controller/common");

var ejs = require("ejs"); // 引入ejs模板

var app = express();

app.set("view engine","ejs");//设置模板类型
app.set('views', path.join(__dirname, 'views')); // 设置模板路径

app.use("/public",express.static("public"));// 设置静态文件目录

//设置路由
app.get("/",function(require,response){
    response.send("this is home page");
});

app.use("/admin",admin);

// 未知路由错误
app.use(function(req,res){
    res.send("页面不存在");
})

// app.get("/test/:str",function(require,response){
    // response.send("this is test page "+require.params.str);
// });


app.listen(8888);// 设置监听端口