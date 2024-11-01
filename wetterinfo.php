<?php
/*
Plugin Name: Wetter.info Wetter Gadget
Plugin URI: http://www.wetter.info
Description: Aktuelles Wetter für Ihre Homepage
Version: 1.2
Author: Deutsche Telekom
Author URI: http://www.wetter.info
License: GPL
Stable Tag: 1.2
*/
define('WETTERINFO_PLUGIN_URL', plugin_dir_url( __FILE__ ));

add_action('widgets_init', 'wetterinfo_init');
add_action('admin_menu', 'wetterinfo_config_page');




function wetterinfo_init() {
	register_widget('wetterinfoGadget');
	wp_register_style('wetterinfo.css', WETTERINFO_PLUGIN_URL.'css/wetterinfo.css');
	wp_enqueue_style('wetterinfo.css');
	
	
	
	if (is_admin()) {
		
		if(!empty($_REQUEST)) {			
			if(isset($_REQUEST['ResultLocation'])) {
				update_option('wetterinfo_code_uni', $_REQUEST['ResultLocation']);
				update_option('wetterinfo_city', $_REQUEST[$_REQUEST['ResultLocation']]);
				
			}
			if(isset($_REQUEST['flag'])) {
				update_option('wetterinfo_userdesign', $_REQUEST['flag']);
				update_option('wetterinfo_user_border', '#FFFFFF');
				update_option('wetterinfo_user_bg', '#FFFFFF');
				update_option('wetterinfo_user_font', '#FFFFFF');
			}
			if(isset($_REQUEST['flag-custom'])) {
				update_option('wetterinfo_userdesign', $_REQUEST['flag-custom']);
				update_option('wetterinfo_user_border', $_REQUEST['color-border']);
				update_option('wetterinfo_user_bg', $_REQUEST['color-bg']);
				update_option('wetterinfo_user_font', $_REQUEST['color-font']);
			}
		} else {
			
		}
		wp_register_script('wetterinfo.js', WETTERINFO_PLUGIN_URL.'scripts/wetterinfo.js');
		wp_enqueue_script('wetterinfo.js');
		wp_register_script('colorpicker.js', WETTERINFO_PLUGIN_URL.'scripts/colorpicker.js');
		wp_enqueue_script('colorpicker.js');		
	}
}

function wetterinfo_config_page() {
	if (function_exists('add_submenu_page')) {
		add_submenu_page('plugins.php', __('wetter.info Wetter'), __('wetter.info Wetter'), 'manage_options', 'wetterinfo-config', 'wetterinfo_config');
		
	}
}

