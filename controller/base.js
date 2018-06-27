'use strict';
var express = require('express');
var router = express.Router();


//访问形式：http://node.qkllt.xin/base/read
router.get('/read', function(req, res) {
    res.send("read")
});


module.exports = router;