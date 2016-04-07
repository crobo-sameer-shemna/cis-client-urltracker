
require("./settings.js");
///HMA Pass Croboloves2015

system = require('system');

// get url from cli
if (system.args.length < 3) {
    console.log('Usage: phantomjs urltracker.js <device> <some URL>');
    phantom.exit(1);
} else {
    device = system.args[1];
    url = system.args[2];
}

selectedDevice = devices[device];

//var env = system.env;
/*Object.keys(env).forEach(function(key) {
  console.log(key + '=' + env[key]);
});*/
//var os = system.os;
//console.log(os.architecture);
//console.log(os.name);
//console.log(os.version);
//console.log(system.platform);

var pageCount = 0;
var urls_list = [];
var unableCount = 0;
var printUrl = '';
var log_folder = 'log';

function parseGET(url){
  var query = url.substr(url.indexOf("?")+1);
  var result = {};
  query.split("&").forEach(function(part) {
    var e = part.indexOf("=")
    var key = part.substr(0, e);
    var value = part.substr(e+1);
    result[key] = decodeURIComponent(value);
  });
  return result;
}

function writeToFile(file_name, text){
  var fs = require('fs');
   try {
    fs.write(file_name, text, 'w');
    } catch(e) {
        console.log(e);
    }
}

function checkRedirects(myurl){
    //resetTimeout();

    page = require('webpage').create();
    // suppress errors from output
    page.onError = function (msg, trace) {
  		console.log('Error occurred');
  		console.log(msg);
  		console.log(trace);
  	}

    // pretend to be a different browser, helps with some shitty browser-detection scripts
    page.settings.userAgent = selectedDevice.userAgent;
    page.viewportSize = { width: selectedDevice.width, height: selectedDevice.height };
	  page.zoomFactor = 1;


    page.onNavigationRequested = function(url, type, willNavigate, main) {
      pageCount++;
        if (
            main &&
            url != myurl //&&
            //url.replace(/\/$/,"") != myurl &&
            //(type=="Other" || type=="Undefined") //  type = not by click/submit etc
        ) {
            if(debug){
              ///take screenshot
              var img_file = log_folder+'/page'+pageCount+'.png';
              //console.log('screenshot saved >>> '+img_file);
              page.render(img_file);

              ///save contents
              var html_file = log_folder+'/page'+pageCount+'.html';
              //console.log('content saved >>> '+html_file);
              writeToFile(html_file, '<!--'+url+' Type:'+type+'-->'+page.content);
            }
            urls_list.push(url);

            final_url = url;
            page.close();
            //console.log('pageclosed');

            //console.debug(type);
            //console.debug(willNavigate);
            //console.debug(main);
            //handle redirects from iframe or subframe
            if(url != printUrl){
              printUrl = url;
              console.log('|---> '+printUrl);

              var params = parseGET(printUrl);
              if(params['url'] && params['url'] != null && params['url'] != ''){
                url = params['url'];
                console.log('|---> '+url);
              }else if(params['target'] && params['target'] != null && params['target'] != ''){
                url = params['target'];
                console.log('|---> '+url);
              }
            }
            checkRedirects(url); // reload on new page
        }
    };

    /*page.onResourceReceived = function(response) {
		if((contains(response.contentType, 'text/html'))){
			  console.log('resource received: '+response.url);
			  //console.log(JSON.stringify(response));
		}
		console.log(JSON.stringify(response));
	};*/
	/*page.onResourceRequested = function(requestData, networkRequest) {
	  console.log('resource requested: '+requestData.url);
	};*/
	/*page.onUrlChanged = function(targetUrl) {
	  console.log('New URL: ' + targetUrl);
	};*/

	//page.open(myurl, function(status) {console.log(status + myurl);});
    page.open(myurl, function(status) {
  		if (status !== 'success') {
  			//console.log('Unable to openpage: '+myurl);

        //console.debug(urls_list);


        if(unableCount < 10){
          unableCount++;
          //console.log('red 1:'+myurl);
          checkRedirects(myurl);
        }else{
          if(debug){
            writeToFile(log_folder+'/urls1.txt', urls_list.join(', '));
            console.log('Bye 1');
          }
          phantom.exit();
        }

  		} else {
  			var ua = page.evaluate(function () {

  				return window.location.href;
  			});

        if(debug){
    			page.render(log_folder+'/rendered'+pageCount+'.png');
          writeToFile(log_folder+'/rendered'+pageCount+'.html', '<!--'+myurl+'-->'+page.content);
        }


        if((ua == 'about:blank') && (unableCount < 10)){
          unableCount++;
          var addRedirect = urls_list[urls_list.length - 1];
          //console.log('red 2:'+addRedirect);
          checkRedirects(addRedirect);
        }else{
          if(debug){
            console.log(status+' Evaluation: '+ua);
            writeToFile(log_folder+'/urls2.txt', urls_list.join(', '));
            console.log('Bye 2');
          }
          //phantom.exit();
        }

  		}
  		//phantom.exit();
    });
}

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
          console.log('IP Address: '+resultObject.ip);
          console.log('Location: ' + resultObject.city + ', ' + resultObject.region + ', ' + resultObject.country + ', ' + resultObject.zip_code);
          console.log('TimeZone: ' + resultObject.timezone + ' (' + resultObject.latitude + ', ' + resultObject.longitude + ')');

          //checkDevice();
          console.log('Ping URL: '+url);
          checkRedirects(url);
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
          console.log('Unable to access network @ checkDevice');
          phantom.exit();
      } else {
          //page.render('client.png');
          var resultObject = JSON.parse(page.plainText);
          console.log('OS: '+resultObject.OS);
          console.log('Browser: '+resultObject.BROWSER);
          console.log('HTTP_USER_AGENT: '+resultObject.HTTP_USER_AGENT);
          console.log('REMOTE_ADDR: '+resultObject.REMOTE_ADDR);
          console.log('HTTP_CLIENT_IP: '+resultObject.HTTP_CLIENT_IP);
          console.log('HTTP_X_FORWARDED_FOR: '+resultObject.HTTP_X_FORWARDED_FOR);
          console.log('HTTP_X_REAL_IP: '+resultObject.HTTP_X_REAL_IP);

          console.log('Ping URL: '+url);
          checkRedirects(url);
      }
      //phantom.exit();
  });

}


// run it!
final_url = url;

//checkClient();
console.log('Ping URL: '+url);
checkRedirects(url);

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
/*
var tosecs = 15 * 1000;
var timeoutHandle = '';

function resetTimeout(){
  if(timeoutHandle !== ''){
    clearTimeout(timeoutHandle);
  }
  timeoutHandle = setTimeout(function(){
      if(debug){
        console.log('Redirect Timeout');
      }
      phantom.exit();
  }, tosecs);
}
*/
