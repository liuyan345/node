'use strict';
var express = require('express');
var router = express.Router();

router.get('/read', function(req, res) {
    res.send("read")
});


module.exports = router;