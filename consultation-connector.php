<?php
/**
 * Plugin Name:       Consultation Connector
 * Description:       相談会の日程を管理し、REST API経由でアプリへ配信するプラグイン。
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       consultation-connector
 */

if (!defined('ABSPATH')) {
    exit; // 直接アクセス禁止
}

define('CC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CC_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once CC_PLUGIN_DIR . 'includes/class-post-type.php';
require_once CC_PLUGIN_DIR . 'includes/class-meta-box.php';
require_once CC_PLUGIN_DIR . 'includes/class-rest-api.php';
require_once CC_PLUGIN_DIR . 'includes/class-settings.php';
require_once CC_PLUGIN_DIR . 'includes/class-single-event.php';

function cc_activate(): void
{
    CC_Post_Type::register();
    flush_rewrite_rules();
}

function cc_deactivate(): void
{
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'cc_activate');
register_deactivation_hook(__FILE__, 'cc_deactivate');

add_action('init', ['CC_Post_Type', 'register']);
CC_Meta_Box::register();
CC_Settings::register();
CC_Single_Event::register();
add_action('rest_api_init', ['CC_Rest_Api', 'register_fields']);

// 相談会日程一覧の動的ブロック(FSE対応)。表示ロジックは blocks/event-list/render.php に集約。
add_action('init', function () {
    register_block_type(CC_PLUGIN_DIR . 'blocks/event-list');
});
