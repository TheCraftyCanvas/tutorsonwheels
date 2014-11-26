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

jimport('joomla.application.component.model');


class GooglesModelGoogle extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the hello identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function &getData()
	{
	
		
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__googlemap '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->greeting = null;
			$this->_data->apiKey = null;
			$this->_data->mapHeight = null;
			$this->_data->mapWidth = null;
			$this->_data->mapEmail = null;
			$this->_data->mapFax = null;
			$this->_data->mapTp = null;
			$this->_data->mapPhone = null;
			$this->_data->mapAddress = null;
			$this->_data->mapLatitude = null;
			$this->_data->mapLongitude = null;
			
			$this->_data->mapViewHeight = null;
			$this->_data->mapView = null;
			$this->_data->mapPointImg = null;
			$this->_data->companyVideoProfile = null;
			$this->_data->companySpamcheck = null;
			
			
			$this->_data->map3dWidth = null;
			$this->_data->map3dHeight = null;
			$this->_data->map3dview = null;
			$this->_data->mapYaw = null;
			$this->_data->mapPitch = null;
			
			
			$this->_data->mapEmailTxtBox = null;
			$this->_data->mapFaxTxtBox = null;
			$this->_data->mapTpTxtBox = null;
			$this->_data->mapPhoneTxtBox = null;
			$this->_data->defaultTxtBox = null;
			$this->_data->defaultTxt = null;
			
			$this->_data->mapComInfoForm = null;
			$this->_data->mapContForm = null;
			$this->_data->mapGoogleForm = null;
			$this->_data->mapDGoogleForm = null;
			$this->_data->mapVideoForm = null;
			
			
			$this->_data->mapEnterYourNameForm = null;
			$this->_data->mapEnterEmailForm = null;
			$this->_data->mapEnterSubForm = null;
			$this->_data->mapEnterMessForm = null;
			$this->_data->mapEnterSpameForm = null;
			$this->_data->mapEnterEmailCopForm = null;
			$this->_data->mapEnterBtnForm = null;			
			
			$this->_data->mapWidthOfForm = null;
			$this->_data->thanksTxt = null;
			
			$this->_data->moreDBox = null;
			$this->_data->placeDBox = null;
			
			$this->_data->adminMailAdress = null;
			
		}
		return $this->_data;
	}

	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{	
	
					
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}

	/**
	 * Method to delete record(s)
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

}