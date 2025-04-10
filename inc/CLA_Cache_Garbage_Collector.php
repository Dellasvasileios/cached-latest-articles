<?php

class CLA_Cache_Garbage_Collector{

        private $CLA_FileManager;
        private $cache_dir;

        public function __construct($cache_dir,$CLA_FileManager){
                
                $this->cache_dir = $cache_dir;
                $this->CLA_FileManager = $CLA_FileManager;
        
        }
        
        function clear_unused_cache_files($options){
            
            if($options == null || empty($options)){
                return;
            }

            $cache_files_names = $this->CLA_FileManager->get_all_file_names_on_dir($this->cache_dir);

            if (empty($cache_files_names)){
                return;
            }

            

            foreach($cache_files_names as $cache_file_name){
                $file_exists = false;
                foreach($options as $option){
                     
                        if(strpos($cache_file_name, $option["id"])){   
                                $file_exists = true;
                                break;
                        }
                }

                if(!$file_exists){
                        $this->CLA_FileManager->delete_file($this->cache_dir . $cache_file_name);
                }
                

            }
        }
}