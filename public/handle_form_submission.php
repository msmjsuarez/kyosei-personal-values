<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        // Retrieve the selected cards
        $selectedCards = $_POST['selected_cards'] ?? [];
        if (!empty($selectedCards)) {
            echo 'Selected Cards:';
            echo '<ul>';
            foreach ($selectedCards as $cardId) {
                echo '<li>' . $cardId . '</li>';
            }
            echo '</ul>';
        } else {
            echo 'No cards selected.';
        }
    }
}
