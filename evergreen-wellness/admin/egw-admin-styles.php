<?php

function egw_live_event_admin_styles($plugin_path)
{
    //TODO: Load styles only on single ai1ec page
    wp_enqueue_style('egw_live_event_admin_styles', EGW_CSS . 'style.css');
}
add_action( 'admin_enqueue_scripts', 'egw_live_event_admin_styles' );