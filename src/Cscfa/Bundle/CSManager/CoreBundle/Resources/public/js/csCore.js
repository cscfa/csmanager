
cs = function() {
};

cs.loader = function(selector){

	var element = new LoaderElement(selector);
	
	if(this.loader.list === undefined){
		this.loader.list = [];
	}
	this.loader.list.push(element);
	
	return element;
}

LoaderElement = function(selector){
	this.container = $(selector);
	this.header = $(selector).find(".cs-loader-head")[0];
	this.body = $(selector).find(".cs-loader")[0];
	this.receiver = $(this.body).find("div")[0];
	this.current = {
		href: null,
		element: null,
		isDefine: function(){
			return (this.href != null) && (this.element != null);
		}
	}
	
	this.setLoading = function(){
		$(this.body).removeClass("cs-loader");
		$(this.body).removeClass("cs-loaded");
		$(this.body).addClass("cs-loading");
	}
	
	this.setLoaded = function(){
		$(this.body).removeClass("cs-loading");
		$(this.body).addClass("cs-loaded");
	}
	
	this.load = function(url, element){
		var selfRef = this;
		
		selfRef.setLoading();

		selfRef.current.href = url;
		if (element) {
			selfRef.current.element = element;
		}
		
		$.get(url).done(function(data){
			selfRef.setLoaded();
			$(selfRef.receiver).html(data);
		}).fail(function(){
			selfRef.setLoaded();
			
			$(selfRef.receiver).html("<p>An error occured</p>");
		}).always(function(){
			if (element) {
				$(element).parent().addClass("active");
			}
		});
	}
	
	this.run = function(){
		var selfRef = this;
		
		$(this.header).find("a").each(function(index, element){
			$(element).on("click", {loaderElement: selfRef}, function(event){
				event.preventDefault();
				event.data.loaderElement.load($(event.target).attr("href"), event.target);
			});
		});
	}
	
	this.reload = function(){
		if(this.current.isDefine()){
			this.load(this.current.href, this.current.element);
		}
	}
}
