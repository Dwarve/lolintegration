<?php

function lolint_add_menu(){
	// Place an admin menu
	$page = add_menu_page("LoL Integration" , "LoL Integration", "manage_options", "lolint", "lolint_admin", "", "42.314" );

}

add_action( 'admin_menu', 'lolint_add_menu' );

function lolint_admin(){
	global $wpdb;
	
	// Settings update function
	if (isset($_POST['lolintupdate']) && $_POST['lolintupdate']=='update')
		$updatemsg=lolint_save_settings();	
	
	// Check to see if an update was ran
	if (isset($_GET['lolintdata'])){

		// Update the static databases
		if ($_GET['lolintdata']=='runupdate'){
			
			$updatemsg=lolint_get_static();
			
		}else{
		
			// Else update the specified icon sections
			$updatemsg=lolint_update_icons($_GET['lolintdata']);
		}
	}
	
	if (isset($_GET['lolintmsg']))
		$updatemsg=base64_decode($_GET['lolintmsg']);
		
		?>
	
	<div class="wrap">
		<h2>LoL Integration Settings <a href="?page=lolint&lolintdata=runupdate" class="add-new-h2">Update LoL Static Data</a></h2>
		
		<?php // Update message display
			if (isset($updatemsg) && $updatemsg!=''){?>
			<div id="message" class="updated below-h2">
				<p>
					<?php echo $updatemsg;?>
				</p>
			</div>
		<?php }?>
		
		<form action="?page=lolint" id="lolint_settings_form" method="post"><input type="hidden" name="page" value="lolint"><input type="hidden" name="lolintupdate" value="update">
			<div id="welcome-panel" class="welcome-panel">
				<div class="welcome-panel-content">
					<table class="form-table">
						<tr>
						<th scope="row"><label for="lolint_api_key">API Key</label></th>
						<td><input name="lolint_api_key" type="text" id="lolint_api_key" value="<?php echo get_option('lolint_api_key');?>" class="regular-text" /></td>
						</tr>
						
						<tr>
						<th scope="row"><label for="lolint_region">Region</label></th>
						<td><select name="lolint_region" id="lolint_region">
								<option value="br"<?php if (get_option('lolint_region')=='br'){echo ' SELECTED';}?>>BR</option>
								<option value="eune"<?php if (get_option('lolint_region')=='eune'){echo ' SELECTED';}?>>EUNE</option>
								<option value="euw"<?php if (get_option('lolint_region')=='euw'){echo ' SELECTED';}?>>EUW</option>
								<option value="kr"<?php if (get_option('lolint_region')=='kr'){echo ' SELECTED';}?>>KR</option>
								<option value="lan"<?php if (get_option('lolint_region')=='lan'){echo ' SELECTED';}?>>LAN</option>
								<option value="las"<?php if (get_option('lolint_region')=='las'){echo ' SELECTED';}?>>LAS</option>
								<option value="na"<?php if (get_option('lolint_region')=='na'){echo ' SELECTED';}?>>NA</option>
								<option value="oce"<?php if (get_option('lolint_region')=='oce'){echo ' SELECTED';}?>>OCE</option>
								<option value="tr"<?php if (get_option('lolint_region')=='tr'){echo ' SELECTED';}?>>TR</option>
								<option value="ru"<?php if (get_option('lolint_region')=='ru'){echo ' SELECTED';}?>>RU</option>
							</select></td>
						</tr>
						
						<tr>
						<th scope="row"><label for="lolint_championicon_width">Champion Icon Width</label></th>
						<td><input type="text" name="lolint_championicon_width" id="lolint_championicon_width" value="<?php echo get_option('lolint_championicon_width');?>" class="small-text" />
						<a href="?page=lolint&lolintdata=champion" class="add-new-h2">Update Champion Icons</a></td>
						</tr>
						
						<tr>
						<th scope="row"><label for="lolint_spellicon_width">Summoner Spell Icon Width</label></th>
						<td><input type="text" name="lolint_spellicon_width" id="lolint_spellicon_width" value="<?php echo get_option('lolint_spellicon_width');?>" class="small-text" />
						<a href="?page=lolint&lolintdata=spell" class="add-new-h2">Update Spell Icons</a></td>
						</tr>
						
						<tr>
						<th scope="row"><label for="lolint_itemicon_width">Item Icon Width</label></th>
						<td><input type="text" name="lolint_itemicon_width" id="lolint_itemicon_width" value="<?php echo get_option('lolint_itemicon_width');?>" class="small-text" />
						<a href="?page=lolint&lolintdata=item" class="add-new-h2">Update Item Icons</a></td>
						</tr>
						
						<tr>
						<th scope="row"><label for="lolint_masteryicon_width">Mastery Icon Width</label></th>
						<td><input type="text" name="lolint_masteryicon_width" id="lolint_masteryicon_width" value="<?php echo get_option('lolint_masteryicon_width');?>" class="small-text" />
						<a href="?page=lolint&lolintdata=mastery" class="add-new-h2">Update Mastery Icons</a></td>
						</tr>
						
						<tr>
						<th scope="row"><label for="lolint_runeicon_width">Rune Icon Width</label></th>
						<td><input type="text" name="lolint_runeicon_width" id="lolint_runeicon_width" value="<?php echo get_option('lolint_runeicon_width');?>" class="small-text" />
						<a href="?page=lolint&lolintdata=rune" class="add-new-h2">Update Rune Icons</a></td>
						</tr>
						
						<tr>
						<th scope="row" colspan="2"><input class="button-primary menu-save" id="lolint_save_button" name="lolint_save_button" type="submit" value="Save Settings" /></th>
						</tr>
					</table>				
				</div>
			</div>
		</form>
	</div>
	
	<?php
}

