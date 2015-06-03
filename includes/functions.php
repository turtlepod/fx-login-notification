<?php
/* do not access this file directly */
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Login Notification Functions
 * @since 0.1.0
 */

/**
 * Get Option helper function
 * @since 0.1.0
 */
function fx_login_nf_get_option( $option, $default = '', $option_name = 'fx-login-nf-admin' ) {

	/* Bail early if no option defined */
	if ( !$option ){
		return false;
	}

	/* Get database and sanitize it */
	$get_option = get_option( $option_name );

	/* if the data is not array, return false */
	if( !is_array( $get_option ) ){
		return $default;
	}

	/* Get data if it's set */
	if( isset( $get_option[ $option ] ) ){
		return $get_option[ $option ];
	}
	/* Data is not set */
	else{
		return $default;
	}
}


/**
 * Get List Of User Roles
 * @return array
 * @since 0.1.0
 */
function fx_login_nf_user_roles(){

	/* globalize wp_roles object */
	global $wp_roles;

	/* roles */
	$roles = array();
	$roles = $wp_roles->roles;

	/* get list of roles */
	$output = array();
	foreach ( $roles as $role => $role_data ){
		$output[$role] = $role_data['name'];
	}

	return apply_filters( 'fx_login_nf_user_roles', $output );
}


/**
 * Sanitize E-Mail Content
 * @since 0.1.0
 */
function fx_login_nf_email_content_sanitize( $input ){

	/* allowed tags */
	$allowedtags = array(
		'a' => array( 'href' => array(), 'title' => array(), 'target' => array() ),
		'abbr' => array( 'title' => array() ), 'acronym' => array( 'title' => array() ),
		'code' => array(), 'pre' => array(), 'em' => array(), 'strong' => array(), 'br' => array(),
		'div' => array(), 'p' => array(), 'ul' => array(), 'ol' => array(), 'li' => array(),
		'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
		'img' => array( 'src' => array(), 'class' => array(), 'alt' => array() )
	);

	$output = wp_kses( $input, $allowedtags );
	return $output;
}


/**
 * Default Email Title Template
 * @since 0.1.0
 */
function fx_login_nf_email_subject_default(){

	$subject = _x( 'Login Alert: %user_login% on %site_name% (%site_url%)', 'email content default template', 'fx-login-notification' );
	$subject  = apply_filters( 'fx_login_nf_email_subject_template_default', $subject );

	return esc_attr( $subject );
}


/**
 * Default Email Content Template
 * @since 0.1.0
 */
function fx_login_nf_email_content_default(){

	$content = _x(
		'Hello a user has logged in on the website %site_name% (%site_url%).' . "\n" .
		'Here are the details of this access:' . "\n\n" .
		'Login Date & Time: %current_time%' . "\n" .
		'User Login (ID): %user_login% (%user_id%)' . "\n" .
		'User Email: %user_email%' . "\n" .
		'User Roles: %user_roles%' . "\n" .
		'HTTP User Agent: %http_user_agent%' . "\n" .
		'HTTP Referer: %http_referer%' . "\n" .
		'IP Address: %ip_address%' . "\n\n" .
		'- Sent by f(x) Login Notification Plugin' . "\n",
		'email content default template',
		'fx-login-notification'
	);
	$content  = apply_filters( 'fx_login_nf_email_content_template_default', $content );

	return fx_login_nf_email_content_sanitize( $content );
}

/**
 * Email Template Tags
 * Display available template tags for e-mail subject and content.
 * @since 0.1.0
 */
function fx_login_nf_email_template_note(){
	$note = _x(
		'You can use tags below in e-mail subject and content:<br />' .
		'<code>%site_name%</code> to display Website Name.<br />' .
		'<code>%site_url%</code> to display Website URL.<br />' .
		'<code>%current_time%</code> to display Current Date and Time.<br />' .
		'<code>%http_user_agent%</code> to display HTTP User Agent.<br />' .
		'<code>%http_referer%</code> to display HTTP Referer.<br />' .
		'<code>%ip_address%</code> to display IP Address.<br />' .
		'<code>%user_id%</code> to display User ID.<br />' .
		'<code>%user_login%</code> to display User Login Name.<br />' .
		'<code>%user_email%</code> to display User E-mail Address.<br />' .
		'<code>%display_name%</code> to display User Display Name.<br />' .
		'<code>%user_roles%</code> to display User Roles.<br />',
		'email template setting description',
		'fx-login-notification'
	);
	return apply_filters( 'fx_login_nf_email_template_note', $note );
}

