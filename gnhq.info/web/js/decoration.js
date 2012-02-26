var Decoration = {
	
	Tooltips: function() {
		$('.line-title').tooltip({'placement' : 'top'});
	},	
	SplashScreen: {
		
		_div: null,
		
		Show: function () {
			if (!Decoration.SplashScreen._div) {
				Decoration.SplashScreen._div = $('<div>').addClass('splashScreen'). css({'height': $(document).height()}).appendTo($('body'));
			}
			Decoration.SplashScreen._div.show();
		},
		Hide: function () {
			Decoration.SplashScreen._div.hide();
		}
	}
};