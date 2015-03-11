<?php
/*
Plugin Name: Meteomedia 
Description: Affichez la m&eacute;t&eacute;o de votre ville sur votre barre lat&eacute;rale. Choisissez parmi diff&eacute;rentes mises en page et dessins.
Author: Meteomedia
Version: 1.0
Author URI: http://www.meteomedia.com
Plugin URI: http://widget.twnmm.com/mm-meteo.zip
*/



function MM_meteo_init() {

    if(!function_exists('register_sidebar_widget') || !function_exists('register_widget_control'))
		return; 

    function MM_meteo_control(){
        $newoptions = get_option('MM_meteo');
    	$options = $newoptions;
		$options_flag=0;
    	if(empty($newoptions)){
			$options_flag=1;
			$newoptions = array(
				'widgetID'=>'', 
				'placecode' => ''
			);
		}

		if($_POST['TWN-weather-submit']){
			$options_flag=1;
			$newoptions['widgetID'] = strip_tags(stripslashes($_POST['TWN-weather-widgetID']));
			$newoptions['placecode'] = strip_tags(stripslashes($_POST['TWN-weather-placecode']));
		}

      	if ( $options_flag ==1 ) {
              $options = $newoptions;
              update_option('MM_meteo', $options);
      	}

      	$widgetID = htmlspecialchars($options['widgetID'], ENT_QUOTES);
      	$placecode = htmlspecialchars($options['placecode'], ENT_QUOTES);
		
		echo "\n";
       
		// Get city
		echo '<p><label for="TWN-weather-city">* D&eacute;finissez vos pr&eacute;f&eacute;rences de widgets au <a href="http://www.meteomedia.com/widget-meteo" target="_blank">http://www.meteomedia.com/widget-meteo</a></label></p>';
        echo '<p><label for="TWN-weather-widgetID">Votre identifiant widget : <input style="width: 90%;" id="TWN-weather-widgetID" name="TWN-weather-widgetID" type="text" value="'.$widgetID.'" /> </label></p>';
		echo '<p><label for="TWN-weather-placecode">Votre premier code de localisation : <input style="width: 90%;" id="TWN-weather-placecode" name="TWN-weather-placecode" type="text" value="'.$placecode.'" /> </label></p>';
		
      	// Hidden "OK" button
      	echo '<label for="TWN-weather-submit">';
      	echo '<input id="TWN-weather-submit" name="TWN-weather-submit" type="hidden" value="Ok" />';
      	echo '</label>';
		


		echo "\n";
        echo '<p>*Enregistrer les modifications</p>';	


    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	DISPLAY WEATHER WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

     function MM_meteo($args){

		// Get values 
      	extract($args);
      	$options = get_option('MM_meteo');

		// Get Metric/Imperial,Buttontype	
      	$widgetID = htmlspecialchars($options['widgetID'], ENT_QUOTES);
      	$placecode = htmlspecialchars($options['placecode'], ENT_QUOTES);
		

		// Get city 
		echo '<!--start of code - Meteomedia '. $placecode .' -->';
		$urlstring="";
		
		//global $wp_version;
		$widget_call_string=wp_remote_get('http://widget.twnmm.com/widget.php?locale=fr_ca&placecode='.$placecode.'&widgetid='.$widgetID);	

		echo $widget_call_string['body'];
    }
  
    register_sidebar_widget('Meteomedia', 'MM_meteo');
    register_widget_control('Meteomedia', 'MM_meteo_control', 245, 300);


}


add_action('plugins_loaded', 'MM_meteo_init');

?>