<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Loader
 *
 * @author Simon Emms
 */
class MY_Loader extends CI_Loader {





    /**
     * Autoreloader
     *
     * Allows anything in the autoload file be included if a new
     * controller has access it.
     *
     * @access	private
     * @param	array
     * @return	void
     */
    function _ci_autoreloader()
    {
            /* The only difference is here - include() instead of include_once() */
            include(APPPATH.'config/autoload'.EXT);

            if ( ! isset($autoload))
            {
                    return FALSE;
            }

            // Autoload packages
            if (isset($autoload['packages']))
            {
                    foreach ($autoload['packages'] as $package_path)
                    {
                            $this->add_package_path($package_path);
                    }
            }

            // Load any custom config file
            if (count($autoload['config']) > 0)
            {
                    $CI =& get_instance();
                    foreach ($autoload['config'] as $key => $val)
                    {
                            $CI->config->load($val);
                    }
            }

            // Autoload helpers and languages
            foreach (array('helper', 'language') as $type)
            {
                    if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
                    {
                            $this->$type($autoload[$type]);
                    }
            }

            // A little tweak to remain backward compatible
            // The $autoload['core'] item was deprecated
            if ( ! isset($autoload['libraries']) AND isset($autoload['core']))
            {
                    $autoload['libraries'] = $autoload['core'];
            }

            // Load libraries
            if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
            {
                    // Load the database driver.
                    if (in_array('database', $autoload['libraries']))
                    {
                            $this->database();
                            $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
                    }

                    // Load all other libraries
                    foreach ($autoload['libraries'] as $item)
                    {
                            /* Give the reloaded library a new name to force reloading */
                            $this->library($item, null, $item);
                    }
            }

            // Autoload models
            if (isset($autoload['model']))
            {
                    $this->_ci_models = array();
                    $this->model($autoload['model']);
            }
    }
}
?>