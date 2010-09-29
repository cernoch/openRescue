function viewNetworkInfo() {
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
					smb_url+= "/rescue";
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
}
