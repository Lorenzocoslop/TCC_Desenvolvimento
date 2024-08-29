<?php
class SessaoEmpresa{
    private $ID_empresa;

    public function __construct($ID_empresa = 1) {
        $this->ID_empresa = $ID_empresa;
    }

    public function getID_empresa() {
        return $this->ID_empresa;
    }
}

?>