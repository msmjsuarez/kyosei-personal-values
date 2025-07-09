(function ($) {
  'use strict';

  let selectedCards = [];
  let cardPairs = [];
  let currentPairIndex = 0;
  const maxCardCount = kyose_ajax_object.max_card_count || 12;
  let selectedModality = null;
  let originalCardList;

  $(document).ready(function () {
    fetchAndPopulateModalities();
    selectedModality = $('#modality-select').val();
    originalCardList = $('.resetthis').html();

    $('.personal-values-list').on('click', '.card', function () {
      selectCard(this);
    });

    $('#modality-select').on('change', function () {
      selectedModality = $(this).val();
      applyModalityFilter();
      $('#modality-select option[value="' + selectedModality + '"]').prop('selected', true);
    });

    $('form').submit(function (event) {
      event.preventDefault();
      hideUnselectedCards();
      showSelectedValueCards();
    });

    $('.selected-value-cards').on('click', '.card', function () {
      selectPairCard(this);
    });

    $('#next-pair-button').click(showNextPair);

    $('.personal-values-list').on('click', '.icon', function (event) {
      event.stopPropagation();
      const title = $(this).data('title');
      $.post(kyose_ajax_object.ajax_url, {
        action: 'get_card_data',
        title: title
      }, function (data) {
        const content = data.long_description || 'No description available';
        displayPopup(data.title, content);
      });
    });

    $('#popup-close-btn').click(closePopup);
    $('.popup').click(function (e) {
      if (e.target === this) closePopup();
    });

    $('.personal-values-list .card').removeClass('selected');
    updateSelectedCount();
  });

  function selectCard(element) {
    const isAlreadySelected = $(element).closest('.card').hasClass('selected');
    const selectedCount = $('.personal-values-list .card.selected').length;
    if (selectedCount >= maxCardCount && !isAlreadySelected) return;
    $(element).closest('.card').toggleClass('selected');
    updateSelectedCount();
  }

  window.selectCard = selectCard;

  function updateSelectedCount() {
    const selectedCount = $('.personal-values-list .card.selected').length;
    $('#selected-count').text(selectedCount);
    const submitButton = $('#submit-button');
    const remainingCount = maxCardCount - selectedCount;
    submitButton.prop('disabled', selectedCount < maxCardCount);
    submitButton.text(selectedCount >= maxCardCount ? 'Continue' : `Select ${remainingCount} or more`);
  }

  function hideUnselectedCards() {
    $('.personal-values-list .card:not(.selected)').hide();
  }

  function showSelectedValueCards() {
    const selected = $('.personal-values-list .card.selected');
    selectedCards = selected.map(function () {
      return $(this).find('.card-body h3').text().trim();
    }).get();

    const pairs = generateCardPairs(selected);
    renderCardPairs(pairs);
    $('.personal-values-list').hide();
    $('.selected-value-cards').show();
  }

  function generateCardPairs(cards) {
    const pairs = [];
    for (let i = 0; i < cards.length - 1; i++) {
      for (let j = i + 1; j < cards.length; j++) {
        pairs.push({
          card1: $(cards[i]).clone(),
          card2: $(cards[j]).clone()
        });
      }
    }
    return pairs;
  }

  function renderCardPairs(pairs) {
    cardPairs = pairs;
    const container = $('#card-pair-container').empty().addClass('card-pair-container row').css('justify-content', 'center');
    const pair = cardPairs[currentPairIndex];
    const card1 = pair.card1.clone().removeClass('selected').addClass('col-md-3');
    const card2 = pair.card2.clone().removeClass('selected').addClass('col-md-3');
    container.append(card1).append($('<div>').addClass('space-div')).append(card2);
    removeSelectedClass();
    updateProgressBar();
  }

  function removeSelectedClass() {
    $('.selected-value-cards .card').removeClass('selected');
  }

  function selectPairCard(element) {
    $(element).closest('.card').toggleClass('selected');
    const selectedCardTitle = $(element).closest('.card').find('.card-body h3').text().trim();

    const pair = cardPairs[currentPairIndex];
    if (!pair) return;

    pair.selectedCardTitle = selectedCardTitle;
    selectedCards.push(selectedCardTitle);
    updateProgressBar();
    showNextPair();
    $(element).closest('.col-md-3').find('.card').removeClass('selected');
  }

  function updateProgressBar() {
    const progress = ((currentPairIndex + 1) / cardPairs.length) * 100;
    $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
    const pairingsLeft = cardPairs.length - (currentPairIndex + 1);
    $('#pairings-left').text(`${pairingsLeft} Pairings Left`);
    $('.pairings-left-text').toggle(pairingsLeft > 0);
  }

  function showNextPair() {
    currentPairIndex++;
    if (currentPairIndex < cardPairs.length) {
      const container = $('#card-pair-container').empty();
      const pairDiv = $('<div>').addClass('col-md-12 row justify-content-md-center');
      ['card1', 'card2'].forEach((key) => {
        const card = $('<div>').addClass('card col-md-3').append(cardPairs[currentPairIndex][key].find('.card-body').clone().removeClass('selected'));
        pairDiv.append(card);
      });
      container.append(pairDiv);
    } else {
      const spinner = $('<div>').addClass('loading-spinner');
      $('#main-core').append(spinner);
      const counts = countSelectedCards(selectedCards);
      const top5 = Object.entries(counts).sort((a, b) => b[1] - a[1]).slice(0, 5).map(e => e[0]);

      const div = $('<div>').addClass('top-titles').append('<h3 style="text-align: center;">Here is a list of your most important values:</h3><p>&nbsp;</p>');
      const ul = $('<ul>');
      fetchAndAppendDescription(top5, 0, ul, function () {
        spinner.hide();
        div.append(ul);
        $('#main-core').append(div);
        storeTopTitlesToDatabase(top5);
      });

      $('.selected-value-cards').hide();
    }
  }

  function countSelectedCards(cardTitles) {
    if (!Array.isArray(cardTitles)) return {};
    return cardTitles.reduce((acc, title) => {
      title = title.trim();
      acc[title] = (acc[title] || 0) + 1;
      return acc;
    }, {});
  }

  function fetchAndAppendDescription(titles, index, ul, callback) {
    if (index >= titles.length) return callback();
    $.post(kyose_ajax_object.ajax_url, {
      action: 'get_card_description',
      title: titles[index]
    }, function (data) {
      const li = $('<li>').addClass(`card-${index}`);
      const container = $('<div>').addClass('pvc-content-container');
      container.append($('<h2>').text(data.title));
      const longDesc = $('<div>').html(data.long_description || '<h3>What is Lorem Ipsum?</h3><p>Lorem Ipsum is simply dummy text...</p>').html().replace(/\\/g, '');
      container.append($('<div>').html(longDesc));
      li.append(container).append($('<div>').addClass('background-image-container').append($('<img>').attr('src', data.image_url)));
      ul.append(li);
      fetchAndAppendDescription(titles, index + 1, ul, callback);
    }).fail(function () {
      fetchAndAppendDescription(titles, index + 1, ul, callback);
    });
  }

  function storeTopTitlesToDatabase(titles) {
    $.post(kyose_ajax_object.ajax_url, {
      action: 'save_top_personal_values',
      user_id: kyose_ajax_object.user_id,
      titles: titles
    }, function (response) {
      console.log(response);

      if (response.success && response.data && response.data.redirect_url) {
        // ✅ Redirect to the final page
        window.location.href = response.data.redirect_url;
      } else {
        alert('Failed to save personal values. You need to login to save your personal values.');
      }
    }).fail(function (_, __, error) {
      console.error('Error saving top personal values:', error);
    });
  }

  function fetchAndPopulateModalities() {
    $.post(kyose_ajax_object.ajax_url, { action: 'get_modality_names' }, function (response) {
      const select = $('#modality-select').empty().append($('<option>').val('').text('All Modalities'));
      response.forEach(modality => {
        select.append($('<option>').val(modality.id).text(modality.name));
      });
      if (selectedModality) select.val(selectedModality);
    });
  }

  function resetCardList() {
    $('.resetthis').html(originalCardList);
    adjustCardsPerRow();
  }

  function applyModalityFilter() {
    const selected = $('#modality-select').val();
    resetCardList();
    if (selected !== '') {
      $('.personal-values-list .card').each(function () {
        const ids = $(this).data('modality-ids').toString().split(',').map(id => parseInt(id, 10));
        if (!ids.includes(parseInt(selected, 10))) $(this).hide();
      });
      adjustCardsPerRow();
      fetchAndPopulateModalities();
    }
  }

  function adjustCardsPerRow() {
    const perRow = kyose_ajax_object.cards_per_row || 3;
    const $cards = $('.personal-values-list .card:visible');
    $('.personal-values-list .row').remove();
    for (let i = 0; i < $cards.length; i += perRow) {
      const $row = $('<div>').addClass('row');
      $cards.slice(i, i + perRow).each(function () {
        const $col = $('<div>').addClass('col-md-' + (12 / perRow)).append($('<div>').addClass('card-container').append($(this).clone()));
        $row.append($col);
      });
      $('.resetthis').append($row);
    }
  }

  function displayPopup(title, content) {
    $('.popup-inner-content').html('<h3>' + title + '</h3>' + content);
    $('#card-popup').show();
  }

  function closePopup() {
    $('#card-popup').hide();
  }

})(jQuery);
