
$(".projectEditMenu").on("click", function(event){
	event.preventDefault();
	pageLoader.load(event.currentTarget.href);
	return false;
});
