<!-- Add Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<!-- Add your custom CSS -->
<style>
    /* Add your custom styles here */
    .personal-values-list {
        /* Your styles for the personal values list */
    }
    .personal-values-list li,
    .personal-values-list .card:hover {
        /* Your styles for each personal value card */
        cursor: pointer;
    }
    .personal-values-list .row {
        margin-bottom: 20px;
    }
    .personal-values-list .card {
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none; /* Remove the border */
        transition: transform 0.3s ease-in-out;
    }
    .personal-values-list .card:hover {
        cursor: pointer;
        background-color: #FCFDCF;
        transform: scale(1.05);
    }
    .personal-values-list .card-content {
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        background-color: rgba(255, 255, 255, 0.8); /* Adjust the opacity and color */
        backdrop-filter: blur(8px); /* Add a blur effect to the background */
    }
    .personal-values-list .card-title,
    .personal-values-list .card-text {
        color: #000;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
        background-color: rgba(255, 255, 255, 0.6); /* Adjust the opacity and color */
        padding: 8px;
        border-radius: 5px;
    }
    .personal-values-list .card-text {
        font-size: 14px;
    }
    .personal-values-list .card img {
        width: 100%;
        height: auto;
        display: block;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .selected {
        background-color: #FCFDCF; /* Add background color for selected cards */
    }
    .fixed-bottom {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #f7f7f7;
        padding: 10px;
        font-weight: bold;
        text-align: center;
        z-index: 999;
    }
    .selected-value-cards {
        display: none;
    }
    .progress {
        height: 30px;
        margin: 20px 0;
    }
    .progress-bar {
        transition: width 0.3s ease-in-out;
        height: 30px;
        background-color: green;
    }

    #card-pair-container .card {
      border-radius: 10px;
      overflow: hidden;
      position: relative;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border: none;
      transition: transform 0.3s ease-in-out;
      margin-right: 20px;
    }

    #card-pair-container .card:hover {
      cursor: pointer;
      background-color: #FCFDCF;
      transform: scale(1.05);
    }

    .pairings-left-text {
      display: none;
    }

    .top-text {
      margin-bottom: 40px;
    }

    .loading-spinner {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }


.top-titles li.card-0,
.pvc-card-0 {
    background: rgb(206, 65, 123);
}

.top-titles li.card-1 {
background: rgb(182, 81, 159);
}

.top-titles li.card-2 {
  background: rgb(139, 104, 171);
}

.top-titles li.card-3 {
  background: rgb(86, 117, 188);
}

.top-titles li.card-4 {
      background: rgb(10, 124, 193);
}

/* Ensure padding and borders are included in the specified width */
.top-titles li div.pvc-content-container,
.top-titles  li div.background-image-container {
  box-sizing: border-box;
}

/* Set the parent li element to use Flexbox */
.top-titles li {
  display: flex;
  padding: 50px;
}

.top-titles li h2 {
  color: #fff;
  font-size: xx-large;
    font-weight: 600;
}

.top-titles li p {
  font-size: medium;
  color: #fff;
}

/* Make the pvc-content-container div grow to take available space */
.top-titles div.pvc-content-container {
  flex-grow: 1;
  padding-right: 10px; /* Add some spacing between the content and the background image */
}

/* Set the width of the background-image-container div */
.top-titles div.background-image-container {
  position: relative; /* Ensure pseudo-element is positioned relative to this container */
}

.space {
    margin: 20px;
}

@media only screen and (max-width: 768px) {
  .top-titles li {
      display: flex;
      flex-direction: column-reverse;
  }
  .top-titles li div.pvc-content-container, 
  .top-titles li div.background-image-container,
  .top-titles li div * {
      text-align: center !important;
  }
}


</style>

