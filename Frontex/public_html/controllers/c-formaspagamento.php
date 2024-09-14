<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
include_once realpath(__DIR__ . '/../../model/form.class.php');

class FormaPagamento {
    private $pdo;
    private $tabela;
    private $empresa;

    public function __construct( $tabela = 'formaspagamento') {
        $this->pdo = $this->conectar();
        $this->tabela = $tabela;
        $this->empresa = new SessaoEmpresa();
    }

    private function conectar() {
        return conectar();
    }


    public function buscarFormasPagamentoEmpresa() {
        $stmt = $this->pdo->prepare('SELECT fp.ID,fp.nome FROM ' . $this->tabela . ' fp 
                                       JOIN formaspagamento_empresas fpe ON fp.ID = fpe.ID_formapagamento
                                      WHERE ID_empresa = :id_empresa');
        $stmt->execute([':id_empresa' => $this->empresa->getID_empresa()]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($view) {
            return (object) $view; 
        }, $result);
    }

}

?>
