
require("./settings.js");

system = require('system');

// get url from cli
if (system.args.length < 2) {
    console.log('Usage: phantomjs urltracker.js <device>');
    phantom.exit(1);
} else {
    device = (system.args[1] !== '') ? system.args[1] : 'Linux_Chrome';
}

selectedDevice = devices[device];

result = {};

function checkClient(){
  var link = proxy_link;

  page = require('webpage').create();
  page.settings.userAgent = selectedDevice.userAgent;
  page.viewportSize = { width: selectedDevice.width, height: selectedDevice.height };
  page.zoomFactor = 1;

  page.open(link, function (status) {
      if (status !== 'success') {
          console.log('Unable to access proxy');
          if(debug){
            console.log('@ checkClient');
          }
          phantom.exit();
      } else {
          //page.render('client.png');
          var resultObject = JSON.parse(page.plainText);
          resultObject.timezone = resultObject.time_zone ? resultObject.time_zone : resultObject.timezone;
          resultObject.region = resultObject.region_name ? resultObject.region_name : resultObject.region;
          resultObject.country = resultObject.country_name ? resultObject.country_name : resultObject.country;
          resultObject.zip_code = resultObject.region_code ? resultObject.region_code : resultObject.zip_code;
          //handle null values
          resultObject.city = resultObject.city ? resultObject.city : '';
          resultObject.region = resultObject.region ? resultObject.region : '';
          resultObject.zip_code = resultObject.zip_code ? resultObject.zip_code : '';
          resultObject.timezone = resultObject.timezone ? resultObject.timezone : '';
          if(debug){
            console.log('IP Address: '+resultObject.ip);
            console.log('Location: ' + resultObject.city + ', ' + resultObject.region + ', ' + resultObject.country + ', ' + resultObject.zip_code);
            console.log('TimeZone: ' + resultObject.timezone + ' (' + resultObject.latitude + ', ' + resultObject.longitude + ')');
          }else{
            result.client = resultObject;
          }

          checkDevice();
      }
      //phantom.exit();
  });

}

function checkDevice(){
  var link = device_link;

  page = require('webpage').create();
  page.settings.userAgent = selectedDevice.userAgent;
  page.viewportSize = { width: selectedDevice.width, height: selectedDevice.height };
  page.zoomFactor = 1;

  page.open(link, function (status) {
      if (status !== 'success') {
        console.log('Unable to access proxy');
        if(debug){
          console.log('@ checkDevice');
        }
          //phantom.exit();
      } else {
          //page.render('client.png');
          var resultObject = JSON.parse(page.plainText);
          if(debug){
            console.log('OS: '+resultObject.OS);
            console.log('Browser: '+resultObject.BROWSER);
            console.log('HTTP_USER_AGENT: '+resultObject.HTTP_USER_AGENT);
            console.log('REMOTE_ADDR: '+resultObject.REMOTE_ADDR);
            console.log('HTTP_CLIENT_IP: '+resultObject.HTTP_CLIENT_IP);
            console.log('HTTP_X_FORWARDED_FOR: '+resultObject.HTTP_X_FORWARDED_FOR);
            console.log('HTTP_X_REAL_IP: '+resultObject.HTTP_X_REAL_IP);
          }else{
            result.device = resultObject;
            console.log(JSON.stringify(result));
          }
      }
      phantom.exit();
  });

}


checkClient();

var scripttimeoutHandle = setTimeout(function(){
    if(debug){
      if(final_url.replace(/\/$/,"") != url.replace(/\/$/,"")){
          console.log('Finished Timeout');
      }else{
          console.log('Error Timeout');
      }
    }
    phantom.exit();
}, 60000);
