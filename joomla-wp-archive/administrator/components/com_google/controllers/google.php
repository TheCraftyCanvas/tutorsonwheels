<?php
/**
 * Google  Map default controller
 * 
 * @package    Joomla.component
 * @subpackage Components
 * @link http://inetlanka.com
 * @license		GNU/GPL
 * @auth inetlanka web team - [ info@inetlanka.com / inetlankapvt@gmail.com ]
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Google Google Controller
 *
 * @package    Joomla.component
 * @subpackage Components
 */
class GooglesControllerGoogle extends GooglesController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'google' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('google');

		if ($model->store($post)) {
			$msg = JText::_( 'Google map Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Google map' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_google';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('google');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Google map Could not be Deleted' );
		} else {
			$msg = JText::_( ' Google map(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_google', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_google', $msg );
	}
}