/**
 * Parse Email Template
 * @since 0.1.0
 */
function fx_login_nf_parse_template( $data , $user = '' ){

	/* Site data */
	$data = str_replace( '%site_name%', get_bloginfo( 'name' ), $data );
	$data = str_replace( '%site_url%', get_bloginfo( 'wpurl' ), $data );

	/* HTTP data */
	$data = str_replace( '%http_user_agent%', ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? esc_html( $_SERVER['HTTP_USER_AGENT'] ) : '' ), $data );
	$data = str_replace( '%http_referer%', ( isset( $_SERVER['HTTP_REFERER'] ) ? esc_html( $_SERVER['HTTP_REFERER'] ) : '' ), $data );
	$data = str_replace( '%ip_address%', fx_login_nf_get_ip(), $data );

	/* User data */
	if( !empty( $user ) ){
		$user_info = get_userdata( $user->ID );
		$data = str_replace( '%user_id%', $user->ID, $data );
		$data = str_replace( '%user_login%', $user_info->user_login, $data );
		$data = str_replace( '%user_email%', $user_info->user_email, $data );
		$data = str_replace( '%display_name%', $user_info->display_name, $data );
		$data = str_replace( '%user_roles%', implode( ', ', $user_info->roles ), $data );
	}

	/* Other data */
	$data = str_replace( '%current_time%', date_i18n( 'Y-m-d H:i:s' ), $data );

	return apply_filters( 'fx_login_nf_parse_template', $data );
}

/**
 * Utillity: Get IP Address.
 * @since 0.1.0
 */
function fx_login_nf_get_ip(){

	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}

/* If user is logged in, send email */
add_action( 'wp_login', 'fx_login_nf_send_mail', 60, 2 );

/**
 * Send E-mail if user is log in
 * @since 0.1.0
 * @param str $user_user_login
 * @param object $user
 */
function fx_login_nf_send_mail( $user_user_login, $user ) {

	/* Get User Data */
	$user_info = get_userdata( $user->ID );

	/* As default send email to all login instance */
	$send_nf = true;

	/* If notification disabled, set to false */
	if( ! fx_login_nf_get_option( 'enable', true ) ){
		$send_nf = false;
	}

	/* if exclude role enabled */
	if( fx_login_nf_get_option( 'exclude_roles', true ) ){

		/* set to false */
		$send_nf = false;

		/* check for each user roles */
		foreach( $user_info->roles as $role ){

			/* if user have the role in excluded list, set to false */
			if( ! in_array( $role, fx_login_nf_get_option( 'roles',  array() ) ) ){
				$send_nf = true;
			}
			/* if at least one of the role match, send the email. */
			if( true === $send_nf ){
				break;
			}
		}
	}

	/* only if it's true. */
	if( true === $send_nf ){

		/* Admin E-mail */
		$admin_email = get_bloginfo( 'admin_email' );

		/* E-mail From */
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		$from_email = 'noreply@' . $sitename;

		/* E-mail From Name */
		$from_name = sprintf( _x( '%1$s Notification', 'email from name', 'fx-login-notification' ), $sitename );

		/* Headers */
		$headers  = 'From: "' . $from_name . '" <' . $from_email . '>'. "\r\n";
		$headers .= "Content-type: text/html; charset: " . get_bloginfo( 'charset' ) . "\r\n";

		/* E-Mail Subject */
		$email_subject = esc_attr( fx_login_nf_get_option( 'email_subject', fx_login_nf_email_subject_default() ) );
		$email_subject = fx_login_nf_parse_template( $email_subject, $user );

		/* E-Mail Content */
		$body_message = fx_login_nf_get_option( 'email_content', fx_login_nf_email_content_default() );
		$body_message = fx_login_nf_parse_template( wpautop( $body_message ), $user );
		$body_message = fx_login_nf_email_content_sanitize( $body_message );

		/* Send Email! */
		wp_mail( $admin_email, $email_subject, $body_message, $headers );

	}
}


