<?php


class EditorDraft{
	
	var $slug = 'pl-draft';
	
	function __construct( ){
		
		if( current_user_can('edit_themes') )
			$this->mode = 'draft';
		else 
			$this->mode = 'live';
			
			
	}

	function save_draft( $data ){
		
		print_r($data);
		// update global option [draft]
		// update type option [draft]
		// update local option [draft]
		
		if( isset($data['pageData']['global']) )
			pl_settings_update( $data['pageData']['global'], 'draft');
		
		if( isset($data['pageData']['type']) )
			pl_settings_update( $data['pageData']['type'], 'draft', $data['typeID'] );
		
		if( isset($data['pageData']['local']) && $data['pageID'] != $data['typeID'])
			pl_settings_update( $data['pageData']['local'], 'draft', $data['pageID'] );
		
		
		// update map [draft]
		
		// check and set draft states
		
	}

	function publish( $data, EditorMap $map ){
		
		$this->reset_state( $data['pageID'] );
		
		$map->publish_map( $data['pageID'] );
		
		
	}
	
	function revert( $data, EditorMap $map ){
		$revert = $data['revert'];
		$pageID = $data['page'];
	
		if( $revert == 'local' || $revert == 'all')
			$this->revert_local($pageID, $map);
			
		if( $revert == 'global' || $revert == 'all')
			$this->revert_global($map);
		
		
	}
	
	function revert_local( $pageID, $map ){
		$map->revert_local( $pageID );
		pl_meta_update( $pageID, $this->slug, false );
	}
	
	function revert_global( $map ){
		$map->revert_global( );
		pl_opt_update( $this->slug, false );
	}

	function get_state( $pageID ){
		
		$state = array();
		
		if( pl_meta( $pageID, $this->slug ) )
			$state[] = 'local';
		
		if( pl_opt( $this->slug ) )
			$state[] = 'global';
	
		if(empty($state))
			return 'clean';
		else 
			return join('-', $state);
		
	}
	
	function reset_state( $pageID ){
		pl_meta_update( $pageID, $this->slug, false );
		pl_opt_update( $this->slug, false );
	}

	function set_local( $pageID, $val = true ){
		pl_meta_update( $pageID, $this->slug, $val );
	}
	
	function set_global( $val = true ){
		pl_opt_update( $this->slug, $val );
	}
	
}