/**
 * @mods Shop and Fleet reduc Time
 * @version 1.0
 * @author Danter14
 * @licence MIT
 * @package 2Moons
 * @version 1.8 - 1.9 - 2.0
 */

// Search Line 32
function GetDuration() {
	var sp = document.getElementsByName("speed")[0].value;
	return Math.max(Math.round((3500 / (sp * 0.1) * Math.pow(dataFlyDistance * 10 / data.maxspeed, 0.5) + 10) / data.gamespeed) * data.fleetspeedfactor, data.fleetMinDuration);
}

// replace
function GetDuration() {
	var sp = document.getElementsByName("speed")[0].value;
	return Math.max(Math.round((3500 / (sp * 0.1) * Math.pow(dataFlyDistance * 10 / data.maxspeed, 0.5) + 10) / data.gamespeed / (1 + (data.fleetReducSpeed / 100))) * data.fleetspeedfactor, data.fleetMinDuration);
}