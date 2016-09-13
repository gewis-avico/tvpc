var fadeSpeed = 500;

var tvpcUpdateTimeout = null;
var timeUpdateInterval = null;
var showSeparator = false;

var settings = null;
var preload = null;
var index = 0;
var subindex = 0;

function tvpcUpdate() {
	console.log("--- Next slide! ---");
	
	// Download new settings
	if (settings == null || index >= settings.pages.length) {
		console.log("Load settings");
		index = 0;
		subindex = 0;
		$.ajax({
			url: path + "/index.php?settings",
			async: false,
			success: function(result) {
					var newsettings = JSON.parse(result);
					if (newsettings.hasOwnProperty("skin") && newsettings.skin != skin) {
						skin = newsettings.skin;
						$("#skincss").attr("href", path + "/skin/" + skin + "/style.css");
					}
					if (settings == null) {
						index = Math.floor(Math.random() * newsettings.pages.length);
					}
					settings = newsettings;
				}
		});
		if (settings == null || settings.pages.length == null) {
			console.log("Could not retrieve settings, try again in 5 seconds.");
			clearTimeout(tvpcUpdateTimeout);
			tvpcUpdateTimeout = setTimeout(function(){tvpcUpdate();}, 5000);
			return;
		}
	}
	
	// Get settings
	var timeout = 20000 / tvpcSpeed;
	if (settings.hasOwnProperty("timeout")) {
		timeout = settings.timeout * 1000 / tvpcSpeed;
	}
	if (settings.pages[index].hasOwnProperty("timeout")) {
		timeout = settings.pages[index].timeout * 1000 / tvpcSpeed;
	}
	
	// Load page
	if (settings.pages[index].type == "photo") {
		console.log("Photo, use preload");
		if (preload == null) {
			console.log("No photo stored, load asynchronous");
			preloadImage(false);
			if (settings == null) {
				clearTimeout(tvpcUpdateTimeout);
				tvpcUpdateTimeout = setTimeout(function(){tvpcUpdate();}, timeout);
				return;
			}
		}
		console.log("Show photo from preload: " + preload.path);
		$("#tvpc-content").html(
				'<article class="photo" style="background-image: url(\'' + preload.path + '\');">'
				+ '<p>'
				+ preload.name
				+ '<span>' + preload.date + '</span>'
				+ '</p>'
				+ '</article>'
			);
	} else {
		console.log("Show slide " + settings.pages[index].type);
		$.ajax({
			url: path + "/index.php?content"
					+ "&type=" + settings.pages[index].type
					+ "&index=" + index
					+ "&subindex=" + subindex,
			success: function(result) {
					// Type didn't match, reload requested.
					if (result == "RELOAD") {
						settings = null;
						return;
					}
					console.log("- fading");
					$("#tvpc-content").fadeOut(
							fadeSpeed,
							function() {
									$("#tvpc-content").html(result).fadeIn(fadeSpeed);
									console.log("- faded in");
								}
						);
					console.log("- faded out");
				}
		});
	}
	
	console.log("Command to switch content issued");
	
	switch (settings.pages[index].type) {
		case "poster":
			subindex++;
			if (subindex >= settings.pages[index].posters.length) {
				subindex = 0;
				index++;
			}
			break;
		default:
			index++;
			break;
	}
	
	if (index < settings.pages.length && settings.pages[index].type == "photo") {
		console.log("Preload next image in advance");
		preloadImage(true);
	}
	
	console.log("Set timeout for next loop: " + timeout);
	clearTimeout(tvpcUpdateTimeout);
	tvpcUpdateTimeout = setTimeout(tvpcUpdate, timeout);
	console.log("Timeout set, all done.");
}

function preloadImage(async) {
		console.log("Preloading photo "
					+ (async ? "asynchronous" : "synchronous") + " :"
					+ path + "/index.php?content"
					+ "&type=" + settings.pages[index].type
					+ "&index=" + index
					+ "&subindex=" + subindex);
		$.ajax({
			url: path + "/index.php?content"
					+ "&type=" + settings.pages[index].type
					+ "&index=" + index
					+ "&subindex=" + subindex,
			async: async,
			success: function(result) {
					// Type didn't match, reload requested.
					if (result == "RELOAD") {
						console.log("- Preloading failed, reload requested");
						settings = null;
						return;
					}
					console.log("- Retrieved result.");
					preload = JSON.parse(result);
					console.log("- Retrieved path: " + preload.path);
					preload.img = new Image().src = preload.path;
					console.log("- Preloaded!");
				}
		});
}

function timeUpdate() {
	//console.log(showSeparator ? "Tick..." : "Tock...");
	var date = new Date();
	$("#tvpc-time").text(("0" + date.getHours()).slice(-2) + (showSeparator ? ":" : " ") + ("0" + date.getMinutes()).slice(-2));
	$("#tvpc-clock").text(("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2) + ":" + ("0" + date.getSeconds()).slice(-2));
	showSeparator = !showSeparator;
}

$(document).ready(function(){
		tvpcUpdateTimeout = setTimeout(tvpcUpdate, 10000);
		timeUpdate();
		timeUpdateInterval = setInterval(timeUpdate, 1000);
	}
);