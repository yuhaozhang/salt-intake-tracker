<?php
define ('IS_ENV_PRODUCTION',false); //true for release , false for development

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors',!IS_ENV_PRODUCTION);
ini_set('error_log','log/phperror.txt');

if (get_magic_quotes_gpc()){
    function _stripslashes_rcurs($variable,$top=true){
        $clean_data = array();
        foreach ($variable as $key => $value){
            $key = ($top) ? $key : stripslashes($key);
            $clean_data[$key] = (is_array($value)) ? stripslashes_rcurs($value,false) : stripslashes($value);
        }
        return $clean_data;
    }
    $_GET = _stripslashes_rcurs($_GET);
    $_POST = _stripslashes_rcurs($_POST);
}
?>