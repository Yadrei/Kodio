<?php
    /*
        Class Manager pour les relations avec la DB sur la table J_CONTENT_TAGS
        @Author Yves Ponchelet
        @Version 1.0
        @Creation: 29/11/2023
        @Last update: 11/12/2023
    */

    class J_Content_TagManager
    {
        private $db;

        public function __construct() {
            $this->db = (new Database())->getConnection();
        }

        // Méthodes privées
        private function Add(J_Content_Tag $tag) {
            $query = $this->db->prepare('INSERT INTO J_CONTENT_TAGS (FK_CONTENT, FK_TAG) VALUES(:content, :tag)');

            $query->bindValue(':content', $tag->getFkContent(), PDO::PARAM_INT);
            $query->bindValue(':tag', $tag->getfkTag(), PDO::PARAM_INT);

            $query->execute();

            $query->closeCursor();
        }

        private function Delete($id) {
            $query = $this->db->prepare('DELETE FROM J_CONTENT_TAGS WHERE ID = :id');

            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
        }

        // Méthodes publiques
        public function Save($tags = []) {
            if (!empty($tags)) {
                // On récupère les tags actuels
                $listTags = $this->GetRelatedTags($tags[0]->getFkContent());
                
                if (count($listTags) != 0) {
                    // Ajout des éventuels tags manquants
                    foreach($tags as $newTag) {
                        $exists = false;

                        foreach ($listTags as $oldTag) {
                            if ($oldTag->getFkContent() == $newTag->getFkContent() && $oldTag->getFkTag() == $newTag->getFkTag()) {
                                $exists = true;
                                break;
                            }
                        }
                        if (!$exists) {
                            $this->Add($newTag);
                        }
                    }

                    // Retrait des éventuels tags n'étant plus utiles au contenu
                    foreach ($listTags as $oldTag) {
                        $exists = false;

                        foreach ($tags as $newTag) {
                            if ($oldTag->getFkContent() == $newTag->getFkContent() && $oldTag->getFkTag() == $newTag->getfkTag()) {
                                $exists = true;
                                break;
                            }
                        }

                        if (!$exists) {
                            $this->Delete($oldTag->getId());
                        }
                    }
                }
                else {
                    foreach ($tags as $tag) {
                        if ($tag->IsValid())
                            $this->Add($tag);
                        else
                            throw new Exception($tag -> GetErrors());
                    }
                }
            }
        }

        public function GetRelatedTags($id) {
            $query = $this->db->prepare('SELECT ID id, FK_CONTENT fkContent, FK_TAG fkTag FROM J_CONTENT_TAGS WHERE FK_CONTENT = :id');

            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'J_Content_Tag');
            $query->execute();

            $listTags = $query->fetchAll();

            $query->closeCursor();

            return $listTags;
        }
    }
?>