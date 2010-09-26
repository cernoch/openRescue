/*
 * Populates the given table with a list of available devices
 */
$.fn.loadDevices = function(options) { return this.each(function() { $this=$(this);
	var opts = jQuery.extend({
		onMount: function() {},
		onBrowse: function() {},
		onBackup: function() {}
	}, options); $.ajax({

	url:"api/list.php",
	success: function(data) {
		$view = $this.children("tbody").html(""); /* TODO: Refactor the variable to $body */
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
			if (mounted) {
				$(".devIcon .devName", $row).addClass("link clickable");
				$(".devIcon img", $row).css("cursor","pointer");
				$(".devIcon", $row).click(function() {
					$("#main").browse({
						path: $(".devName", $(this).parents(".device")).text(),
						onBrowse: opts.onBrowse,
						onBackup: opts.onBackup
					});	
				});
			}
			// Add the table with details of the drive					
			$info = $td.append("<table class='devDetails'><tr><td class='devPath'/></tr><tr><td class='devDets'/></tr></table>").children(":last");
			$(".devPath",$info).text(i);
			size = humanSize(data[i].size); if (size != "") size += ", ";
			$(".devDets",$info).text(size + data[i].fsys);
		}
		
		// Toggle Online/Offline status
		$(".devStat", $view).addClass("link clickable").click(function() {
			$dev = $(this).parents(".device");
			data = {
				path:$(".devPath", $dev).text(),
				name:$(".devName", $dev).text()
			};
			$.ajax({
				type:"PUT",
				url: $(".devStat", $dev).hasClass("online") ? "api/umount.php" : "api/mount.php",
				data: $.toJSON(data),
				success: function(result) {
					opts.onMount(result);
					$this.loadDevices();
				}
			});
		});
	}
});});};


// BROWSER: Navigation
$.fn.browse = function(options) {return this.each(function() { $this=$(this);
	var opts = jQuery.extend({
		path: "/",
		onBrowse: function() {},
		onBackup: function() {}
	}, options); $.ajax({

	url:"api/dir.php", type:"PUT",
	data: $.toJSON({path:opts.path}),
	success: function(data) {
		// Determine if $this is the real browser or a container of a browser.
		$b = $this.hasClass("browser") ? $this.children("tbody") : $("table.browser > tbody",$this);
		$b.html("");

		// Not the root directory => add the ".." list item
		if (opts.path.indexOf("/") > 0) {
			$row = $b.append("<tr/>").children(":last");
			$row.append("<td/>").children(":last").html("<img/>").children(":last").css("cursor","pointer").attr("src","img/16/up.png");
			$row.append("<td/>").children(":last").text("..").css("cursor","pointer").click(function() {
				$this.browse({path: opts.path.substr(0,opts.path.lastIndexOf("/"))});
			});
			$row.append("<td/>").children(":last").text(" ");
		}				
		
		// Populate the table
		$.each(data.entries, function(i,e) {
			var fullpath = opts.path+"/"+e.name;
			function deeper() { $this.browse({path:fullpath}); }
			function display() { window.open("api/get.php/"+fullpath,'welcome',''); return false; }
			
			var mtyp = mime[e.mime]; // Lookup the icon name
			if (mtyp == undefined)   // not found -> use generic
				mtyp = e.mime.substr(0,e.mime.lastIndexOf("/"))+"-x-generic.png";

			$row = $b.append("<tr/>").children(":last");
			$row.append("<td/>").children(":last").html("<img/>").children(":last").attr("src","img/16/mimetypes/"+mtyp);//.css("cursor","pointer").click(deeper);
			       if (e.type == "d") { $row.append("<td/>").children(":last").text(e.name).addClass("clickable").click(deeper);
			} else if (e.type == "f") { $row.append("<td/>").children(":last").html("<a/>").children(":last").attr("href","api/get.php/"+fullpath).text(e.name).addClass("clickable").click(display);
			} else { $row.append("<td/>").children(":last").text(e.name); }
			$row.append("<td/>").children(":last").text(humanSize(e.size));
		});
		$("tr:odd",  $this).addClass("odd");
		$("tr:even", $this).addClass("even");
		
		// Generate the path toolbar
		pathParts = opts.path.split("/");
		pathSoFar = pathParts[0];
		
		$path = $(".path", $this);
		$path.html("<li></li>").children(":last").addClass("barItem root clickable").text(pathParts[0]).click(function() { $this.browse({path:pathParts[0]}); });
		for (i=1; i<pathParts.length; i++) {
			pathSoFar += "/" + pathParts[i];
			$path.append("<li></li>").children(":last").addClass("barItem delim").text("/");
			(function(pathSoFar) {
				$path.append("<li></li>").children(":last").addClass("barItem dir clickable").text(pathParts[i]).click(function() {
					$this.browse({path:pathSoFar});
				});
			})(pathSoFar);
		}
		
		// Setup download-button paths
		$(".download.tgz a", $this).attr("href","api/pack.php/tgz/"+opts.path).click(function() {opts.onBackup(opts.path);});		
		$(".download.zip a", $this).attr("href","api/pack.php/zip/"+opts.path).click(function() {
			if (confirm("WARNING: Creating an ZIP archive can consume a lot of memory.\n\n"+
					"Make sure your RAM memory is at least twice as big as the archive. "+
					"This limitation should be removed in future versions.\n\n"+
					"Do you still want to continue?")) {
				opts.onBackup(opts.path);
				return true;
			} else
				return false;
		});	

		// If the navigation bar is hidden, show it!		
		$(".fsbar", $this).css("display","");
		
		// And call the hooks
		opts.onBrowse(data, $this);				
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
	if (bytes == "?") return "";
	if (bytes > 1024) { bytes /= 1024.0;
	if (bytes > 1024) { bytes /= 1024.0;
	if (bytes > 1024) { bytes /= 1024.0;
	if (bytes > 1024) { bytes /= 1024.0;
	return trim(bytes) + " TB"; }
	return trim(bytes) + " GB"; }
	return trim(bytes) + " MB"; }
	return trim(bytes) + " kB"; }
	return trim(bytes) + " B";
}


