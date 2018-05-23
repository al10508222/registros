<?php
require_model('registrov.php');

class registrov_ingreso extends fs_controller {
    //put your code here
    public $registrov;
    public $listado; 
    public $listado2; 
    public $agente;

   public function __construct()
   {
      parent::__construct(__CLASS__, 'Agregar Registro', 'Vehículos', TRUE, TRUE);
   }
   
   protected function private_core()
   {
      $this->registrov = new registrov();
      $this->agente = new agente();

      if( isset($_POST['id']) )
      {
          $bo = new registrov();
          $bo->id = $bo->get_new_codigo();
          $bo->marca   = $_POST['marca'];
          $bo->modelo   = $_POST['modelo'];
          $bo->anio   = $_POST['anio'];
          $bo->color   = $_POST['color'];
          $bo->placas   = $_POST['placas'];
          $bo->id_vehiculo   = $_POST['id_vehiculo'];
          $bo->codagente   = $_POST['ncodagente'];


          
         if( $bo->save() )
         {
          //si se guarda lo redireccionamos a la pagina que muestra todos los registrov
            header('Location: index.php?page=registrov_mostrar');
         }
         else
            $this->new_error_msg("Imposible guardar!");
      }
      else if( isset($_GET['delete']) )
      {
         $bo = $this->registrov->get($_GET['delete']);
         if($bo)
         {
            if( FS_DEMO )
            {
               $this->new_error_msg('En el modo <b>demo</b> no se pueden eliminar.');
            }
            else if( $bo->delete() )
            {
               $this->new_message("Registro ".$bo->id." eliminado correctamente.");
            }
            else
               $this->new_error_msg("Imposible eliminar!");
         }
         else
            $this->new_error_msg("Registro no encontrado!");
      }
      
   }
}
