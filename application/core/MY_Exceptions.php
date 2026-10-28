<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Exceptions
 *
 * Extends the exceptions library.
 *
 * @author Simon Emms <simon@testpeople.co.uk>
 * @since 05-May-2011
 */
class MY_Exceptions extends CI_Exceptions {



    /**
     * Show 404
     *
     * Changed the behaviour of the 404 error page so
     * that it displays the 404 page.
     *
     * @param string $page
     * @param bool $log_error
     */
    function show_404($page = '', $log_error = TRUE) {
        $objRouter = &load_class('Router', 'core');
        $objConfig = &load_class('Config', 'core');

        /* Get the 404 method */
        $method = isset($objRouter->routes['_404']) ? $objRouter->routes['_404'] : '';

        /**
         * @todo make this work if the controller is valid
         */
        if(!empty($method)) {

            /* If the controller is valid, it's loaded by this point - hack around it */
            if(class_exists('CI_Controller')) {

                include(APPPATH.'controllers/'.$objRouter->routes['default_controller'].EXT);

                /* Load the default controller */
                $CI = new $objRouter->routes['default_controller'](true);

                /* Controller already loaded - have to reload autoloaded stuff */
                $CI->load->_ci_autoreloader();
                
            } else {

                /* Controller not loaded - load the given controller */
                require BASEPATH.'core/Controller'.EXT;

                if (file_exists(APPPATH.'core/'.$objConfig->config['subclass_prefix'].'Controller'.EXT))
                {
                        require APPPATH.'core/'.$objConfig->config['subclass_prefix'].'Controller'.EXT;
                }


                include(APPPATH.'controllers/'.$objRouter->routes['default_controller'].EXT);

                /* Log the error */
                if ($log_error) {
                    log_message('error', '404 Page Not Found --> '.$page);
                }

                /* Load the default controller */
                $CI = new $objRouter->routes['default_controller']();

            }

            /* Activate the method */
            $CI->$method();

        } else {
            /* Use the parent method */
            parent::show_404($page, $log_error);
        }
    }

}
?>