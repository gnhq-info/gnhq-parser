var Viol = {
	Init: function() {
		var _wDiv;
		for (var prCode in StaticData.Watchers) {
			
			_wDiv = $('<div>')
				.append($('<input>').attr('type', 'checkbox').val(prCode).attr('id', prCode).change(function() {
					Viol.Exchange.loadData();
					return false;
				}))
				.append($('<label>').attr('for', prCode).html(StaticData.Watchers[prCode]));
			if (Viol.Dict.Watchers.isOnline(prCode)) {
				_wDiv.addClass('online').attr('title', 'Проект предоставляет данные по нарушениям или предварительным результатам в режиме реального времени');
			}
			_wDiv.appendTo($('#watchers'));
		}
		
		$('#watchers input').each(function(){
			$(this).get(0).checked = true;
		});
		$('#watchersAll').click(function() {
					$('#watchers input').each(function(){
						$(this).get(0).checked = true;
					});
					Viol.Exchange.loadData();
					return false;
				});
				
		
		for (var _ord in StaticData.ViolationTypeGroupDataOrder) {
			var _vTypeName, _chboxId, _grp;
			_grp = StaticData.ViolationTypeGroupDataOrder[_ord];
			_vTypeName = Viol.Dict.ViolTypeGroups.getName(_grp);
			_chboxId = 'vType-'+_grp;
			$('<div>').attr('id', 'vTypeCont-'+_grp).addClass('selected')
					.append($('<input>').attr('type', 'checkbox').attr('id',_chboxId).attr('grpId', _grp).click(function() {
						Viol.Feed.applyFilters();
						if ($(this).parent().hasClass('selected')) {
							$(this).parent().removeClass('selected');
						} else {
							$(this).parent().addClass('selected');
						}
					}))
					.append($('<label>').attr('for',_chboxId).html(_vTypeName))
					.append($('<span>').addClass('val'))
					.appendTo($('#vTypes .data'));
			$('#'+_chboxId).get(0).checked = true;	
		}		
		
		$('#violTypeAll').click(function() {
					$('#vTypes .data input').each(function(){
						$(this).get(0).checked = true;
						$(this).parent().addClass('selected');
					});
					Viol.Feed.applyFilters();
					return false;
				});
		$('#violTypeNone').click(function() {
					$('#vTypes .data input').each(function(){
						$(this).get(0).checked = false;
						$(this).parent().removeClass('selected');
					});
					Viol.Feed.applyFilters();
					Viol.Feed.hideAll();
					return false;
				});		
		
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
		$('#filterForm button').click(function() {
			Viol.Exchange.loadData();
			return false;
		});
		
		$('#violFeedUikFilter').change(function(){
			Viol.Feed.applyFilters();
		});
		
		Viol.Exchange.loadData();
	},
	
	Exchange: {
		
		firstLoad: true,
		
		loadData: function () {
			Decoration.SplashScreen.Show();
			var _data = {
				'ProjectCode': Viol.Filter.GetProjectCodes(),
				'ViolType':    $('#ViolType').val(),
				'regionNum':   $('#regionNum').val(),
				'okrug':       $('#okrug').val(),
				'uikNum':      $('#uikNum').val(),
				'onlyClean':   $('#onlyClean').is(':checked') ? 1 : 0,
				'onlyControlRelTrue' : $('#onlyControlRel').is(':checked') ? 1 : 0,
				'loadViol':    Viol.Exchange.firstLoad ? 1 : 0
			}; 
			$.ajax(
				'getViolData.php', 
				{
					'data'      : _data,
					'dataType'  : 'json',
					'async'     : true,
					'success'   : function(data, status, request) {
										Viol.SetResult.processResult(data);
										if (Viol.Exchange.firstLoad) {
											Viol.SetResult.setTwits(data.twits);
											Viol.Exchange.firstLoad = false;
										}
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
		toggleByGroupId: function(grpId) {
			$('#violFeed tbody tr').each(function() {
				if (Viol.Dict.ViolType.getGroup($(this).attr('typeId')) == grpId) {
					if ($(this).css('display') == 'none') {
						$(this).css('display', 'table-row');
					} else {
						$(this).css('display', 'none');
					}
				}
			});
		}, 
		applyFilters: function() {
			var _uikFull, _grps = [];
			_uikFull = $('#violFeedUikFilter option:checked').val();
			$('#vTypes input[type="checkbox"]').each(function(){
				if ($(this).is(':checked')) {
					_grps.push($(this).attr('grpid'));
				}
			});
			$('#violFeed tbody tr').each(function() {
				var _curGrp, _curUikFull, _isHidden;
				_curGrp = Viol.Dict.ViolType.getGroup($(this).attr('typeId'))+""; //must be string
				_curUikFull = $(this).attr('uikfull');
				_isHidden = false;

				if ( (_grps.length > 0) && ($.inArray(_curGrp, _grps) == -1) ) {
					_isHidden = true;
				} else if ( (_uikFull) && (parseInt(_curUikFull, 10) != parseInt(_uikFull, 10)) ) {
					_isHidden = true;
				}
				if (_isHidden) {
					$(this).css('display', 'none');
				} else {
					$(this).css('display', 'table-row');
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
			
			Viol.SetResult.setUikCount(data.uikCnt);
			
			
			// results
			EResult.SetWatchersUikCount(data.watchersUIKCount);
			EResult.SetOfUikCount(data.ofUIKCount);
			EResult.SetWatchersResult(data.watchersData);
			EResult.SetOfResult(data.ofData);
			EResult.SetResultDiscrepancy(data.watchersData, data.ofData);
			
			
			// violations
			if (Viol.Exchange.firstLoad) {
				Viol.FeedFilters.ClearUik();
			
				// feed
				Viol.Feed.clear();
				for (var _i in data.vshort) {
					Viol.Feed.add(Viol.SetResult.buildViolTr(data.vshort[_i]));
					if (data.vshort[_i]['UIKNum'] && data.vshort[_i]['UIKNum'] != '0') {
						Viol.FeedFilters.AddUik(data.vshort[_i]['RegionNum'], data.vshort[_i]['UIKNum']);
					}
				}
			
				Viol.FeedFilters.SortUik();
			
				// violation types
				var _grpCnt = [];
				for (var _j in StaticData.ViolationTypeGroupData) {
					_grpCnt[_j] = 0;
				}
			
				for (var _k in data.vTypeCount) {
					_grpCnt[Viol.Dict.ViolType.getGroup(_k)] += data.vTypeCount[_k];
				}
			
				for (_j in _grpCnt) {
					Viol.Filter.SetVGrpCount(_j, _grpCnt[_j]);
				}
			
				Viol.FeedTable.buildHeaders();
				Viol.FeedTable.reSort();
			}
		},
		
		
		
		buildViolTr: function(row) {
			var _place, _time, _descrHtml, _tr, _vtypeHdr, _uikNum, _uikFull;
			_place = Viol.Utility.buildPlace(row);
			_uikNum = Viol.Utility.buildUiknum(row);
			_time = Viol.Utility.formatTime(row.Obstime);
			_vtypeHdr = $('<span>').addClass('vhdr').html(Viol.Dict.ViolType.getName(row.MergedTypeId) + ': ');
			_descrHtml =  row.Description.replace('\\r\\n', "<br/>");
			_uikFull = parseInt(row.RegionNum) * 10000;
			if (row.UIKNum && row.UIKNum != '0') {
				_uikFull += parseInt(row.UIKNum);
			}
			_tr = $('<tr>').attr('uikFull', _uikFull).attr('tikNum', row.TIKNum).attr('typeId', row.MergedTypeId).css('display','table-row');
			$('<td>').html(Viol.Dict.Watchers.getName(row.ProjectCode)).appendTo(_tr);
			$('<td>').html(_time).appendTo(_tr);
			$('<td>').html(_place).appendTo(_tr);
			$('<td>').html(_uikNum).appendTo(_tr);
			$('<td>').addClass('description').append(_vtypeHdr).append($('<a>').html(_descrHtml).click(function() {
				Viol.Exchange.showViolation(row.ProjectCode, row.ProjectId);
			})).appendTo(_tr);
			if (!$('#vType-'+ Viol.Dict.ViolType.getGroup(row.MergedTypeId)).is(':checked')) {
				_tr.hide();
			}
			return _tr;
		},
		
		
		setCount: function(cnt) {
			$('#violCount .val').html(cnt);
		},
		
		setUikCount: function(cnt) {
			$('#uikCount .val').html(cnt);
		},
		
		processSingleResult: function(row) {
			$('#violationModel .place').html(Viol.Utility.buildPlace(row) + ' ' 
				+ (Viol.Utility.buildUiknum(row) ? 'УИК ' + Viol.Utility.buildUiknum(row) :'')  +  '   ' 
				+ Viol.Utility.formatTime(row.Obstime));
			$('#violationModel .violationType span').html(Viol.Dict.ViolType.getName(row.MergedTypeId));
			$('#violationModel .description span').html(row.Description.replace('\\r\\n', "<br/>"));
			$('#violationModel .hqcomment span').html(row.Hqcomment.replace('\\r\\n', "<br/>"));
			//$('#violationModel .mobilegroup span').html(row.Mobgroupsent ? 'отправлена' : 'не отправлена');
			$('#violationModel .mobilegroup span').html('нет данных');
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
	
	FeedTable: {
		buildHeaders: function () {
			$('#th-prj').html('Проект');
			$('#th-time').html('Время');
			$('#th-place').html('Место');
			$('#th-uik').html('УИК');
			$('#th-txt').html('Подробности');
		},
		
		reSort: function() {
			fdTableSort.init('"violFeedTable"');
		}
	},
	
	FeedFilters: {
		
		ClearUik: function () {
			$('#violFeedUikFilter').find('option').each(function(){
				if(!$(this).is(':first-child')) {
					$(this).remove();
				}
			});
		},
		
		AddUik: function (regionNum, uikNum) {
			regionNum = parseInt(regionNum, 10);
			uikNum = parseInt(uikNum, 10);
			$('<option>').val(regionNum * 10000 + uikNum).html(Viol.Dict.Region.getName(regionNum) + ': УИК ' + uikNum) 
				.appendTo($('#violFeedUikFilter'));
		},
		
		SortUik: function() {
			var _buf = {};
			var _ord = [];
			$('#violFeedUikFilter').find('option').each(function(){
				if(!$(this).is(':first-child')) {
					if (typeof(_buf[parseInt($(this).val(),10)]) == 'undefined') {
						_buf[parseInt($(this).val(),10)] = $(this).html();
						_ord.push(parseInt($(this).val(),10));
					}
					$(this).remove();
				}
			});
			_ord.sort();

			for (var _i = 0; _i < _ord.length; _i++) {
				$('<option>').val(_ord[_i]).html(_buf[_ord[_i]]).appendTo($('#violFeedUikFilter'));
			}
		}
	},
	
	Filter: {
		
		SetVGrpCount: function (vGrpId, cnt) {
			$('#vTypeCont-'+vGrpId).find('.val').html(cnt);
		},
		
		
		RedrawOkrugs: function(regionNum) {
			$('#okrug').find('option').each(function(){
				if(!$(this).is(':first-child')) {
					$(this).remove();
				}
			});
			if (StaticData.Okrugs[regionNum]) {
				$('#okrug').removeClass('disabled').removeAttr('disabled');
				$('#uikNum').removeClass('disabled').removeAttr('disabled');
				for (var okrugCode in StaticData.Okrugs[regionNum]) {
					$('<option>').val(okrugCode).html(StaticData.Okrugs[regionNum][okrugCode]).appendTo($('#okrug'));
				} 
			} else {
				$('#okrug').addClass('disabled').attr('disabled', 'disabled');
				$('#uikNum').addClass('disabled').attr('disabled', 'disabled');
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
			var _place = Viol.Dict.Region.getName(row.RegionNum) + ' ';
			if (row.UIKNum && row.UIKNum != '0') {
				
			} else if (row.TIKNum && row.TIKNum != '0') {
				_place += Viol.Dict.TIK.getName(row.RegionNum, row.TIKNum);
			} else if (row.Place) {
				_place += row.Place;
			} 
			return _place;
		},
		
		buildUiknum: function (row) { 
			if (row.UIKNum && row.UIKNum != '0') {
				return row.UIKNum;
			} else {
				return '';
			}
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
			},
			isOnline: function(ProjectCode) {
				return StaticData.WatchersOnline[ProjectCode];
			}
		},
		
		TIK: {
			getName: function(RegionNum, TikNum) {
				return StaticData.Tiks[parseInt(RegionNum, 10)][parseInt(TikNum, 10)];
			}
		},
		
		Region: {
			getName: function(RegionNum) {
				return StaticData.Regions[parseInt(RegionNum, 10)];
			}
		},
		
		ViolType: {
			getName: function(MergedTypeId) {
				return StaticData.ViolationTypes[parseInt(MergedTypeId, 10)];
			}, 
			getGroup: function(MergedTypeId) {
				return StaticData.ViolationTypeGroups[parseInt(MergedTypeId, 10)];
			}
		},
		
		ViolTypeGroups: {
			getName: function(grp) {
				return StaticData.ViolationTypeGroupData[parseInt(grp, 10)];
			}
		}
	}
};


var EResult = {
	SetWatchersUikCount: function(cnt) {
		$('#watchersUikCount').html(cnt);
	},
	SetOfUikCount: function(cnt) {
		$('#ofUikCount').html(cnt);
	},
	SetWatchersResult: function(data) {
		StatScreen.ResultSetter.setGnResult(data);
	},
	SetOfResult: function(data) {
		StatScreen.ResultSetter.setOfResult(data);
	},
	SetResultDiscrepancy: function(watchersResult, ofResult)
	{
		StatScreen.ResultSetter.setResultDiscrepancy(ofResult, watchersResult);	
	}
};

$(document).ready(function() {
	// default ajax settings
	$.ajaxSetup({cache: false, ifModified: true});
	
	Viol.Init();
});