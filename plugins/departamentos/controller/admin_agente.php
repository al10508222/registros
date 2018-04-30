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

    private function modificar()
    {
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


            $this->agente->f_alta = NULL;
            if ($_POST['f_alta'] != '') {
                $this->agente->f_alta = $_POST['f_alta'];
            }

            $this->agente->f_baja = NULL;
            if ($_POST['f_baja'] != '') {
                $this->agente->f_baja = $_POST['f_baja'];
            }

            // $this->agente->seg_social = $_POST['seg_social'];
            // $this->agente->banco = $_POST['banco'];
            // $this->agente->porcomision = floatval($_POST['porcomision']);

            if ($this->agente->save()) {
                $this->new_message("Datos del empleado guardados correctamente.");
            } else {
                $this->new_error_msg("¡Imposible guardar los datos del empleado!");
            }
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
