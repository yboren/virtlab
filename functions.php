<?php
    function check_form($form){
        if(!get_magic_quotes_gpc()){
            //if magic_quotes is not set,then should add slashes to
            //user given variable
            for($i=0;$i<count($form);$i++){
                $form[$i] = addslashes($form[$i]);
            }
        }
        return $form;
    }
?>
