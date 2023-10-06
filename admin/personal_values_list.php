<!-- Add Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<div class="wrap">

    <!-- Add Card Form -->
    <h1>Personal Values</h1>

    <?php
    if (intval($_GET['paged']) == '') {
        $getpage = 1;
    } else {
        $getpage = intval($_GET['paged']);
    }

    if (isset($_POST['update_card'])) {
        echo '<div class="notice notice-success"><p>Card updated successfully!</p></div>';
    }
    ?>

    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin.php?page=personal-values&paged=' . $getpage)); ?>">

        <div class="form-group">
            <label for="title">Title:</label>
            <!-- <input type="text" class="form-control" name="title" placeholder="Title" value="<?php echo isset($_POST['title']) ? esc_attr($_POST['title']) : ''; ?>" required> -->
            <input type="text" class="form-control" name="title" placeholder="Title" value="" required>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control" name="image" required>
        </div>
        <div class="form-group">
            <label for="description">Short Description:</label>
            <!-- <textarea name="description" class="form-control" placeholder="Description" required><?php echo isset($_POST['description']) ? esc_textarea($_POST['description']) : ''; ?></textarea> -->
            <textarea name="description" class="form-control" placeholder="Description" required></textarea>
        </div>
        <div class="form-group">
            <label for="long_description">Long Description:</label>
            <?php
            // Get the long description content if it exists (when editing)
            // $longDescriptionContent = isset($_POST['long_description']) ? wp_kses_post($_POST['long_description']) : '';
            $longDescriptionContent = '';
            // Output the WordPress editor for long_description field
            wp_editor($longDescriptionContent, 'long_description', array(
                'media_buttons' => false,
                'textarea_name' => 'long_description',
                'textarea_rows' => 5,
                'teeny' => true
            ));
            ?>
        </div>
        <div class="form-group">
            <!-- Retrieve and display the modality tags from the modality tags table -->
            <?php
            global $wpdb;
            $modalityTagsTable = $wpdb->prefix . 'kyosei_personal_value_modality_tags';
            $modalityTags = $wpdb->get_results("SELECT * FROM $modalityTagsTable", ARRAY_A);
            if (!empty($modalityTags)) {
                echo '<div><label for="modality_tags">Modality Tags:</label></div>';
                echo '<div>';
                foreach ($modalityTags as $tag) {
                    echo '<input type="checkbox" class="form-control" name="modality_tags[]" value="' . esc_attr($tag['id']) . '"> ' . esc_html($tag['name']) . '<br>';
                }
                echo '</div>';
            }
            ?>
        </div>
        <input type="submit" class="btn btn-info" name="add_card" value="Add Card">
    </form>

    <div class="mt-4"></div>

    <!-- Personal Values List -->
    <div class="heading-search">
        <div>
            <h1>Personal Values List</h1>
        </div>
        <div class="personal-values-search">
            <form method="get" action="?page=personal-values" id="personal-values-search-form">
                <input type="text" name="search" id="personal-values-search-input" placeholder="Search by title" value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
                <span id="personal-values-search-clear" class="search-clear">&times;</span>
                <input type="submit" value="Search" class="hidden">
            </form>
        </div>
    </div>

    <?php
    global $wpdb;
    $personalValuesTable = $wpdb->prefix . 'kyosei_personal_values';
    $modalityTagsTable = $wpdb->prefix . 'kyosei_personal_value_modality_tags';

    // Retrieve the total number of cards
    $totalCards = $wpdb->get_var("SELECT COUNT(*) FROM $personalValuesTable");
    echo '<p>Total Cards: ' . $totalCards . '</p>';

    // Calculate the total number of pages
    $totalPages = ceil($totalCards / 5);

    // Get the current page from the URL query parameter
    $current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;

    // Calculate the offset and limit for the current page
    $offset = ($current_page - 1) * 5;
    $limit = 5;

    // Retrieve the search term from the URL query parameter
    $searchTerm = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    // Build the SQL query based on the search term
    $query = "SELECT p.*, GROUP_CONCAT(t.name SEPARATOR ', ') AS modality_tags
              FROM $personalValuesTable AS p
              LEFT JOIN $modalityTagsTable AS t
              ON FIND_IN_SET(t.id, p.modality_tag_ids)
              WHERE p.title LIKE '%$searchTerm%'
              GROUP BY p.id
              ORDER BY p.title ASC
              LIMIT $limit OFFSET $offset";

    // Retrieve the cards for the current page and search term
    $cards = $wpdb->get_results($query, ARRAY_A);
    ?>

    <div class="personal-values-list">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Modality Tags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="personal-values-table-body">
                <?php
                foreach ($cards as $card) {
                    echo '<tr>';
                    echo '<td>' . esc_html($card['title']) . '</td>';
                    echo '<td>' . esc_html($card['description']) . '</td>';
                    echo '<td><img src="' . esc_url($card['image']) . '" width="100" height="100"></td>';
                    echo '<td>' . (!empty($card['modality_tags']) ? esc_html($card['modality_tags']) : '-') . '</td>';
                    echo '<td>';
                    echo '<a href="?page=personal-values&action=edit&card_id=' . esc_attr($card['id']) . '&paged=' . $_GET['paged'] . '">Edit</a> | ';
                    echo '<a href="#" class="delete-card" data-card-id="' . esc_attr($card['id']) . '">Delete</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <?php
    if ($totalPages > 1) {
        echo '<div class="pagination">';
        if ($current_page > 1) {
            $prevPage = $current_page - 1;
            echo '<a href="?page=personal-values&paged=' . $prevPage . '">&laquo; Previous</a>';
        }

        // Calculate the range of page numbers to display
        $startRange = max(1, $current_page - 2);
        $endRange = min($current_page + 2, $totalPages);

        // Display ellipsis before the first page if necessary
        if ($startRange > 1) {
            echo '<span class="ellipsis">...</span>';
        }

        for ($i = $startRange; $i <= $endRange; $i++) {
            $currentClass = ($current_page == $i) ? 'current' : '';
            echo '<a class="' . $currentClass . '" href="?page=personal-values&paged=' . $i . '">' . $i . '</a>';
        }

        // Display ellipsis after the last page if necessary
        if ($endRange < $totalPages) {
            echo '<span class="ellipsis">...</span>';
        }

        if ($current_page < $totalPages) {
            $nextPage = $current_page + 1;
            echo '<a href="?page=personal-values&paged=' . $nextPage . '">Next &raquo;</a>';
        }
        echo '</div>';
    }
    ?>

    <style>
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #f2f2f2;
            border-radius: 4px;
            color: #333;
        }

        .pagination a.current {
            background-color: #333;
            color: #fff;
        }

        .pagination a.padding {
            /* pointer-events: none; */
        }

        .personal-values-search {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .personal-values-search form {
            display: flex;
            align-items: center;
        }

        .personal-values-search input[type="text"],
        .personal-values-search input[type="submit"] {
            margin-left: 10px;
        }

        .heading-search div {
            display: inline-flex;
        }

        .personal-values-search {
            float: right;
        }

        .hidden {
            display: none;
        }

        .pagination .ellipsis {
            margin: 0 5px;
            color: #333;
        }

        .personal-values-search .search-clear {
            cursor: pointer;
            font-size: 18px;
            color: #999;
            margin-left: -25px;
            display: none;
        }

        .personal-values-search .search-clear:hover {
            color: #333;
        }

        div#wpfooter {
            display: none;
        }

        .modality-tag {
            color: #007F8C;
        }

        .modality-tag:hover {
            text-decoration: underline;
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            // Delete Card Action
            $('.delete-card').on('click', function(e) {
                e.preventDefault();

                var cardId = $(this).data('card-id');
                var confirmation = confirm('Are you sure you want to delete this card?');

                if (confirmation) {
                    // Perform Ajax request to delete the card
                    $.ajax({
                        url: ajaxurl + '?paged=' + <?php echo $current_page; ?>,
                        type: 'POST',
                        data: {
                            action: 'delete_card',
                            card_id: cardId,
                            security: '<?php echo wp_create_nonce("delete_card"); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Card deleted successfully, remove the row from the table
                                $('tr[data-card-id="' + cardId + '"]').remove();
                                location.reload(); // Refresh the page
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

            // Fetch Personal Values via AJAX
            function fetchPersonalValues(searchTerm) {
                $.ajax({
                    url: ajaxurl,
                    type: 'GET',
                    data: {
                        action: 'fetch_personal_values',
                        search: searchTerm,
                        security: '<?php echo wp_create_nonce("fetch_personal_values"); ?>'
                    },
                    beforeSend: function() {
                        // Show loading indicator or spinner
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the table body with the new HTML content
                            $('#personal-values-table-body').html(response.data.html);
                        } else {
                            alert(response.data.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', errorThrown);
                    },
                    complete: function() {
                        // Hide loading indicator or spinner
                    }
                });
            }

            // Show search clear icon when search input has value
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

            // Prevent form submission on Enter keypress
            $('#personal-values-search-form').on('submit', function(e) {
                e.preventDefault();
            });
        });
    </script>

</div>
</div>