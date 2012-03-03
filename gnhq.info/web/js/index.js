$(document).ready(function() {
	// default ajax settings
	$.ajaxSetup({cache: true, ifModified: true});
	// decoration
	Decoration.Tooltips();
	
	
	StatScreen.Init();
	StatScreen.Exchange.Activate();
});
