<div class="personal-values-list">
    <form method="post">

        <!-- Modality Filter Dropdown -->
        <div class="modality-filter">
            <label for="modality-select">Filter by Modality:</label>
            <select id="modality-select">
                <option value="">All Modalities</option>
            </select>
        </div>

        <div class="space"></div>

        <div class="resetthis">
            <?php
            $cards_to_select = get_option('personal_values_selected_card_count', 5);
            $cards_per_row = get_option('personal_values_cards_per_row', 3);

            if (!empty($cards)) :
                $card_count = count($cards);
                $row_count = ceil($card_count / $cards_per_row);
                $index = 0;

                for ($row = 1; $row <= $row_count; $row++) :
            ?>
                    <div class="row">
                        <?php for ($col = 1; $col <= $cards_per_row; $col++) : ?>
                            <?php if ($index < $card_count) :
                                $card = $cards[$index];
                            ?>
                                <div class="col-md-<?php echo esc_attr(intval(12 / $cards_per_row)); ?>">
                                    <div class="card"
                                        data-original-modality-ids="<?php echo esc_attr($card['modality_tag_ids']); ?>"
                                        data-modality-ids="<?php echo esc_attr($card['modality_tag_ids']); ?>">

                                        <div class="card-body">
                                            <h3 class="personal-value-title"><?php echo esc_html($card['title']); ?></h3>
                                            <p><?php echo esc_html($card['description']); ?></p>
                                            <img src="<?php echo esc_url($card['image']); ?>"
                                                alt="<?php echo esc_attr($card['title']); ?>" class="img-fluid">

                                            <input type="hidden" name="selected_cards[]" value="<?php echo esc_attr($card['id']); ?>">

                                            <div class="icon" data-toggle="popup"
                                                data-title="<?php echo esc_attr($card['title']); ?>"
                                                data-content="<?php echo esc_attr($card['long_description']); ?>">
                                                <i class="fas fa-info-circle"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;
                            $index++; ?>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            <?php else : ?>
                <p>No personal value cards found.</p>
            <?php endif; ?>

            <!-- Submit Button -->
            <div class="fixed-bottom">
                <span id="selected-count"><?php echo esc_html(count($_POST['selected_cards'] ?? [])); ?></span> card(s) selected
                <button type="submit" class="btn btn-primary" id="submit-button"
                    <?php echo (count($_POST['selected_cards'] ?? []) < $cards_to_select) ? 'disabled' : ''; ?>>
                    <?php
                    $selected = count($_POST['selected_cards'] ?? []);
                    echo ($selected >= $cards_to_select)
                        ? 'Continue'
                        : 'Select ' . ($cards_to_select - $selected) . ' or more';
                    ?>
                </button>
            </div>
        </div>
    </form>
</div>

<div class="selected-value-cards">
    <h2>Selected Value Cards</h2>

    <?php
    $topText = get_option('personal_values_compare_page_text_top', '');
    if (!empty($topText)) {
        echo '<div class="top-text col-md-12">' . wpautop($topText) . '</div>';
    }
    ?>

    <div id="card-pair-container"></div>

    <div class="pairings-left-text" id="pairings-left">0 Pairings Left</div>

    <div class="progress">
        <div class="progress-bar" role="progressbar"
            style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <?php
    $bottomText = get_option('personal_values_compare_page_text_bottom', '');
    if (!empty($bottomText)) {
        echo '<div class="bottom-text col-md-12">' . wpautop($bottomText) . '</div>';
    }
    ?>
</div>

<div class="popup" id="card-popup">
    <div class="popup-content">
        <span class="popup-close" id="popup-close-btn">&times;</span>
        <div class="popup-inner-content"></div>
    </div>
</div>