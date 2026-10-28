<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function form_ckeditor($data) {
    $data['language'] = isset($data['language']) ? $data['language'] : 'en';

    $size = isset($data['width']) ? 'width: "' . $data['width'] . '", ' : '';
    $size .= isset($data['height']) ? 'height: "' . $data['height'] . '", ' : '';

    $options = "{" . $size .
            "language: '{$data["language"]}',
            startupOutlineBlocks: true,
            entities: false,
            entities_latin: false,
            entities_greek: false,
            forcePasteAsPlainText: false,
            extraPlugins: 'uploadimage',
            removePlugins: 'easyimage,cloudservices',
            
            uploadUrl: '" . base_url("plugins/") . "ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
            filebrowserBrowseUrl: '" . base_url("plugins/") . "ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl: '" . base_url("plugins/") . "ckfinder/ckfinder.html?type=Images',
            filebrowserUploadUrl: '" . base_url("plugins/") . "ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: '" . base_url("plugins/") . "ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images', 
            
            toolbar: [{
          name: 'document',
          items: ['Print']
        },
        {
          name: 'clipboard',
          items: ['Undo', 'Redo']
        },
        {
          name: 'styles',
          items: ['Format', 'Font', 'FontSize']
        },
        {
          name: 'colors',
          items: ['TextColor', 'BGColor']
        },
        {
          name: 'align',
          items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
        },
        '/',
        {
          name: 'basicstyles',
          items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting']
        },
        {
          name: 'links',
          items: ['Link', 'Unlink']
        },
        {
          name: 'paragraph',
          items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
        },
        {
          name: 'insert',
          items: ['Image', 'Table']
        },
        {
          name: 'tools',
          items: ['Maximize']
        },
        {
          name: 'editing',
          items: ['Scayt']
        }
      ],
        }";

    return
            "<script type='text/javascript' src='" . base_url() . "application/plugins/ckeditor/ckeditor.js'></script>
             <script type='text/javascript' src='" . base_url() . "application/plugins/ckfinder/ckfinder.js'></script>
            <script type='text/javascript'>var editor = CKEDITOR.replace('{$data['id']}', $options);CKFinder.setupCKEditor( editor );</script>";
}

function cke_initialize($data = array()) {

    $return = '';

    if (!defined('CI_CKEDITOR_HELPER_LOADED')) {

        define('CI_CKEDITOR_HELPER_LOADED', TRUE);
        $return = '<script type="text/javascript" src="' . base_url() . $data['path'] . '/ckeditor.js"></script>';
        $return .= "<script type=\"text/javascript\">CKEDITOR_BASEPATH = '" . base_url() . $data['path'] . "/';</script>";
    }

    return $return;
}

function cke_create_instance($data = array()) {

    $return = "<script type=\"text/javascript\">
     	CKEDITOR.replace('" . $data['id'] . "', {";

//Adding config values
    if (isset($data['config'])) {
        foreach ($data['config'] as $k => $v) {

// Support for extra config parameters
            if (is_array($v)) {
                $return .= $k . " : [";
                $return .= config_data($v);
                $return .= "]";
            } else {
                $return .= $k . " : '" . $v . "'";
            }

            if ($k !== end(array_keys($data['config']))) {
                $return .= ",";
            }
        }
    }

    $return .= '});</script>';

    return $return;
}

function display_ckeditor($data = array()) {
// Initialization
    $return = cke_initialize($data);

// Creating a Ckeditor instance
    $return .= cke_create_instance($data);


// Adding styles values
    if (isset($data['styles'])) {

        $return .= "<script type=\"text/javascript\">CKEDITOR.addStylesSet( 'my_styles_" . $data['id'] . "', [";


        foreach ($data['styles'] as $k => $v) {

            $return .= "{ name : '" . $k . "', element : '" . $v['element'] . "', styles : { ";

            if (isset($v['styles'])) {
                foreach ($v['styles'] as $k2 => $v2) {

                    $return .= "'" . $k2 . "' : '" . $v2 . "'";

                    if ($k2 !== end(array_keys($v['styles']))) {
                        $return .= ",";
                    }
                }
            }

            $return .= '} }';

            if ($k !== end(array_keys($data['styles']))) {
                $return .= ',';
            }
        }

        $return .= ']);';

        $return .= "CKEDITOR.instances['" . $data['id'] . "'].config.stylesCombo_stylesSet = 'my_styles_" . $data['id'] . "';
		</script>";
    }

    return $return;
}

function config_data($data = array()) {
    $return = '';
    foreach ($data as $key) {
        if (is_array($key)) {
            $return .= "[";
            foreach ($key as $string) {
                $return .= "'" . $string . "'";
                if ($string != end(array_values($key)))
                    $return .= ",";
            }
            $return .= "]";
        } else {
            $return .= "'" . $key . "'";
        }
        if ($key != end(array_values($data)))
            $return .= ",";
    }
    return $return;
}

?>