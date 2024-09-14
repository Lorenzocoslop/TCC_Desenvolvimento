<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
include_once realpath(__DIR__ . '/../../model/form.class.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Usuario {
    private $pdo;
    private $tabela;
    private $empresa;

    public function __construct( $tabela = 'usuarios') {
        $this->pdo = $this->conectar();
        $this->tabela = $tabela;
        $this->empresa = new SessaoEmpresa();
    }

    private function conectar() {
        return conectar();
    }


    public function buscarUsuario() {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->tabela . '
                                      WHERE ID = :id');
        $stmt->execute([':id' => $_SESSION['ID']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

}

?>
