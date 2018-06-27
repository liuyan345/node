'use strict';
var express = require('express');
var admin = express.Router();


//访问形式：http://node.qkllt.xin/base/read
admin.get('/login', function(req, res) {
    res.send("login")
});

admin.get('/plat', function(req, res) {
    res.send("plat")
});

module.exports = admin;