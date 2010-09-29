<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Web view - openRescue </title>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.4.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="js/iphone-style-checkboxes.js" charset="utf-8"></script> 
<script type="text/javascript" src="mimetypes.js"></script>
<script type="text/javascript" src="browser.js"></script>

<style type="text/css">
	/*demo page css*/
	body {font: 11pt "Droid Sans", sans-serif; margin: 0px; cursor: default}
	a {color: inherit; text-decoration: none}
	
	.demoHeaders { margin-top: 2em; }
	#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
	#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
	ul#icons {margin: 0; padding: 0;}
	ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
	ul#icons span.ui-icon {float: left; margin: 0 4px;}
	
	.ui-state-error, .ui-state-highlight {padding: 0 .7em}			
	.ui-state-error .ui-icon, .ui-state-highlight .ui-icon {float: left; margin-right: .3em}
	
	.ui-widget { font-family: "Droid Sans", sans-serif; }
	.ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button	{ font-family: "Droid Sans", sans-serif; }
	
	/* Helpers */
	.link {text-decoration:underline}
	.clickable {cursor:pointer}
	.clickable:hover {text-decoration:underline}
	
	/* Basic layout */
	.layout {position:fixed; margin:0px; padding:0px; overflow: auto}
	#head {right:0px;    left:0px; height:36px;    top:0px; overflow:hidden}
	#side {width:300px;  left:0px;    top:37px; bottom:0px}
	#main { left:301px; right:0px;    top:37px; bottom:0px}
	
	/* Borders */
	#side {border-right: 1px solid #667483}
	#head {border-bottom: 1px solid #667483}

	/* The head */
	ul#head {
		font-weight:normal; font-size:10pt;
		background-image: url('img/head-bg.png');
		background-position: left center;
	}
	ul#head li {
		display:block; float:left; margin:0px;
		padding:0px; padding-left:2em; padding-right:2em;
		border-right: 1px solid #86A4B3;	
		height:36px; line-height:36px;
	}
	ul#head li.ajax {float:right; padding: 2px}
	/* head's icons */
	ul#head li.devs, ul#head li.svcs, ul#head li.info {
		padding-left: 34px; margin-left:1.5em;
		background-repeat: no-repeat;
		background-position: left center;
	}
	ul#head li.devs { background-image: url('img/24/drive.png');}
	ul#head li.svcs { background-image: url('img/24/services.png');}
	ul#head li.info { background-image: url('img/24/network.png');}
	
	
	div.devices > * {margin: 8pt}
			
	.device > td {padding-bottom:1em}
			
	.devIcon {text-align: center}
	.devName {font-weight: bold}
	.devDetails {margin:4pt; color:#666; font-size: 80%}}
	.devDetails th {text-align:right; display:none}							
		
	.devStat.online  {background-image: url('img/22/online.png');}			
	.devStat.offline {background-image: url('img/22/offline.png');}						
	.devStat {font-size: 150%; margin-bottom:12pt;
		padding-left: 26px;
		background-repeat: no-repeat;
		background-position: left center;
	}
	
	.browser {
		width:100%;
		border-top: 1px solid #CCC;
		border-collapse:collapse;
	}
	.browser tr > td:first-child {width:16px}
	.browser tr > td:first-child + td + td,
	.browser tr > th:first-child + th {width:8em}
	.browser tr.odd  {background-color: #EEE}
	.browser tr.even {background-color: #DDD}
	
	.fsbar .barItem {height:26px; line-height:26px;}
	.fsbar .download {
		float:right; display:block;
		margin-right:1em;
		padding-left:26px;
		background-repeat: no-repeat;
		background-position: left center;
		background-image: url('img/22/download.png');
	}
	
	ul.path {
		margin:0px; padding:0px;
		padding-left: 1em;
		list-style: none;
		font-family: "Droid Sans Mono", monospace;
	}
	ul.path li {float:left;}
	ul.path li.delim {margin-left:0.3em; margin-right:0.3em }
	ul.path li.root {
		background-repeat: no-repeat;
		background-position: left center;
		background-image: url('img/16/drive.png');
		height:26px; padding-left:20px;
	}
	
	.infoBox .ui-icon-closethick {float:right}
	
	.infoBox .content .stepNum {
		line-height:60pt; font-size:60pt; 
		color:#d5bf30; font-family:"Arial Black"; font-weight:bold;
		margin:0.2em; padding:0px;
	}
	.infoBox .content .stepData {
		font-size:14pt; margin-top:-3em; margin-left:2em
	}
</style>
<link type="text/css" href="css/iphone-style-checkboxes.css" rel="stylesheet" charset="utf-8" /> 
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.5.custom.css" rel="stylesheet"/>
<link type="text/css" href="css/screen.css" rel="stylesheet"/>


</head><body>
	
<ul id='head' class='status layout'>
	<li class='devs' title='Toggle the display of all devices in your system'>Devices</li>
	<li class='svcs'>Services</li>
	<li class='info'>Network status</li>
	<li class='ajax'></li>
</ul>
	
<div id='side' class='devices layout'>
	<div class="infoBox ui-widget">
		<div class="ui-state-error ui-corner-all"> 
			<p><span class="ui-icon ui-icon-alert">Warning</span>
			<span class="ui-icon ui-icon-closethick">Close</span>
			<strong>Be careful!</strong></p>
			<p>All your <em>Online</em> drives are accessible to
				<strong>anyone</strong> in your network.
				Make sure there are no unauthorized users of your network,
				who might steal or delete your data.</p>
		</div>
	</div>

	<table class='devices'><tbody/></table>	
</div>
	
<div id='main' class='layout'>
	<div class='fsbar' style='display:none'>
		<div class='barItem download zip'><a href='#'>ZIP</a></div>
		<div class='barItem download tgz'><a href='#'>TAR.GZ</a></div>
		<ul class='path'></ul>
	</div>
	<table class='browser'><tbody/></table>
	
	<div class="infoBox ui-widget" style="margin:10%">
		<div class="ui-state-highlight ui-corner-all"> 
			<p><span class="ui-icon ui-icon-closethick">close</span></p>
			<div class="content"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
// BOOT: setup ajax
$(function() { $.ajaxSetup({dataType:"json"}); });

// AXAJ-BOOT: Showing the "clock" while ajax working
var clockTimer = null;
function showClock() { $(".status .ajax")
	.html("<img width='32' height='32'/>").children(":last")
	.attr("src","img/ajax-loader.gif");
}
$("body").ajaxStart(function() { clockTimer = setTimeout("showClock();",500); });
$("body").ajaxStop(function() { clearTimeout(clockTimer); $(".status .ajax").html(""); });

// AXAJ-BOOT: Error shows a modal dialog with the error
$("body").ajaxError(function(evt, data, opts, thrown) {
	$d = $("body").append("<div></div>").children(":last");
	
	$d.html("<div class='ui-widget'><div class='ui-state-error ui-corner-all'>" 
			+"<p><span class='ui-icon ui-icon-alert'></span>" 
			+"<strong>Alert:</strong> Something went wrong. The requested action"
			+" was not been performed.</p>"
			+"</div></div><pre></pre>").children("pre").text(data.responseText);
	if (data.resonseText != undefined) $("pre",$d).prepend("<p>Server resonse:</p>");
			
	$d.dialog({
		title: data.statusText,
		modal: true,
		width: "700px",
		height: "250",
		close: function() {$d.remove();},
		buttons: {"Close": function() { $d.dialog("close"); }}
	});	
});

// USER INTERFACE: Show/hide of the "sidebar"
$(function() { $("#head .devs").addClass("clickable").click(toggleSide); });
function toggleSide() {
	side = $("#side");
	if (side.css("display") == "none") {
		// Show the sidebar
		side.css("overflow", "hidden");
		side.slideDown(400);
		$("#main").animate({"left":"301px"});
	} else {
		// Hide the sidebar
		side.slideUp(1500, function() {
			side.css("overflow","");
			$("#main").animate({"left":"0"});
		});
	}
}

$(function() {
	$(".infoBox .ui-icon-closethick").addClass("clickable").click(function() {
		$(this).parents(".infoBox").remove();
	});
});

var infoText = [
"<p class='stepNum'>Step 1.</p>"+
"<p class='stepData'>Click on the <strong>"+
"<img src='img/22/online.png' style='width:1em; height:1em'/> Offline"+
"</strong> label next to one of your drives."+
"This will switch the drive to online mode.</p>",

"<p class='stepNum'>Step 2.</p>"+
"<p class='stepData'>Browse the drive by clicking on the "+
"<img src='img/64/drive.png' style='width:1em; height:1em'/> "+
"icon above the drive's name.</p>",

"<p class='stepNum'>Step 3.</p>"+
"<p class='stepData'>Display the folder you want to backup and click on the "+
"<img src='img/22/download.png' style='width:1em; height:1em'/> "+
"icon to start the download.</p>"];


$(function() {
	var curStat = 0;
	var infoBox = $("#main .infoBox");
	var content = $(".content", infoBox).html(infoText[curStat]);
	// Load the list of devices...
	$("#side .devices").loadDevices({
		onMount: function(info) {
			//alert("onMount:" + curStat);
			if (info.stat == "mounted") {
				curStat = curStat < 1 ? 1 : curStat;
				content.html(infoText[curStat]);
			}
		},
		onBrowse: function(info) {
			//alert("onBrowse:" + curStat);
			curStat = curStat < 2 ? 2 : curStat;
			infoBox.css("margin","3%");
			content.html(infoText[curStat]);
		},
		onBackup: function() {
			//alert("onBackup:" + curStat);
			infoBox.remove();
		}
	});	
});

$(function() {
	$("#head .svcs").addClass("clickable").click(function() {
		d = $("body").append("<div></div>").children(":last");
		d.html("<table class='services'>"+
			"<tr class='icons'>"+
				"<td class='web'><img src='img/128/svc-web.png'/></td>"+
				"<td class='ftp'><img src='img/128/svc-ftp.png'/></td>"+
				"<td class='smb'><img src='img/128/svc-smb.png'/></td>"+
			"</tr>"+
			"<tr class='names'>"+
				"<td class='web'>World Wide Web</td>"+
				"<td class='ftp'>File Transport Protocol</td>"+
				"<td class='smb'>Windows share</td>"+
			"</tr>"+
			"<tr class='state'>"+
				"<td class='web'></td>"+
				"<td class='ftp'></td>"+
				"<td class='smb'></td>"+
			"</tr>"+
			"</table>");

		var a = $.ajax({ // Keep track of the AJAX request to cancel it...
			url: "api/service.php", type:"PUT",
			success: function(data) {
				a = null; // Reset the request
				for (svc in data) { (function(svc,running) {
					// For each service create an iPhone check-box
					var chbox = $(".state td."+svc, d).html("<input type='checkbox'/>").children(":first").attr("checked", running);
					chbox = chbox.iphoneStyle({ checkedLabel: 'Running', uncheckedLabel: 'Stopped' });

					// If the user clicks the iphone-checkbox...
					$(".state td."+svc+" .iPhoneCheckContainer").click(function() {
						var cmd = {}; // Prepare the AJAX request
						cmd[svc] = { command: chbox.is(':checked') ? "start" : "stop" };
						
						$.ajax({ // Send the request
							url: "api/service.php", type:"PUT",
							data: $.toJSON(cmd),
							global: false, // ignore global errors
							success: function(update) { // Update checkbox according to the new state 
								chbox.attr("checked", update[svc].status == "running").change();
							},
							error: function(err) { // Revert the checkbox to its original state
								chbox.attr("checked", !chbox.is(":checked")).change();
							}
						});
						
					});
				})(svc,data[svc].status == "running");}
				
				// Disable the "WEB" checkbox (to prevent cutting-off the client)
				$(".state .web :checkbox",d).attr("disabled","disabled").change();
			},
			error: function() {d.dialog("close");}
		});
		d.dialog({
			modal: true,
			width: "700px", height: "300",
			title: "Available services for data backup",
			close: function() {
				if (a != null) a.abort();
				d.remove();
			}
		});
	});
});

function alertText(message) {
	return "<div class='ui-widget'><div class='ui-state-error ui-corner-all'>"
			+"<p><span class='ui-icon ui-icon-alert'></span>"+message+"</p>"
			+"</div></div>";
}

$(function() {
	$("#head .info").addClass("clickable").click(function() {
		d = $("body").append("<div></div>").children(":last");
		d.html("<p></p><table class='iplist'></table>");
		d.children("p").html("You can access your data from any"+
			" computer on your local network by using one of the following"+
			" addresses:");
				
		var a = $.ajax({ // Keep track of the AJAX request to cancel it...
			url: "api/ipaddr.php", type:"PUT",
			success: function(data) {
				a = null; // Reset the request
				
				// If no IP address is found...
				if (data.length == 0) {
					$("p",d).remove();
					$("table",d).html("<tr><td>"+alertText("<b>No IP address found.</b> "+
						"Check your network connection using NetworkManager in the system statusbar.")+"</td></tr>");
					
				} else {					
					// Populate the table with data...
					for (i in data) { (function(ip) {
						var smb_url = navigator.appVersion.toLowerCase().indexOf('win') != -1
									? "\\\\"+ip : "smb://"+ip;
						$("table",d).append(
							"<tr>"+
								"<th>"+ip+"</th>"+
								"<td>"+
									"<div class='web'><a href='http://"+ip+"'>http://"+ip+"</a></div>"+
									"<div class='ftp'><a href='ftp://"+ip+"'>ftp://"+ip+"</a></div>"+
									"<div class='smb'><a href='"+smb_url+"'>"+smb_url+"</a></div>"+
							"</tr>");
					})(data[i]);}
					
					
					// Disable the "WEB" checkbox (to prevent cutting-off the client)
					$(".state .web :checkbox",d).attr("disabled","disabled");
					
					// Disable URLs of offline services
					$.ajax({
						url: "api/service.php", type:"PUT", global: false,
						success: function(data) {
							for (svc in data) { (function(svc,running) {
								if (!running)
									$("."+svc,d).slideUp(3000);
							})(svc,data[svc].status == "running");}
						}
					});
				}
				
			},
			error: function() {d.dialog("close");}
		});
		d.dialog({
			modal: true,
			width: "400px", height: "300",
			title: "List of IP addresses",
			close: function() {
				if (a != null) a.abort();
				d.remove();
			}
		});
	});
});
</script>
	
































		<script type="text/javascript">
			$(function(){

				// Accordion
				$("#accordion").accordion({ header: "h3" });
	
				// Tabs
				$('#tabs').tabs();
	

				// Dialog			
				$('#dialog').dialog({
					autoOpen: false,
					width: 600,
					buttons: {
						"Ok": function() { 
							$(this).dialog("close"); 
						}, 
						"Cancel": function() { 
							$(this).dialog("close"); 
						} 
					}
				});
				
				// Dialog Link
				$('#dialog_link').click(function(){
					$('#dialog').dialog('open');
					return false;
				});

				// Datepicker
				$('#datepicker').datepicker({
					inline: true
				});
				
				// Slider
				$('#slider').slider({
					range: true,
					values: [17, 67]
				});
				
				// Progressbar
				$("#progressbar").progressbar({
					value: 20 
				});
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});
		</script>

<div style='display:none'>
	<h1 style='margin-top: 20em'>Welcome to jQuery UI!</h1>
	<p style="font-size: 1.3em; line-height: 1.5; margin: 1em 0; width: 50%;">This page demonstrates the widgets you downloaded using the theme you selected in the download builder. We've included and linked to minified versions of <a href="js/jquery-1.4.2.min.js">jQuery</a>, your personalized copy of <a href="js/jquery-ui-1.8.4.custom.min.js">jQuery UI (js/jquery-ui-1.8.4.custom.min.js)</a>, and <a href="css/flick/jquery-ui-1.8.4.custom.css">css/flick/jquery-ui-1.8.4.custom.css</a> which imports the entire jQuery UI CSS Framework. You can choose to link a subset of the CSS Framework depending on your needs. </p>
	<p style="font-size: 1.2em; line-height: 1.5; margin: 1em 0; width: 50%;">You've downloaded components and a theme that are compatible with jQuery 1.3+. Please make sure you are using jQuery 1.3+ in your production environment.</p>	

	<p style="font-weight: bold; margin: 2em 0 1em; font-size: 1.3em;">YOUR COMPONENTS:</p>
		
		<!-- Accordion -->
		<h2 class="demoHeaders">Accordion</h2>
		<div id="accordion">
			<div>
				<h3><a href="#">First</a></h3>
				<div>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</div>
			</div>
			<div>
				<h3><a href="#">Second</a></h3>
				<div>Phasellus mattis tincidunt nibh.</div>
			</div>
			<div>
				<h3><a href="#">Third</a></h3>
				<div>Nam dui erat, auctor a, dignissim quis.</div>
			</div>
		</div>
	
		<!-- Tabs -->
		<h2 class="demoHeaders">Tabs</h2>
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">First</a></li>
				<li><a href="#tabs-2">Second</a></li>
				<li><a href="#tabs-3">Third</a></li>
			</ul>
			<div id="tabs-1">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
			<div id="tabs-2">Phasellus mattis tincidunt nibh. Cras orci urna, blandit id, pretium vel, aliquet ornare, felis. Maecenas scelerisque sem non nisl. Fusce sed lorem in enim dictum bibendum.</div>
			<div id="tabs-3">Nam dui erat, auctor a, dignissim quis, sollicitudin eu, felis. Pellentesque nisi urna, interdum eget, sagittis et, consequat vestibulum, lacus. Mauris porttitor ullamcorper augue.</div>
		</div>
	
		<!-- Dialog NOTE: Dialog is not generated by UI in this demo so it can be visually styled in themeroller-->
		<h2 class="demoHeaders">Dialog</h2>
		<p><a href="#" id="dialog_link" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-newwin"></span>Open Dialog</a></p>
		
		
		<h2 class="demoHeaders">Overlay and Shadow Classes <em>(not currently used in UI widgets)</em></h2>
		<div style="position: relative; width: 96%; height: 200px; padding:1% 4%; overflow:hidden;" class="fakewindowcontain">
			<p>Lorem ipsum dolor sit amet,  Nulla nec tortor. Donec id elit quis purus consectetur consequat. </p><p>Nam congue semper tellus. Sed erat dolor, dapibus sit amet, venenatis ornare, ultrices ut, nisi. Aliquam ante. Suspendisse scelerisque dui nec velit. Duis augue augue, gravida euismod, vulputate ac, facilisis id, sem. Morbi in orci. </p><p>Nulla purus lacus, pulvinar vel, malesuada ac, mattis nec, quam. Nam molestie scelerisque quam. Nullam feugiat cursus lacus.orem ipsum dolor sit amet, consectetur adipiscing elit. Donec libero risus, commodo vitae, pharetra mollis, posuere eu, pede. Nulla nec tortor. Donec id elit quis purus consectetur consequat. </p><p>Nam congue semper tellus. Sed erat dolor, dapibus sit amet, venenatis ornare, ultrices ut, nisi. Aliquam ante. Suspendisse scelerisque dui nec velit. Duis augue augue, gravida euismod, vulputate ac, facilisis id, sem. Morbi in orci. Nulla purus lacus, pulvinar vel, malesuada ac, mattis nec, quam. Nam molestie scelerisque quam. </p><p>Nullam feugiat cursus lacus.orem ipsum dolor sit amet, consectetur adipiscing elit. Donec libero risus, commodo vitae, pharetra mollis, posuere eu, pede. Nulla nec tortor. Donec id elit quis purus consectetur consequat. Nam congue semper tellus. Sed erat dolor, dapibus sit amet, venenatis ornare, ultrices ut, nisi. Aliquam ante. </p><p>Suspendisse scelerisque dui nec velit. Duis augue augue, gravida euismod, vulputate ac, facilisis id, sem. Morbi in orci. Nulla purus lacus, pulvinar vel, malesuada ac, mattis nec, quam. Nam molestie scelerisque quam. Nullam feugiat cursus lacus.orem ipsum dolor sit amet, consectetur adipiscing elit. Donec libero risus, commodo vitae, pharetra mollis, posuere eu, pede. Nulla nec tortor. Donec id elit quis purus consectetur consequat. Nam congue semper tellus. Sed erat dolor, dapibus sit amet, venenatis ornare, ultrices ut, nisi. </p>
			
			<!-- ui-dialog -->
			<div class="ui-overlay"><div class="ui-widget-overlay"></div><div class="ui-widget-shadow ui-corner-all" style="width: 302px; height: 152px; position: absolute; left: 50px; top: 30px;"></div></div>
			<div style="position: absolute; width: 280px; height: 130px;left: 50px; top: 30px; padding: 10px;" class="ui-widget ui-widget-content ui-corner-all">
				<div class="ui-dialog-content ui-widget-content" style="background: none; border: 0;">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
				</div>
			</div>
		
		</div>

		
		<!-- ui-dialog -->
		<div id="dialog" title="Dialog Title">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		</div>
			
				
				
		<h2 class="demoHeaders">Framework Icons (content color preview)</h2>
		<ul id="icons" class="ui-widget ui-helper-clearfix">
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-1-n"><span class="ui-icon ui-icon-carat-1-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-1-ne"><span class="ui-icon ui-icon-carat-1-ne"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-1-e"><span class="ui-icon ui-icon-carat-1-e"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-1-se"><span class="ui-icon ui-icon-carat-1-se"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-1-s"><span class="ui-icon ui-icon-carat-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-1-sw"><span class="ui-icon ui-icon-carat-1-sw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-1-w"><span class="ui-icon ui-icon-carat-1-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-1-nw"><span class="ui-icon ui-icon-carat-1-nw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-2-n-s"><span class="ui-icon ui-icon-carat-2-n-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-carat-2-e-w"><span class="ui-icon ui-icon-carat-2-e-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-1-n"><span class="ui-icon ui-icon-triangle-1-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-1-ne"><span class="ui-icon ui-icon-triangle-1-ne"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-1-e"><span class="ui-icon ui-icon-triangle-1-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-1-se"><span class="ui-icon ui-icon-triangle-1-se"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-1-s"><span class="ui-icon ui-icon-triangle-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-1-sw"><span class="ui-icon ui-icon-triangle-1-sw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-1-w"><span class="ui-icon ui-icon-triangle-1-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-1-nw"><span class="ui-icon ui-icon-triangle-1-nw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-2-n-s"><span class="ui-icon ui-icon-triangle-2-n-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-triangle-2-e-w"><span class="ui-icon ui-icon-triangle-2-e-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-1-n"><span class="ui-icon ui-icon-arrow-1-n"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-1-ne"><span class="ui-icon ui-icon-arrow-1-ne"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-1-e"><span class="ui-icon ui-icon-arrow-1-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-1-se"><span class="ui-icon ui-icon-arrow-1-se"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-1-s"><span class="ui-icon ui-icon-arrow-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-1-sw"><span class="ui-icon ui-icon-arrow-1-sw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-1-w"><span class="ui-icon ui-icon-arrow-1-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-1-nw"><span class="ui-icon ui-icon-arrow-1-nw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-2-n-s"><span class="ui-icon ui-icon-arrow-2-n-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-2-ne-sw"><span class="ui-icon ui-icon-arrow-2-ne-sw"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-2-e-w"><span class="ui-icon ui-icon-arrow-2-e-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-2-se-nw"><span class="ui-icon ui-icon-arrow-2-se-nw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowstop-1-n"><span class="ui-icon ui-icon-arrowstop-1-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowstop-1-e"><span class="ui-icon ui-icon-arrowstop-1-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowstop-1-s"><span class="ui-icon ui-icon-arrowstop-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowstop-1-w"><span class="ui-icon ui-icon-arrowstop-1-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-1-n"><span class="ui-icon ui-icon-arrowthick-1-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-1-ne"><span class="ui-icon ui-icon-arrowthick-1-ne"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-1-e"><span class="ui-icon ui-icon-arrowthick-1-e"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-1-se"><span class="ui-icon ui-icon-arrowthick-1-se"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-1-s"><span class="ui-icon ui-icon-arrowthick-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-1-sw"><span class="ui-icon ui-icon-arrowthick-1-sw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-1-w"><span class="ui-icon ui-icon-arrowthick-1-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-1-nw"><span class="ui-icon ui-icon-arrowthick-1-nw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-2-n-s"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-2-ne-sw"><span class="ui-icon ui-icon-arrowthick-2-ne-sw"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-2-e-w"><span class="ui-icon ui-icon-arrowthick-2-e-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthick-2-se-nw"><span class="ui-icon ui-icon-arrowthick-2-se-nw"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthickstop-1-n"><span class="ui-icon ui-icon-arrowthickstop-1-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthickstop-1-e"><span class="ui-icon ui-icon-arrowthickstop-1-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthickstop-1-s"><span class="ui-icon ui-icon-arrowthickstop-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowthickstop-1-w"><span class="ui-icon ui-icon-arrowthickstop-1-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowreturnthick-1-w"><span class="ui-icon ui-icon-arrowreturnthick-1-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowreturnthick-1-n"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowreturnthick-1-e"><span class="ui-icon ui-icon-arrowreturnthick-1-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowreturnthick-1-s"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowreturn-1-w"><span class="ui-icon ui-icon-arrowreturn-1-w"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowreturn-1-n"><span class="ui-icon ui-icon-arrowreturn-1-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowreturn-1-e"><span class="ui-icon ui-icon-arrowreturn-1-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowreturn-1-s"><span class="ui-icon ui-icon-arrowreturn-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowrefresh-1-w"><span class="ui-icon ui-icon-arrowrefresh-1-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowrefresh-1-n"><span class="ui-icon ui-icon-arrowrefresh-1-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowrefresh-1-e"><span class="ui-icon ui-icon-arrowrefresh-1-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrowrefresh-1-s"><span class="ui-icon ui-icon-arrowrefresh-1-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-4"><span class="ui-icon ui-icon-arrow-4"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-arrow-4-diag"><span class="ui-icon ui-icon-arrow-4-diag"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-extlink"><span class="ui-icon ui-icon-extlink"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-newwin"><span class="ui-icon ui-icon-newwin"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-refresh"><span class="ui-icon ui-icon-refresh"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-shuffle"><span class="ui-icon ui-icon-shuffle"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-transfer-e-w"><span class="ui-icon ui-icon-transfer-e-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-transferthick-e-w"><span class="ui-icon ui-icon-transferthick-e-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-folder-collapsed"><span class="ui-icon ui-icon-folder-collapsed"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-folder-open"><span class="ui-icon ui-icon-folder-open"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-document"><span class="ui-icon ui-icon-document"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-document-b"><span class="ui-icon ui-icon-document-b"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-note"><span class="ui-icon ui-icon-note"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-mail-closed"><span class="ui-icon ui-icon-mail-closed"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-mail-open"><span class="ui-icon ui-icon-mail-open"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-suitcase"><span class="ui-icon ui-icon-suitcase"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-comment"><span class="ui-icon ui-icon-comment"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-person"><span class="ui-icon ui-icon-person"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-print"><span class="ui-icon ui-icon-print"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-trash"><span class="ui-icon ui-icon-trash"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-locked"><span class="ui-icon ui-icon-locked"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-unlocked"><span class="ui-icon ui-icon-unlocked"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-bookmark"><span class="ui-icon ui-icon-bookmark"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-tag"><span class="ui-icon ui-icon-tag"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-home"><span class="ui-icon ui-icon-home"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-flag"><span class="ui-icon ui-icon-flag"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-calculator"><span class="ui-icon ui-icon-calculator"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-cart"><span class="ui-icon ui-icon-cart"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-pencil"><span class="ui-icon ui-icon-pencil"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-clock"><span class="ui-icon ui-icon-clock"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-disk"><span class="ui-icon ui-icon-disk"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-calendar"><span class="ui-icon ui-icon-calendar"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-zoomin"><span class="ui-icon ui-icon-zoomin"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-zoomout"><span class="ui-icon ui-icon-zoomout"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-wrench"><span class="ui-icon ui-icon-wrench"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-gear"><span class="ui-icon ui-icon-gear"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-heart"><span class="ui-icon ui-icon-heart"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-star"><span class="ui-icon ui-icon-star"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-link"><span class="ui-icon ui-icon-link"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-cancel"><span class="ui-icon ui-icon-cancel"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-plus"><span class="ui-icon ui-icon-plus"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-plusthick"><span class="ui-icon ui-icon-plusthick"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-minus"><span class="ui-icon ui-icon-minus"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-minusthick"><span class="ui-icon ui-icon-minusthick"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-close"><span class="ui-icon ui-icon-close"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-closethick"><span class="ui-icon ui-icon-closethick"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-key"><span class="ui-icon ui-icon-key"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-lightbulb"><span class="ui-icon ui-icon-lightbulb"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-scissors"><span class="ui-icon ui-icon-scissors"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-clipboard"><span class="ui-icon ui-icon-clipboard"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-copy"><span class="ui-icon ui-icon-copy"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-contact"><span class="ui-icon ui-icon-contact"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-image"><span class="ui-icon ui-icon-image"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-video"><span class="ui-icon ui-icon-video"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-script"><span class="ui-icon ui-icon-script"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-alert"><span class="ui-icon ui-icon-alert"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-info"><span class="ui-icon ui-icon-info"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-notice"><span class="ui-icon ui-icon-notice"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-help"><span class="ui-icon ui-icon-help"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-check"><span class="ui-icon ui-icon-check"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-bullet"><span class="ui-icon ui-icon-bullet"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-radio-off"><span class="ui-icon ui-icon-radio-off"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-radio-on"><span class="ui-icon ui-icon-radio-on"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-pin-w"><span class="ui-icon ui-icon-pin-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-pin-s"><span class="ui-icon ui-icon-pin-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-play"><span class="ui-icon ui-icon-play"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-pause"><span class="ui-icon ui-icon-pause"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-seek-next"><span class="ui-icon ui-icon-seek-next"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-seek-prev"><span class="ui-icon ui-icon-seek-prev"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-seek-end"><span class="ui-icon ui-icon-seek-end"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-seek-first"><span class="ui-icon ui-icon-seek-first"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-stop"><span class="ui-icon ui-icon-stop"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-eject"><span class="ui-icon ui-icon-eject"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-volume-off"><span class="ui-icon ui-icon-volume-off"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-volume-on"><span class="ui-icon ui-icon-volume-on"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-power"><span class="ui-icon ui-icon-power"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-signal-diag"><span class="ui-icon ui-icon-signal-diag"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-signal"><span class="ui-icon ui-icon-signal"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-battery-0"><span class="ui-icon ui-icon-battery-0"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-battery-1"><span class="ui-icon ui-icon-battery-1"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-battery-2"><span class="ui-icon ui-icon-battery-2"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-battery-3"><span class="ui-icon ui-icon-battery-3"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-plus"><span class="ui-icon ui-icon-circle-plus"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-minus"><span class="ui-icon ui-icon-circle-minus"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-close"><span class="ui-icon ui-icon-circle-close"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-triangle-e"><span class="ui-icon ui-icon-circle-triangle-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-triangle-s"><span class="ui-icon ui-icon-circle-triangle-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-triangle-w"><span class="ui-icon ui-icon-circle-triangle-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-triangle-n"><span class="ui-icon ui-icon-circle-triangle-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-arrow-e"><span class="ui-icon ui-icon-circle-arrow-e"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-arrow-s"><span class="ui-icon ui-icon-circle-arrow-s"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-arrow-w"><span class="ui-icon ui-icon-circle-arrow-w"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-arrow-n"><span class="ui-icon ui-icon-circle-arrow-n"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-zoomin"><span class="ui-icon ui-icon-circle-zoomin"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-zoomout"><span class="ui-icon ui-icon-circle-zoomout"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circle-check"><span class="ui-icon ui-icon-circle-check"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circlesmall-plus"><span class="ui-icon ui-icon-circlesmall-plus"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circlesmall-minus"><span class="ui-icon ui-icon-circlesmall-minus"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-circlesmall-close"><span class="ui-icon ui-icon-circlesmall-close"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-squaresmall-plus"><span class="ui-icon ui-icon-squaresmall-plus"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-squaresmall-minus"><span class="ui-icon ui-icon-squaresmall-minus"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-squaresmall-close"><span class="ui-icon ui-icon-squaresmall-close"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-grip-dotted-vertical"><span class="ui-icon ui-icon-grip-dotted-vertical"></span></li>
		
		<li class="ui-state-default ui-corner-all" title=".ui-icon-grip-dotted-horizontal"><span class="ui-icon ui-icon-grip-dotted-horizontal"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-grip-solid-vertical"><span class="ui-icon ui-icon-grip-solid-vertical"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-grip-solid-horizontal"><span class="ui-icon ui-icon-grip-solid-horizontal"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-gripsmall-diagonal-se"><span class="ui-icon ui-icon-gripsmall-diagonal-se"></span></li>
		<li class="ui-state-default ui-corner-all" title=".ui-icon-grip-diagonal-se"><span class="ui-icon ui-icon-grip-diagonal-se"></span></li>
		</ul>

	
		<!-- Slider -->
		<h2 class="demoHeaders">Slider</h2>
		<div id="slider"></div>
	
		<!-- Datepicker -->
		<h2 class="demoHeaders">Datepicker</h2>
		<div id="datepicker"></div>
	
		<!-- Progressbar -->
		<h2 class="demoHeaders">Progressbar</h2>	
		<div id="progressbar"></div>
			
		<!-- Highlight / Error -->
		<h2 class="demoHeaders">Highlight / Error</h2>
		<div class="ui-widget">
			<div class="ui-state-highlight ui-corner-all"> 
				<p><span class="ui-icon ui-icon-info"></span>
				<strong>Hey!</strong> Sample ui-state-highlight style.</p>
			</div>
		</div>
		<br/>
		<div class="ui-widget">
			<div class="ui-state-error ui-corner-all"> 
				<p><span class="ui-icon ui-icon-alert"></span> 
				<strong>Alert:</strong> Sample ui-state-error style.</p>
			</div>
		</div>
</div>
		
	</body>
</html>


