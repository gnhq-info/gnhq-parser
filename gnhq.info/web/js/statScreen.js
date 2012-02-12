var StatScreen = {
	
	Init: function() {
		StatScreen.Jq.getOkrug().change(function() {
			StatScreen.Jq.clearUik();
			StatScreen.Exchange.Activate();	
		});
		StatScreen.Jq.getUik().change(function() {
			StatScreen.Exchange.Activate();
			
		});
		StatScreen.Jq.getSelectionRow().find('input').change(function() {
			StatScreen.Exchange.Activate();	
		});
	},
	
	Jq: {
		getOkrug: function() {
			return $('#okrug');
		},
		
		getUik: function() {
			return $('#uik');
		},
		
		clearUik: function() {
			StatScreen.Jq.getUik().children().each(function() {
				if(!$(this).is(':first-child')) {
					$(this).remove();
				}
			});
		},
		
		getSelectionRow: function() {
			return $('#selectionRow');
		},
		
		getSelectionType: function() {
			return $('input[name=selectionType]:checked');
		},
		
		getTotalCount: function() {
			return $('#uikCount');
		},
		
		getProtocolCount: function() {
			return $('#protocolCount');
		},
		
		getDiscrepancyCount: function() {
			return $('#discrepancyCount');
		},
		
		getDiscrepancy: function() {
			return $('#hasDiscrepancy');
		},
		
		getDiscrepancyOne: function() {
			return $('#discrepancyOne');
		},
		
		getDiscrepancyMany: function() {
			return $('#discrepancyMany');
		},
		
		getNoDiscrepancy: function() {
			return $('#noDiscrepancy');
		},
		
		getLineValue: function(lineCode, watchType) {
			return $('#' + lineCode + '_' + watchType);
		},
		
		getLineDiagCont: function(lineCode, watchType) {
			return $('#' + lineCode + '_' + watchType + '_IMAGE');
		},
		
		getLineDiag: function(lineCode, watchType) {
			return StatScreen.Jq.getLineDiagCont(lineCode, watchType).find('div');
		},
		
		getCurrentStats: function() {
			return $('#currentStats');
		},
		
		getUikMenu: function() {
			return $('#uikMenu');
		},
		
		getHasProtocol: function() {
			return $('#hasProtocol');
		},
		
		getNoProtocol: function() {
			return $('#noProtocol');
		},
		
		getReport: function() {
			return $('#report');
		}, 
		
		getNoReport: function() {
			return $('#noReport');
		},
		
		getStatHdr: function() {
			return $('#statsHdr');
		},
		
		getOfCount: function() {
			return $('#ofCount');
		},
		
		getGnCount: function() {
			return $('#gnCount');
		}
	},
	
	Filter: {
		getOkrugAbbr: function() {
			return StatScreen.Jq.getOkrug().val();
		},
		
		getUik: function() {
			return StatScreen.Jq.getUik().val();
		},
		
		getSelectionType: function() {
			return StatScreen.Jq.getSelectionType().attr('id');
		}
	},
	
	Exchange: {
		
		cache: {},
		
		FormRequest: function() {
			return {
				'okrugAbbr':     StatScreen.Filter.getOkrugAbbr(),
				'uik':           StatScreen.Filter.getUik(),
				'selectionType': StatScreen.Filter.getSelectionType(),
				'key':           StatScreen.Filter.getOkrugAbbr() + '|' + StatScreen.Filter.getUik() + '|' + StatScreen.Filter.getSelectionType() 
			};
		},
		
		Activate: function() {
			var rqData, data;
			Decoration.SplashScreen.Show();
			rqData = StatScreen.Exchange.FormRequest();
			if (data = StatScreen.Exchange.cache[rqData.key]) {
				StatScreen.Exchange.OnResponse(data);
			} else {
				$.ajax(
				'getData.php', 
				{
					'data'      : rqData,
					'dataType'  : 'json',
					'async'     : true,
					'success'   : function(data, status, request) {
										StatScreen.Exchange.OnResponse(data);
    									StatScreen.Exchange.cache[rqData.key] = data;
								  },
					'error'     : function(data, status, request) {
										Decoration.SplashScreen.Hide();
					              }
				}
				);
			}
		}, 
		
		OnResponse: function(data) {
			Decoration.SplashScreen.Hide();
			StatScreen.Exchange.Redraw(data.mode);
    		StatScreen.Exchange.SetResult(data);
		},
		
		Redraw: function(mode) {
			if (mode == 'UIK') {
				StatScreen.Jq.getUikMenu().show();
				StatScreen.Jq.getCurrentStats().hide();
				StatScreen.Jq.getSelectionRow().find('input[type=radio]').attr('disabled', 'disabled');
			} else {
				StatScreen.Jq.getUikMenu().hide();
				StatScreen.Jq.getCurrentStats().show();
				StatScreen.Jq.getSelectionRow().find('input[type=radio]').removeAttr('disabled');
			}
		},
		
		SetResult: function(data) {
			StatScreen.ResultSetter.setStatsHdr(data);
			if (data.mode != 'UIK') {
				StatScreen.ResultSetter.setTotalCount(data.totalCount);
				StatScreen.ResultSetter.setDiscrepancyCount(data.discrepancyCount);
				StatScreen.ResultSetter.setProtocolCount(data.protocolCount);
				StatScreen.ResultSetter.setUiks(data.uiks);
			} else {
				StatScreen.ResultSetter.setHasProtocol(data.hasProtocol);
				StatScreen.ResultSetter.setReport(data.reportLink);
			}
			StatScreen.ResultSetter.setGnResult(data.gnResult);
			StatScreen.ResultSetter.setOfResult(data.ofResult);
			StatScreen.ResultSetter.setResultDiscrepancy(data.ofResult, data.gnResult);
			StatScreen.ResultSetter.setOfCount(data.ofCount);
			StatScreen.ResultSetter.setGnCount(data.gnCount);
		}
	},
	
	ResultSetter: {
		
		setStatsHdr: function(data) {
			if (data.mode == 'UIK') {
				StatScreen.Jq.getStatHdr().html('УИК ' + StatScreen.Jq.getUik().val());
			} else if (data.mode == 'OIK') {
				StatScreen.Jq.getStatHdr().html(StatScreen.Jq.getOkrug().val());
			} else {
				StatScreen.Jq.getStatHdr().html('Москва');
			}
		}, 
		
		setTotalCount: function(cnt) {
			StatScreen.Jq.getTotalCount().text(cnt);
		},
		
		setOfCount: function(cnt) {
			StatScreen.Jq.getOfCount().text(cnt);
		},
		
		setGnCount: function(cnt) {
			StatScreen.Jq.getGnCount().text(cnt);
		},
		
		setDiscrepancyCount: function(cnt) {
			if (cnt == 0) {
				StatScreen.Jq.getNoDiscrepancy().show();
				StatScreen.Jq.getDiscrepancy().hide();
			} else {
				StatScreen.Jq.getNoDiscrepancy().hide();
				StatScreen.Jq.getDiscrepancy().show();
				StatScreen.Jq.getDiscrepancyCount().text(cnt);
				if (cnt == 1) {
					StatScreen.Jq.getDiscrepancyMany().hide();
					StatScreen.Jq.getDiscrepancyOne().show();
				} else {
					StatScreen.Jq.getDiscrepancyMany().show();
					StatScreen.Jq.getDiscrepancyOne().hide();
				}
			}
		},
		
		setProtocolCount: function(cnt) {
			StatScreen.Jq.getProtocolCount().text(cnt);
		},
		
		setReport: function(reportLink) {
			if (!reportLink) {
				StatScreen.Jq.getReport().hide();
				StatScreen.Jq.getNoReport().show();
			} else {
				StatScreen.Jq.getNoReport().hide();
				StatScreen.Jq.getReport().show().find('a').each(function(){
					$(this).attr('href', reportLink);
				});
			}
		},
		
		setUiks: function(uiks) {
			StatScreen.Jq.clearUik();
			for (var i in uiks) {
				StatScreen.ResultSetter._addUik(uiks[i]);
			}
		},
		
		setGnResult: function(gnResult) {
			StatScreen.ResultSetter._setResult(gnResult, 'GN');
		},
		
		setOfResult: function(ofResult) {
			StatScreen.ResultSetter._setResult(ofResult, 'OF');
		},
		
		setResultDiscrepancy: function(ofResult, gnResult) {
			var _discr;
			for (var lineCode in ofResult) {
				_discr = (ofResult[lineCode] - gnResult[lineCode]).toFixed(2);
				StatScreen.ResultSetter._setLineNumber(lineCode, 'DISCR', _discr);
			}
		},
		
		setHasProtocol: function(hasProtocol) {
			if (hasProtocol) {
				StatScreen.Jq.getNoProtocol().hide();
				StatScreen.Jq.getHasProtocol().show();
			} else {
				StatScreen.Jq.getHasProtocol().hide();
				StatScreen.Jq.getNoProtocol().show();
			}
		},
		
		_setResult: function(result, watchType) {
			for (var lineCode in result) {
				StatScreen.ResultSetter._setLineValue(lineCode, watchType, result[lineCode]);
			}
		},
		
		_setLineValue: function(lineCode, watchType, result) {
			StatScreen.ResultSetter._setLineNumber(lineCode, watchType, result);
			StatScreen.ResultSetter._setLineDiag(lineCode, watchType, result);
		},
		
		_setLineNumber: function(lineCode, watchType, result) {
			StatScreen.Jq.getLineValue(lineCode, watchType).html(result + '%');
			if (result < 0) {
				StatScreen.Jq.getLineValue(lineCode, watchType).addClass('line-negative').removeClass('line-positive');
			} else {
				StatScreen.Jq.getLineValue(lineCode, watchType).removeClass('line-negative').addClass('line-positive');
			}
		},
		
		_setLineDiag: function(lineCode, watchType, result) {
			var realWidth;
			realWidth = (StatScreen.Jq.getLineDiagCont(lineCode, watchType).width() * parseFloat(result) / 100).toFixed(0);
			StatScreen.Jq.getLineDiag(lineCode, watchType).css('width', realWidth + 'px');	
		}, 
		
		_addUik: function(uik) {
			$('<option>').val(uik).html('УИК ' + uik).appendTo(StatScreen.Jq.getUik());
		}
	}
};

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

$(document).ready(function() {
	// default ajax settings
	$.ajaxSetup({cache: true, ifModified: true});
	// decoration
	Decoration.Tooltips();
	
	
	StatScreen.Init();
	StatScreen.Exchange.Activate();
});

