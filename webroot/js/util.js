function utf8_to_base64(str) {
	return window.btoa(unescape(encodeURIComponent(str)));
}

function base64_to_utf8(str) {
	return decodeURIComponent(escape(window.atob(str)));
}

function escapeHtml(unsafe) {
	unsafe=unsafe+'';
	return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

function strpos (haystack, needle, offset) {
	var i = (haystack + '').indexOf(needle, (offset || 0));
	return i === -1 ? false : i;
}