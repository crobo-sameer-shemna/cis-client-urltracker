
require("./settings.js");

system = require('system');

// get url from cli
if (system.args.length < 2) {
    console.log('Usage: phantomjs map.js <device>');
    phantom.exit(1);
} else {
    device = (system.args[1] !== '') ? system.args[1] : 'Linux_Chrome';
}

selectedDevice = devices[device];



var link = 'http://whatismycountry.com/';
//link = 'https://check.torproject.org/';


var webPage = require('webpage');
var page = webPage.create();

// pretend to be a different browser, helps with some shitty browser-detection scripts
page.settings.userAgent = selectedDevice.userAgent;
page.viewportSize = { width: selectedDevice.width, height: selectedDevice.height };
page.zoomFactor = 1;

page.open(link, function (status) {
    if (status !== 'success') {
        console.log('Unable to access network');
    } else {
        //page.render('country.png');
        console.log(page.content);
        var imgs = page.evaluate(function() {
          return document.images;
        });
        for (var i = 0; i < imgs.length; i++) {
          if (imgs[i])
            console.log("source: " + imgs[i].src);
        }
    }
    phantom.exit();
});


///sudo pmset -a sleep 0
///http://apple.stackexchange.com/questions/120639/how-can-i-stop-my-macbook-pro-from-automatically-sleeping-when-i-lock-the-screen
