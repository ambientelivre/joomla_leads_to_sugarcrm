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

require_once( "nusoap.php" );

class MySoap {

	var $sessionid;
	var $user_auth;
	var $user_guid;
	var $soapclient2;

	function ConnectoAccount($sugarcrmlivesite, $username, $password ) {
			
		$this->soapclient2 = new nusoap_client($sugarcrmlivesite.'/soap.php?wsdl',true);
		$this->user_auth = array(
					 'user_auth' => array(
					 'user_name' => $username,
					 'password' => $password,
					 'version' => "0.1"
						 ),
					 'application_name' => "contactsandleadscapture");
		 $result_array = $this->soapclient2->call('login',$this->user_auth);
	 	 
		 if( !$result_array['error']['number'] ) {
			 $this->sessionid =  $result_array['id'];
			 $this->user_guid = $this->soapclient2->call('get_user_id',$this->sessionid);
			 return true;
		 } else return false;
	 
	}
	
	function insertItemsInSugarCRM( $mode = 'Leads', $firstname, $lastname, $email, $msg, $msgfield = 'description' ) {

		
		$name_value_list = array(
								   array('name'=>'first_name','value'=>$firstname ),
								   array('name'=>'last_name','value'=>$lastname ),
								   array('name'=>'status', 'value'=>'New' ),
								   array('name'=>'email1', 'value'=>$email ),
								   array('name'=> $msgfield, 'value'=>$msg ),
								   array('name'=>'lead_source','value'=>'Web Site'),
								   array('name'=>'assigned_user_id', 'value'=>$this->user_guid));
							   
		$set_entry_params = array(
							   'session' => $this->sessionid,
							   'module_name' => $mode,
							   'name_value_list'=> $name_value_list);
							   
		$result = $this->soapclient2->call('set_entry',$set_entry_params);
		if($result) return true;
		else return false;
	}
}
?>