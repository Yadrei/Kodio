<?php
    /* 
        Classe J_Content_Tags qui représente la table J_CONTENT_TAGS en base de données
        @Author Yves P.
        @Version 1.0
        @Date Création: 29/11/2023
        @Dernière modification: 29/11/2023
    */

    class J_Content_Tag
    {
        private $errors = [], $id, $fkContent, $fkTag;

        // Constantes pour les erreurs
        const INVALID_CONTENT = "L'ID du contenu ne peux pas être vide";
        const INVALID_TAG= "L'ID du tag ne peut pas être vide";

        public function __construct($values = []) {
            if (!empty($values))
                $this -> SettingAttributes($values);
        }

        // Méthodes
        public function SettingAttributes($data) {
            foreach ($data as $attribute => $value) 
            {
                $method = 'set'.ucfirst($attribute);

                if (is_callable([$this, $method]))
                    $this -> $method($value);
            }
        }

        public function isNew() {
            return empty($this->id);
        }

        public function isValid() {
            return !(empty($this->fkContent) || empty($this->fkTag));
        }

        // Setters
        public function setId($id) {
            $this->id = $id;
        }

        public function setFkContent($fkContent) {
            if (!is_string($fkContent) || empty($fkContent))
                $this->errors[] = self::INVALID_CONTENT;
            else
                $this->fkContent = $fkContent;
        }

        public function setFkTag($fkTag) {
            if (!is_string($fkTag) || empty($fkTag))
                $this->errors[] = self::INVALID_TAG;
            else
                $this->fkTag = $fkTag;
        }

        // Getters
        public function getErrors() {
            return $this->errors;
        }

        public function getId() {
            return $this->id;
        }

        public function getFkContent() {
            return $this->fkContent;
        }

        public function getFkTag() {
            return $this->fkTag;
        }
    }   
?>