function runFormAdder(container, listClass, button, counter, label, remover, removerLabel){
	$("."+container).append("<ul class='"+listClass+"'></ul>");
	$("."+container).attr(counter, 0);

	$("#"+button).on("click", function(){
		
		var data = $("."+container).attr("data-prototype");
		data = data.replace(/__name__label__/g, label);
		data = data.replace(/__name__/g, $("."+container).attr(counter));
		$("."+container).attr(counter, parseInt($("."+container).attr(counter))+1);
		
		var li = $("<li></li>");
		var form = $("<div style='padding: 1%;'></div>");
		var buttonContainer = $("<div class='cs-center'></div>");
		var button = $("<button class='btn btn-warning "+remover+"' type='button'>"+removerLabel+"</button>");
		
		$(button).on("click", function(event){
			$(event.target).parent().parent().parent().remove();
		});
		
		$(form).append(data);
		$(buttonContainer).append(button);
		$(form).append(buttonContainer);
		$(li).append(form);
		
		$("."+listClass).append(li);
	});
};

function runFormSubmit(selector){
	$(selector).on("submit", function(event){
		
		pageLoader.setLoading();
		
		$.ajax({type:event.currentTarget.method, data: $(this).serialize(), url:event.currentTarget.action,
			success: function(data){
				$(".loader-content").html(data);
				pageLoader.setLoaded();
			},
			error: function(){
				$(".loader-content").html('Une erreur est survenue.');
				pageLoader.setLoaded();
			}
		});
		
		event.preventDefault();
		return false;
	});
};

$(function(){
	runFormAdder("tagWigetContainer", "tagWigetList", "addTagButton", "tagsNumber", "new tag : ", "tagRemover", "Remove tag");
	runFormAdder("linksWigetContainer", "linkWigetList", "addLinksButton", "linksNumber", "new link : ", "linkRemover", "Remove link");
	
	runFormSubmit("form");
});