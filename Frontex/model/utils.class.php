<?php
    class Utils{

        public static function listarEstados(){
            $estadosBrasil = [
                'AC' => 'AC', // Acre
                'AL' => 'AL', // Alagoas
                'AP' => 'AP', // Amapá
                'AM' => 'AM', // Amazonas
                'BA' => 'BA', // Bahia
                'CE' => 'CE', // Ceará
                'DF' => 'DF', // Distrito Federal
                'ES' => 'ES', // Espírito Santo
                'GO' => 'GO', // Goiás
                'MA' => 'MA', // Maranhão
                'MT' => 'MT', // Mato Grosso
                'MS' => 'MS', // Mato Grosso do Sul
                'MG' => 'MG', // Minas Gerais
                'PA' => 'PA', // Pará
                'PB' => 'PB', // Paraíba
                'PR' => 'PR', // Paraná
                'PE' => 'PE', // Pernambuco
                'PI' => 'PI', // Piauí
                'RJ' => 'RJ', // Rio de Janeiro
                'RN' => 'RN', // Rio Grande do Norte
                'RS' => 'RS', // Rio Grande do Sul
                'RO' => 'RO', // Rondônia
                'RR' => 'RR', // Roraima
                'SC' => 'SC', // Santa Catarina
                'SP' => 'SP', // São Paulo
                'SE' => 'SE', // Sergipe
                'TO' => 'TO'  // Tocantins
            ];

            return $estadosBrasil;
        }

        public static function definirCores(){
            $primaria = "#003399";
            return $primaria;
       } 

       public static function parseMoney($valor) {
            $valorLimpo = preg_replace('/[^\d,\.]/', '', $valor);
            
            $valorLimpo = str_replace(',', '.', $valorLimpo);
            
            return $valorLimpo;
        }
        public static function parsePhone($telefone) {

            $telefoneLimpo = preg_replace('/\D/', '', $telefone);
            
            return $telefoneLimpo;
        }
    }

?>