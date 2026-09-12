<?php
/**
 * Plugin Name:       Ototsugu Connector
 * Description:       相談会の日程を管理し、REST API経由でアプリへ配信するプラグイン。
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ototsugu-connector
 */

if (!defined('ABSPATH')) {
    exit; // 直接アクセス禁止
}

define('OC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('OC_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once OC_PLUGIN_DIR . 'includes/class-post-type.php';
require_once OC_PLUGIN_DIR . 'includes/class-meta-box.php';
require_once OC_PLUGIN_DIR . 'includes/class-rest-api.php';
require_once OC_PLUGIN_DIR . 'includes/class-settings.php';
require_once OC_PLUGIN_DIR . 'includes/class-single-event.php';

function oc_activate(): void
{
    OC_Post_Type::register();
    flush_rewrite_rules();
}

function oc_deactivate(): void
{
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'oc_activate');
register_deactivation_hook(__FILE__, 'oc_deactivate');

add_action('init', ['OC_Post_Type', 'register']);
OC_Meta_Box::register();
OC_Settings::register();
OC_Single_Event::register();
add_action('rest_api_init', ['OC_Rest_Api', 'register_fields']);

// 相談会日程一覧の動的ブロック(FSE対応)。表示ロジックは blocks/event-list/render.php に集約。
add_action('init', function () {
    register_block_type(OC_PLUGIN_DIR . 'blocks/event-list');
});
