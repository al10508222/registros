<?php
require_model('mostrar.php');
require_model('divisa.php'); 

class mostrar_datos extends fs_controller{
    //put your code here
    public $registrov;
    public $divisa; 

    public function __construct()
   {
      parent::__construct(__CLASS__, 'Actualizar', 'registrov', FALSE, FALSE);
   }
   
   protected function private_core()
   {
      
      $this->registrov = new mostrar(); 
      $this->registrov = FALSE;
      $this->divisa = new divisa(); 
      $this->agente = new agente();
      if( isset($_GET['id']) )
      {
        $registrov = new mostrar();
        $this->registrov = $registrov->get2($_GET['id']);
      }
      
      if($this->registrov)
      {
         $this->page->title .= ' ' . $this->registrov->id;
      }
      else
         $this->new_error_msg("Registro No Encontrado.");
   }
   
   private function user_can_edit()
   {
      if(FS_DEMO)
      {
         return ($this->user->id == $this->registrov->id);
      }
      else
      {
         return TRUE;
      }
   }
}
