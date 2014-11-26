<?php
/**
* @title		Shape 5 Quick Contact
* @version		1.0
* @package		Joomla
* @website		http://www.shape5.com
* @copyright	Copyright (C) 2009 Shape 5 LLC. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$spamtext_s5_qc		= $params->get( 'spamtext' );
$pretext_s5_qc		= $params->get( 'pretext' );
$email_address = $params->get( 'adminemail' );
$subject_prefix = $params->get( 'subjectprefix' );
$nametext_s5_qc = $params->get( 'nametext' );
$emailtext_s5_qc = $params->get( 'emailtext' );
$subjecttext_s5_qc = $params->get( 'subjecttext' );
$subjectspacer = " ";
$bodytext_s5_qc = $params->get( 'bodytext' );
$sendtext_s5_qc = $params->get( 'sendtext' );
$width_s5_qc = $params->get( 'width' );
$height_s5_qc = $params->get( 'height' );
$thankyou_s5_qc = $params->get( 'thankyou' );
$emailerror_s5_qc = $params->get( 'emailerror' );
$spamerror_s5_qc = $params->get( 'spamerror' );
$notcomplete_s5_qc = $params->get( 'notcomplete' );
$ex1 = $params->get( 'ex1', 's5_qc_null');
$ex2 = $params->get( 'ex2', 's5_qc_null');
$ex3 = $params->get( 'ex3', 's5_qc_null');
$ex4 = $params->get( 'ex4', 's5_qc_null');
$ex5 = $params->get( 'ex5', 's5_qc_null');
$ex6 = $params->get( 'ex6', 's5_qc_null');
$ex7 = $params->get( 'ex7', 's5_qc_null');
$ex8 = $params->get( 'ex8', 's5_qc_null');
$ex9 = $params->get( 'ex9', 's5_qc_null');
$ex10 = $params->get( 'ex10', 's5_qc_null');
$ex11 = $params->get( 'ex11', 's5_qc_null');
$ex12 = $params->get( 'ex12', 's5_qc_null');
$ex13 = $params->get( 'ex13', 's5_qc_null');
$ex14 = $params->get( 'ex14', 's5_qc_null');
$ex15 = $params->get( 'ex15', 's5_qc_null');
$ex16 = $params->get( 'ex16', 's5_qc_null');
$ex17 = $params->get( 'ex17', 's5_qc_null');
$ex18 = $params->get( 'ex18', 's5_qc_null');
$ex19 = $params->get( 'ex19', 's5_qc_null');
$ex20 = $params->get( 'ex20', 's5_qc_null');
$ex21 = $params->get( 'ex21', 's5_qc_null');
$ex22 = $params->get( 'ex22', 's5_qc_null');
$ex23 = $params->get( 'ex23', 's5_qc_null');
$ex24 = $params->get( 'ex24', 's5_qc_null');
$ex25 = $params->get( 'ex25', 's5_qc_null');
$ex26 = $params->get( 'ex26', 's5_qc_null');
$ex27 = $params->get( 'ex27', 's5_qc_null');
$ex28 = $params->get( 'ex28', 's5_qc_null');
$ex29 = $params->get( 'ex29', 's5_qc_null');
$ex30 = $params->get( 'ex30', 's5_qc_null');

jimport( 'joomla.application.component.view');

require(JModuleHelper::getLayoutPath('mod_s5_quick_contact'));

?>