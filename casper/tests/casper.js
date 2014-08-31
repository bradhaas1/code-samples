// Testing with casperjs
//	Get a casper instance using the create() method.
//	Both the create() function accepts a single options argument which is a standard javascript object:

var sys = require("system");

for(items in sys.args){
	console.log(items.name);
}

var casper = require("casper").create({
	verbose: true,
	loglevel: "debug"
});

