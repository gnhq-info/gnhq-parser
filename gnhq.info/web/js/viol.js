var Viol = {
	Init: function() {
		for (var vtCode in StaticData.ViolationTypes) {
			$('<option>').val(vtCode).html(StaticData.ViolationTypes[vtCode]).appendTo($('#ViolType'));
		}
		
		for (var prCode in StaticData.Watchers) {
			$('<option>').val(prCode).html(StaticData.Watchers[prCode]).appendTo($('#ProjectCode'));
		}
		
		for (var regionNum in StaticData.Regions) {
			$('<option>').val(regionNum).html(StaticData.Regions[regionNum]).appendTo($('#regionNum'));
		}
		$('#regionNum').val(77);
		Viol.Filter.RedrawOkrugs(77);
		
		$('#regionNum').change(function() {
			Viol.Filter.RedrawOkrugs($('#regionNum').val()); 
		});
		
		$('#filterForm').submit(function() {
			Viol.Exchange.loadData();
			return false;
		});
		$('#filterForm > button').click(function() {
			Viol.Exchange.loadData();
			return false;
		});
	},
	
	Exchange: {
		loadData: function () {
			Decoration.SplashScreen.Show();
			var _data = {
				'ProjectCode': $('#ProjectCode').val(),
				'ViolType':    $('#ViolType').val(),
				'regionNum':   $('#regionNum').val(),
				'okrug':       $('#okrug').val()
			}
			$.ajax(
				'getViolData.php', 
				{
					'data'      : _data,
					'dataType'  : 'json',
					'async'     : true,
					'success'   : function(data, status, request) {
										Viol.SetResult.processResult(data);
    									Decoration.SplashScreen.Hide();
								  },
					'error'     : function(data, status, request) {
										Decoration.SplashScreen.Hide();
					              }
				}
			);
		}
	},
	
	SetResult: {
		processResult: function(data) {
			Viol.SetResult.setCount(data.count);
		},
		
		setCount: function(cnt) {
			$('#violCount .val').html(cnt);
		}
	},
	
	
	Filter: {
		RedrawOkrugs: function(regionNum) {
			$('#okrug').find('option').each(function(){
				if(!$(this).is(':first-child')) {
					$(this).remove();
				}
			});
			if (StaticData.Okrugs[regionNum]) {
				$('#okrug').removeClass('disabled').removeAttr('disabled');
				for (var okrugCode in StaticData.Okrugs[regionNum]) {
					$('<option>').val(okrugCode).html(StaticData.Okrugs[regionNum][okrugCode]).appendTo($('#okrug'));
				} 
			} else {
				$('#okrug').addClass('disabled').attr('disabled', 'disabled');
			}
		}
	},
	
	Dict: {
		TIK: {
			getName: function(RegionNum, TikNum) {
				return StaticData.Tiks[RegionNum, TikNum];
			}
		},
		ViolType: {
			getName: function(MergedTypeId) {
				return StaticData.ViolationTypes[MergedTypeId];
			}
		}
	}
};

$(document).ready(function() {
	// default ajax settings
	$.ajaxSetup({cache: false, ifModified: true});
	
	Viol.Init();
});