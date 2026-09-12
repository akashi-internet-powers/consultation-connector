<?php

if (!defined('ABSPATH')) {
    exit;
}

class OTSG_Rest_Api
{
    /**
     * register_post_meta の show_in_rest だけでも取得は可能だが、
     * アプリ側が期待するレスポンス形状に整形するため register_rest_field も併用する想定。
     */
    public static function register_fields(): void
    {
        // TODO: 実装フェーズで、アプリ側の型定義(ConsultationEvent)と
        // フィールド名・形状を一致させる
    }
}
