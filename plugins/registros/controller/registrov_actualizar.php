<?php
require_model('registrov.php');
require_model('divisa.php'); 

class registrov_actualizar extends fs_controller{
    //put your code here
    public $registrov;
    public $divisa; 

    public function __construct()
   {
      parent::__construct(__CLASS__, 'Actualizar', 'registrov', FALSE, FALSE);
   }
   
   protected function private_core()
   {
      $this->ppage = $this->page->get('registrov_mostrar');
      $this->allow_delete = $this->user->allow_delete_on(__CLASS__);
      $this->registrov = new registrov(); 
      $this->registrov = FALSE;
      $this->divisa = new divisa(); 
      $this->agente = new agente();
      if( isset($_GET['id']) )
      {
         $registrov = new registrov();
         $this->registrov = $registrov->get($_GET['id']);
      }
      
      if($this->registrov)
      {
         $this->page->title .= ' ' . $this->registrov->id;
         
         if( isset($_POST['id']) )
         {
            if( $this->user_can_edit() )
            {
               
               $this->registrov->marca = $_POST['marca'];
               $this->registrov->modelo = $_POST['modelo'];
               $this->registrov->anio = $_POST['anio'];
               $this->registrov->color = $_POST['color'];
               $this->registrov->placas = $_POST['placas'];
               $this->registrov->id_vehiculo = $_POST['id_vehiculo'];
               $this->registrov->codagente = $_POST['ncodagente'];
                                                          
               if( $this->registrov->save() )
               {
                    for($i = 1; $i<=count($_FILES); $i++){
                        if(is_uploaded_file($_FILES['nombre_image']['tmp_name'])){
                            echo count($_FILES); 
                            //Definir nombres
                            $nombre=$_FILES['nombre_image']['name'];
                            $nombre=strtolower($nombre);
                            $tipo=$_FILES['nombre_image']['type'];
                            $tipo=strtolower($tipo);
                            $size=$_FILES['nombre_image']['size'];
                            $error=$_FILES['nombre_image']['error'];
                            $extension=substr($tipo,strpos($tipo,'/')+1);
                            
                            $lugar='plugins/registros/imagescars/';
                            //Fin de definir nombres
                            $ruta = "'".$lugar.$this->registrov->id_vehiculo."'"; 
                        
                        
                            if(!empty($nombre) && isset($nombre)){
                                if($error==0){
                                    if(strpos($tipo,'gif') || strpos($tipo,'jpg') || strpos($tipo,'jpeg') || strpos($tipo,'bmp') || strpos($tipo,'png')){
                                        if(move_uploaded_file($_FILES['nombre_image']['tmp_name'],$lugar.$this->registrov->id_vehiculo)){
                                            $id_v = "'".$this->registrov->id_vehiculo.$i."'"; 
                                            $sql = 'INSERT INTO image_vehiculo (imagen, id_vehiculo)VALUES('.$ruta.', '.$id_v.')'; 
                                            $this->db->exec($sql); 
                                            
                                        }
                                    }
                                }
                            }
                            $this->new_message("Datos actualizados correctamente.");
                        }
                    }
                
                    
            }
            else
               $this->new_error_msg('No tienes permiso para modificar estos datos.');
         }
      }
      else
         $this->new_error_msg("Registro No Encontrado.");
   }
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
   
   public function url()
   {
      if( !isset($this->registrov) )
      {
         return parent::url();
      }
      else if($this->registrov)
      {
         return $this->registrov->url();
      }
      else
         return $this->page->url();
   }

   public function image_user()
    {
        $data = $this->db->select("SELECT * FROM registrov WHERE id = '".$_GET['id']."';");
        if ($data) {
            foreach ($data as $u) {
                echo '<a href="'.$this->url().'">'.'<img src="data:image/jpg;base64,' .  base64_encode($u['foto'])  . '" height="100" width="100" align="center"/>'; 
            }
        }
    }

}
