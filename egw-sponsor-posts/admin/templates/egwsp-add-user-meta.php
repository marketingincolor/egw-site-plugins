<?php
/**
 * Purpose: Add Meta to User Profiles
 * Author: AD
 * Date: 02/16/2017
 */


// ADD COMPANY NAME TO USER PROFILES
add_action( 'show_user_profile', 'egwsp_show_fields' );
add_action( 'edit_user_profile', 'egwsp_show_fields' );

function egwsp_show_fields( $user ) { ?>

	<h3>Sponsor Post Information</h3>

	<table class="form-table">

		<!-- Company Name -->
		<tr>
			<th><label for="egwsp_company_name">Company Name</label></th>

			<td>
				<input type="text" name="egwsp_company_name" id="egwsp_company_name" value="<?php echo esc_attr( get_the_author_meta( 'egwsp_company_name', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your company name</span>
			</td>
		</tr>

		<!-- Company Website -->
		<tr>
			<th><label for="egwsp_company_name">Company Website</label></th>

			<td>
				<input type="text" name="egwsp_company_website" id="egwsp_company_website" value="<?php echo esc_attr( get_the_author_meta( 'egwsp_company_website', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your company website</span>
			</td>
		</tr>

	</table>
<?php }


//SAVE COMPANY NAME ON UPDATE
add_action( 'personal_options_update', 'egwsp_user_profile_fields' );
add_action( 'edit_user_profile_update', 'egwsp_user_profile_fields' );

function egwsp_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	update_usermeta( $user_id, 'egwsp_company_name', $_POST['egwsp_company_name'] );
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	update_usermeta( $user_id, 'egwsp_company_website', $_POST['egwsp_company_website'] );
}