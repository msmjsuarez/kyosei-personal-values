(function($) {
	'use strict';

	$(document).ready(function () {

		// Set card widths based on cards per row setting
		var cardsPerRow = kyose_personal_values_settings.cards_per_row;
		var cardWidth = 100 / cardsPerRow + '%';
		$('.personal-value-card').css('width', cardWidth);

		// Delete Card Action
		$('.delete-card').on('click', function(e) {
			e.preventDefault();

			var cardId = $(this).data('card-id');
			var confirmation = confirm('Are you sure you want to delete this card?');

			if (confirmation) {
				$.ajax({
					url: ajaxurl + '?paged=' + kyose_personal_values_settings.current_page,
					type: 'POST',
					data: {
						action: 'delete_card',
						card_id: cardId,
						security: kyose_personal_values_settings.delete_nonce
					},
					success: function(response) {
						if (response.success) {
							$('tr[data-card-id="' + cardId + '"]').remove();
							location.reload();
						} else {
							alert(response.data.message);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.error('Error:', errorThrown);
					}
				});
			}
		});

		// Search Personal Values
		$('#personal-values-search-input').on('keyup', function(e) {
			var searchTerm = $(this).val();
			fetchPersonalValues(searchTerm);
		});

		function fetchPersonalValues(searchTerm) {
			$.ajax({
				url: ajaxurl,
				type: 'GET',
				data: {
					action: 'fetch_personal_values',
					search: searchTerm,
					security: kyose_personal_values_settings.search_nonce
				},
				success: function(response) {
					if (response.success) {
						$('#personal-values-table-body').html(response.data.html);
					} else {
						alert(response.data.message);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('Error:', errorThrown);
				}
			});
		}

		// Show clear icon
		$('#personal-values-search-input').on('input', function() {
			var searchValue = $(this).val();
			$(this).siblings('.search-clear').toggle(searchValue !== '');
		});

		// Clear search text
		$('#personal-values-search-clear').on('click', function(e) {
			e.preventDefault();
			$('#personal-values-search-input').val('').focus();
			$(this).hide();
			fetchPersonalValues('');
		});

		// Prevent form submission
		$('#personal-values-search-form').on('submit', function(e) {
			e.preventDefault();
		});
	});

})(jQuery);
