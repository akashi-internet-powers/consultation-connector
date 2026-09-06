<?php

if (!defined('ABSPATH')) {
    exit;
}

class CC_Post_Type
{
    public static function register(): void
    {
        register_post_type('consultation_event', [
            'label'        => '相談会日程',
            'public'       => true,
            'show_in_rest' => true, // consultation-app からの取得はこのフラグが前提
            'supports'     => ['title', 'editor', 'custom-fields'],
            'menu_icon'    => 'dashicons-calendar-alt',
        ]);

        // カスタムフィールド(開催日・時間帯表示・場所・ステータス・予約URL)
        // ACFは使わず register_post_meta + 自作メタボックス(class-meta-box.php)で実装(CLAUDE.md参照)
        register_post_meta('consultation_event', 'start_at', [
            'type'         => 'string',
            'single'       => true,
            'show_in_rest' => true,
        ]);
        register_post_meta('consultation_event', 'time_note', [
            'type'         => 'string', // 複数時間枠がある場合の表示用自由記述。例: "10:00〜 / 13:00〜 / 15:00〜"
            'single'       => true,
            'show_in_rest' => true,
        ]);
        register_post_meta('consultation_event', 'location', [
            'type'         => 'string',
            'single'       => true,
            'show_in_rest' => true,
        ]);
        register_post_meta('consultation_event', 'status', [
            'type'         => 'string', // "open" | "full" | "closed"
            'single'       => true,
            'show_in_rest' => true,
        ]);
        register_post_meta('consultation_event', 'reservation_url', [
            'type'         => 'string',
            'single'       => true,
            'show_in_rest' => true,
        ]);
    }
}
