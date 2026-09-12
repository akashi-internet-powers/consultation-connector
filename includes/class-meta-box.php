<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ACFを使わず、register_post_meta + 素のメタボックスでフィールドを実装する。
 * フィールドが単純(テキスト・日付・URL・選択肢のみ)かつ、
 * 他法人への配布物として依存関係を増やしたくないという判断による(CLAUDE.md参照)。
 */
class OC_Meta_Box
{
    private const FIELDS = ['start_at', 'time_note', 'location_name', 'location_address', 'status', 'reservation_url'];
    private const NONCE_ACTION = 'ce_save_meta_box';
    private const NONCE_NAME = 'ce_meta_box_nonce';

    public static function register(): void
    {
        add_action('add_meta_boxes', [self::class, 'add_box']);
        add_action('save_post_consultation_event', [self::class, 'save']);
    }

    public static function add_box(): void
    {
        add_meta_box(
            'ce_event_details',
            __('相談会 詳細情報', 'ototsugu-connector'),
            [self::class, 'render'],
            'consultation_event',
            'normal',
            'high'
        );
    }

    public static function render(WP_Post $post): void
    {
        wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME);

        $start_at        = get_post_meta($post->ID, 'start_at', true);
        $time_note       = get_post_meta($post->ID, 'time_note', true);
        $location_name    = get_post_meta($post->ID, 'location_name', true) ?: get_post_meta($post->ID, 'location', true);
        $location_address = get_post_meta($post->ID, 'location_address', true);
        $status          = get_post_meta($post->ID, 'status', true) ?: 'open';
        $reservation_url = get_post_meta($post->ID, 'reservation_url', true);
        ?>
        <p>
                 <label for="ce_start_at"><?php esc_html_e('開催日・並び順', 'ototsugu-connector'); ?></label><br>
            <input type="datetime-local" id="ce_start_at" name="ce_start_at"
                   value="<?php echo esc_attr($start_at); ?>">
                 <span class="description"><?php esc_html_e('同日に複数の日程がある場合、この日時の時刻を一覧の並び順に使用します。表示上の時間帯は下の「時間帯」に入力します。', 'ototsugu-connector'); ?></span>
        </p>
        <p>
            <label for="ce_time_note"><?php esc_html_e('時間帯(表示用・自由記述)', 'ototsugu-connector'); ?></label><br>
            <input type="text" id="ce_time_note" name="ce_time_note" class="widefat"
                   placeholder="<?php echo esc_attr__('例: 10:00〜 / 13:00〜 / 15:00〜(各回45分)', 'ototsugu-connector'); ?>"
                   value="<?php echo esc_attr($time_note); ?>">
            <span class="description"><?php esc_html_e('複数時間枠がある場合も、厳密な枠管理はせず表示用の文言としてここに記載します。', 'ototsugu-connector'); ?></span>
        </p>
        <p>
            <label for="ce_location_name"><?php esc_html_e('場所の名称', 'ototsugu-connector'); ?></label><br>
            <input type="text" id="ce_location_name" name="ce_location_name" class="widefat"
                   placeholder="<?php echo esc_attr__('例: ○○市民センター', 'ototsugu-connector'); ?>"
                   value="<?php echo esc_attr($location_name); ?>">
        </p>
        <p>
            <label for="ce_location_address"><?php esc_html_e('住所', 'ototsugu-connector'); ?></label><br>
            <input type="text" id="ce_location_address" name="ce_location_address" class="widefat"
                   placeholder="<?php echo esc_attr__('例: 東京都○○区○○1-2-3', 'ototsugu-connector'); ?>"
                   value="<?php echo esc_attr($location_address); ?>">
        </p>
        <p>
            <label for="ce_status"><?php esc_html_e('ステータス', 'ototsugu-connector'); ?></label><br>
            <select id="ce_status" name="ce_status">
                <option value="open" <?php selected($status, 'open'); ?>><?php esc_html_e('受付中', 'ototsugu-connector'); ?></option>
                <option value="full" <?php selected($status, 'full'); ?>><?php esc_html_e('満席', 'ototsugu-connector'); ?></option>
                <option value="closed" <?php selected($status, 'closed'); ?>><?php esc_html_e('終了', 'ototsugu-connector'); ?></option>
            </select>
        </p>
        <p>
            <label for="ce_reservation_url"><?php esc_html_e('予約URL(外部システム)', 'ototsugu-connector'); ?></label><br>
            <input type="url" id="ce_reservation_url" name="ce_reservation_url" class="widefat"
                   value="<?php echo esc_attr($reservation_url); ?>">
        </p>
        <?php
    }

    public static function save(int $post_id): void
    {
        if (
            !isset($_POST[self::NONCE_NAME]) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[self::NONCE_NAME])), self::NONCE_ACTION)
        ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $map = [
            'ce_start_at'        => 'start_at',
            'ce_time_note'       => 'time_note',
            'ce_location_name'   => 'location_name',
            'ce_location_address' => 'location_address',
            'ce_status'          => 'status',
            'ce_reservation_url' => 'reservation_url',
        ];

        foreach ($map as $field_name => $meta_key) {
            if (!isset($_POST[$field_name])) {
                continue;
            }
            $value = $meta_key === 'reservation_url'
                ? esc_url_raw(wp_unslash($_POST[$field_name]))
                : sanitize_text_field(wp_unslash($_POST[$field_name]));

            update_post_meta($post_id, $meta_key, $value);
        }
    }
}
