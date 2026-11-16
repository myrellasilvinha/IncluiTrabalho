<?php

    class Empresa{

        private $id;
        public $nome;
        public $email;
        public $senha;
        public $telefone;

        public function getId(){
            return $this->id;
        }
        
        public function setId($id){
            $this->id = $id;
        }

        public function getEmail(){
            return $this->email;
        }
        
        public function setEmail($email){
            $this->email = $email;
        }

        public function getSenha(){
            return $this->senha;
        }
        
        public function setSenha($senha){
            $this->senha = $senha;
        } 
        

        public function getNome(){
            return $this->nome;
        }
        
        public function setNome($nome){
            $this->nome = $nome;
        }

        public function getTelefone(){
            return $this->telefone;
        }
        
        public function setTelefone($telefone){
            $this->telefone = $telefone;
        }

        /**
         * Permite leitura das propriedades privadas como $empresa->id,
         * mantendo compatibilidade com as telas e controladores.
         */
        public function __get($nome){
            $metodo = 'get' . ucfirst($nome);
            return method_exists($this, $metodo) ? $this->$metodo() : null;
        }

        public function __isset($nome){
            return isset($this->$nome) || method_exists($this, 'get' . ucfirst($nome));
        }


    }

?>