function wetterinfo_config() {	
	if($color_border = get_option('wetterinfo_user_border')) {
		$color_border = get_option('wetterinfo_user_border');
	}else {
		$color_border = "#FFFFFF";
	}
	if($color_font = get_option('wetterinfo_user_font')) {
		$color_font = get_option('wetterinfo_user_font');
	}else {
		$color_font = "#FFFFFF";
	}
	if($color_bg = get_option('wetterinfo_user_bg')) {
		$color_bg = get_option('wetterinfo_user_bg');
	}else {
		$color_bg = "#FFFFFF";
	}
	if($wi_ciry = get_option('wetterinfo_city')) {
		$wi_ciry = get_option('wetterinfo_city');
	}else {
		$wi_ciry = "";
	}
	
	?>
				<div id="wi-picker" class="wi-picker">
					  <div class="wi-picker-gradientbox" id="gradientBox" ">
					    <img id="gradientImg" style="display:block;width:154px;height:154px;" src="../wp-content/plugins/wetterinfo-wetter/images/color_picker_gradient.png" />
					    <img id="circle" style="position:absolute;height:11px;width:11px;" src="../wp-content/plugins/wetterinfo-wetter/images/color_picker_circle.gif" />
					  </div>
					  <div id="hueBarDiv" class="wi-picker-huebar">
					    <img id="huebar" style="position:absolute;height:154px;width:19px;left:8px;" src="../wp-content/plugins/wetterinfo-wetter/images/color_picker_bar.png" />
					    <img id="arrows" style="position:absolute;height:9px;width:35px;left:2px;" src="../wp-content/plugins/wetterinfo-wetter/images/color_picker_arrows.gif" />
					    <br />
					  </div>
					  <div class ="wi-picker-quick-static" >
						  <div class="wi-picker-color-container">
						    <div class="wi-picker-quick" id="quickColor""></div>
						    <div class="wi-picker-static" id="staticColor""></div>
						  </div>
					  <br />
						<div class="wi-picker-input">
					
						 <div> R <input maxlength="3" type="text" id="redBox" onchange="redBoxChanged();" /></div>
					     <div> G <input maxlength="3" type="text" id="greenBox" onchange="greenBoxChanged();" /></div>
					     <div> B <input maxlength="3" type="text" id="blueBox" onchange="blueBoxChanged();" /></div>
					     <div style="width:72px"> # <input style="width:50px" maxlength="7" type="text" id="hexBox" onchange="hexBoxChanged();" /></div>
						</div>
						<div class="wi-picker-btns">
							<img id="wi-picker-btn-cancel" src="../wp-content/plugins/wetterinfo-wetter/images/cancel.png">
							<img id="wi-picker-btn-accept" src="../wp-content/plugins/wetterinfo-wetter/images/accept.png">
							
						</div>
					  </div>
					</div> 
	<div id="wetterinfo-plugin-admin" class="wi-admin">
        <h2>wetter.info-Plugin</h2>
        <p>Mit unserem Wordpress-Plugin können Sie spielend leicht das Wetter für ihre Stadt in ihrem Blog anzeigen lasssen.<br />
        Wählen Sie die gewünschte Stadt aus, übernehmen sie das Design ihres Blogs oder konfigurieren Sie die Farben individuell.</p>
        <div class="wi-city">
            <div class="wi-header">Ort w&auml;hlen</div>
            <div class="wi-box">
            	<form method="post" action=""> 
                <div class="wi-box-content">
                	<div id="wi-loc-search">
                		<span class="wi-content-header">Geben Sie die Postleitzahl oder den Ort ein:</span><br>
                		<input class="wi-loc-input" name="wi-loc-input" id="SuchOrt" value="<? echo $wi_ciry; ?>" ><br>
                		<select class="wi-dropdown-region" name="region" size="1" id="SuchLand">
						      <option value="Germany">Deutschland</option>
						      <option value="worldwide">Weltweit</option>
						</select>
						<input type="button" value="suchen" onclick="mvGetCity(document.getElementById('SuchOrt').value, document.getElementById('SuchLand').value);">
                	</div>
                	<br>
                	<div id="wi-loc-results-container">
	                	<span class="wi-result-header">Es wurden folgende Stationen gefunden:</span><br>
	                	
	                	<div class="wi-loc-results">
	                		
	                	</div>
	                	<input class="wi-loc-btn" type="submit" value="Übernehmen">
                	</div>
                </div>
                </form> 
                <div class="wi-box-footer"></div>
            </div>        
        </div>
        <div class="wi-design">
            <div class="wi-header">Blog-Design &uuml;bernehmen</div>
            <div class="wi-box">
                <div class="wi-box-content">
                	<div id="wi-design-text"> Wenn Sie keine Anpassungen am Design des Plugins vornehmen wollen, klicken Sie hier übernehmen und aktivieren Sie das Plugin.</div>
                	<form method="post" action="">
                		<input class="hidden" name="flag" value="false">  
                		<input class="wi-design-btn" type="submit" value="Übernehmen">
                	</form>  
                </div>
                <div class="wi-box-footer"></div>
            </div>   
        </div>
        <div class="wi-color">
            <div class="wi-header">Farbe individuell w&auml;hlen</div>
            <div class="wi-box">
                <div class="wi-box-content">
                	<div id="wi-design-text">Sie wollen das Plugin individuell gestalten? Dann sind Sie hier richtig. Wählen Sie mit dem Farbwähler die Farben für Rahmen, Hintergrund und Schrift.</div>
                	<div id="wi-color-picker">
                	<form method="post" action="">
                		<div id="wi-color-picker-border">
                			<div>Rahmen:</div> 
                			<div id="color-border" style="height:20px;width:20px;background:<?php echo $color_border;?>" class="wi-picker-color"></div>
                			<input name="color-border" type="hidden" value="<?php echo $color_border;?>"> 
                		</div>
                		<div id="wi-color-picker-bg">
                			<div>Hintergrund:</div>
                			<div id="color-bg" name="color-bg" style="height:20px;width:20px;background:<?php echo $color_bg;?>" class="wi-picker-color"></div>
                			<input name="color-bg" type="hidden" value="<?php echo $color_bg;?>"> 
                		</div>
                		<div id="wi-color-picker-font">
                			<div>Schrift:</div>
                			<div id="color-font" style="height:20px;width:20px;background:<?php echo $color_font;?>" class="wi-picker-color"></div>
                			<input name="color-font" type="hidden" value="<?php echo $color_font;?>"> 
                		</div> 

                	</div>
                	
                		<input class="hidden" name="flag-custom" value="true">
                		<input class="wi-design-btn" type="submit" value="Übernehmen">
                	</form>
                </div>
                
               
		
			
	
	
					<script type="text/javascript">
					fixGradientImg();
					var currentColor = Colors.ColorFromRGB(64,128,128);
					new dragObject("arrows", "hueBarDiv", arrowsLowBounds, arrowsUpBounds, arrowsDown, arrowsMoved, endMovement);
					new dragObject("circle", "gradientBox", circleLowBounds, circleUpBounds, circleDown, circleMoved, endMovement);
					colorChanged('box');
					</script>

			
			
			
                <div class="wi-box-footer"></div>
            </div>   
        </div>
    </div>
	<?php
}



class wetterinfoGadget extends WP_Widget {
	
    function wetterinfoGadget() {
		$this->WP_Widget('wetterinfoGadget', 'wetter.info Wetter');
    }

    function widget($args, $instance) {	
    	if(get_option('wetterinfo_code_uni') != "") { 
    		$code_uni = get_option('wetterinfo_code_uni');
    	}else {
    		$code_uni = "K11000000";
    	}
    	$userdesign = get_option('wetterinfo_userdesign');
        extract($args, EXTR_SKIP);
        
        echo $before_widget;
		if($userdesign == "true"){
			
			$color_border = get_option('wetterinfo_user_border');
			$color_bg = get_option('wetterinfo_user_bg');
			$color_font = get_option('wetterinfo_user_font');
			
			echo '<div id="wi-weather-container" style="border:1px solid; border-color:'.$color_border.'; color:'.$color_font.'; background:'.$color_bg.';">';
		}

		echo '<div id="wetterinfo_data">&nbsp;</div>				
			  <script language="javascript" src="http://wiga.t-online.de/wetter/webgadgetWordpress/getWetterinfoTemplate.php?uni='.$code_uni.'"></script>';

		if($userdesign == "true"){
			echo '</div>';
		}
		echo $after_widget;
    }
	
	function form( $instance ) {
		?>
        
		<p>
			Stadt: <?php echo get_option('wetterinfo_city'); ?>
		</p>

		<p>
        	Weitere Einstellungen können <a href="plugins.php?page=wetterinfo-config">hier</a> vorgenommen werden.
        </p>	
		
	<?php
	}
}

?>