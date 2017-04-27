<?php
add_action( 'phpmailer_init', 'fix_egw_email_return_path' );
 
function fix_egw_email_return_path( $phpmailer ) {
    $phpmailer->Sender = $phpmailer->From;
}
