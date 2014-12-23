<?php

function lolint_shortcode( $atts ) {
	// Initialize variables
	global $wpdb;
	$returnatts='';
	
	// Create array from $atts
	$a=shortcode_atts( array(
		'champion'=>null,
		'spell'=>null,
		'summonerspell'=>null,
		'rune'=>null,
		'item'=>null,
		'mastery'=>null,
		'size'=>null
		), $atts );
		
	// To display items in order
	$keys=array_keys($atts);
	
	// Create an array for size to use later
	if ($a['size']!=null)
		$sizearray=explode(',',$a['size']);  
	
	// Variable to count making size make sense
	$count=0;
	
	// Parse the array using keys in proper order
	foreach ($keys as $key=>$value){
		if ($value!='size' && isset($a[$value])){
			$b=explode(',',$a[$value]);
	
			// Process each potential value
			foreach ($b as $k=>$v){
				
				if ($value=='summonerspell')
					$value='spell';
					
				// If no custom found then use default
				if (!isset($sizearray[$count]) || $sizearray[$count]==''){$sizearray[$count]=constant('LOLINT_'.strtoupper($value).'ICON_W');}
		
				// Force only lower case letters and query db for a match
				$formattedatts = preg_replace('/[^a-z]/i', '', strtolower($v));
				$lolquery = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".constant('LOLINT_'.strtoupper($value))." WHERE simplified_name='%s'",$formattedatts), ARRAY_A);
		
				// If there is a match then add the image code to the return variable
				if (isset($lolquery['lolkey']) && ($value=='champion' || $value=='spell')){
					$returnatts .= '<img src="'.LOLINT_PLUGIN_URL.'images/'.$value.'icons/'.$lolquery['lolkey'].'.png" width="'.$sizearray[$count].'">';
				}
				if (isset($lolquery['id']) && ($value=='mastery' || $value=='item')){
					$returnatts .= '<img src="'.LOLINT_PLUGIN_URL.'images/'.$value.'icons/'.$lolquery['id'].'.png" width="'.$sizearray[$count].'">';
				}
				if (isset($lolquery['image']) && $value=='rune'){
					$returnatts .= '<img src="'.LOLINT_PLUGIN_URL.'images/'.$value.'icons/'.$lolquery['image'].'" width="'.$sizearray[$count].'">';
				}
			$count++;
			}
		}
	}
	
	return $returnatts;
}
add_shortcode( 'lolint', 'lolint_shortcode' );

// Everything below here is currently deprecated - Still works for now just not as well as the above code
function lolint_champion( $atts ) {
	// Initialize variables
	global $wpdb;
	$returnatts='';
	
	// Combine the array together and then split by commas
	$atts=implode('',$atts);
	$atts=explode(',',$atts);
	
	// Process each potential value
	foreach ($atts as $k=>$v){
		$w='';
		// Check for custom width
		if (stristr($v, '-')!==false){
			$varray=explode('-',$v);
			$v=$varray[0];
			$w=$varray[1];
		}
		
		// If no custom found then use default
		if (!isset($w) || $w==''){$w=LOLINT_CHAMPIONICON_W;}
		
		// Force only lower case letters and query db for a match
		$formattedatts = preg_replace('/[^a-z]/i', '', strtolower($v));
		$lolquery = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".LOLINT_CHAMPION." WHERE simplified_name='%s'",$formattedatts), ARRAY_A);
		
		// If there is a match then add the image code to the return variable
		if (isset($lolquery['lolkey'])){
			$returnatts .= '<img src="'.LOLINT_PLUGIN_URL.'images/championicons/'.$lolquery['lolkey'].'.png" width="'.$w.'">';
		}
	}
	return $returnatts;
}
add_shortcode( 'champion', 'lolint_champion' );

