<?php

if (!defined('ABSPATH')) {
    exit;
}

class OC_Single_Event
{
    public static function register(): void
    {
        add_filter('the_content', [self::class, 'append_details']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_styles']);
    }

    public static function enqueue_styles(): void
    {
        if (is_singular('consultation_event')) {
            wp_enqueue_style(
                'oc-single-event',
                OC_PLUGIN_URL . 'assets/single-event.css',
                [],
                '0.1.0'
            );
        }
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
            $fields[] = '<dt>' . esc_html__('開催日', 'ototsugu-connector') . '</dt><dd>' . esc_html($date->format('Y年n月j日')) . '</dd>';
        }
        if ($time_note) {
            $fields[] = '<dt>' . esc_html__('時間帯', 'ototsugu-connector') . '</dt><dd>' . esc_html($time_note) . '</dd>';
        }
        if ($location_name) {
            $fields[] = '<dt>' . esc_html__('場所の名称', 'ototsugu-connector') . '</dt><dd>' . esc_html($location_name) . '</dd>';
        }
        if ($location_address) {
            $fields[] = '<dt>' . esc_html__('住所', 'ototsugu-connector') . '</dt><dd>' . esc_html($location_address) . '</dd>';
        }

        $status_labels = [
            'open'   => __('受付中', 'ototsugu-connector'),
            'full'   => __('満席', 'ototsugu-connector'),
            'closed' => __('終了', 'ototsugu-connector'),
        ];
        $status_label = $status_labels[$status] ?? $status;
        $fields[]      = '<dt>' . esc_html__('ステータス', 'ototsugu-connector') . '</dt><dd>' . esc_html($status_label) . '</dd>';

        $details = '<section class="oc-event-details">'
            . '<h2>' . esc_html__('相談会 詳細情報', 'ototsugu-connector') . '</h2>'
            . '<dl>' . implode('', $fields) . '</dl>';

        if ($reservation_url && $status === 'open') {
            $details .= '<p class="oc-event-details__reservation">'
                . '<a href="' . esc_url($reservation_url) . '" target="_blank" rel="noopener">' . esc_html__('予約する', 'ototsugu-connector') . '</a>'
                . '</p>';
        }

        $details .= '</section>';

        if (OC_Settings::get_detail_layout() === 'two-pane') {
            return '<div class="oc-event-details-layout oc-event-details-layout--two-pane">'
                . '<div class="oc-event-details-layout__main">' . $content . '</div>'
                . '<div class="oc-event-details-layout__side">' . str_replace(
                    'class="oc-event-details"',
                    'class="oc-event-details oc-event-details--card"',
                    $details
                ) . '</div>'
                . '</div>';
        }

        return $details . $content;
    }
}