<!-- Personal Values List -->
<div class="personal-values-list">
    <form action="" method="post">

      <!-- Modality Filter Dropdown -->
      <div class="modality-filter">
          <label for="modality-select">Filter by Modality:</label>
          <select id="modality-select">
              <option value="">All Modalities</option>
              <!-- Fetch and display modality names here -->
          </select>
      </div>

      <div class="space"></div>
      

        <?php
        // Retrieve the number of cards to be selected from settings
        $cards_to_select = get_option('personal_values_selected_card_count', 5);
        // Retrieve the number of cards per row from settings
        $cards_per_row = get_option('personal_values_cards_per_row', 3);

        if (!empty($cards)) :
            $card_count = count($cards);
            $row_count = ceil($card_count / $cards_per_row);
            $index = 0;
            for ($row = 1; $row <= $row_count; $row++) :
                ?>
                <div class="row">
                    <?php for ($col = 1; $col <= $cards_per_row; $col++) : ?>
                        <?php if ($index < $card_count) : ?>
                            <div class="col-md-<?php echo intval(12 / $cards_per_row); ?>">
                            <div class="card" onclick="selectCard(this)" data-modality-ids="<?php echo ($cards[$index]['modality_tag_ids']); ?>">
                                <!--<div class="card" onclick="selectCard(this)">-->
                                    <div class="card-body">
                                        <h3><?php echo esc_html($cards[$index]['title']); ?></h3>
                                        <p><?php echo esc_html($cards[$index]['description']); ?></p>
                                        <img src="<?php echo esc_url($cards[$index]['image']); ?>" alt="<?php echo esc_attr($cards[$index]['title']); ?>" class="img-fluid">
                                        <input type="hidden" name="selected_cards[]" value="<?php echo esc_attr($cards[$index]['id']); ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php $index++; ?>
                    <?php endfor; ?>
                </div>
            <?php
            endfor;
        else :
            ?>
            <p>No personal value cards found.</p>
        <?php endif; ?>

        <!-- Submit Button -->
        <div class="fixed-bottom">
            <span id="selected-count"><?php echo count($_POST['selected_cards'] ?? []); ?></span> card(s) selected
            <button type="submit" class="btn btn-primary" id="submit-button" <?php echo (count($_POST['selected_cards'] ?? []) < $cards_to_select) ? 'disabled' : ''; ?>>
                <?php echo (count($_POST['selected_cards'] ?? []) >= $cards_to_select) ? 'Continue' : 'Select ' . ($cards_to_select - (count($_POST['selected_cards'] ?? []))) . ' or more'; ?>
            </button>
        </div>
    </form>
</div>



<!-- Selected Value Cards -->
<div class="selected-value-cards">
    <h2>Selected Value Cards</h2>

    <!-- Print the Top text above the card-pair-container -->
    <?php
    // Fetch the top text from the options table
    $topText = get_option('personal_values_compare_page_text_top', '');
    echo '<div class="top-text col-md-12">' . wpautop($topText) . '</div>';
    ?>
    
    <div id="card-pair-container"></div>
    <!--<button type="button" class="btn btn-primary" id="next-pair-button">Next Pair</button>-->
     
      <!-- Number of card pairings left -->
    <div class="pairings-left-text" id="pairings-left">0 Pairings Left</div>
     
     <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <!-- Print the Bottom text below the card-pair-container -->
    <?php
    // Fetch the top text from the options table
    $bottomText = get_option('personal_values_compare_page_text_bottom', '');
    echo '<div class="bottom-text col-md-12">' . wpautop($bottomText) . '</div>';
    ?>
</div>

<!-- Add jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Add Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<script>
  var selectedCards = [];
  var cardPairs = [];
  var currentPairIndex = 0;
  var maxCardCount = <?php echo intval(get_option('personal_values_selected_card_count', 12)); ?>;

  function selectCard(element) {
    var selectedCount = $('.personal-values-list .card.selected').length;

    // Check if the maximum number of cards is reached
    if (selectedCount >= maxCardCount && !$(element).closest('.card').hasClass('selected')) {
      return; // Do not allow selecting more cards than the maximum count
    }

    $(element).closest('.card').toggleClass('selected');
    updateSelectedCount();
  }

  function updateSelectedCount() {
    var selectedCount = $('.personal-values-list .card.selected').length;
    $('#selected-count').text(selectedCount);

    // Enable/disable the 'Select' button based on the selected count
    var submitButton = $('#submit-button');
    var remainingCount = maxCardCount - selectedCount;
    submitButton.prop('disabled', selectedCount < maxCardCount);
    submitButton.text(selectedCount >= maxCardCount ? 'Continue' : 'Select ' + remainingCount + ' or more');
  }


