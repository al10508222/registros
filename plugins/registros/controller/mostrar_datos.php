<?php
require_model('registrov.php');
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
      
      $this->registrov = new registrov(); 
      $this->registrov = FALSE;
      $this->divisa = new divisa(); 
      $this->agente = new agente();
      if( isset($_GET['id']) )
      {
        $registrov = new registrov();
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
   


   public function image_user()
    {
        $data = $this->db->select("SELECT 
                                        rv.marca,
                                        rv.modelo,
                                        rv.anio,
                                        rv.color,
                                        rv.placas,
                                        rv.id_vehiculo,
                                        rv.codagente,
                                        rv.foto,
                                        a.nombre
                                    FROM registrov rv 
                                    LEFT JOIN agentes a USING(codagente)
                                    WHERE rv.id = '".$_GET['id']."';");
        if ($data) {
            foreach ($data as $u) {
                echo '<a href="'.$this->url().'">'.'<img src="data:image/jpg;base64,' .  base64_encode($u['foto'])  . '" height="100" width="100" align="center"/>'; 
            }
        }
    }

}
