<?php
require_model('registrov.php');

class registrov_mostrar extends fs_controller {
    //put your code here
    public $registrov;
    public $num_resultados; 
    public $resultado; 
    public $offset;
    public $orden;

   public function __construct()
   {
      parent::__construct(__CLASS__, 'Todos los registros', 'Vehículos', TRUE, TRUE);
   }
   
   protected function private_core()
   {
      $this->registrov = new registrov();
      $this->buscar(); 
      $this->buscar2(); 

 
      if( isset($_POST['id']) )
      {
          $em0 = new registrov();
          $em0->id = $em0->get_new_codigo();
          $em0->registrov = $_POST['marca'];
          $em0->registrov = $_POST['modelo']; 
          $em0->registrov = $_POST['anio']; 
          $em0->registrov = $_POST['color']; 
          $em0->registrov = $_POST['placas']; 
          $em0->registrov = $_POST['foto']; 
          $em0->registrov = $_POST['codagente']; 





         if( $em0->save() )
         {
            header('location: '.$em0->url());
         }
         else
            $this->new_error_msg("Imposible guardar!");

      }
      else if( isset($_GET['delete']) )
      {
         $em0 = $this->registrov->get($_GET['delete']);
         if($em0)
         {
            if( FS_DEMO )
            {
               $this->new_error_msg('En el modo <b>demo</b> no se pueden eliminar.');
            }
            else if( $em0->delete() )
            {
               $this->new_message("Registro ".$em0->id." eliminado correctamente.");
            }
            else
               $this->new_error_msg("Imposible eliminar!");
         }
         else
            $this->new_error_msg("Registro no encontrado!");
      }
      
   }

   private function buscar()
   {
      $this->total_resultados = 0;
      $query = mb_strtolower( $this->registrov->no_html($this->query), 'UTF8' );
      $sql = " FROM registrov";
      $and = ' WHERE ';
      
      if( is_numeric($query) )
      {
         $sql .= $and."(id LIKE '%".$query."%')";
         $and = ' AND ';
      }
      else
      {
         $buscar = str_replace('', '%', $query);
         $sql .= $and."(lower(marca) LIKE '%".$buscar."%'"
                 . " OR lower(modelo) LIKE '%".$buscar."%')";
      $and = ' AND ';
      }

      $data = $this->db->select("SELECT COUNT(id) as total".$sql.';');
      if($data)
      {
         $this->num_resultados = intval($data[0]['total']);
         
         $data2 = $this->db->select("SELECT *".$sql." ORDER BY anio asc".";");
    }
  }


  public function buscar2()
   {


      $this->total_resultados = 0;
      $query = mb_strtolower( $this->registrov->no_html($this->query), 'UTF8' );
      $sql = " FROM registrov";
      $and = ' WHERE ';
      
      if( is_numeric($query) )
      {
         $sql .= $and."(id LIKE '%".$query."%')";
         $and = ' AND ';
      }
      else
      {
         $buscar = str_replace('', '%', $query);
         $sql .= $and."(lower(marca) LIKE '%".$buscar."%'"
                 . " OR lower(modelo) LIKE '%".$buscar."%')";
      $and = ' AND ';
      }




      $data = $this->db->select("SELECT COUNT(id) as total".$sql.';');
      if($data)
      {
         $this->num_resultados = intval($data[0]['total']);
         

         
         $data2 = $this->db->select("SELECT *".$sql." ORDER BY marca asc".";");

         if($data2)
         {
              foreach($data2 as $d)
              {
                 $this->resultados[] = new registrov($d);
              }
        }
    }
  }
   
}

