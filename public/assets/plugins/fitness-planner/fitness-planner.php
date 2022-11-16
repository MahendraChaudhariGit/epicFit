<?php
/*
Plugin Name: Fitness Planner
Plugin URI: www.onesportevent.com
Description: Fitness Planner Plug-in, allows you to define [fitplanner webkey="key-here"] in your content to embed a fitness planning tool
Version: 1.0
Author: Ewart MacLucas
Author URI: www.onesportevent.com
*/

function insert_fitness_planner($atts) {

	// Get parameters with defaults as fallbacks
	extract( shortcode_atts( array(
			'webkey' => '',
            'webkeypassword' => '',
			'metric' => '1',
            'showpreview' => true
		), $atts ) );
		 
	// Only available if user logged in
	if ( is_user_logged_in() ) {

		$current_user = wp_get_current_user();

		if ( ($current_user instanceof WP_User) ) {
			
			if ( empty($atts['webkey']) || empty($atts['webkeypassword']) ) {

                $plugin_code = '<p>Sorry You must specifiy a valid webkey and password to use fitness planner, e.g [fitplanner webkey="93-42493-4938934-4329843" webkeypassword="38738-43249832-3232"]  You can get a webkey for free instantly from <a href="http://www.onesportevent.com/get-widget-key">http://www.onesportevent.com/get-widget-key</a></p>';

			}
			else {

				$plugin_code = fit_build_fit_planner( $webkey, $current_user->user_email, $webkeypassword, $showpreview);

			}

		}
		else {

			$plugin_code = '<p>Login to use fitness workout logger.</p>';

		}

	}
	else {

		$plugin_code = '<p>Login to use fitness workout logger.</p>';
	
	}

	return $plugin_code;
}

function fit_build_fit_planner( $webKeyGUID, $email, $webKeyPassword, $showpreview )
{
    // Will enable this when we are live
    if( 1 == 1 )
    {
	    // Get user session token from API and convert to JSON
	    $url='http://192.168.0.50/result/public/Login/PartnerAuthenticate?email=' . urlencode($email) . '&webKeyGuid=' . urlencode($webKeyGUID) . '&webKeyPassword=' . urlencode($webKeyPassword);
	    $data=json_decode(file_get_contents($url),true);
        
	    if( $data == null || $data == "" || $data['MessageId'] != 0 )
	    {
		    // The api has automatically emailed about a possible security intrusion, no need to email here just advise end user.
		    return '<p>Authentication error.  Admins have been emailed about this.</p>';
	    }
    }
	
	$fit_code = <<<EOD
	<script type='text/javascript'>
        jQuery(document).ready( function() {
		    PT.Start( [[show_preview]], '[[webKeyGUID]]' );
        });
	</script>
EOD;

	$fit_layout = fit_planner_file();
   
    // Default path if not specified
    if( $logpath == null || $logpath == '' )
    {
        $logpath = '/member/nutrition-log/';
    }
    
    if( $mealpaneCollapsible == null || $mealpaneCollapsible == '' )
    {
        $mealpaneCollapsible = 'false';
    }
    
	// Replace our tag with login email details
	$fit_layout = str_replace('[[email]]', $email, $fit_layout);
    $fit_layout = str_replace('[[SessionGUID]]', $data['SessionGUID'], $fit_layout);
    $fit_layout = str_replace('[[CalorieGoal]]', $data['CalorieGoal'], $fit_layout);
    $fit_layout = str_replace('[[CityID]]', $data['CityID'], $fit_layout);
    $fit_layout = str_replace('[[Gender]]', $data['Gender'], $fit_layout);
    $fit_layout = str_replace('[[Age]]', $data['Age'], $fit_layout);
    $fit_layout = str_replace('[[Height]]', $data['Height'], $fit_layout);
    $fit_layout = str_replace('[[Weight]]', $data['Weight'], $fit_layout);
    
    $fit_code = str_replace('[[show_preview]]', $showpreview, $fit_code);
    $fit_code = str_replace('[[webKeyGUID]]', $webKeyGUID, $fit_code);
    
    //<input type="hidden" id="fit_session" value="' . $data['SessionGUID'] . '" />
	//<input type="hidden" id="fit_caloriegoal" value="' . $data['CalorieGoal'] . '" />
	//<input type="hidden" id="fit_email" value="' . $email . '" />

	// Replace our tag with login email details
	// $fit_code = str_replace('[[email]]', $email, $fit_code);
	
	$plugin_base = plugin_dir_url( __FILE__ );
	$fit_layout = str_ireplace('<img src="images', '<img src="'. $plugin_base .'images', $fit_layout);

	return $fit_code . $fit_layout;

}

function fit_planner_file(/* $webKeyGUID */)
{
    return file_get_contents(dirname(__FILE__) . '/planner.html');
}