function hideUnselectedCards() {
  $('.personal-values-list .card:not(.selected)').hide();
}

// When the form is submitted, hide unselected cards and show selected value cards
$('form').submit(function (event) {
  event.preventDefault(); // Prevent the form from submitting

  hideUnselectedCards();
  showSelectedValueCards();

  return false; // Prevent the form from submitting
});

function showSelectedValueCards() {
  selectedCards = $('.personal-values-list .card.selected');
  var pairs = generateCardPairs(selectedCards);
  renderCardPairs(pairs);
  $('.personal-values-list').hide();
  $('.selected-value-cards').show();
}

function generateCardPairs(cards) {
  var pairs = [];
  for (var i = 0; i < cards.length - 1; i++) {
    for (var j = i + 1; j < cards.length; j++) {
      var pair = {
        card1: $(cards[i]).clone(),
        card2: $(cards[j]).clone()
      };
      pairs.push(pair);
    }
  }
  return pairs;
}

function renderCardPairs(pairs) {
  cardPairs = pairs;
  var container = $('#card-pair-container');
  container.empty();
  var pair = cardPairs[currentPairIndex];

  // Add a new class and style to the card-pair-container div
  container.addClass('card-pair-container');
  container.addClass('row'); // Add Bootstrap class for a row
  container.css('justify-content', 'center'); // Center the card pairs

  var card1 = pair.card1.clone().removeClass('selected');
  var card2 = pair.card2.clone().removeClass('selected');

  // Add class "col-md-3" to each card
  card1.addClass('col-md-3');
  card2.addClass('col-md-3');

  // Create a new div for the space between the cards
  var spaceDiv = $('<div>').addClass('space-div');

  container.append(card1);
  container.append(spaceDiv);
  container.append(card2);
  removeSelectedClass();

  // Update the progress bar
  var progress = (currentPairIndex / cardPairs.length) * 100;
  $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
}

function removeSelectedClass() {
  $('.selected-value-cards .card').removeClass('selected');
}

