<?php

/**
 * MooKwai create database
 *
 * @link https://mookwai.com
 * 
 * @package MooKwai
 * @copyright Designed and developed by PUJI Design. https://puji.design
 */

namespace MOOKWAI\App;

defined('ABSPATH') || exit;

use MOOKWAI\App\Traits\Singleton;

class MooKwai_Database
{

  use Singleton;

  protected function __construct()
  {
    $this->setup_hooks();
  }

  protected function setup_hooks()
  {
    add_action('after_switch_theme', [$this, 'mookwai_register_individual_database']);
  }

  function mookwai_register_individual_database()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mookwai_block_settings';
    $wpdb_collate = $wpdb->collate;
    $sql =
      "CREATE TABLE {$table_name} (
        setting_id mediumint(8) unsigned NOT NULL auto_increment ,
        the_type varchar(30) NOT NULL,
        page_id varchar(100) NULL,
        parts longtext NULL,
        color_scheme longtext NULL,
        the_value longtext NULL,
        page_setting longtext NULL,
        custom_css longtext NULL,
        custom_js longtext NULL,
        import_file longtext NULL,
        PRIMARY KEY  (setting_id),
        KEY first (page_id)
      )
      COLLATE {$wpdb_collate}";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}
