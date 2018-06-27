'use strict';
var express = require('express');
var router = express.Router();


//访问形式：http://node.qkllt.xin/base/read
router.get('/login', function(req, res) {
    res.send("login")
});

router.get('/plat', function(req, res) {
    res.send("plat")
});

module.exports = admin;