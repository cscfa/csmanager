
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

function runPageSelector(selector){
	$(selector).each(function(){
		$(this).on("click", function(event){
			pageLoader.load(event.currentTarget.href);
			event.preventDefault();
			return false;
		})
	});
};

function runElementSelector(selector){
	$(selector).each(function(){
		$(this).on("click", function(event){
			pageLoader.load($(event.currentTarget).attr('href'));
			event.preventDefault();
			return false;
		})
	});
};

$(function(){
	runFormSubmit("form");
	runPageSelector(".cs-paginator-selector");
	runElementSelector(".cs-datagrid-selectable-datagrid");
});