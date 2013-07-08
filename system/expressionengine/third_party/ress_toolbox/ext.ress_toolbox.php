<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * RESS Toolbox Extension - Registers screensize variables as a globals
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @version		1.0
 * @author		André Elvan
 * @link		  https://github.com/aelvan/RESS-Toolbox-EE
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 */


class Ress_toolbox_ext {

	var $settings        = array();

	var $name            = 'RESS Toolbox';
	var $version         = '1.0';
	var $description     = '';
	var $settings_exist  = 'y';
	var $docs_url        = 'https://github.com/aelvan/RESS-Toolbox-EE';

	private $EE;

	/**
	 * Constructor
	 *
	 * @paramarray of settings
	 */
	function Ress_toolbox_ext($settings='')
	{
		$this->settings = $settings;
		$this->EE =& get_instance();    	// Make a local reference to the ExpressionEngine super object
	}

	/**
	 * Settings
	 */
	function settings()
	{
		$settings = array();

		$settings['fallback_size'] = array('i', '', '960,1');
		$settings['debug_mode'] = array('r', array('y' => "Yes", 'n' => "No"), 'n');
		$settings['debug_size'] = array('i', '', '960,1');

		return $settings;

	}

	/**
	 * Activate the extension
	 *
	 * This function is run on install and will register all hooks
	 *
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array(
			'fallback_size'   => '960,1',
			'debug_mode'   => 'n',
			'fallback_size'   => '960,1'
		);

		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'on_sessions_start',
			'hook'		=> 'sessions_start',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$this->EE->db->insert('extensions', $data);

	}

	// ----------------------------------------------------------------------

	/**
	 * on_sessions_start
	 *
	 * @param
	 * @return
	 */

	function on_sessions_start($ref)
	{
		$fallback_size = $this->settings['fallback_size'];
		$resolution_val = $this->settings['debug_mode']=='y' ? $this->settings['debug_size'] : (!empty($_COOKIE['resolution']) ? $_COOKIE['resolution'] : $fallback_size);
		$t = explode(',', $resolution_val);
		$screensize = $t[0];
		$density = $t[1];

		$this->EE->config->_global_vars['ress'] = $screensize;
		$this->EE->config->_global_vars['ress_raw'] = $resolution_val;
		$this->EE->config->_global_vars['ress_screensize'] = $screensize;
		$this->EE->config->_global_vars['ress_density'] = $density;
	}

// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
	}

	// ----------------------------------------------------------------------
}

/* End of file ext.ress.php */
/* Location: ./system/expressionengine/third_party/ress_toolbox/ext.ress_toolbox.php */