// Admin Save Settings Function
function lolint_save_settings(){
	global $wpdb;
	
	// Return Var
	$returnme = '';
	
	// Quick way to do my update 
	foreach ($_POST as $k=>$v){
		
		// All form element names start with this so only ones that changed will be updated and show the updated message
		if (stristr($k,'lolint_')!==false) {
			
			$updatestatus=update_option( $k, $v );
			
			if ($returnme!='')
				$returnme.=' | ';
				
			if ($updatestatus!==false)
				$returnme.=$k.' Updated';
				
		}
	}
	
	return $returnme;
}

// Retrieve Static Data Function
function lolint_get_static(){
	global $wpdb;
		
	// Input Champion Data
	$lolint_getchampiondata = wp_remote_get(LOLINT_STATIC_DATA.'champion?api_key='.LOLINT_API_KEY);
	$lolint_championjson=json_decode($lolint_getchampiondata['body']);
	//var_dump($lolint_championjson->data);
	lolint_data('champion',$lolint_championjson->data);
		
	// Input Mastery Data
	$lolint_getmasterydata = wp_remote_get(LOLINT_STATIC_DATA.'mastery?api_key='.LOLINT_API_KEY);
	$lolint_masteryjson=json_decode($lolint_getmasterydata['body']);
	lolint_data('mastery',$lolint_masteryjson->data);
		
	// Input Rune Data
	$lolint_getrunedata = wp_remote_get('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/data/en_US/rune.json');
	$lolint_runejson=json_decode($lolint_getrunedata['body']);
	//var_dump($lolint_runejson->data);
	lolint_data('rune',$lolint_runejson->data);
		
	// Input Summoner Spell Data
	$lolint_getsummonerdata = wp_remote_get(LOLINT_STATIC_DATA.'summoner-spell?api_key='.LOLINT_API_KEY);
	$lolint_summonerjson=json_decode($lolint_getsummonerdata['body']);
	lolint_data('spell',$lolint_summonerjson->data);
		
	// Input Item Data
	$lolint_getitemdata = wp_remote_get(LOLINT_STATIC_DATA.'item?api_key='.LOLINT_API_KEY);
	$lolint_itemjson=json_decode($lolint_getitemdata['body']);
	lolint_data('item',$lolint_itemjson->data);
		
	return 'Static data updated';
}
	
// Update Icon Function
function lolint_update_icons($type,$name=null){
	global $wpdb;
	
	// If name is null then pull names from database else just run the named icon
	if ($name==null){
		
		// If statements ensure type is acceptable. Each is pulling the correct column from the db to keep the file names the same.
		if ($type=='champion'){
			$lolimages=$wpdb->get_results( "SELECT lolkey FROM ".constant('LOLINT_'.strtoupper($type)) );
			foreach ($lolimages as $lolimage){
				copy('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/img/'.strtolower($type).'/'.$lolimage->lolkey.'.png',LOLINT_PLUGIN_DIR.'images/'.strtolower($type).'icons/'.$lolimage->lolkey.'.png');
			}
			return 'Updated '.$type.' icons.';
		}
		if ($type=='item'){
			$lolimages=$wpdb->get_results( "SELECT id FROM ".constant('LOLINT_'.strtoupper($type)) );
			foreach ($lolimages as $lolimage){
				copy('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/img/'.strtolower($type).'/'.$lolimage->id.'.png',LOLINT_PLUGIN_DIR.'images/'.strtolower($type).'icons/'.$lolimage->id.'.png');
			}
			return 'Updated '.$type.' icons.';
		}
		if ($type=='mastery'){
			$lolimages=$wpdb->get_results( "SELECT id FROM ".constant('LOLINT_'.strtoupper($type)) );
			foreach ($lolimages as $lolimage){
				copy('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/img/'.strtolower($type).'/'.$lolimage->id.'.png',LOLINT_PLUGIN_DIR.'images/'.strtolower($type).'icons/'.$lolimage->id.'.png');
			}
			return 'Updated '.$type.' icons.';
		}
		if ($type=='rune'){
			$lolimages=$wpdb->get_results( "SELECT image FROM ".constant('LOLINT_'.strtoupper($type)) );
			foreach ($lolimages as $lolimage){
				copy('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/img/'.strtolower($type).'/'.$lolimage->image,LOLINT_PLUGIN_DIR.'images/'.strtolower($type).'icons/'.$lolimage->image);
			}
			return 'Updated '.$type.' icons.';
		}
		if ($type=='spell'){
			$lolimages=$wpdb->get_results( "SELECT lolkey FROM ".constant('LOLINT_'.strtoupper($type)) );
			foreach ($lolimages as $lolimage){
				copy('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/img/'.strtolower($type).'/'.$lolimage->lolkey.'.png',LOLINT_PLUGIN_DIR.'images/'.strtolower($type).'icons/'.$lolimage->lolkey.'.png');
			}
			return 'Updated '.$type.' icons.';
		}
	
		return 'Type Incorrect - Nothing Updated.';
	
	}else{
		// Copy the remote image files to local server
		copy('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/img/'.$type.'/'.$name.'.png',LOLINT_PLUGIN_DIR.'images/'.$type.'icons/'.$name.'.png');
	
		// Masteries have a greyed out version as well
		if ($type=='mastery')
			copy('http://ddragon.leagueoflegends.com/cdn/'.LOLINT_VERSION.'/img/'.$type.'/gray_'.$name.'.png',LOLINT_PLUGIN_DIR.'images/'.$type.'icons/gray_'.$name.'.png');
	
	}	
}
	
