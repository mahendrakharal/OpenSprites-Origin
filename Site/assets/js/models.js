OpenSprites.models = {};

OpenSprites.models.BaseModel = function(_target){
	var modelObj = {};
	modelObj._target = _target;
	modelObj.loadJson = function(json){
		
	};
	return modelObj;
};

OpenSprites.models.AssetList = function(_target){
	var modelObj = OpenSprites.models.BaseModel(_target); // attempting a java-class-like structure
	modelObj.loadJson = function(json){
		modelObj._target.html("");
		for(var i = 0;i<json.length;i++) {
			var html = $("<div>").addClass("file").addClass(json[i].type).attr("data-name", json[i].name).attr("data-utime", json[i].upload_time);
			if(json[i].type == "image"){ 
				html.css("background-image", "url("+OpenSprites.domain + json[i].url+")");
			}
        		modelObj._target.append(html);
		}
	};
	return modelObj;
};

OpenSprites.models.SortableAssetList = function(_target){
	var modelObj = OpenSprites.models.BaseModel(_target);
	
	var baseHtml = $('<div class="sortby toggleset">Sort by: </div><div class="types toggleset">Types: </div><br/>'+
                        '<div class="assets-list" data-sort="popularity" data-type="all">Loading...</div>');
	_target.html('').append(baseHtml);
	var listing = baseHtml.eq(3);
	var subModel = OpenSprites.models.AssetList(listing);
	function loadAssetList(sort, max, type){
		$.get(OpenSprites.domain + "/site-api/list.php?sort="+sort+"&max="+max+"&type="+type, function(data){
			subModel.loadJson(data);
		});
	}
	loadAssetList("popularity", 15, "all");
	
	var orderBy = {
		popularity: "Popularity",
		alphabetical: "A-Z",
		newest: "Newest",
		oldest: "Oldest"
	};
	var types = {
		all: "All",
		image: "Costumes",
		sound: "Sounds",
		script: "Scripts"
	};
	
	var sortButtons = baseHtml.eq(0);
	for(key in orderBy){
		var button = $("<button>").attr("data-for", key).click(function(){
			listing.attr("data-sort", key);
			loadAssetList(key, 15, listing.attr("data-type"));
		});
		button.text(orderBy[key]);
		if(key == "popularity") button.addClass("selected");
		sortButtons.append(button);
	}
	var typesByttons = baseHtml.eq(1);
	for(key in types){
		var button = $("<button>").attr("data-for", key).click(function(){
			listing.attr("data-type", key);
			loadAssetList(listing.attr("data-sort"), 15, key);
		});
		button.text(types[key]);
		if(key == "all") button.addClass("selected");
		typesButtons.append(button);
	}
	var buttonSetClick = function(){
		$(this).parent().find("button").removeClass("selected");
		$(this).addClass("selected");
	};
	sortButtons.find("button").click(buttonSetClick);
	typesButtons.find("button").click(buttonSetClick);
	return modelObj;
};
