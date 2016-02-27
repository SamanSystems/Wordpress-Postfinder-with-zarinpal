<?php
/**********************************************************************************************************************************
*
* Social Logins (Facebook)
* 
* Author: Webbu Design
*
***********************************************************************************************************************************/
function PointFinder_Social_Facebook_Logout() {
	if(PFSAIssetControl('setup4_membersettings_facebooklogin','','0') == 1){
		$setup4_membersettings_facebooklogin_appid = PFSAIssetControl('setup4_membersettings_facebooklogin_appid','','');
		$setup4_membersettings_facebooklogin_secretid = PFSAIssetControl('setup4_membersettings_facebooklogin_secretid','','');
		
		if ($setup4_membersettings_facebooklogin_appid != '' && $setup4_membersettings_facebooklogin_secretid != '') {
			$facebook = new Facebook(array(
			'appId'  => $setup4_membersettings_facebooklogin_appid,
			'secret' => $setup4_membersettings_facebooklogin_secretid,
			'cookie' => true
			));
			$facebook->destroySession();
		}
	}
}
add_action('wp_logout', 'PointFinder_Social_Facebook_Logout');

/*

*/
if(isset($_GET['pferror']) && $_GET['pferror']!=''){
			$pferror = esc_attr($_GET['pferror']);
		}

if(isset($pferror)){


	add_action('wp_footer','PF_SocialErrorHandler',400);
	function PF_SocialErrorHandler($pferror){
		$pferror = esc_attr($_GET['pferror']);
		switch ($pferror) {
			case 'fbem':
				$pferror_text = sprintf(esc_html__('Please complete %s Api setup from Admin Panel first.','pointfindert2d'),esc_html__('Facebook','pointfindert2d'));
				$pftype = 0;
				break;
			case 'tbem':
				$pferror_text = sprintf(esc_html__('Please complete %s Api setup from Admin Panel first.','pointfindert2d'),esc_html__('Twitter','pointfindert2d'));
				$pftype = 0;
				break;
			case 'lbem':
				$pferror_text = sprintf(esc_html__('Please complete %s Api setup from Admin Panel first.','pointfindert2d'),esc_html__('LinkedIn','pointfindert2d'));
				$pftype = 0;
				break;
			case 'gbem':
				$pferror_text = sprintf(esc_html__('Please complete %s Api setup from Admin Panel first.','pointfindert2d'),esc_html__('Google+','pointfindert2d'));
				$pftype = 0;
				break;



			case 'fbun':
				$pferror_text = sprintf(esc_html__('This %s already exits in our system. Please use <a id="pf-lp-trigger-button-inner" class="glink ext">Lost Password</a> section for retrieve your information.','pointfindert2d'),esc_html__('Username','pointfindert2d'));
				$pftype = 0;
				break;
			case 'fbux':
				$pferror_text = esc_html__('The user can not found in our system. Login not completed.','pointfindert2d');
				$pftype = 0;
				break;



			case 'tbem2':
				$pferror_text = esc_html__('Could not connect to the Twitter. Please try again later.','pointfindert2d');
				$pftype = 0;
				break;
			case 'tbem3':
				$pferror_text = esc_html__('The session is old. Please close/reopen your browser and try again.','pointfindert2d');
				$pftype = 0;
				break;
			case 'tbem4':
				$pferror_text = esc_html__('We could not verify your twitter account.','pointfindert2d');
				$pftype = 0;
				break;
			case 'twemail':
				$pferror_text = esc_html__('Please update your email address and information.','pointfindert2d');

				$pftype = 1;

				if(is_user_logged_in()){
					
					if(isset($_POST['action'])){
						if (esc_attr($_POST['action']) == 'pfget_updateuserprofile') {
							$nonce = esc_attr($_POST['security']);
							if ( wp_verify_nonce( $nonce, 'pfget_updateuserprofile' ) ) {
								
								$vars = $_POST;
								$domain_name =  preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
								if (strpos($vars['email'], $domain_name)==false) {
									$pftype = 2;
								}
							}
						}
					}
					
				}
				
				break;



			default:
				$pferror_text = esc_html__('No information','pointfindert2d');
				$pftype = 0;
				break;
		}

		

		echo '<script type="text/javascript">
				(function($) {
					"use strict";
					$(function() {
						$.pfOpenLogin("open","error","'.$pferror_text.'","'.$pftype.'");
					});
				})(jQuery);</script>';
	}

}


