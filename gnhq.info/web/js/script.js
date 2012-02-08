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
);