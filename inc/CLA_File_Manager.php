<?php
class CLA_File_Handler{
    

    function update_file($full_path , $content){  

        if(!is_string($full_path)){
            throw new Exception("Parameter is not a string as expected.");
        }
        
        if(empty($full_path)){
            throw new Exception("File path is empty.");
        }
        
        file_put_contents($full_path, json_encode($content));
        
    }

    function delete_file($full_path){
        
        if(!is_string($full_path)){
            throw new Exception("Parameter is not a string as expected.");
        }

        if(empty($full_path)){
            throw new Exception("File path is empty.");
        }

        if(file_exists($full_path)){
            unlink($full_path);
        }
        return true;

    }

    function get_all_file_names_on_dir($path){
        
        if(!is_string($path)){
            throw new Exception("Parameter is not a string as expected.");
        }

        if(empty($path)){
            throw new Exception("File path is empty.");
        }

        $files = scandir($path);
        $files = array_diff($files, array('.', '..'));
        return $files;

    }

}