function selectPairCard(element) {
  $(element).closest('.card').toggleClass('selected');

  // Get the title of the selected card
  var selectedCardTitle = $(element).closest('.card').find('.card-body h3').text().trim();

  // Store the selected card title for the current pair
  var pair = cardPairs[currentPairIndex];
  pair.selectedCardTitle = selectedCardTitle;

  // Add the selected card title to the selectedCards array
  selectedCards.push(selectedCardTitle);

  console.log(selectedCardTitle);

  // Update the progress bar
  var progress = ((currentPairIndex + 1) / cardPairs.length) * 100;
  $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);

  showNextPair();

  // Find the parent column of the selected card and remove the 'selected' class from the card
  $(element).closest('.col-md-3').find('.card').removeClass('selected');
}

  // Function to update the progress bar and the number of card pairings left
  function updateProgressBar() {
    var progress = ((currentPairIndex + 1) / cardPairs.length) * 100;
    $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);

    // Calculate the number of pairings left
    var pairingsLeft = cardPairs.length - (currentPairIndex + 1);

    // Update the text of the number of pairings left element
    $('#pairings-left').text(pairingsLeft + ' Pairings Left');

    // Show the pairings-left-text element if there are pairings left
    $('.pairings-left-text').toggle(pairingsLeft > 0);
  }

  function showNextPair() {
    currentPairIndex++;
    if (currentPairIndex < cardPairs.length) {
      var container = $('#card-pair-container');
      container.empty();
      var pair = cardPairs[currentPairIndex];

      // Create a new div for the pair with class 'col-md-3'
      var pairDiv = $('<div>').addClass('col-md-12 row justify-content-md-center');

      // Create a new div with class 'card' for the first card
      var card1 = $('<div>').addClass('card col-md-3'); // Add 'col-md-12' to make the first card take the full width of the column
      card1.append(pair.card1.find('.card-body').clone().removeClass('selected'));
      pairDiv.append(card1);

      // Create a new div with class 'card' for the second card
      var card2 = $('<div>').addClass('card col-md-3'); // Add 'col-md-12' to make the second card take the full width of the column
      card2.append(pair.card2.find('.card-body').clone().removeClass('selected'));
      pairDiv.append(card2);

      container.append(pairDiv);
    } else {

        // Show loading spinner
      var loadingSpinner = $('<div>').addClass('loading-spinner');
      $('#main-core').append(loadingSpinner);

    // Fetch descriptions for the top 5 titles
    if (selectedCards.length > 0) {
      var cardTitleCounts = countSelectedCards(selectedCards);
      console.log('Selected Card Title Counts:', cardTitleCounts);

      var sortedCounts = Object.entries(cardTitleCounts).sort((a, b) => b[1] - a[1]);
      var top5Titles = sortedCounts.slice(0, 5).map(entry => entry[0]);

      console.log('Top 5 Titles:', top5Titles);

      // Create a new div for top titles and descriptions
      var topTitlesDiv = $('<div>').addClass('top-titles');
      topTitlesDiv.append('<h3 style="text-align: center;">Here is a list of your most important values:</h3><p>&nbsp;</p>');
      var ul = $('<ul>');

      // Fetch and append the descriptions one by one
        fetchAndAppendDescription(top5Titles, 0, ul, function() {
          // All descriptions fetched and appended, hide the loading spinner
          loadingSpinner.hide();

          // Now, display the container with the top 5 cards' data
          topTitlesDiv.append(ul);
          $('#main-core').append(topTitlesDiv);

          // store to the table
          storeTopTitlesToDatabase(top5Titles);

        });
    }

      // Get the title of the selected card from the last pair
      var lastPair = cardPairs[currentPairIndex - 1];
      var lastSelectedCardTitle = lastPair.selectedCardTitle;
      console.log('Last selected card title:', lastSelectedCardTitle);

      // Hide the div with class "selected-value-cards"
      $('.selected-value-cards').hide();
    }

    updateProgressBar();

  }

    function countSelectedCards(cardTitles) {
      var cardTitleCounts = {};
      for (var i = 0; i < cardTitles.length; i++) {
        var title = cardTitles[i];
        if (typeof title === 'string') {
          title = title.trim();
          if (cardTitleCounts[title]) {
            cardTitleCounts[title]++;
          } else {
            cardTitleCounts[title] = 1;
          }
        }
      }
      return cardTitleCounts;
    }


    function fetchAndAppendDescription(titles, index, ul, callback) {
      if (index >= titles.length) {
          // All descriptions fetched and appended, call the callback function
          callback();
          return;
      }
      var title = titles[index];
      var uniqueClass = 'card-' + index; // Unique class based on the index
      var li = $('<li>').addClass(uniqueClass); // Add the unique class to the list item
      //var li = $('<li>');
      var contentContainer = $('<div>').addClass('pvc-content-container'); // Add container for title, description, and long_description

      // Fetch the description from the server using Ajax
      $.ajax({
      url: '<?php echo admin_url('admin-ajax.php'); ?>',
      method: 'POST',
      data: {
        action: 'get_card_description',
        title: title
      },
      dataType: 'json',
      success: function(data) {
        // Remove any existing content in the list item
        li.empty();

        // Add the title to the list item
      var titleElement = $('<h2>').text(data.title);
      contentContainer.append(titleElement);
        //li.append(titleElement);

        // Add the description to the list item
        var descriptionElement = $('<p>').text(data.description);
        contentContainer.append(descriptionElement);
        //li.append(descriptionElement);

        // Use wpautop() to display the long_description with proper formatting
        var longDescriptionElement = $('<div>').html(data.long_description);
        contentContainer.append(longDescriptionElement);
        //li.append(longDescriptionElement);

        li.append(contentContainer); 

        // Create a new div element inside li for the background image
        var imagecont = $('<div>').addClass('background-image-container'); // Add a class to the new div element
        li.append(imagecont);

        // Create an img element to display the image
        var imageElement = $('<img>').attr('src', data.image_url);
        imagecont.append(imageElement); // Append the img element to the div

            // Append the list item to the list
            ul.append(li);

            // Fetch and append the next description
            fetchAndAppendDescription(titles, index + 1, ul, callback);

          },
              error: function(xhr, status, error) {
              console.error('Error fetching description:', error);

              // Continue to fetch and append the next description even if there's an error
              fetchAndAppendDescription(titles, index + 1, ul, callback);
              }
          });
      }

        function storeTopTitlesToDatabase(topTitles) {
        // Send an AJAX request to save the top titles
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            method: 'POST',
            data: {
            action: 'save_top_personal_values', // Your WordPress action
            user_id: '<?php echo get_current_user_id(); ?>', 
            titles: topTitles
            },
            dataType: 'json',
            success: function(response) {
            // Log the response (you can handle the response accordingly)
            console.log(response);
            },
            error: function(xhr, status, error) {
            console.error('Error saving top personal values:', error);
            }
        });
        }


  // Fetch and populate modality names into the dropdown
  function fetchAndPopulateModalities() {
      $.ajax({
          url: '<?php echo admin_url('admin-ajax.php'); ?>',
          method: 'POST',
          data: {
              action: 'get_modality_names' // Add your WordPress action here
          },
          dataType: 'json',
          success: function(response) {
              var modalitySelect = $('#modality-select');
              modalitySelect.empty(); // Clear existing options

              // Add the "All Modalities" option
              modalitySelect.append($('<option>').attr('value', '').text('All Modalities'));

              // Add modality names as options
              response.forEach(function(modality) {
                  modalitySelect.append($('<option>').attr('value', modality.id).text(modality.name));
              });
          },
          error: function(xhr, status, error) {
              console.error('Error fetching modality names:', error);
          }
      });
  }

  // Call the function to populate modality names on page load
  $(document).ready(function() {
      fetchAndPopulateModalities();
  });

