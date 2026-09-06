<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ACFを使わず、register_post_meta + 素のメタボックスでフィールドを実装する。
 * フィールドが単純(テキスト・日付・URL・選択肢のみ)かつ、
 * 他法人への配布物として依存関係を増やしたくないという判断による(CLAUDE.md参照)。
 */
class CC_Meta_Box
{
    private const FIELDS = ['start_at', 'time_note', 'location', 'status', 'reservation_url'];
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
            '相談会 詳細情報',
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
        $location        = get_post_meta($post->ID, 'location', true);
        $status          = get_post_meta($post->ID, 'status', true) ?: 'open';
        $reservation_url = get_post_meta($post->ID, 'reservation_url', true);
        ?>
        <p>
            <label for="ce_start_at">開催日</label><br>
            <input type="datetime-local" id="ce_start_at" name="ce_start_at"
                   value="<?php echo esc_attr($start_at); ?>">
        </p>
        <p>
            <label for="ce_time_note">時間帯(表示用・自由記述)</label><br>
            <input type="text" id="ce_time_note" name="ce_time_note" class="widefat"
                   placeholder="例: 10:00〜 / 13:00〜 / 15:00〜(各回45分)"
                   value="<?php echo esc_attr($time_note); ?>">
            <span class="description">複数時間枠がある場合も、厳密な枠管理はせず表示用の文言としてここに記載します。</span>
        </p>
        <p>
            <label for="ce_location">開催場所</label><br>
            <input type="text" id="ce_location" name="ce_location" class="widefat"
                   value="<?php echo esc_attr($location); ?>">
        </p>
        <p>
            <label for="ce_status">ステータス</label><br>
            <select id="ce_status" name="ce_status">
                <option value="open" <?php selected($status, 'open'); ?>>受付中</option>
                <option value="full" <?php selected($status, 'full'); ?>>満席</option>
                <option value="closed" <?php selected($status, 'closed'); ?>>終了</option>
            </select>
        </p>
        <p>
            <label for="ce_reservation_url">予約URL(外部システム)</label><br>
            <input type="url" id="ce_reservation_url" name="ce_reservation_url" class="widefat"
                   value="<?php echo esc_attr($reservation_url); ?>">
        </p>
        <?php
    }

    public static function save(int $post_id): void
    {
        if (
            !isset($_POST[self::NONCE_NAME]) ||
            !wp_verify_nonce($_POST[self::NONCE_NAME], self::NONCE_ACTION)
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
            'ce_location'        => 'location',
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