function lolint_summonerspell( $atts ) {
	// Initialize variables
	global $wpdb;
	$returnatts='';
	
	// Combine the array together and then split by commas
	$atts=implode('',$atts);
	$atts=explode(',',$atts);
	
	// Process each potential value
	foreach ($atts as $k=>$v){
		$w='';
		// Check for custom width
		if (stristr($v, '-')!==false){
			$varray=explode('-',$v);
			$v=$varray[0];
			$w=$varray[1];
		}
		
		// If no custom found then use default
		if (!isset($w) || $w==''){$w=LOLINT_SPELLICON_W;}
		
		// Force only lower case letters and query db for a match
		$formattedatts = preg_replace('/[^a-z]/i', '', strtolower($v));
		$lolquery = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".LOLINT_SPELL." WHERE simplified_name='%s'",$formattedatts), ARRAY_A);
		
		// If there is a match then add the image code to the return variable
		if (isset($lolquery['lolkey'])){
			$returnatts .= '<img src="'.LOLINT_PLUGIN_URL.'images/spellicons/'.$lolquery['lolkey'].'.png" width="'.$w.'">';
		}
	}
	return $returnatts;
}
add_shortcode( 'summonerspell', 'lolint_summonerspell' );

function lolint_item( $atts ) {
	// Initialize variables
	global $wpdb;
	$returnatts='';
	
	// Combine the array together and then split by commas
	$atts=implode('',$atts);
	$atts=explode(',',$atts);
	
	// Process each potential value
	foreach ($atts as $k=>$v){
		$w='';
		// Check for custom width
		if (stristr($v, '-')!==false){
			$varray=explode('-',$v);
			$v=$varray[0];
			$w=$varray[1];
		}
		
		// If no custom found then use default
		if (!isset($w) || $w==''){$w=LOLINT_ITEMICON_W;}
		
		// Force only lower case letters and query db for a match
		$formattedatts = preg_replace('/[^a-z]/i', '', strtolower($v));
		$lolquery = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".LOLINT_ITEM." WHERE simplified_name='%s'",$formattedatts), ARRAY_A);
		
		// If there is a match then add the image code to the return variable
		if (isset($lolquery['id'])){
			$returnatts .= '<img src="'.LOLINT_PLUGIN_URL.'images/itemicons/'.$lolquery['id'].'.png" width="'.$w.'">';
		}
	}
	return $returnatts;
}
add_shortcode( 'item', 'lolint_item' );

function lolint_mastery( $atts ) {
	// Initialize variables
	global $wpdb;
	$returnatts='';
	
	// Combine the array together and then split by commas
	$atts=implode('',$atts);
	$atts=explode(',',$atts);
	
	// Process each potential value
	foreach ($atts as $k=>$v){
		$w='';
		// Check for custom width
		if (stristr($v, '-')!==false){
			$varray=explode('-',$v);
			$v=$varray[0];
			$w=$varray[1];
		}
		
		// If no custom found then use default
		if (!isset($w) || $w==''){$w=LOLINT_MASTERYICON_W;}
		
		// Force only lower case letters and query db for a match
		$formattedatts = preg_replace('/[^a-z]/i', '', strtolower($v));
		$lolquery = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".LOLINT_MASTERY." WHERE simplified_name='%s'",$formattedatts), ARRAY_A);
		
		// If there is a match then add the image code to the return variable
		if (isset($lolquery['id'])){
			$returnatts .= '<img src="'.LOLINT_PLUGIN_URL.'images/masteryicons/'.$lolquery['id'].'.png" width="'.$w.'">';
		}
	}
	return $returnatts;
}
add_shortcode( 'mastery', 'lolint_mastery' );

function lolint_rune( $atts ) {
	// Initialize variables
	global $wpdb;
	$returnatts='';
	
	// Combine the array together and then split by commas
	$atts=implode('',$atts);
	$atts=explode(',',$atts);
	
	// Process each potential value
	foreach ($atts as $k=>$v){
		$w='';
		// Check for custom width
		if (stristr($v, '-')!==false){
			$varray=explode('-',$v);
			$v=$varray[0];
			$w=$varray[1];
		}
		
		// If no custom found then use default
		if (!isset($w) || $w==''){$w=LOLINT_RUNEICON_W;}
		
		// Force only lower case letters and query db for a match
		$formattedatts = preg_replace('/[^a-z]/i', '', strtolower($v));
		$lolquery = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".LOLINT_RUNE." WHERE simplified_name='%s'",$formattedatts), ARRAY_A);
		
		// If there is a match then add the image code to the return variable
		if (isset($lolquery['image'])){
			$returnatts .= '<img src="'.LOLINT_PLUGIN_URL.'images/runeicons/'.$lolquery['image'].'" width="'.$w.'">';
		}
	}
	return $returnatts;
}
add_shortcode( 'rune', 'lolint_rune' );
?>