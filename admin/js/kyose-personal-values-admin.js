(function( $ ) {
	'use strict';

	$(document).ready(function () {
		// Get the number of cards per row from the localized data
		var cardsPerRow = kyose_personal_values_settings.cards_per_row;

		// Adjust the width of personal value cards based on the number of cards per row
		var cardWidth = 100 / cardsPerRow + '%';
		$('.personal-value-card').css('width', cardWidth);
	});

})( jQuery );
