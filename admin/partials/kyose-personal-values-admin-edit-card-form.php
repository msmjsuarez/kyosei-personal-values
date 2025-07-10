<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<div class="wrap">
    <h1>Edit Personal Value Card</h1>

    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'kyosei_personal_values';
    $card_id = $_GET['card_id'];
    $card = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $card_id), ARRAY_A);


    if (intval($_GET['paged']) == '') {
        $getpage = 1;
    } else {
        $getpage = intval($_GET['paged']);
    }

    ?>

    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin.php?page=personal-values&action=edit&card_id=' . esc_attr($card['id'])) . '&paged=' . $getpage); ?>">
        <input type="hidden" name="card_id" value="<?php echo esc_attr($card['id']); ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <!-- <input type="text" class="form-control" name="title" placeholder="Title" value="<?php echo esc_attr($card['title']); ?>" required> -->
            <input type="text" class="form-control" name="title" placeholder="Title" value="<?php echo isset($_POST['update_card']) ? '' : esc_attr($card['title']); ?>" required>

        </div>
        <div class="form-group">
            <img src="<?php echo esc_url($card['image']); ?>" width="100" height="100">
            <input type="file" class="form-control" name="image">
        </div>
        <div class="form-group">
            <label for="description">Short Description</label>
            <textarea name="description" class="form-control" placeholder="Description" required><?php echo esc_html($card['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="long_description">Long Description</label>
            <?php
            $long_description_content = stripslashes(wp_kses_post($card['long_description']));
            $long_description_editor_id = 'long_description_editor';
            $long_description_settings = array(
                'textarea_name' => 'long_description',
                'textarea_rows' => 8,
            );
            wp_editor($long_description_content, $long_description_editor_id, $long_description_settings);
            ?>
        </div>
        <div class="form-group">
            <!-- Retrieve and display the modality tags from the modality tags table -->
            <?php
            $modalityTagsTable = $wpdb->prefix . 'kyosei_personal_value_modality_tags';
            $modalityTags = $wpdb->get_results("SELECT * FROM $modalityTagsTable", ARRAY_A);
            if (!empty($modalityTags)) {
                echo '<div><label for="modality_tags">Modality Tags:</label></div>';
                echo '<div>';
                foreach ($modalityTags as $tag) {
                    $checked = in_array($tag['id'], explode(',', $card['modality_tag_ids'])) ? 'checked' : '';
                    echo '<input type="checkbox" class="form-control" name="modality_tags[]" value="' . esc_attr($tag['id']) . '" ' . $checked . '> ' . esc_html($tag['name']) . '<br>';
                }
                echo '</div>';
            }
            ?>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-info" name="update_card" value="Update Card">
        </div>

    </form>
</div>