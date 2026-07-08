<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// <!-- VERSI 1.1 : TEMPLATE TERBARU  -->
class Template {

    protected $CI;
    protected $template_data = [];

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function set($name, $value)
    {
        $this->template_data[$name] = $value;
    }

    public function load($template = '', $view = '', $view_data = [], $return = FALSE)
    {
        if (empty($template) || empty($view)) {
            show_error('Template or view is not defined');
        }

        // load view ke variable contents
        $this->template_data['contents'] = $this->CI->load->view($view, $view_data, TRUE);

        return $this->CI->load->view($template, $this->template_data, $return);
    }
}


// /* End of file Template.php */
// /* Location: ./system/application/libraries/Template.php */