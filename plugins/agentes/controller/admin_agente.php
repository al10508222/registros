<?php


require_model('agente.php');

class admin_agente extends fs_controller
{

    public $agente;

    public function __construct()
    {
        parent::__construct(__CLASS__, 'Agentes', 'Agentes', FALSE, FALSE);
    }

    protected function private_core()
    {
        parent::private_core();

        $this->ppage = $this->page->get('admin_agentes');
        $this->allow_delete = $this->user->admin;
        $this->agente = FALSE;
        if (isset($_GET['cod'])) {
            $agente = new agente();
            $this->agente = $agente->get($_GET['cod']);
            //$this->allow_delete = $this->agente->delete();  
        }

        if ($this->agente) {
            $this->page->title .= ' ' . $this->agente->codagente;

            if (isset($_POST['nombre'])) {
                if ($this->user_can_edit()) {
                    $this->modificar();
                } else {
                    $this->new_error_msg('No tienes permiso para modificar estos datos.');
                }
            }
        } else {
            $this->new_error_msg("Empleado no encontrado.", 'error', FALSE, FALSE);
        }
    }

    private function modificar(){
        if ($this->agente) {
        $this->agente->nombre = $_POST['nombre'];
        $this->agente->apellidos = $_POST['apellidos'];
        $this->agente->dnicif = $_POST['dnicif'];
        $this->agente->telefono = $_POST['telefono'];
        $this->agente->email = $_POST['email'];
        $this->agente->dependencia = $_POST['dependencia'];
        $this->agente->ubicacion = $_POST['ubicacion'];
        $this->agente->situacion = $_POST['situacion'];
        $this->agente->movil = $_POST['movil'];
        $this->agente->tag = $_POST['tag'];
        $this->agente->grado = $_POST['grado'];
        $this->agente->especialidad = $_POST['especialidad'];
        $this->agente->extension = $_POST['extension'];
        
        
        
        $this->agente->f_alta = NULL;
        if ($_POST['f_alta'] != '') {
            $this->agente->f_alta = $_POST['f_alta'];
        }
        
        $this->agente->f_baja = NULL;
        if ($_POST['f_baja'] != '') {
            $this->agente->f_baja = $_POST['f_baja'];
        }
        //aqui vamos a colocar la imagen
        
        if ($this->agente->save()) {
            if(is_uploaded_file($_FILES['nombre_image']['tmp_name'])){
                //Definir nombres
                $nombre=$_FILES['nombre_image']['name'];
                $nombre=strtolower($nombre);
                $tipo=$_FILES['nombre_image']['type'];
                $tipo=strtolower($tipo);
                $size=$_FILES['nombre_image']['size'];
                $error=$_FILES['nombre_image']['error'];
                $extension=substr($tipo,strpos($tipo,'/')+1);
                
                $lugar='plugins/registros/infantiles/';
                //Fin de definir nombres
                $ruta = "'".$lugar.$this->agente->codagente.".jpg'"; 
                
                if(!empty($nombre) && isset($nombre)){
                    if($error==0){
                        if(strpos($tipo,'gif') || strpos($tipo,'jpg') || strpos($tipo,'jpeg') || strpos($tipo,'bmp') || strpos($tipo,'png')){
                            if($size<819200){
                                if(move_uploaded_file($_FILES['nombre_image']['tmp_name'],$lugar.$this->agente->codagente.'.jpg')){
                                    
                                    //comprobar si ya existe imagen para el e mpleado
                                    
                                    $query = $this->db->select('SELECT * FROM image_agente WHERE codagente = '.$this->agente->codagente.';');
                                    if($query){
                                         $sql = 'UPDATE image_agente SET imagen = '.$ruta.' WHERE codagente = '.$this->agente->codagente.''; 
                                    }else{
                                         $sql = 'INSERT INTO image_agente (imagen, codagente)VALUES('.$ruta.', '.$this->agente->codagente.')'; 
                                    
                                    }
                                    $this->db->exec($sql); 
                                }
                            }
                        }
                        }else{
                            $this->new_error_msg('Imagen demasiado grande.');
                        }
                    }else{
                        $this->new_error_msg('Formato incorrecto.');
                    }
                }else{
                    $this->new_error_msg('No se selecciono una foto para el empleado.');
                }
            }else{
                $this->new_error_msg('La imagen no se subio bien.');
            }
            $this->new_message("Datos del empleado guardados correctamente.");
        } else {
            $this->new_error_msg("¡Imposible guardar los datos del empleado!");
        }
    }
    
    private function user_can_edit()
    {
        if (FS_DEMO) {
            return ($this->user->codagente == $this->agente->codagente);
        }
        
        return TRUE;
    }
    
    public function url()
    {
        if (!isset($this->agente)) {
            return parent::url();
        } else if ($this->agente) {
            return $this->agente->url();
        }
        
        return $this->page->url();
    }
}

