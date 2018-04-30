<?php

namespace FacturaScripts\model;


class agente extends \fs_model
{
    public $codagente;
    public $dnicif;
    public $nombre;
    public $apellidos;
    public $email;
    public $telefono;
    public $f_alta;
    public $f_baja;
    public $dependencia; 
    public $ubicacion; 
    public $tag; 
    public $situacion; 
    public $movil; 

 

    public function __construct($data = FALSE)
    {
        parent::__construct('agentes');
        if ($data) {
            $this->codagente = $data['codagente'];
            $this->nombre = $data['nombre'];
            $this->apellidos = $data['apellidos'];
            $this->dnicif = $data['dnicif'];
            $this->email = $data['email'];
            $this->telefono = $data['telefono'];
            $this->dependencia = $data['dependencia'];
            $this->ubicacion = $data['ubicacion'];
            $this->tag = $data['tag'];
            $this->situacion = $data['situacion'];
            $this->movil = $data['tel_movil'];




            $this->f_alta = NULL;
            if ($data['f_alta'] != '') {
                $this->f_alta = Date('d-m-Y', strtotime($data['f_alta']));
            }

            $this->f_baja = NULL;
            if ($data['f_baja'] != '') {
                $this->f_baja = Date('d-m-Y', strtotime($data['f_baja']));
            }


        } else {
            $this->codagente = NULL;
            $this->nombre = '';
            $this->apellidos = '';
            $this->dnicif = '';
            $this->email = NULL;
            $this->telefono = NULL;
            $this->f_alta = Date('d-m-Y');
            $this->f_baja = NULL;
            $this->dependencia = NULL;
            $this->ubicacion = NULL;
            $this->tag = NULL;
            $this->situacion = NULL;
            $this->movil = NULL;



        }
    }

    protected function install()
    {
        $this->clean_cache();
        return "INSERT INTO " . $this->table_name . " (codagente,nombre,apellidos,dnicif)
         VALUES ('1','Paco','Pepe','00000014Z');";
    }

    /**
     * Devuelve nombre + apellidos del agente.
     * @return string
     */
    public function get_fullname()
    {
        return $this->nombre . " " . $this->apellidos;
    }

    /**
     * Genera un nuevo código de agente
     * @return string
     */
    public function get_new_codigo()
    {
        $sql = "SELECT MAX(" . $this->db->sql_to_int('codagente') . ") as cod FROM " . $this->table_name . ";";
        $data = $this->db->select($sql);
        if ($data) {
            return (string) (1 + (int) $data[0]['cod']);
        }

        return '1';
    }

    /**
     * Devuelve la url donde se pueden ver/modificar estos datos
     * @return string
     */
    public function url()
    {
        if (is_null($this->codagente)) {
            return "index.php?page=admin_agentes";
        }

        return "index.php?page=admin_agente&cod=" . $this->codagente;
    }

    /**
     * Devuelve el empleado/agente con codagente = $cod
     * @param string $cod
     * @return \agente|boolean
     */
    public function get($cod)
    {
        $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE codagente = " . $this->var2str($cod) . ";");
        if ($data) {
            return new \agente($data[0]);
        }

        return FALSE;
    }

    /**
     * Devuelve TRUE si el agente/empleado existe, false en caso contrario
     * @return boolean
     */
    public function exists()
    {
        if (is_null($this->codagente)) {
            return FALSE;
        }

        return $this->db->select("SELECT * FROM " . $this->table_name . " WHERE codagente = " . $this->var2str($this->codagente) . ";");
    }

    /**
     * Comprueba los datos del empleado/agente, devuelve TRUE si son correctos
     * @return boolean
     */
    public function test()
    {
        $this->apellidos = $this->no_html($this->apellidos);
        $this->dnicif = $this->no_html($this->dnicif);
        $this->email = $this->no_html($this->email);
        $this->nombre = $this->no_html($this->nombre);
        $this->telefono = $this->no_html($this->telefono);

        if (strlen($this->nombre) < 1 || strlen($this->nombre) > 50) {
            $this->new_error_msg("El nombre del empleado debe tener entre 1 y 50 caracteres.");
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Guarda los datos en la base de datos
     * @return boolean
     */
    public function save()
    {
        if ($this->test()) {
            $this->clean_cache();

            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " SET nombre = " . $this->var2str($this->nombre) .
                    ", apellidos = " . $this->var2str($this->apellidos) .
                    ", dnicif = " . $this->var2str($this->dnicif) .
                    ", telefono = " . $this->var2str($this->telefono) .
                    ", email = " . $this->var2str($this->email) .
                    ", f_alta = " . $this->var2str($this->f_alta) .
                    ", f_baja = " . $this->var2str($this->f_baja) .
                    ", dependencia = " . $this->var2str($this->dependencia) .
                    ", ubicacion = " . $this->var2str($this->ubicacion) .
                    ", situacion = " . $this->var2str($this->situacion) .
                    ", tel_movil = " . $this->var2str($this->movil) .
                    ", tag = " . $this->var2str($this->tag) .

                    "  WHERE codagente = " . $this->var2str($this->codagente) . ";";
            } else {
                if (is_null($this->codagente)) {
                    $this->codagente = $this->get_new_codigo();
                }

                $sql = "INSERT INTO " . $this->table_name . " (codagente,nombre,apellidos,dnicif,telefono,
               email,f_alta,f_baja,dependencia,ubicacion,situacion,tel_movil,tag) VALUES (" . $this->var2str($this->codagente) .
                    "," . $this->var2str($this->nombre) .
                    "," . $this->var2str($this->apellidos) .
                    "," . $this->var2str($this->dnicif) .
                    "," . $this->var2str($this->telefono) .
                    "," . $this->var2str($this->email) .
                    "," . $this->var2str($this->f_alta) .
                    "," . $this->var2str($this->f_baja) .
                    "," . $this->var2str($this->dependencia) .
                    "," . $this->var2str($this->ubicacion) .
                    "," . $this->var2str($this->situacion) .
                    "," . $this->var2str($this->movil) .
                    "," . $this->var2str($this->tag) .
                     ");";
            }

            return $this->db->exec($sql);
        }

        return FALSE;
    }

    /**
     * Elimina este empleado/agente
     * @return boolean
     */
    public function delete()
    {
        $this->clean_cache();
        $delete = $this->db->exec("DELETE FROM " . $this->table_name . " WHERE codagente = " . $this->var2str($this->codagente) . ";");
        if($delete){
            return "index.php?page=admin_agentes";
        }
    }

    /**
     * Limpiamos la caché
     */
    private function clean_cache()
    {
        $this->cache->delete('m_agente_all');
    }

    /**
     * Devuelve un array con todos los agentes/empleados.
     * @return \agente
     */
    public function all($incluir_debaja = FALSE)
    {
        if ($incluir_debaja) {
            $listagentes = array();
            $data = $this->db->select("SELECT * FROM " . $this->table_name . " ORDER BY nombre ASC, apellidos ASC;");
            if ($data) {
                foreach ($data as $a) {
                    $listagentes[] = new \agente($a);
                }
            }
        } else {
            /// leemos esta lista de la caché
            $listagentes = $this->cache->get_array('m_agente_all');

            if (empty($listagentes)) {
                /// si no está en caché, leemos de la base de datos
                $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE f_baja IS NULL ORDER BY nombre ASC, apellidos ASC;");
                if ($data) {
                    foreach ($data as $a) {
                        $listagentes[] = new \agente($a);
                    }
                }

                /// guardamos la lista en caché
                $this->cache->set('m_agente_all', $listagentes);
            }
        }

        return $listagentes;
    }
}
