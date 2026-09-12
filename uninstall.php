<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('oc_detail_layout');
