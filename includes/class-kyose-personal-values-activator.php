<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://kyoseicreative.com/
 * @since      1.0.0
 *
 * @package    Kyose_Personal_Values
 * @subpackage Kyose_Personal_Values/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Kyose_Personal_Values
 * @subpackage Kyose_Personal_Values/includes
 * @author     MJ of Kyosei <mjsuarez@kyoseicreative.com>
 */
class Kyose_Personal_Values_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		// Create 'kyosei_personal_values' table
        $table_name1 = $wpdb->prefix . 'kyosei_personal_values';
        $sql1 = "CREATE TABLE $table_name1 (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            long_description TEXT,
            image VARCHAR(255) NOT NULL,
            modality_tag_ids VARCHAR(255)
        ) $charset_collate;";
        dbDelta($sql1);

        // Create 'kyosei_personal_value_modality_tags' table
        $table_name2 = $wpdb->prefix . 'kyosei_personal_value_modality_tags';
        $sql2 = "CREATE TABLE $table_name2 (
            id INT UNSIGNED AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            PRIMARY KEY (id),
            UNIQUE (slug)
        ) $charset_collate;";
        dbDelta($sql2);

       // Create 'kyosei_personal_values_user' table to store top 5 personal values for each user
        $table_name3 = $wpdb->prefix . 'kyosei_personal_values_user';
        $sql3 = "CREATE TABLE $table_name3 (
            id INT UNSIGNED AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            title text,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)
        ) $charset_collate;";
        dbDelta($sql3);

		// Add another debugging statement to check if the table is created
        if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name3}'") != $table_name3) {
            error_log('The third table was not created.');
        } else {
            error_log('The third table was created successfully.');
        }
	}

}
