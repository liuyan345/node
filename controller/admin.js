'use strict';
var express = require('express');
var admin = express.Router();

// 中间件处理程序
admin.use(function(req,res,next){
    console.log("middel1");
    next();
});

admin.use(function(req,res,next){
    console.log("middel2");
    next();
});

//访问形式：http://node.qkllt.xin/base/read
admin.get('/login', function(req, res) {
    res.send("login")
});

admin.get('/plat', function(req, res) {
    res.send("plat")
});

module.exports = admin;