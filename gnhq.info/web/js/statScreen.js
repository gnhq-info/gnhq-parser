StatScreen = {
	Filter: {
		getOkrugAbbr: function() {
			return $('#okrug').val();
		},
		
		getUik: function() {
			return $('#uik').val();
		},
		
		getSelectionType: function() {
			return $('input[name=selectionType]:checked').attr('id');
		}, 
		
		setUiks: function(uiks) {
			
		}
	},
	
	Exchange: {
		
	},
	
	ResultSetter: {
		setTotalCount: function(cnt) {
			$('#uikCount').text(cnt);
		},
		setDiscrepancyCount: function(cnt) {
			$('#uikCount').text(cnt);
		},
		setUiks: function(uiks) {
			
		}
	}
	
	
}
/*
$.ajax(
'getData.php', 
{
'data': {'okrugAbbr': $('#okrug').val(), 'uik': $('#uik').val()},
'dataType': 'json',
'cache': false,
'async': false,
'success': function(data, status, request) {
    console.log(data.k);
}
}
);*/