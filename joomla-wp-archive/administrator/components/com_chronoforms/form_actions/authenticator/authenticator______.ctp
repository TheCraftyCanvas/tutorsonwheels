<div class="dragable" id="cfaction_authenticator">Authenticator</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_authenticator_element">
	<label class="action_label" style="display: block; float:none!important;">Authenticator</label>
	<div id="cfactionevent_authenticator_{n}_allowed" class="form_event good_event">
		<label class="form_event_label">Allowed</label>
	</div>
	<div id="cfactionevent_authenticator_{n}_denied" class="form_event bad_event">
		<label class="form_event_label">Denied</label>
	</div>
	
	<input type="hidden" name="chronoaction[{n}][action_authenticator_{n}_groups]" id="action_authenticator_{n}_groups" value="<?php echo $action_params['groups']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_authenticator_{n}_guests]" id="action_authenticator_{n}_guests" value="<?php echo $action_params['guests']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="authenticator" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_authenticator_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'authenticator_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_authenticator_{n}_groups_config', array('type' => 'text', 'label' => "Allowed groups", 'class' => 'big_input', 'label_over' => true, 'smalldesc' => "Enter comma separated list of groups ids for groups allowed to proceed:
			18: Registered users<br />
			19: Authors<br />
			20: Editors<br />
			21: Publishers<br />
			23: Managers<br />
			24: Administrators<br />
			25: Super Administrators<br />
		")); ?>
		<?php echo $HtmlHelper->input('action_authenticator_{n}_guests_config', array('type' => 'select', 'label' => 'Allow guests', 'options' => array(0 => 'No', 1 => 'Yes'), 'class' => 'medium_input', 'smalldesc' => "Guests are non logged in users, choose wheather you want to allow them access or not.")); ?>
	
		<?php //echo $HtmlHelper->input('action_authenticator_{n}_content1_config', array('type' => 'textarea', 'label' => "Code", 'rows' => 20, 'cols' => 70, 'smalldesc' => 'any code can be placed here, any PHP code should include the PHP tags.')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>Write which user groups should be allowed access.</li>
				<li>Insert next form events in the "Allowed" event or insert "Show stopper" in the "Denied" event to halt the form.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>