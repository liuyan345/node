var user = {};

user.login = function (req,res,next){
    res.send("this is login page");
}

module.exports = user;