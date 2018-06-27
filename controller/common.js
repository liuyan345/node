var dateFormat = require("format-datetime");
var common = {};

// 生成订单号
common.getOrderNum = function(){
    var myDate  = new Date();
    return dateFormat(myDate,"yyyyMMddHHiiss")+common.randomNum(1000,9999);
}


//生成从minNum到maxNum的随机数
common.randomNum = function (minNum,maxNum){
    switch(arguments.length){
        case 1:
            return parseInt(Math.random()*minNum+1,10);
            break;
        case 2:
            return parseInt(Math.random()*(maxNum-minNum+1)+minNum,10);
            break;
        default:
            return 0;
            break;
    }
}


module.exports = common;