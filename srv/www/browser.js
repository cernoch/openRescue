/*
 * Populates the given table with a list of available devices
 */
$.fn.loadDevices = function() { return this.each(function() { $this=$(this); $.ajax({
	url:"api/list.php",
	success: function(data) {
		$view = $("tbody", $this); /* TODO: Refactor the variable to $body */
		// Add each drive into the table
		for (i in data) {
			$row = $view.append("<tr class='device'></tr>").children(":last");
					
			$td = $row.append("<td class='devIcon'/>").children(":last");
			// Add the icon + name
			$td.append("<img class='devType'/>").children("img").attr("src", "img/64/"+data[i].type+".png");
			$td.append("<div class='devName'/>").children(":last").text(data[i].name);
			
			$td = $row.append("<td></td>").children("td:last");
			// Add the online/offline status
			mounted = data[i].stat == "mounted";
			$td.append("<div/>").children(":last").html(
				mounted ? "<span class='devStat online'>Online</span>"
						: "<span class='devStat offline'>Offline</span>" );
			if (mounted)
				$(".devIcon", $row).addClass("link clickable").click(function() {
					$("#main .browser").browse($(".devName", $(this).parents(".device")).text());	
				});
			// Add the table with details of the drive					
			$info = $td.append("<table class='devDetails'><tr><td class='devPath'/></tr><tr><td class='devDets'/></tr></table>").children(":last");
			$(".devPath",$info).text(i);
			$(".devPath",$info).text(humanSize(data[i].size) + ", " + data[i].fsys);
		}
		
		// Toggle Online/Offline status
		$(".devStat", $view).addClass("link clickable").click(function() {
			$dev = $(this).parents(".device");
			$.ajax({
				type:"PUT",
				url: $(".devStat", $dev).hasClass("online") ? "api/umount.php" : "api/mount.php",
				data: $.toJSON({
					path:$(".devPath", $dev).text(),
					name:$(".devName", $dev).text()
				}),
				success:function() { $this.loadDevices(); }
			});
		});
	}
});});};


// BROWSER: Navigation
$.fn.browse = function(path) {return this.each(function() { $this=$(this); $.ajax({
	url:"api/dir.php", type:"PUT",
	data: $.toJSON({path:path}),
	success: function(data) {
		$b = $("tbody",$this).html("");

		// Not the root directory => add the ".." option
		if (path.indexOf("/") > 0) {
			$row = $b.append("<tr/>").children(":last");
			$row.append("<td/>").children(":last").html("<img/>").children(":last").css("cursor","pointer").attr("src","img/16/up.png");
			$row.append("<td/>").children(":last").text("..").css("cursor","pointer").click(function() {
				$this.browse(path.substr(0,path.lastIndexOf("/")));
			});
			$row.append("<td/>").children(":last").text(" ");
		}				
		
		// Populate the table
		$.each(data.entries, function(i,e) {
			function deeper() { $this.browse(path+"/"+e.name); }
			var mtyp = mime[e.mime]; // Lookup the icon name
			if (mtyp == undefined)   // not found -> use generic
				mtyp = e.mime.substr(0,e.mime.lastIndexOf("/"))+"-x-generic.png";

			$row = $b.append("<tr/>").children(":last");
			$row.append("<td/>").children(":last").html("<img/>").children(":last").attr("src","img/16/mimetypes/"+mtyp);//.css("cursor","pointer").click(deeper);
			$row.append("<td/>").children(":last").text(e.name).css("cursor","pointer").click(deeper);
			$row.append("<td/>").children(":last").text(humanSize(e.size));
		});
	}
});});};

/*
 * Formats the given number of bytes into human-readable format
 */
function humanSize(bytes) {
	function trim(size) {
		var out = (size>100 ? Math.round(size) : Math.round(size*10.0)/10.0);
		return out == undefined ? "unknown" : out;
	}
	if (bytes > 1024) { bytes /= 1024.0;
	if (bytes > 1024) { bytes /= 1024.0;
	if (bytes > 1024) { bytes /= 1024.0;
	if (bytes > 1024) { bytes /= 1024.0;
	return trim(bytes) + " TB"; }
	return trim(bytes) + " GB"; }
	return trim(bytes) + " MB"; }
	return trim(bytes) + " kB"; }
}


