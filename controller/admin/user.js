var common = require("../common.js");
var user = {};

user.login = function (req,res,next){
    res.send("this is login page");
}

user.getOrder = function(req,res,next){
    var orderNum = common.getOrderNum();
    res.send(orderNum);
}

module.exports = user;