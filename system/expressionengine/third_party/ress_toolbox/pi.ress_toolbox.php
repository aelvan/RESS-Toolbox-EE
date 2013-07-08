<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * RESS Toolbox Plugin - Creates a cookie which contains the screen size detected by javascript. Utility functions to retreive optimal image sizes.
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @version		1.0
 * @author		André Elvan
 * @link		  https://github.com/aelvan/RESS-Toolbox-EE
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 */


$plugin_info = array(
  'pi_name' => 'RESS Toolbox',
  'pi_version' => '1.0',
  'pi_author' => 'André Elvan',
  'pi_author_url' => 'http://www.andreelvan.net',
  'pi_description' => '',
  'pi_usage' => Ress_toolbox::usage()
  );

class Ress_toolbox {

	public function __construct()
	{
		$this->EE =& get_instance();
	}

	public function cookie() {
		if(!isset($_COOKIE['resolution']))
		{
			return "<script>
				// Set a cookie to test if they're enabled
				document.cookie = 'testcookie=true';
				cookiesEnabled = (document.cookie.indexOf('testcookie')!=-1)? true : false;

				document.cookie='resolution='+Math.max(screen.width,screen.height)+('devicePixelRatio' in window ? ','+devicePixelRatio : ',1')+'; path=/';
				
				// Only reload if cookies are enabled
				if (cookiesEnabled)
				{
					date = new Date();
					date.setDate(date.getDate() -1);
					// Delete test cookie
					document.cookie = 'testcookie=;expires=' + date;
					location.reload(true);
				}
			</script>";
		}
	}

	public function calc() {
		$in_size = $this->EE->TMPL->fetch_param('size', '960');
		$subtract = $this->EE->TMPL->fetch_param('subtract', '0');
		$retina = $this->EE->TMPL->fetch_param('retina', 'no');

		$settings_query = $this->EE->db->select('settings')->where('class', 'Ress_toolbox_ext')->limit(1)->get('extensions');
		$settings = ($settings_query->num_rows() > 0) ? @unserialize($settings_query->row()->settings) : array() ; 

		$resolution_val = $settings['debug_mode']=='y' ? ($settings['debug_size']) : (!empty($_COOKIE['resolution']) ? $_COOKIE['resolution'] : $settings['fallback_size']);
		$t = explode(',', $resolution_val);
		$screensize = $t[0];
		$density = $t[1];

		$out_size = min((int)$in_size, (int)$screensize-(int)$subtract);

		if ($retina=='yes') {
			$out_size = $out_size*(float)$density;
		}

		return $out_size;
	}


	// --------------------------------------------------------------------
	/**
	 * Usage
	 *
	 * This function describes how the plugin is used.
	 *
	 * @access	public
	 * @return	string
	 */
	  public static function usage()
	  {
	  ob_start();
	  ?>
		Documentation at https://github.com/aelvan/RESS-Toolbox-EE
	  <?php
	  $buffer = ob_get_contents();

	  ob_end_clean();

	  return $buffer;
	  }
	  // END

}

/* End of file pi.ress_toolbox.php */
/* Location: ./system/expressionengine/third_party/ress_toolbox/pi.ress_toolbox.php */