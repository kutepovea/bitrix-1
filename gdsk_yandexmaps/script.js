$('.ymaps-b-clusters-content__sidelist-text').live('click', $.proxy(function (e) {

	var source = $('.placemarker_trigger');	
	var coords = source.prop('rel');
	var view = source.data('view');
	var activelink = source.prop('href');

	console.log(coords+" / "+view);
	
	this.map.map.setCenter(coords.split(','), 15, {
		callback: $.proxy(function() {
		
			for(k in this.map.myGeoObjects[view]) {
				console.log(this.map.myGeoObjects[view][k]['geometry']['_coordinates']);
				
				if(this.map.myGeoObjects[view][k]['geometry']['_coordinates'][0] == coords.split(',')[0] && this.map.myGeoObjects[view][k]['geometry']['_coordinates'][1] == coords.split(',')[1]) {
				
					var balloonOpener = $.proxy(function(e){ 
						if(e.get('newMap') != null) {
							setTimeout($.proxy(function() {
								console.log(this.map);
								this.map.myGeoObjects[view][k].balloon.open();
							}, {'map':this.map}), 300);      						
						}
						this.map.myGeoObjects[view][k].events.remove('mapchange', balloonOpener);
					}, {'map':this.map});
					this.map.myGeoObjects[view][k].events.add('mapchange', balloonOpener);
					break;									
				}
			}
			
		}, {'map':this.map})
	});
	return false;
}, {'map':this}));