// Update or Insert Data Function
function lolint_data($type,$data){
	global $wpdb;
		
	// Turn the outer shell into an array and sort it
	$dataarray=get_object_vars($data);
	ksort($dataarray);
	//var_dump($dataarray);
	
	// Run through the array
	foreach($dataarray as $k=>$v){
		
		// Need a simplified name to make the shortcode more logical
		$simplified = preg_replace('/[^a-z]/i', '', strtolower($dataarray[$k]->name));
			
		// Build the input/update array depending on type
		if ($type=='champion')
			$inputarray=array('id'=>$dataarray[$k]->id,'lolkey'=>$dataarray[$k]->key,'name'=>$dataarray[$k]->name,'title'=>$dataarray[$k]->title,'simplified_name'=>$simplified);
				
		if ($type=='mastery')
			$inputarray=array('id'=>$dataarray[$k]->id,'description'=>json_encode($dataarray[$k]->description),'name'=>$dataarray[$k]->name,'simplified_name'=>$simplified);
			
		if ($type=='rune')
			$inputarray=array('id'=>$k,'description'=>$dataarray[$k]->description,'name'=>$dataarray[$k]->name,'image'=>$dataarray[$k]->image->full,'tier'=>$dataarray[$k]->rune->tier,'type'=>$dataarray[$k]->rune->type,'stats'=>json_encode($dataarray[$k]->stats),'simplified_name'=>$simplified);
			
		if ($type=='spell')
			$inputarray=array('id'=>$dataarray[$k]->id,'description'=>$dataarray[$k]->description,'name'=>$dataarray[$k]->name,'lolkey'=>$dataarray[$k]->key,'summonerlevel'=>$dataarray[$k]->summonerLevel,'simplified_name'=>$simplified);
			
		if ($type=='item'){
			if (!isset($dataarray[$k]->plaintext))
				$dataarray[$k]->plaintext=null;
			if (!isset($dataarray[$k]->description))
				$dataarray[$k]->description=null;
			if (!isset($dataarray[$k]->group))
				$dataarray[$k]->group=null;
				
			$inputarray=array('id'=>$dataarray[$k]->id,'plaintext'=>$dataarray[$k]->plaintext,'description'=>$dataarray[$k]->description,'name'=>$dataarray[$k]->name,'lolgroup'=>$dataarray[$k]->group,'simplified_name'=>$simplified);
		}
		
		// Check the db for an element that has the same simple name
		$dbcheck = $wpdb->get_results("SELECT * FROM ".constant('LOLINT_'.strtoupper($type))." WHERE simplified_name='".$simplified."'");
		if ( $dbcheck ) {
		
			// If one exists then update it
			$wpdb->update(constant('LOLINT_'.strtoupper($type)),$inputarray,array( 'simplified_name' => $simplified ));
			
		} else {
		
			// If one does not then create it
			$wpdb->insert(constant('LOLINT_'.strtoupper($type)),$inputarray);
		}
		
		// Runes use a different image naming convention so this fixes the issue
		if ($type=='rune')
			$k=str_replace('.png','',$dataarray[$k]->image->full);
		
		// Call the icon function each time to get the icons
		// Old version updated icons and data but the time to update all icons was too much for one page load.
		// If you want the full update by clicking the update static button then uncomment the next line but load time will be approx 5 mins.
		//lolint_update_icons($type,$k);
	}
}
?>