function PointFinder_Social_Facebook_Login(){
	if(PFSAIssetControl('setup4_membersettings_facebooklogin','','0') == 1){
		
		require_once( get_template_directory().'/admin/core/Facebook/base_facebook.php' );
		require_once( get_template_directory().'/admin/core/Facebook/facebook.php' );

		if(isset($_GET['uaf']) && $_GET['uaf']!=''){
			$ua_action = esc_attr($_GET['uaf']);
		}


		if(isset($ua_action)){

			/**
			*Start : Facebook Login
			**/
			if ($ua_action == 'fblogin') {

				$setup4_membersettings_facebooklogin_appid = PFSAIssetControl('setup4_membersettings_facebooklogin_appid','','');
				$setup4_membersettings_facebooklogin_secretid = PFSAIssetControl('setup4_membersettings_facebooklogin_secretid','','');
				$setup4_membersettings_requestupdateinfo = PFSAIssetControl('setup4_membersettings_requestupdateinfo','','1');
				$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','',site_url());
				$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
				
				$homeurllink = site_url($path = '/');
				$pfmenu_perout = PFPermalinkCheck();

				$special_linkurl = $homeurllink;
				switch ($setup4_membersettings_requestupdateinfo) {
					case '1':
						$special_linkurl = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile';
						break;
					case '2':
						$special_linkurl = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';
						break;
					case '3':
						$special_linkurl = $homeurllink;
						break;
				}

				if ($setup4_membersettings_facebooklogin_appid == '' && $setup4_membersettings_facebooklogin_secretid == '') {
					wp_redirect($homeurllink.$pfmenu_perout.'pferror=fbem');
					exit;
				}

				$facebook = new Facebook(array(
				  'appId'  => $setup4_membersettings_facebooklogin_appid,
				  'secret' => $setup4_membersettings_facebooklogin_secretid,
				  'cookie' => true
				));

				$user_fb = $facebook->getUser();
				
				if($user_fb) {

			        $user_profile = $facebook->api('/me','GET');
			       
			        if (is_array($user_profile)) {
			        	
			        	global $wpdb;
			        	
			        	$resultid = $wpdb->get_var( $wpdb->prepare(
							"SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s", 
							'user_socialloginid',
							'fb'.$user_profile['id']
						) );
			        	
			        	
						if ( !empty($resultid) ) {
						
						  $user = get_user_by( 'id', $resultid ); 
						
							if( $user ) {
							  wp_set_current_user( $user->ID, $user->user_login );
							  wp_set_auth_cookie( $user->ID );
							  do_action( 'wp_login', $user->user_login );
							}
							update_user_meta($user_id, 'user_facebook', 'http://facebook.com/'.$user_profile['id']);
						
						wp_redirect($special_linkurl);exit;

						} elseif(empty($result_id)) {
						  
						  $email = sanitize_email($user_profile['email']);
						  $username = sanitize_user( 'fb'.$user_profile['id'], $strict = true );
      
      					  $user_exist = username_exists( $username );
						  $user_email_exist = email_exists( $email );

						  if ($user_exist) {
						  	wp_redirect($homeurllink.$pfmenu_perout.'pferror=fbun');
						  	exit;
						  }
						  if ($user_email_exist) {
						  	wp_redirect($homeurllink.$pfmenu_perout.'pferror=fbue');
						  	exit;
						  }

						  $password = wp_generate_password( 12, false );
						  $user_id = wp_create_user( $username, $password, $email );


						  $user = new WP_User( $user_id );
						  $user->set_role( 'subscriber' );

						  add_user_meta( $user_id, 'user_socialloginid', 'fb'.$user_profile['id'], true );
						  update_user_meta($user_id, 'user_facebook', 'http://facebook.com/'.$user_profile['id']);

						  pointfinder_mailsystem_mailsender(
									array(
										'toemail' => $email,
								        'predefined' => 'registration',
								        'data' => array('password' => $password,'username'=>$username),
									)
								);

							$user = get_user_by( 'id', $user_id ); 
							if( $user ) {
							  wp_set_current_user( $user_id, $user->user_login );
							  wp_set_auth_cookie( $user_id );
							  do_action( 'wp_login', $user->user_login );
							}

							wp_redirect($special_linkurl);exit;
						  
						}else{
							wp_redirect($homeurllink.$pfmenu_perout.'pferror=fbux');exit;
						}
			        }
			       
			    } else {
					wp_redirect($facebook->getLoginUrl(array( 
						'scope' => 'email',
						'redirect_uri' => ''.$homeurllink.$pfmenu_perout.'uaf=fblogin'
						)));
					exit;
			    }
				
			}
			/**
			*End : Facebook Login
			**/




			/**
			*Start : Twitter Login
			**/
			if ($ua_action == 'twlogin') {
				require_once( get_template_directory().'/admin/core/Twitter/twitteroauth.php');

				$setup4_membersettings_twitterlogin_appid = PFSAIssetControl('setup4_membersettings_twitterlogin_appid','','');
				$setup4_membersettings_twitterlogin_secretid = PFSAIssetControl('setup4_membersettings_twitterlogin_secretid','','');
				
				$setup4_membersettings_requestupdateinfo = PFSAIssetControl('setup4_membersettings_requestupdateinfo','','1');
				$setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','',site_url());
				$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);

				$homeurllink = site_url($path = '/');
				$pfmenu_perout = PFPermalinkCheck();


				$special_linkurl = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile';


				if ($setup4_membersettings_twitterlogin_appid == '' && $setup4_membersettings_twitterlogin_secretid == '') {
					wp_redirect($homeurllink.$pfmenu_perout.'pferror=tbem');//Thereis no secret id please configurate
					exit;
				}

				$twitter_arr = array(
				  'CONSUMER_KEY'  => $setup4_membersettings_twitterlogin_appid,
				  'CONSUMER_SECRET' => $setup4_membersettings_twitterlogin_secretid,
				  'OAUTH_CALLBACK' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=twlogin'
				);

				if (!isset($_REQUEST['oauth_token'])) {
				
					$connection = new TwitterOAuth($twitter_arr['CONSUMER_KEY'], $twitter_arr['CONSUMER_SECRET']);
					$request_token = $connection->getRequestToken($twitter_arr['OAUTH_CALLBACK']);
					$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
					$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
					
					switch ($connection->http_code) {
					  case 200:
					    $url = $connection->getAuthorizeURL($token,1);
					    wp_redirect($url);exit;
					    break;
					  default:
					    wp_redirect($homeurllink.$pfmenu_perout.'pferror=tbem2');
						exit;
					}

				}elseif (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
				  
				  $_SESSION['oauth_status'] = 'oldtoken';
				  session_destroy();
				  wp_redirect($homeurllink.$pfmenu_perout.'pferror=tbem3');exit;

				}elseif(isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] === $_REQUEST['oauth_token']){

					$connection = new TwitterOAuth($twitter_arr['CONSUMER_KEY'], $twitter_arr['CONSUMER_SECRET'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
					$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
					$_SESSION['access_token'] = $access_token;
					
					unset($_SESSION['oauth_token']);
					unset($_SESSION['oauth_token_secret']);
					
					if (200 == $connection->http_code) {
						$_SESSION['status'] = 'verified';

						$user_profile = $connection->get('account/verify_credentials');

						if(!empty($user_profile)){
							global $wpdb;
				        	
				        	$resultid = $wpdb->get_var( $wpdb->prepare(
								"SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s", 
								'user_socialloginid',
								'tw'.$user_profile->id
							) );
				        }else{
				        	$resultid = '';
				        }

						if ( !empty($resultid) ) {
						
						    $user = get_user_by( 'id', $resultid ); 
						
							if( $user ) {
							  wp_set_current_user( $user->ID, $user->user_login );
							  wp_set_auth_cookie( $user->ID );
							  do_action( 'wp_login', $user->user_login );
							}
							update_user_meta($user_id, 'user_twitter', 'http://twitter.com/'.$user_profile->screen_name);
						
							wp_redirect($special_linkurl);exit;

						}elseif(empty($result_id)) {
							$domain_name =  preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
							$email = sanitize_email('twitter_user_'.$user_profile->id.'@'.$domain_name);
							$username = sanitize_user( 'tw'.$user_profile->id, $strict = true );

							$user_exist = username_exists( $username );
							$user_email_exist = email_exists( $email );

							if ($user_exist) {
								wp_redirect($homeurllink.$pfmenu_perout.'pferror=fbun');
								exit;
							}
							if ($user_email_exist) {
								wp_redirect($homeurllink.$pfmenu_perout.'pferror=fbue');
								exit;
							}

							$password = wp_generate_password( 12, false );
							$user_id = wp_create_user( $username, $password, $email );

							

							$user = new WP_User( $user_id );
							$user->set_role( 'subscriber' );

							add_user_meta( $user_id, 'user_socialloginid', 'tw'.$user_profile->id, true );
							update_user_meta($user_id, 'user_twitter', 'http://twitter.com/'.$user_profile->screen_name);
							
							/* Don't send email.
							
								*/

							$user = get_user_by( 'id', $user_id ); 
							if( $user ) {
							  wp_set_current_user( $user_id, $user->user_login );
							  wp_set_auth_cookie( $user_id );
							  do_action( 'wp_login', $user->user_login );
							}

							wp_redirect($special_linkurl.'&pferror=twemail');exit;

						}
						
					} else {
						wp_redirect($homeurllink.$pfmenu_perout.'pferror=tbem4');exit;
					}
			       
			    } else {
					wp_redirect($homeurllink);exit;
			    }
				
			}
			/**
			*End : Twitter Login
			**/
		}

	};
}
add_action('init','PointFinder_Social_Facebook_Login',10 );

?>