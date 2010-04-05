jQuery(document).ready(function() {
	
	Symphony.Language.add({
	    'Go to QuickEvent' : false
	});
	
	// Add the 'show/hide all' button
	jQuery('form h2').append('<a class="button" title="Go to QuickEvent" href="' + Symphony.WEBSITE + '/symphony/extension/quickevent/edit/">' + Symphony.Language.get('Go to QuickEvent') + '</a>');

});