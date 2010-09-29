/*
 * Displays the dialog with a list of services (web, ftp, smb).
 */
function viewServices() {
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
};
