<?php

if (!defined('ABSPATH')) {
    exit;
}

class CC_Single_Event
{
    public static function register(): void
    {
        add_filter('the_content', [self::class, 'append_details']);
    }

    public static function append_details(string $content): string
    {
        if (!is_singular('consultation_event') || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        $post_id          = get_the_ID();
        $start_at         = get_post_meta($post_id, 'start_at', true);
        $time_note        = get_post_meta($post_id, 'time_note', true);
        $location_name    = get_post_meta($post_id, 'location_name', true);
        $location_address = get_post_meta($post_id, 'location_address', true);
        $legacy_location  = get_post_meta($post_id, 'location', true);
        $status            = get_post_meta($post_id, 'status', true) ?: 'open';
        $reservation_url  = get_post_meta($post_id, 'reservation_url', true);

        if (!$location_name && !$location_address) {
            $location_name = $legacy_location;
        }

        $fields = [];
        $date   = DateTimeImmutable::createFromFormat('Y-m-d\\TH:i', $start_at, wp_timezone());

        if ($date) {
            $fields[] = '<dt>開催日</dt><dd>' . esc_html($date->format('Y年n月j日')) . '</dd>';
        }
        if ($time_note) {
            $fields[] = '<dt>時間帯</dt><dd>' . esc_html($time_note) . '</dd>';
        }
        if ($location_name) {
            $fields[] = '<dt>場所の名称</dt><dd>' . esc_html($location_name) . '</dd>';
        }
        if ($location_address) {
            $fields[] = '<dt>住所</dt><dd>' . esc_html($location_address) . '</dd>';
        }

        $status_labels = [
            'open'   => '受付中',
            'full'   => '満席',
            'closed' => '終了',
        ];
        $status_label = $status_labels[$status] ?? $status;
        $fields[]      = '<dt>ステータス</dt><dd>' . esc_html($status_label) . '</dd>';

        $details = '<section class="cc-event-details">'
            . '<h2>相談会 詳細情報</h2>'
            . '<dl>' . implode('', $fields) . '</dl>';

        if ($reservation_url && $status === 'open') {
            $details .= '<p class="cc-event-details__reservation">'
                . '<a href="' . esc_url($reservation_url) . '" target="_blank" rel="noopener">予約する</a>'
                . '</p>';
        }

        return $details . '</section>' . $content;
    }
}
