<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('cc_detail_layout');
