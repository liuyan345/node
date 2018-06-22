
function apple(require,response) {
    // console.log("About to route a request for " + pathname);
    response.send("this is test page "+require.params.str);
}

exports.base = base;
