
function addOrigin(selector){
	var value = $(selector).val();
	$(selector).val(window.location.origin + value);
}

$(function(){
	addOrigin(".baseUrlNeeded");
    new Clipboard('.clipboardCopy');
});
