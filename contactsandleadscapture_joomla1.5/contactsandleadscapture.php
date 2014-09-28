<?php
/*------------------------------------------------------------------------
# Contacts and Leads Capture 1.1 - February, 2009
# ------------------------------------------------------------------------
# Copyright (C) 2009 Source Creativity, All Rights Reserved.
# @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
# Author: Source Creativity
# Websites:  http://www.sourcecreativity.com
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgContactContactsandLeadsCapture extends JPlugin {

	function plgContactContactsandLeadsCapture( & $subject, $config ) {
		parent::__construct( & $subject, $config );
		
	    // load plugin parameters
       $this->_plugin = JPluginHelper::getPlugin( 'contact', 'contactsandleadscapture' );
       $this->_params = new JParameter( $this->_plugin->params );
	}


	function onSubmitContact( &$contact, &$post ) {
		
		// load plugin params info
		$activate	= $this->params->get('activate');
		
		$sugarcrmlivesite 	= $this->params->get('sugarcrmlivesite');
		$sugarusername	= $this->params->get('username');
		$sugarpassword	= md5($this->params->get('password'));
		$mode	= $this->params->get('mode');
		$msgfield = ($this->params->get('msgfield') == "") ? "description" : $this->params->get('msgfield');
		
		
		// include nusoap
		require_once( "lc_includes/mysoap.php" );
		
		 $name = $post['name'];
		 $email = $post['email'];
		 $subject = $post['subject'];
		 $body = $post['text'];
		 $msg = "Subject : ".$subject."\n\n Message : \n".$body;
		///////////////////////////////////////////////////////////////////////
		$nametab 	= explode( ' ', $name );
		$count 	= count( $nametab );
	
		// handles conversion of name entry into firstname, lastname distinction
		$firstname	= '';
		$lastname	= '';
	
		switch( $count ) {
			case 1:
				$lastname		= $nametab[0];
				break;
	

			default:
				$firstname 		= $nametab[0];
				for ( $i = 1; $i < $count; $i++ ) {
					$lastname	.= $nametab[$i] .' ';
				}
				break;
		}
		
		
		if($activate) {
			$mysoap = new MySoap();
			$conectsuccess = $mysoap->ConnectoAccount($sugarcrmlivesite, $sugarusername, $sugarpassword);
			if($conectsuccess)
				$inseredinsugar = $mysoap->insertItemsInSugarCRM( $mode, $firstname, $lastname, $email, $msg, $msgfield );
			}
		

	}

}
?>