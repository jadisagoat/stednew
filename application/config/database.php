<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$active_group = 'default';
$active_record = TRUE;

// $db['default']['hostname'] = 'localhost';
// $db['default']['username'] = 'root';
// $db['default']['password'] = '';
// $db['default']['database'] = 'nadiq';

// $db['default']['hostname'] = 'localhost';
// $db['default']['username'] = 'root';
// $db['default']['password'] = '';
// $db['default']['database'] = 'tpointlc_mrayed';


// $db['default']['dbdriver'] = 'mysql';
// $db['default']['dbprefix'] = '';
// $db['default']['pconnect'] = TRUE;
// $db['default']['db_debug'] = TRUE;
// $db['default']['cache_on'] = FALSE;
// $db['default']['cachedir'] = '';
// $db['default']['char_set'] = 'utf8';
// $db['default']['dbcollat'] = 'utf8_general_ci';
// $db['default']['swap_pre'] = '';
// $db['default']['autoinit'] = TRUE;
// $db['default']['stricton'] = FALSE;





$db['default'] = array(
    'dsn' => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'digitek8_stedtraining',
    
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
