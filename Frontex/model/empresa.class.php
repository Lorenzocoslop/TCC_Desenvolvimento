<?php
    class Empresa {
        private $pdo;
        private $tabela;

        public function __construct($pdo, $tabela = 'empresas') {
            $this->pdo = $pdo;
            $this->tabela = $tabela;
        }

        public function searchEmpresas(){
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->tabela . ' WHERE ativo <> 0');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($view) {
            return (object) $view; 
        }, $result);
        }
    }
?>