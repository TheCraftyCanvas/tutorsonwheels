<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
class CfactionJoomlaUserSave{
	var $formname;
	var $formid;
	var $group = array('id' => 'joomla_functions', 'title' => 'Joomla Functions');
	var $events = array('success' => 0, 'fail' => 0);
	var $details = array('title' => 'Joomla User Create/Update', 'tooltip' => 'Create or Update a Joomla user record.');
	var $params = null;
	
	function run($form, $actiondata){
		$this->params = $params = new JParameter($actiondata->params);
		$mainframe =& JFactory::getApplication();
		//set activation link
		if(trim($this->params->get('activation_link', '')) == ''){
			$this->params->set('activation_link', 'index.php?option=com_users&task=registration.activate');
		}
		// Get required system objects
		$user 		= clone(JFactory::getUser());
		$pathway 	=& $mainframe->getPathway();
		$config		=& JFactory::getConfig();
		$authorize	=& JFactory::getACL();
		$document   =& JFactory::getDocument();
		$language =& JFactory::getLanguage();
        $language->load('com_users');

		// Initialize new usertype setting
		$usersConfig = JComponentHelper::getParams('com_users');
		// Default to Registered.
		$defaultUserGroup = $params->get('new_usertype', '');
		if(empty($defaultUserGroup)){
			if(trim($params->get('new_usertype_field', ''))){
				$posted_groups_field = $params->get('new_usertype_field', '');
				if(isset($form->data[$posted_groups_field]) && !empty($form->data[$posted_groups_field])){
					if(!is_array($form->data[$posted_groups_field])){
						$form->data[$posted_groups_field] = array($form->data[$posted_groups_field]);
					}
					$defaultUserGroup = $form->data[$posted_groups_field];
				}
			}else{
				$defaultUserGroup = $userConfig->get('new_usertype', 'Registered');
			}
		}else{
			$_groups = explode(",", trim($defaultUserGroup));
			$defaultUserGroup = array();
			foreach($_groups as $_group){
				$defaultUserGroup[] = (int)$_group;
			}
		}
		
		//set the post fields values
		$form->data['name'] = $form->data[$params->get('name', '')];
		$form->data['username'] = $form->data[$params->get('username', '')];
		$form->data['email'] = $form->data[$params->get('email', '')];
		$form->data['password'] = $form->data[$params->get('password', '')];
		$form->data['password2'] = $form->data[$params->get('password2', '')];
		if((bool)$params->get('enable_old_password', 0) === true){
			$form->data['old_password'] = $form->data[$params->get('old_password', '')];
		}else{
			$form->data['old_password'] = '';
		}
		
		//check empty fields
		$checks = array('name', 'username', 'email');
		foreach($checks as $check){
			if(!trim($form->data[$check])){
				$this->events['fail'] = 1;
				$form->validation_errors[$params->get($check)] = 'You must provide your '.$check.'.';
				//return false;
			}
		}
		if($this->events['fail'] == 1){
			return false;
		}
		//case create/update
		if($params->get('function', 0) == 0){
			//check if the password is empty
			if(!trim($form->data['password'])){
				$this->events['fail'] = 1;
				$form->validation_errors[$params->get('password')] = 'You must provide a Password.';
				return false;
			}
			//check the 2 passwords
			if($form->data['password'] != $form->data['password2']){
				$this->events['fail'] = 1;
				$form->validation_errors[$params->get('password2')] = 'Passwords do NOT match.';
				$form->debug[] = "Couldn't create/update user, Passwords do NOT match.";
				return false;
			}
		}else if($params->get('function', 0) == 1){	
			if(!$user->get('id')){
				$this->events['fail'] = 1;
				$form->validation_errors[] = 'No users logged in.';
				$form->debug[] = "Couldn't get logged in user data.";
				return false;
			}else{
				$form->data['id'] = $user->get('id');
			}
			//user is updating his own record
			if(trim($form->data['old_password']) || trim($form->data['password']) || trim($form->data['password2'])){
				//some password field has been changed, make sure they are correct
				//check the 2 passwords
				if($form->data['password'] != $form->data['password2']){
					$this->events['fail'] = 1;
					$form->validation_errors[$params->get('password2')] = 'Passwords do NOT match.';
					$form->debug[] = "Couldn't create/update user, Passwords do NOT match.";
					return false;
				}
				//chek old password
				if((bool)$params->get('enable_old_password', 0) === true){
					//print_r2($user);
					$parts = explode(":", $user->get('password'));
					$salt = $parts[1];
					$enc_pass = md5($form->data['old_password'].$salt).":".$salt;
					if($enc_pass != $user->get('password')){
						$this->events['fail'] = 1;
						$form->validation_errors[$params->get('old_password')] = 'Wrong password entered.';
						$form->debug[] = "Old password has been entered incorrectly.";
						return false;
					}else{
						//check if the password is empty
						if(!trim($form->data['password']) || !trim($form->data['password2'])){
							$this->events['fail'] = 1;
							$form->validation_errors[$params->get('password')] = 'Please enter a new password.';
							return false;
						}
					}
				}
				
			}else{
				unset($form->data['old_password']);
				unset($form->data['password']);
				unset($form->data['password2']);
			}
		}
		// Bind the post array to the user object
		$post_data = $form->data;
		if(!$user->bind($post_data)){
			//JError::raiseError( 500, $user->getError());
			$this->events['fail'] = 1;
			$form->validation_errors[] = $user->getError();
			$form->debug[] = "Couldn't bind new user, Joomla returned this error : ".$user->getError();
			return false;
		}

		if($params->get('function', 0) == 0){
			// Set some initial user values
			if(!isset($form->data['id']) || empty($form->data['id'])){
				$user->set('id', 0);
				$user->set('usertype', $defaultUserGroup);
				$user->set('gid', $authorize->get_group_id('', $defaultUserGroup, 'ARO'));

				$date =& JFactory::getDate();
				$user->set('registerDate', $date->toMySQL());
			}else{
				$user->set('id', (int)$form->data['id']);
			}			
		}
		// If there was an error with registration, set the message and display form
		if(!$user->save()){
			/*JError::raiseWarning('', JText::_( $user->getError()));
			$this->register();*/
			$this->events['fail'] = 1;
			$form->validation_errors[] = $user->getError();
			$form->debug[] = "Couldn't save user, Joomla returned this error : ".$user->getError();
			return false;
		}else{
			$this->events['success'] = 1;
		}
		//store user data
		$user_data = (array)$user;
		$removes = array('params', '_params', 'guest', '_errorMsg', '_errors');
		foreach($removes as $remove){
			unset($user_data[$remove]);
		}
		$form->data['_PLUGINS_']['joomla_user_save'] = $user_data;		
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'name' => '',
				'username' => '',
				'email' => '',
				'password' => '',
				'password2' => '',
				'old_password' => '',
				'new_usertype' => '',
				'new_usertype_field' => '',
				'function' => 0,
				'enable_old_password' => 0,
			);
		}
		return array('action_params' => $action_params);
	}
}
?>