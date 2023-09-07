<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        // Retrieve the selected cards
        $selectedCards = $_POST['selected_cards'] ?? [];

        // Display the selected cards
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
?>
