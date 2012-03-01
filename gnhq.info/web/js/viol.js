var Viol = {
	Init: function() {
		for (var prCode in StaticData.Watchers) {
			
			$('<div>')
				.append($('<input>').attr('type', 'checkbox').val(prCode).attr('id', prCode).change(function() {
					Viol.Exchange.loadData();
					return false;
				}))
				.append($('<label>').attr('for', prCode).html(StaticData.Watchers[prCode]))
				.appendTo($('#watchers'));
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
		
		for (var _grp in StaticData.ViolationTypeGroupData) {
			var _vTypeName, _chboxId;
			_vTypeName = Viol.Dict.ViolTypeGroups.getName(_grp);
			_chboxId = 'vType-'+_grp;
			$('<div>').attr('id', 'vTypeCont-'+_grp).addClass('selected')
					.append($('<input>').attr('type', 'checkbox').attr('id',_chboxId).attr('grpId', _grp).click(function() {
						Viol.Feed.toggleByGroupId($(this).attr('grpId'));
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
					Viol.Feed.showAll();
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
		},
		
		
		
		buildViolTr: function(row) {
			var _place, _time, _descrHtml, _tr, _vtypeHdr;
			_place = Viol.Utility.buildPlace(row);
			_time = Viol.Utility.formatTime(row.Obstime);
			_vtypeHdr = $('<span>').addClass('vhdr').html(Viol.Dict.ViolType.getName(row.MergedTypeId) + ': ');
			_descrHtml =  row.Description;
			_tr = $('<tr>').attr('tikNum', row.TIKNum).attr('typeId', row.MergedTypeId).css('display','table-row');
			$('<td>').html(Viol.Dict.Watchers.getName(row.ProjectCode)).appendTo(_tr);
			$('<td>').html(_time).appendTo(_tr);
			$('<td>').html(_place).appendTo(_tr);
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
			var _place = Viol.Dict.Region.getName(row.RegionNum) + ' ';
			if (row.UIKNum && row.UIKNum != '0') {
				_place += 'УИК ' + row.UIKNum;
			} else if (row.TIKNum && row.TIKNum != '0') {
				_place += Viol.Dict.TIK.getName(row.RegionNum, row.TIKNum);
			} else if (row.Place) {
				_place += row.Place;
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

$(document).ready(function() {
	// default ajax settings
	$.ajaxSetup({cache: false, ifModified: true});
	
	Viol.Init();
});