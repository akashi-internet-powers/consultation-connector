<?php
/**
 * 「相談会日程一覧」ブロックのサーバーサイドレンダリング。
 * consultation-app 側のREST APIと同じデータソース(consultation_event投稿)を参照するため、
 * 表示ロジックはここに集約し、フィールド名の変更があった場合の修正箇所を1つにまとめる。
 *
 * @var array $attributes
 */

$events = get_posts([
    'post_type'      => 'consultation_event',
    'posts_per_page' => -1,
    'orderby'        => 'meta_value',
    'meta_key'       => 'start_at',
    'order'          => 'ASC',
]);

if (empty($events)) {
    echo '<p>' . esc_html__('現在、公開中の相談会日程はありません。', 'consultation-connector') . '</p>';
    return;
}
?>
<ul class="ce-event-list">
    <?php foreach ($events as $event) :
        $start_at        = get_post_meta($event->ID, 'start_at', true);
        $time_note       = get_post_meta($event->ID, 'time_note', true);
        $location        = get_post_meta($event->ID, 'location', true);
        $status          = get_post_meta($event->ID, 'status', true) ?: 'open';
        $reservation_url = get_post_meta($event->ID, 'reservation_url', true);
        $status_label    = ['open' => '受付中', 'full' => '満席', 'closed' => '終了'][$status] ?? '';
        ?>
        <li class="ce-event-list__item ce-event-list__item--<?php echo esc_attr($status); ?>">
            <h3 class="ce-event-list__title"><?php echo esc_html($event->post_title); ?></h3>
            <p class="ce-event-list__meta">
                <?php echo esc_html($start_at); ?> / <?php echo esc_html($location); ?>
            </p>
            <?php if ($time_note) : ?>
                <p class="ce-event-list__time-note"><?php echo esc_html($time_note); ?></p>
            <?php endif; ?>
            <p class="ce-event-list__status"><?php echo esc_html($status_label); ?></p>
            <?php if ($reservation_url && $status === 'open') : ?>
                <p class="ce-event-list__reserve">
                    <a href="<?php echo esc_url($reservation_url); ?>">
                        <?php esc_html_e('予約する', 'consultation-connector'); ?>
                    </a>
                </p>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
