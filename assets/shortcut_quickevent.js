jQuery(document).ready(function() {
	
	// Add the QuickEvent shortcut for 2.0.7+
	if(Symphony.Language.add) {
		Symphony.Language.add({'Go to QuickEvent':false});
		jQuery('form h2').append('<a class="button" title="Go to QuickEvent" href="' + Symphony.WEBSITE + '/symphony/extension/quickevent/edit/">' + Symphony.Language.get('Go to QuickEvent') + '</a>');
		
	// Add the QuickEvent shortcut for 2.0.6
	} else {
		$website = jQuery('script[src]')[0].src.match('(.*)/symphony')[1];
		jQuery('form h2').append('<a class="button" title="Go to QuickEvent" href="' + $website + '/symphony/extension/quickevent/edit/">' + 'Go to QuickEvent' + '</a>');
	}
});