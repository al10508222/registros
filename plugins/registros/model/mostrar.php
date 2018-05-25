<?php

class mostrar extends fs_model {

    public $id; 
    public $marca;
    public $modelo;
    public $anio; 
    public $color;
    public $placas; 
    public $foto;
    public $id_vehiculo;
    public $codagente; 
    public $nombre; 
    public $apellidos; 
    public $dnicif; //matricula
    public $dependencia; 
    public $lugar;
    public $telefono; 
    public $movil; 
    public $imagen_auto; 
    public $grado;
    public $especialidad;
    public $extension; 
    public $imagen_agente; 
    

    

    public function __construct($r = FALSE) {
        parent::__construct(''); 
        if ($r) {
            $this->marca = $r['marca'];
            $this->modelo = $r['modelo']; 
            $this->anio = $r['anio'];
            $this->color = $r['color']; 
            $this->placas = $r['placas'];
            $this->foto = $r['foto'];   
            $this->id_vehiculo = $r['id_vehiculo'];   
            $this->codagente = $r['codagente'];  
            $this->nombre = $r['nombre']; 
            $this->apellidos = $r['apellidos'];  
            $this->dnicif = $r['dnicif']; 
            $this->dependencia = $r['dependencia']; 
            $this->lugar = $r['ubicacion'];
            $this->telefono = $r['telefono']; 
            $this->movil = $r['tel_movil']; 
            $this->imagen_auto = $r['imagen']; 
            $this->grado = $r['grado']; 
            $this->especialidad = $r['especialidad']; 
            $this->extension = $r['extension']; 
            $this->imagen_agente = $r['imagen_agente']; 
            
            


        } else {
            // $this->id = NULL; 
            $this->marca = NULL; 
            $this->modelo = NULL; 
            $this->anio = NULL; 
            $this->color = NULL; 
            $this->placas = NULL; 
            $this->foto = NULL; 
            $this->id_vehiculo = NULL;  
            $this->codagente = NULL; 
            $this->nombre = NULL; 
            $this->apellidos = NULL; 
            $this->dnicif = NULL; 
            $this->dependencia = NULL;
            $this->lugar = NULL;
            $this->telefono = NULL;
            $this->movil = NULL; 
            $this->imagen_auto = NULL; 
            $this->grado = NULL; 
            $this->especialidad = NULL; 
            $this->extension = NULL; 
            $this->imagen_agente = NULL; 
            


        }
    }

    public function get_fullname()
    {
        return $this->titulo;
    }

    public function get_fullname2()
    {
        return $this->id; 
    }


    protected function install() {
        $this->clean_cache();
    }

    public function get_new_codigo() {
        $sql = "SELECT MAX(" . $this->db->sql_to_int('id') . ") as id FROM " . $this->table_name . ";";
        $id = $this->db->select($sql);
        if ($id) {
            return 1 + intval($id[0]['id']);
        } else
            return 1;
    }

    public function url() {
        if (is_null($this->id)) {
            return "index.php?page=registrov_ingreso";
        } else
            return "index.php?page=registrov_actualizar&id=" . $this->id;
    }

    public function get($id) {
        $r = $this->db->select("SELECT * FROM registrov WHERE id = " . $this->var2str($id) . ";");
        if ($r) {
            return new registrov($r[0]);
        } else
            return FALSE;
    }
    
    public function get2($id) {
        $r = $this->db->select("SELECT 
                        rv.marca,
                        rv.modelo,
                        rv.anio,
                        rv.color,
                        rv.placas,
                        rv.id_vehiculo,
                        rv.codagente,
                        rv.foto,
                        a.nombre,
                        a.apellidos,
                        a.dnicif,
                        a.dependencia,
                        a.ubicacion,
                        a.telefono,
                        a.tel_movil,
                        iv.imagen,
                        a.grado,
                        a.especialidad,
                        a.extension,
                        ia.imagen as imagen_agente
                    FROM registrov rv 
                    LEFT JOIN agentes a USING(codagente)
                    INNER JOIN image_vehiculo iv ON(iv.id_vehiculo = rv.id_vehiculo)
                    INNER JOIN image_agente ia ON(ia.codagente = rv.codagente)
                    
                    WHERE a.codagente = " . $this->var2str($id) . ";");
        if ($r) {
            return new mostrar($r[0]);
        } else
            return FALSE;
    }


    public function exists() {
    }

    public function save() {

    }

    public function delete() {
        $this->clean_cache();
        return $this->db->exec("DELETE FROM " . $this->table_name . " WHERE id = " . $this->var2str($this->id) . ";");
    }

    private function clean_cache() {
        $this->cache->delete('m_registrov_all');
    }

    public function all_images($id) {
        $listaregistrov = $this->cache->get_array('m_image_vehiculo_all');
        if (!$listaregistrov) {
            $registrov = $this->db->select("SELECT * FROM image_vehiculo WHERE id_vehiculo = '".$id."' ORDER BY id DESC;");
            if ($registrov) {
                foreach ($registrov as $r)
                    $listaregistrov[] = $r['imagen'];
            }
        }
        return $listaregistrov;
    }

}