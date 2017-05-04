<?php
/**
 * Author - Doe
 * Date - 05/02/2017
 * Purpose - Adding Facebook Comments
 */

/**
 * Adds FB Comment Menu
 * @return [type] [description]
 */
function egw_fb_comments()
{
    add_options_page( 'Facebook Comments', 'Facebook Comments', 'manage_options', 'egw-fb-comments', "egw_fb_comments_load");
}
add_action('admin_menu', 'egw_fb_comments');

function egw_fb_comments_load()
{
?>
<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_attr_e( 'Facebook Comments', 'wp_admin_style' ); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h2 class="hndle"><span><?php esc_attr_e( 'Settings', 'wp_admin_style' ); ?></span>
						</h2>

						<div class="inside">
					        <form method="post" action="options.php">
					            <?php wp_nonce_field('update-options') ?>
					            <p><strong>API Key:</strong><br />
					                <input type="text" name="egw_fb_comments_api_key" size="45" value="<?php echo get_option('egw_fb_comments_api_key'); ?>" />
					            </p>
					            <h2 style="padding-left:0px;"><strong><?php esc_attr_e('Show/Hide', 'wp_admin_style'); ?></strong></h2>
					            <hr />
					            <p><strong>Posts</strong><br />
					            	<input name="egw_fb_comments_single_posts" type="radio" value="1" <?php checked( '1', get_option( 'egw_fb_comments_single_posts' ) ); ?> />
									<label>Show</label>
					                <input name="egw_fb_comments_single_posts" type="radio" value="0" <?php checked( '0', get_option( 'egw_fb_comments_single_posts' ) ); ?> />
					                <label>Hide</label>
					            </p>
					            <p><strong>Videos</strong><br />
					            	<input name="egw_fb_comments_single_videos" type="radio" value="1" <?php checked( '1', get_option( 'egw_fb_comments_single_videos' ) ); ?> />
									<label>Show</label>
					                <input name="egw_fb_comments_single_videos" type="radio" value="0" <?php checked( '0', get_option( 'egw_fb_comments_single_videos' ) ); ?> />
					                <label>Hide</label>
					            </p>
					            <p><strong>Events</strong><br />
					            	<input name="egw_fb_comments_single_events" type="radio" value="1" <?php checked( '1', get_option( 'egw_fb_comments_single_events' ) ); ?> />
									<label>Show</label>
					                <input name="egw_fb_comments_single_events" type="radio" value="0" <?php checked( '0', get_option( 'egw_fb_comments_single_events' ) ); ?> />
					                <label>Hide</label>
					            </p>
					           	<p><strong>Sponsored Posts</strong><br />
					            	<input name="egw_fb_comments_single_sponsored_posts" type="radio" value="1" <?php checked( '1', get_option( 'egw_fb_comments_single_sponsored_posts' ) ); ?> />
									<label>Show</label>
					                <input name="egw_fb_comments_single_sponsored_posts" type="radio" value="0" <?php checked( '0', get_option( 'egw_fb_comments_single_sponsored_posts' ) ); ?> />
					                <label>Hide</label>
					            </p>						           
					            <p><input type="submit" name="Submit" value="Update" class="button-primary"/></p>
					            <input type="hidden" name="action" value="update" />
					            <input type="hidden" name="page_options" value="egw_fb_comments_api_key, egw_fb_comments_single_posts, egw_fb_comments_single_videos, egw_fb_comments_single_events, egw_fb_comments_single_sponsored_posts" />

					        </form>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<div class="handlediv" title="Click to toggle"><br></div>
						<!-- Toggle -->

						<h2 class="hndle"><span><?php esc_attr_e(
									'Instructions', 'wp_admin_style'
								); ?></span></h2>

						<div class="inside">
							<p><?php esc_attr_e( 'Use this plugin to update & control the display of Facebook Comments for Evergreen Wellness.' ); ?></p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->

<?php
}
?>


