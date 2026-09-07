<?php

if (!defined('ABSPATH')) {
    exit;
}

class CC_Settings
{
    private const OPTION_NAME = 'cc_detail_layout';
    private const PAGE_SLUG = 'cc-settings';

    public static function register(): void
    {
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    public static function get_detail_layout(): string
    {
        $layout = get_option(self::OPTION_NAME, 'standard');

        return in_array($layout, ['standard', 'two-pane'], true) ? $layout : 'standard';
    }

    public static function add_menu(): void
    {
        add_submenu_page(
            'edit.php?post_type=consultation_event',
            __('相談会日程設定', 'consultation-connector'),
            __('設定', 'consultation-connector'),
            'manage_options',
            self::PAGE_SLUG,
            [self::class, 'render_page']
        );
    }

    public static function register_settings(): void
    {
        register_setting('cc_settings', self::OPTION_NAME, [
            'type'              => 'string',
            'sanitize_callback' => [self::class, 'sanitize_layout'],
            'default'           => 'standard',
        ]);

        add_settings_section(
            'cc_display_settings',
            __('詳細画面の表示設定', 'consultation-connector'),
            '__return_false',
            self::PAGE_SLUG
        );

        add_settings_field(
            self::OPTION_NAME,
            __('レイアウト', 'consultation-connector'),
            [self::class, 'render_layout_field'],
            self::PAGE_SLUG,
            'cc_display_settings'
        );
    }

    public static function sanitize_layout(string $layout): string
    {
        return in_array($layout, ['standard', 'two-pane'], true) ? $layout : 'standard';
    }

    public static function render_layout_field(): void
    {
        $layout = self::get_detail_layout();
        ?>
        <select name="<?php echo esc_attr(self::OPTION_NAME); ?>">
            <option value="standard" <?php selected($layout, 'standard'); ?>><?php esc_html_e('標準表示', 'consultation-connector'); ?></option>
            <option value="two-pane" <?php selected($layout, 'two-pane'); ?>><?php esc_html_e('2ペイン表示', 'consultation-connector'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('2ペイン表示では、本文を左側、相談会の詳細情報を右側に表示します。モバイル幅では1カラムに切り替わります。', 'consultation-connector'); ?></p>
        <?php
    }

    public static function render_page(): void
    {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('相談会日程設定', 'consultation-connector'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('cc_settings');
                do_settings_sections(self::PAGE_SLUG);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
