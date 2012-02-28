var Viol = {
	Init: function() {
		for (var prCode in StaticData.Watchers) {
			
			$('<div>')
				.append($('<input>').attr('type', 'checkbox').val(prCode).attr('id', prCode).change(function() {
					Viol.Exchange.loadData();
					return false;
				}))
				.append($('<label>').attr('for',prCode).html(StaticData.Watchers[prCode]))
				.appendTo($('#watchers'));
		}
		$('#watchers input').each(function(){
			$(this).get(0).checked = true;
		});
		
		for (var _vTypeOrder in StaticData.ViolationTypesOrder) {
			var _vTypeName, _vTypeId, _chboxId;
			_vTypeId = StaticData.ViolationTypesOrder[_vTypeOrder];
			_vTypeName = Viol.Dict.ViolType.getName(_vTypeId);
			_chboxId = 'vType-'+_vTypeId;
			$('<div>').attr('id', 'vTypeCont-'+_vTypeId).addClass('selected')
					.append($('<input>').attr('type', 'checkbox').attr('id',_chboxId).attr('typeId', _vTypeId).click(function() {
						Viol.Feed.toggleByTypeId($(this).attr('typeId'));
						if ($(this).parent().hasClass('selected')) {
							$(this).parent().removeClass('selected')
						} else {
							$(this).parent().addClass('selected');
						}
					}))
					.append($('<label>').attr('for',_chboxId).html(_vTypeName))
					.append($('<span>').addClass('val'))
					.appendTo($('#vTypes .data'));
			$('#'+_chboxId).get(0).checked = true;	
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

		$('#regionNum').change(function() {
			Viol.Exchange.loadData();
			return false;
		});
		$('#okrug').change(function() {
			Viol.Exchange.loadData();
			return false;
		});
		
		

		Viol.Exchange.loadData();
	},
	
	Exchange: {
		loadData: function () {
			Decoration.SplashScreen.Show();
			var _data = {
				'ProjectCode': Viol.Filter.GetProjectCodes(),
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
										Viol.SetResult.setTwits(data.twits);
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
										Viol.SetResult.setTwits(data.twits);
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
		toggleByTypeId: function(typeId) {
			$('#violFeed tbody tr').each(function() {
				if ($(this).attr('typeId') == typeId) {
					if ($(this).css('display') == 'none') {
						$(this).css('display', 'table-row');
					} else {
						$(this).css('display', 'none');
					}
				}
			});
		}
	},
	
	
	SetResult: {
		setTwits: function (twits) {
			$('#twitterFeed').children().each(function(){
				$(this).remove();
			});
			for (var _twitNum in twits) {
				$('<div>').html(Viol.Utility.formatTime(twits[_twitNum].time) + '&nbsp;&nbsp;' + twits[_twitNum].html).appendTo('#twitterFeed');
			}
		},
		
		setCurrentPlace: function () {
			var place;
			place = $('#regionNum option:selected').html();
			if (!$('#okrug').hasClass('disabled') && $('#okrug option:selected').val() != '') {
				place += '; ' + $('#okrug option:selected').html();
			}
			if ($('#uikNum option:selected').val() != '') {
				place += '; ' + $('#uikNum option:selected').html();
			}
			$('#selectedPlace').html(place);
		},
		
		processResult: function(data) {
			// place
			Viol.SetResult.setCurrentPlace();
			
			// count
			Viol.SetResult.setCount(data.cnt);
			
			// feed
			Viol.Feed.clear();
			
			for (var _i in data.vshort) {
				Viol.Feed.add(Viol.SetResult.buildViolTr(data.vshort[_i]));
			}
			
			// violation types
			var _violTypeId;
			for (var i in StaticData.ViolationTypesOrder) {
				_violTypeId = StaticData.ViolationTypesOrder[i];
				if (data.vTypeCount[_violTypeId]) {
					Viol.Filter.SetVTypeCount(_violTypeId, data.vTypeCount[_violTypeId]);
				} else {
					Viol.Filter.SetVTypeCount(_violTypeId, 0);
				}
			}
		},
		
		
		
		buildViolTr: function(row) {
			var _place, _time, _descrHtml, _tr, _vtypeHdr;
			_place = Viol.Utility.buildPlace(row);
			_time = Viol.Utility.formatTime(row.Obstime);
			_vtypeHdr = $('<span>').addClass('vhdr').html(Viol.Dict.ViolType.getName(row.MergedTypeId) + ': ');
			_descrHtml =  row.Description;
			_tr = $('<tr>').attr('tikNum', row.TIKNum).attr('typeId', row.MergedTypeId).css('display','table-row');
			$('<td>').append($('<span>').html(row.ProjectCode).attr('title', Viol.Dict.Watchers.getName(row.ProjectCode))
															.tooltip({'placement' : 'top'})
															.addClass('my-tooltip')).appendTo(_tr);
			$('<td>').html(_time).appendTo(_tr);
			$('<td>').html(_place).appendTo(_tr);
			$('<td>').append(_vtypeHdr).append($('<a>').html(_descrHtml)).click(function() {
				Viol.Exchange.showViolation(row.ProjectCode, row.ProjectId);
			}).appendTo(_tr);
			if (!$('#vType-'+row.MergedTypeId).is(':checked')) {
				_tr.hide();
			}
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
		
		SetVTypeCount: function (vTypeId, cnt) {
			$('#vTypeCont-'+vTypeId).find('.val').html(cnt);
		},
		
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
		},
		
		GetProjectCodes: function () {
			var _codes = [];
			$('#watchers input:checked').each(function(){
				_codes.push($(this).val()); 
			});
			return _codes;
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