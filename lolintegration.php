<?php
/**
* Plugin Name: League of Legends Integration
* Description: A quick way to integrate the League of Legends API into any Wordpress website.
* Version: 0.1
* Author: Bruce Lance
* License: GPL2
*/
class lolint {
	
	private static $instance;
	
	// Main function to setup the class
	public static function instance() {
		
		// Checks to see if a current instance of the class is found before creating a new one
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof lolint ) ) {
			
			// Create new instance of class and run the functions to build it
			self::$instance = new lolint;
			self::$instance->init();
			self::$instance->includes();
	
			register_activation_hook( __FILE__, 'lolint_activation' );
			add_action('admin_init', 'lolint_redirect');
		}

		// Returns either a fresh instance if created or the original if already existed
		return self::$instance;
	}
	
	// Initializes all of the globals
	public function init(){
		global $wpdb;
		
		/* Define Constants */
		// Champions Table
		if ( ! defined( 'LOLINT_CHAMPION' ) )
			define( 'LOLINT_CHAMPION', $wpdb->prefix."lolint_champions" );
		
		// Items Table
		if ( ! defined( 'LOLINT_ITEM' ) )
			define( 'LOLINT_ITEM', $wpdb->prefix."lolint_items" );
		
		// Runes Table
		if ( ! defined( 'LOLINT_RUNE' ) )
			define( 'LOLINT_RUNE', $wpdb->prefix."lolint_runes" );
		
		// Masteries Table
		if ( ! defined( 'LOLINT_MASTERY' ) )
			define( 'LOLINT_MASTERY', $wpdb->prefix."lolint_masteries" );
		
		// Summoner Spell Table
		if ( ! defined( 'LOLINT_SPELL' ) )
			define( 'LOLINT_SPELL', $wpdb->prefix."lolint_summoners" );
			
		// API_Key
		if ( ! defined( 'LOLINT_API_KEY' ) ) 
			define( 'LOLINT_API_KEY', get_option('lolint_api_key') );
				
		// Champion Icon Width
		if ( ! defined( 'LOLINT_CHAMPIONICON_W' ) ) 
			define( 'LOLINT_CHAMPIONICON_W', get_option('lolint_championicon_width') );
			
		// Summoner Spell Icon Width
		if ( ! defined( 'LOLINT_SPELLICON_W' ) ) 
			define( 'LOLINT_SPELLICON_W', get_option('lolint_spellicon_width') );
			
		// Mastery Icon Width
		if ( ! defined( 'LOLINT_MASTERYICON_W' ) ) 
			define( 'LOLINT_MASTERYICON_W', get_option('lolint_masteryicon_width') );
			
		// Rune Icon Width
		if ( ! defined( 'LOLINT_RUNEICON_W' ) ) 
			define( 'LOLINT_RUNEICON_W', get_option('lolint_runeicon_width') );
			
		// Item Icon Width
		if ( ! defined( 'LOLINT_ITEMICON_W' ) ) 
			define( 'LOLINT_ITEMICON_W', get_option('lolint_itemicon_width') );
				
		// Region
		if ( ! defined( 'LOLINT_REGION' ) ) 
			define( 'LOLINT_REGION', get_option('lolint_region') );
				
		// Static Data Link
		if ( ! defined( 'LOLINT_STATIC_DATA' ) )
			define( 'LOLINT_STATIC_DATA', 'https://global.api.pvp.net/api/lol/static-data/'.LOLINT_REGION.'/v1.2/');
		
		// Version
		if ( ! defined( 'LOLINT_VERSION' ) ) {
			$this->getversiondata = wp_remote_get(LOLINT_STATIC_DATA.'versions?api_key='.LOLINT_API_KEY);
			$versionjson=json_decode($this->getversiondata['body'],'ASSOC_A');
			define( 'LOLINT_VERSION', $versionjson[0] );
		}		
		
		// Plugin version
		if ( ! defined( 'LOLINT_PLUGIN_VS' ) )
			define( 'LOLINT_PLUGIN_VS', '0.1' );

		// Plugin Folder Path
		if ( ! defined( 'LOLINT_PLUGIN_DIR' ) )
			define( 'LOLINT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

		// Plugin Folder URL
		if ( ! defined( 'LOLINT_PLUGIN_URL' ) )
			define( 'LOLINT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

		// Plugin Root File
		if ( ! defined( 'LOLINT_PLUGIN_FILE' ) )
			define( 'LOLINT_PLUGIN_FILE', __FILE__ );

		// Test filter
		add_filter('the_content',array($this,'lolint_test'));
		
	}
	
	// Requires the additional files
	function includes(){
		require_once(LOLINT_PLUGIN_DIR.'shortcode/shortcode.php');
		require_once(LOLINT_PLUGIN_DIR.'admin/admin.php');
		require_once(LOLINT_PLUGIN_DIR.'admin/activation.php');
		
	}
	
	// Test Function - Will be removed in actual release
	function lolint_test($thecontent){
		//$this->getdata = wp_remote_get('https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/Dwarve?api_key='.LOLINT_API_KEY);
		//$thecontent.=var_dump($retrievedarray);
		//$thecontent.=var_dump($retrievedarray);
		//$thecontent.='INSERT INTO '.$wpdb->prefix.'lolint_champions VALUES ('.$retrievedarray[$k]->id.','.$retrievedarray[$k]->key.','.$retrievedarray[$k]->name.','.$retrievedarray[$k]->title.','.$simplified.');<br>';
		//$thecontent.='<img width="25px" src="http://ddragon.leagueoflegends.com/cdn/4.20.1/img/champion/'.$k.'.png">';
		//$thecontent.=var_dump();
		//foreach ($retrieved as $k=>$v){
		//	$thecontent.="\n".$k.'=>'.$v;
		//}
		//$this->getchampiondata = wp_remote_get('https://global.api.pvp.net/api/lol/static-data/na/v1.2/versions?api_key='.LOLINT_API_KEY);
		//$championjson=json_decode($this->getchampiondata['body']);
		//$thecontent.=$championjson[0];
		//$this->lolint_item=json_decode(get_option('lolint_item'));
		//$thevars=get_object_vars($this->lolint_item);
		//foreach ($this->lolint_item as $k=>$v){
		//	$thecontent.=$this->lolint_item->$k->name;
		//	break;
		//}
		/*$thecontent.=var_dump($this->lolint_item);
		$lolint_getrunedata = wp_remote_get('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/data/en_US/rune.json');
		$lolint_runejson=json_decode($lolint_getrunedata['body']);
		$dataarray=get_object_vars($lolint_runejson->data);
		ksort($dataarray);
	
		//var_dump($lolint_runejson['data']);
		foreach ($dataarray as $k=>$v){
			$storejson=json_encode($dataarray[$k]->stats);
			$simplifiedname=strtolower(preg_replace('/[^a-z]/i', '', $dataarray[$k]->name));
			$inputarray=array('id'=>$k,'description'=>$dataarray[$k]->description,'name'=>$dataarray[$k]->name,'image'=>$dataarray[$k]->image->full,'tier'=>$dataarray[$k]->rune->tier,'type'=>$dataarray[$k]->rune->type,'stats'=>$storejson,'simplified_name'=>$simplified);
			$thecontent.=$k.' '.$simplifiedname.' '.$dataarray[$k]->name.' '.$dataarray[$k]->description.' '.$dataarray[$k]->image->full.' '.$dataarray[$k]->rune->tier.' '.$dataarray[$k]->rune->type.' '.$storejson.'<br />';
			break;
		}
		//lolint_data('rune',$lolint_runejson->data);
		*/
		return $thecontent;
	}
	
	
}	

// Highlander function to force a single instance
function lolint() {
	return lolint::instance();
}

// Initialization call and allows the class to be assigned to a variable by Example: $lolint=lolint();
lolint();