<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Web view - openRescue</title>
<script type="text/javascript" charset="utf-8" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/jquery-ui-1.8.4.custom.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="js/iphone-style-checkboxes.js"></script> 
<script type="text/javascript" charset="utf-8" src="js/jquery.blockUI.js"></script> 
<script type="text/javascript" charset="utf-8" src="mimetypes.js"></script>
<script type="text/javascript" charset="utf-8" src="services.js"></script>
<script type="text/javascript" charset="utf-8" src="netinfo.js"></script>
<script type="text/javascript" charset="utf-8" src="browser.js"></script>
<link type="text/css" charset="utf-8" rel="stylesheet" href="css/iphone-style-checkboxes.css"/> 
<link type="text/css" charset="utf-8" rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.5.custom.css"/>
<link type="text/css" charset="utf-8" rel="stylesheet" href="css/screen.css"/>
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

	<span class="buttonRefresh ui-state-default ui-corner-all ui-icon ui-icon-refresh">refresh</span>
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

// USER INTERFACE: Hook the icons in the toolbar to dialog creators. 
$(function() {
	$("#head .svcs").addClass("clickable").click(viewServices);
	$("#head .info").addClass("clickable").click(viewNetworkInfo);
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

// USER INTERFACE: Clicking on the [X] icon inside ".infoBox" removes it.
$(function() { $(".infoBox .ui-icon-closethick").addClass("clickable").click(function() {
	$(this).parents(".infoBox").remove();
});});


// MAIN INIT ENTRY: Load a list of devices and connect all hooks. 
$(function() {
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
	"<p class='stepData'>Display the folder you want to backup and click on "+
	"the <img src='img/22/download.png' style='width:1em; height:1em'/> "+
	"icon to start the download.</p>"];
		
	var curStat = 0;
	var infoBox = $("#main .infoBox");
	var content = $(".content", infoBox).html(infoText[curStat]);
	// Load the list of devices...
	
	function refreshDevices() {
		$("#side .devices").loadDevices({
			toBlock: $("#side"),
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
	};
	
	refreshDevices();
	$("#side .buttonRefresh").button().click(refreshDevices);
});

function alertText(message) {
	return "<div class='ui-widget'><div class='ui-state-error ui-corner-all'>"
			+"<p><span class='ui-icon ui-icon-alert'></span>"+message+"</p>"
			+"</div></div>";
}

</script>
</body></html>