// TODO: could define parameter to say UseAlternateStyles and if specified, don't include this link?
//wp_enqueue_style( 'fit_plugin_theme1', $api_path . 'fitness-theme/jquery-ui-1.8.16.custom.css' );
//wp_enqueue_style( 'fit_plugin_theme2', $api_path . 'bootstrap/bootstrap.css' );


//wp_enqueue_style( 'fit_woplugin_hands', plugins_url( '/css/jquery.handsontable.full.css?v=1', __FILE__ ) );
//wp_enqueue_style( 'fit_woplugin_full_calendar', plugins_url( '/css/fullcalendar.css?v=1', __FILE__ ) );
wp_enqueue_style( 'fit_planner_style', plugins_url( '/css/pt-planner.css?v=1', __FILE__ ) );
wp_enqueue_style( 'jqueryui_labeledslider', plugins_url( '/css/jquery.ui.labeledslider.css?v=1', __FILE__ ) );
wp_enqueue_style( 'fit_planner_customstyle', plugins_url( '/custom/style.css?v=1', __FILE__ ) );
//wp_enqueue_style( 'fit_woplugin_datetime_picker', plugins_url( '/css/datetimepicker.min.css?v=1', __FILE__ ) );


/*if(!function_exists('fit_enqueue_jqueryui'))
{
	function fit_enqueue_jqueryui() {
		wp_deregister_script( 'fit_jqueryui' );
		wp_register_script( 'fit_jqueryui', 'http://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js');
		wp_enqueue_script( 'fit_jqueryui' );
	}    
}*/

if(!function_exists('fit_enqueue_planner_js'))
{
	function fit_enqueue_planner_js() {
        
        global $wp_scripts;

        // <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        //wp_register_script( 'html5shiv', 'http://html5shim.googlecode.com/svn/trunk/html5.js', array(), '1.0.0' );
        //$wp_scripts->add_data( 'html5shiv', 'conditional', 'lt IE 9' );

        //$plugin_dir_path = plugins_url( '/plugins/fitness-core/jquery.ui.1.8.16.ie.css', __FILE__ );
        //wp_register_script( 'jqueryui_ie', $plugin_dir_path, array(), '1.0.0' );
        //$wp_scripts->add_data( 'jqueryui_ie', 'conditional', 'IE' );

        //fit_enqueue_script( 'fit_fullcalendar', plugins_url('/js/fullcalendar.min.js', __FILE__), false);
        //fit_enqueue_script( 'fit_handsontable', plugins_url('/js/jquery.handsontable.full.js', __FILE__), false);
        //fit_enqueue_script( 'fit_watermark', plugins_url('/js/jquery.watermark.min.js', __FILE__), false);
        fit_enqueue_script( 'fit_planner', plugins_url('/js/PTPlanner.js?v=1'.microtime(), __FILE__), false);
        //fit_enqueue_script( 'fit_planner_custom', plugins_url('/custom/js/pt.js?v=1', __FILE__), false);
        fit_enqueue_script( 'fit_planner_custom_popup_js', plugins_url('/custom/js/popup.js?v=1', __FILE__), false);
        fit_enqueue_script( 'fit_planner_custom_placeholder_js', plugins_url('/custom/js/jquery.placeholder.js?v=1', __FILE__), false);
        fit_enqueue_script( 'fit_planner_custom_touch_punch_js', plugins_url('/custom/js/jquery.ui.touch-punch.min.js?v=1', __FILE__), false);
        fit_enqueue_script( 'fit_planner_jwplayer_js', plugins_url('/custom/jwplayer/jwplayer.js?v=1', __FILE__), false);
        
        fit_enqueue_script( 'jqueryui_labeldslider_js', plugins_url('/js/jquery.ui.labeledslider.js', __FILE__), false);
        //fit_enqueue_script( 'fit_logger_regional', plugins_url('/js/WO.Regional-en-US.js', __FILE__), false);
        //fit_enqueue_script( 'fit_logger_datetimepicker', plugins_url('/js/datetimepicker.min.js', __FILE__), false);
	}
}

add_action('wp_enqueue_scripts', 'fit_enqueue_planner_js');

add_shortcode('fitplanner', 'insert_fitness_planner');

    //<!--<link type="text/css" rel="Stylesheet" href="Content/jquery-ui-1.10.0.custom.css" />
    //<link type="text/css" rel="Stylesheet" href="Content/bootstrap.min.css" />
    //<link type="text/css" rel="Stylesheet" href="Content/datetimepicker.min.css" />-->
//<!--    <script type="text/javascript" src="Scripts/jquery.min.js"></script>
    //<script type="text/javascript" src="Scripts/jquery-ui.custom.min.js"></script>
    //<script type="text/javascript" src="Scripts/bootstrap.min.js"></script>
    //<script type="text/javascript" language="javascript" src="Scripts/datetimepicker.min.js"></script>-->
//    
    
?>