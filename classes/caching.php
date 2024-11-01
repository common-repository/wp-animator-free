<?php
namespace animator;

class Caching{
    public $use_storage=false;
    protected $elements=array();
    private $period=0;
    public function __construct($use=true) {
        $this->period=2*DAY_IN_SECONDS; //2 day
        $this->use_storage= $use;
        add_action("animator_file_updated", array($this,"clear_cache_file_update"));
        add_action("wp_ajax_animator_clear_cache", array($this,"ajax_clear_cache"));
    }
    public function update_data(){
        update_option('animator_use_storage',false);
    }
    public function storage_data(){
        update_option('animator_use_storage',true);
    }
    protected function get_curent_url() {
        global $wp;

        $current_url =  home_url( $wp->request );
        $position = strpos( $current_url , '/page' );
        $nopaging_url = ( $position ) ? substr( $current_url, 0, $position ) : $current_url;

        return trailingslashit( $nopaging_url );
    }
    protected function get_key($str){
        return md5($str);
    }
    public function get_curent_key(){
        return $this->get_key($this->get_curent_url());
    }
    public function  set_data($key,$data){
         set_transient($key, $data, $this->period );
         $this->storage_data();
    }
    protected function get_data($key){
        return get_transient($key);
    }
    public function check_transient($key){
        if(!$this->use_storage){
            return false;
        }
        return $this->get_data($key);
    }
    public function clear_cache($key){
        delete_transient($key);
    }
    public function clear_cache_file_update(){
        $this->clear_cache("animator_elements_data");
    }
    public function ajax_clear_cache(){
        if(isset($_POST['animator_clear_cache_key'])){
            $cache_key= sanitize_text_field($_POST['animator_clear_cache_key']);
            $this->clear_cache($cache_key);
        }
        die();
    }
}