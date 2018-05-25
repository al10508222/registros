<?php

class registrov extends fs_model {

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
    

    

    public function __construct($r = FALSE) {
        parent::__construct('registrov'); 
        if ($r) {
            $this->id = $r['id'];
            $this->marca = $r['marca'];
            $this->modelo = $r['modelo']; 
            $this->anio = $r['anio'];
            $this->color = $r['color']; 
            $this->placas = $r['placas'];
            $this->foto = $r['foto'];   
            $this->id_vehiculo = $r['id_vehiculo'];   
            $this->codagente = $r['codagente'];  

        } else {
            $this->id = NULL; 
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
                        a.tel_movil
                    FROM registrov rv 
                    LEFT JOIN agentes a USING(codagente)
                    WHERE rv.id = " . $this->var2str($id) . ";");
        if ($r) {
            return new registrov($r[0]);
        } else
            return FALSE;
    }
    

    public function exists() {
        if (is_null($this->id)) {
            return FALSE;
        } else
            return $this->db->select("SELECT * FROM " . $this->table_name . " WHERE id = " . $this->var2str($this->id) . ";");
    }

    public function save() {
            $this->clean_cache();
            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " SET marca = " . $this->var2str($this->marca) .
                        
                        ", modelo =         "      .$this->var2str($this->modelo) .
                        ", anio =         "      .$this->var2str($this->anio) .
                        ", color =         "      .$this->var2str($this->color) .
                        ", placas =         "      .$this->var2str($this->placas) .
                        ", id_vehiculo =         "      .$this->var2str($this->id_vehiculo) .
                        ", codagente =         "      .$this->var2str($this->codagente) .
                        "  WHERE id = " .$this->var2str($this->id).";";
            } else {
                $sql = "INSERT INTO " . $this->table_name . " (marca,modelo,anio,color,placas,id_vehiculo,codagente)
                  VALUES (" . $this->var2str($this->marca) .
                        "," . $this->var2str($this->modelo).
                        "," . $this->var2str($this->anio).
                        "," . $this->var2str($this->color).
                        "," . $this->var2str($this->placas).
                        "," . $this->var2str($this->id_vehiculo).
                        "," . $this->var2str($this->codagente).

                        ");";

            }
        return $this->db->exec($sql);
    }

    public function delete() {
        $this->clean_cache();
        return $this->db->exec("DELETE FROM " . $this->table_name . " WHERE id = " . $this->var2str($this->id) . ";");
    }

    private function clean_cache() {
        $this->cache->delete('m_registrov_all');
    }

    public function all() {
        $listaregistrov = $this->cache->get_array('m_registrov_all');
        if (!$listaregistrov) {
            $registrov = $this->db->select("SELECT * FROM " . $this->table_name . " ORDER BY id DESC;");
            if ($registrov) {
                foreach ($registrov as $r)
                    $listaregistrov[] = new registrov($r);
            }
           $this->cache->set('m_registrov_all', $listaregistrov);
        }
        return $listaregistrov;
    }

    public function all_images($id_vehiculo) {
        $listaregistrov = $this->cache->get_array('m_image_vehiculo_all');
        if (!$listaregistrov) {
            $registrov = $this->db->select("SELECT * FROM image_vehiculo WHERE id_vehiculo = ".$this->var2str($this->id_vehiculo)." ORDER BY id DESC;");
            if ($registrov) {
                foreach ($registrov as $r)
                    $listaregistrov[] = $r['imagen'];
            }
        }
        return $listaregistrov;
    }
}