function applyModalityFilter() {
    var selectedModality = $('#modality-select').val();

    // If a modality is selected, hide cards that do not match the selected modality
    if (selectedModality !== '') {
        $('.personal-values-list .card').each(function() {
            // Retrieve and parse the data-modality-ids attribute
            var cardModalityIds = $(this).data('modality-ids');

            if (cardModalityIds && typeof cardModalityIds === 'string') {
                cardModalityIds = cardModalityIds.split(',').map(function(id) {
                    return parseInt(id, 10); // Parse as integer with base 10
                });

                if (cardModalityIds.indexOf(parseInt(selectedModality)) === -1) {
                    $(this).hide();
                } else {
                    // Convert the array of modality IDs back to a string
                    $(this).attr('data-modality-ids', cardModalityIds.join(','));
                    $(this).show();
                }
            }
        });
    } else {
        // Show all cards when no modality is selected
        $('.personal-values-list .card').each(function() {
            // Reset the data-modality-ids attribute to its original value
            var originalModalityIds = $(this).data('original-modality-ids');
            if (originalModalityIds) {
                $(this).attr('data-modality-ids', originalModalityIds);
            }
        });
        $('.personal-values-list .card').show();
    }
}






  // Call the function whenever the modality selection changes
  $('#modality-select').change(function() {
      applyModalityFilter();
  });


  $('.selected-value-cards').on('click', '.card', function () {
    selectPairCard(this);
  });

  $('#next-pair-button').click(function () {
    showNextPair();
  });

  $('.personal-values-list .card').removeClass('selected');
  updateSelectedCount();
  updateSubmitButton();

</script>