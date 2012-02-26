var Viol = {
	Init: function() {
		for (var i in StaticData.ViolationTypesOrder) {
			var vtCode = StaticData.ViolationTypesOrder[i];
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
		Viol.Exchange.loadData();
	},
	
	Exchange: {
		loadData: function () {
			Decoration.SplashScreen.Show();
			var _data = {
				'ProjectCode': $('#ProjectCode').val(),
				'ViolType':    $('#ViolType').val(),
				'regionNum':   $('#regionNum').val(),
				'okrug':       $('#okrug').val()
			}; 
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
		},
		
		showViolation: function(projectCode, projectId) {
			var _data = {
				'isSingle': 1,	
				'ProjectCode': projectCode,
				'ProjectId':   projectId
			}; 
			$.ajax(
				'getViolData.php', 
				{
					'data'      : _data,
					'dataType'  : 'json',
					'async'     : false,
					'success'   : function(data, status, request) {
										Viol.SetResult.processSingleResult(data.violData);
								  },
					'error'     : function(data, status, request) {
										alert('Ошибка при загрузке информации о нарушении');
					              }
				}
			);
		}
	},
	
	Feed: {
		clear: function() {
			$('#violFeed tbody tr').each(function() {
				$(this).remove();
			});
		},
		add: function(tr) {
			tr.appendTo($('#violFeed tbody'));
		},
		hideAll: function() {
			$('#violFeed tbody tr').each(function() {
				$(this).hide();
			});
		},
		showAll: function() {
			$('#violFeed tbody tr').each(function() {
				$(this).show();
			});
		},
		showTik: function(tikNum) {
			Viol.Feed.hideAll();
			$('#violFeed tbody tr').each(function() {
				if ($(this).attr('tikNum') == tikNum) {
					$(this).show();
				}
			});
		}
	},
	
	GeoTree: {
		clear: function() {
			$('#geoTree .tree').children().each(function() {
				$(this).remove();
			});
		}, 
		addTotal: function(cnt) {
			Viol.GeoTree.add($('<a>').addClass('total').html('Всего: ' + cnt).click(function() {
				Viol.Feed.showAll();
				Viol.GeoTree.select($(this));
				}));
		},
		add: function(a) {
			a.appendTo($('#geoTree .tree'));
		},
		select: function(a) {
			$('#geoTree a').removeClass('selected');
			a.addClass('selected');
		},
		selectTotal: function() {
			$('#geoTree a.total').addClass('selected');
		}
	},
	
	SetResult: {
		processResult: function(data) {
			// count
			Viol.SetResult.setCount(data.cnt);
			
			// feed
			Viol.Feed.clear();
			
			for (var _i in data.vshort) {
				Viol.Feed.add(Viol.SetResult.buildViolTr(data.vshort[_i]));
			}
			
			// geo tree
			Viol.GeoTree.clear();
			Viol.GeoTree.addTotal(data.cnt);
			Viol.GeoTree.selectTotal();
			var _tikNum;
			for (var i in StaticData.TiksOrder[data.regionNum]) {
				_tikNum = StaticData.TiksOrder[data.regionNum][i];
				if (data.tikCount[_tikNum]) {
					Viol.SetResult.addTikDiv(data.tikCount[_tikNum], _tikNum, data.regionNum);
				}
			}
		},
		
		addTikDiv: function (cnt, tikNum, regionNum) {
			var _tikName;
			_tikName = Viol.Dict.TIK.getName(regionNum, tikNum);
			if (_tikName) {
				Viol.GeoTree.add(
				$('<a>').html(_tikName + ': ' + cnt).click(function() {
					Viol.Feed.showTik(tikNum);
					Viol.GeoTree.select($(this));
				}));
			}
		},
		
		buildViolTr: function(row) {
			var _place, _time, _descrHtml, _tr, _vtypeHdr;
			_place = Viol.Utility.buildPlace(row);
			_time = Viol.Utility.formatTime(row.Obstime);
			_vtypeHdr = $('<span>').addClass('vhdr').html(Viol.Dict.ViolType.getName(row.MergedTypeId) + ': ');
			_descrHtml =  row.Description;
			_tr = $('<tr>').attr('tikNum', row.TIKNum);
			$('<td>').append($('<span>').html(row.ProjectCode).attr('title', Viol.Dict.Watchers.getName(row.ProjectCode))
															.tooltip({'placement' : 'top'})
															.addClass('my-tooltip')).appendTo(_tr);
			$('<td>').html(_time).appendTo(_tr);
			$('<td>').html(_place).appendTo(_tr);
			$('<td>').append(_vtypeHdr).append($('<a>').html(_descrHtml)).click(function() {
				Viol.Exchange.showViolation(row.ProjectCode, row.ProjectId);
			}).appendTo(_tr);
			return _tr;
		},
		
		setCount: function(cnt) {
			$('#violCount .val').html(cnt);
		},
		
		processSingleResult: function(row) {
			$('#violationModel .place').html(Viol.Utility.buildPlace(row) + '   ' + Viol.Utility.formatTime(row.Obstime));
			$('#violationModel .violationType span').html(Viol.Dict.ViolType.getName(row.MergedTypeId));
			$('#violationModel .description span').html(row.Description);
			$('#violationModel .hqcomment span').html(row.Hqcomment);
			$('#violationModel .mobilegroup span').html(row.Mobgroupsent ? 'отправлена' : 'не отправлена');
			var _policeReaction;
			switch (row.PoliceReaction) {
				case "1":
				_policeReaction = 'Вызвана';
				break;
				case "2":
				_policeReaction = 'Прибыла';
				break;
				default:
				_policeReaction = 'Нет данных';
				break;
			}
			$('#violationModel .police span').html(_policeReaction);
			$('#violationModel').modal('show');
			$('#violationModel .media div').each(function(){
				$(this).remove();
			});
			var _mediaData;
			for (var i in row.Media) {
				_mediaData = row.Media[i];
				$('<div>')
					.append($('<div>').html(_mediaData['description']))
					.append($('<a>').attr('href', _mediaData['url']).attr('target', '_blank').html(_mediaData['url']))
					.appendTo($('#violationModel .media'));
			}
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
	
	Utility: {
		buildPlace: function (row) {
			if (row.UIKNum && row.UIKNum != '0') {
				_place = 'УИК ' + row.UIKNum;
			} else if (row.TIKNum && row.TIKNum != '0') {
				_place = Viol.Dict.TIK.getName(row.RegionNum, row.TIKNum);
			} else if (row.Place) {
				_place = row.Place;
			} else {
				_place = 'Не известно';
			}
			return _place;
		},
		
		formatTime: function(t) {
			var _timeArray, _dateParts, _timeParts;
			_timeArray = t.split(' ');
			_dateParts = _timeArray[0].split('-');
			_timeParts = _timeArray[1].split(':');
			return _dateParts[2] + '.' + _dateParts[1] + ' ' + _timeParts[0] + ':' + _timeParts[1];
		}
	},
	
	Dict: {
		Watchers: {
			getName: function(ProjectCode) {
				return StaticData.Watchers[ProjectCode];
			}
		},
		
		TIK: {
			getName: function(RegionNum, TikNum) {
				return StaticData.Tiks[parseInt(RegionNum, 10)][parseInt(TikNum, 10)];
			}
		},
		ViolType: {
			getName: function(MergedTypeId) {
				return StaticData.ViolationTypes[parseInt(MergedTypeId, 10)];
			}
		}
	}
};

$(document).ready(function() {
	// default ajax settings
	$.ajaxSetup({cache: false, ifModified: true});
	
	Viol.Init();
});