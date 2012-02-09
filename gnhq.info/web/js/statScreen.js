var StatScreen = {
	
	init: function() {
		StatScreen.Jq.getOkrug().change(function() {
			StatScreen.Jq.clearUik();
			StatScreen.Exchange.Activate();	
		});
		StatScreen.Jq.getUik().change(function() {
			if (StatScreen.Jq.getUik().val() != '') {
				StatScreen.Jq.getSelectionRow().find('#ALL').get(0).checked = true;
			}
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
			})
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
		
		getLineValue: function(lineCode, watchType) {
			return $('#' + lineCode + '_' + watchType);
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
		FormRequest: function() {
			return {
				'okrugAbbr':     StatScreen.Filter.getOkrugAbbr(),
				'uik':           StatScreen.Filter.getUik(),
				'selectionType': StatScreen.Filter.getSelectionType()
			}
		},
		
		Activate: function() {
			$.ajax(
				'getData.php', 
				{
					'data'      : StatScreen.Exchange.FormRequest(),
					'dataType'  : 'json',
					'cache'     : true,
					'async'     : true,
					'success'   : function(data, status, request) {
										StatScreen.Exchange.Redraw(data.mode);
    									StatScreen.Exchange.SetResult(data);
								  }
				}
			);
		}, 
		
		Redraw: function(mode) {
			if (mode == 'UIK') {
				StatScreen.Jq.getUikMenu().show();
				StatScreen.Jq.getCurrentStats().hide();
				StatScreen.Jq.getSelectionRow().css({'visibility': 'hidden'});
			} else {
				StatScreen.Jq.getUikMenu().hide();
				StatScreen.Jq.getCurrentStats().show();
				StatScreen.Jq.getSelectionRow().css({'visibility': 'visible'});
			}
		},
		
		SetResult: function(data) {
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
		}
		
		
	},
	
	ResultSetter: {
		setTotalCount: function(cnt) {
			StatScreen.Jq.getTotalCount().text(cnt);
		},
		
		setDiscrepancyCount: function(cnt) {
			StatScreen.Jq.getDiscrepancyCount().text(cnt);
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
					$(this).attr('href', reportLink)	
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
			StatScreen.Jq.getLineValue(lineCode, watchType).html(result + '%');
		},
		
		_addUik: function(uik) {
			$('<option>').val(uik).html('УИК ' + uik).appendTo(StatScreen.Jq.getUik());
		}
	}
};

$(document).ready(function() {
	StatScreen.init();
	StatScreen.Exchange.Activate();
});

