<?php
	/* 
		Classe réprésentant la pagination, globale à tout le site
		@Author Yves P.
		@Version 1.0
		@Date création: 01/11/2019
		@Dernière modification: 10/09/2023
	*/

	class Pagination {
		// Attributs
		protected $pageName, $currentPage, $totalPages, $showItems, $output, $options;
	
		// Constructeur
		public function __construct($pageName, $currentPage, $totalRecords, $options = array()) {
			// Mise à jour des options de la pagination
			$this->options = $options;
			
			// Mise à jour de l'adresse URL
			$this->pageName = $pageName;
			
			// Mise à jour de la page courante
			$this->currentPage = intval($currentPage);
			
			// Définition du nombre total de pages
			$this->totalPages = ceil(intval($totalRecords) / intval($this->options['posts_per_page']));
			
			// Nombres de liens à afficher
			$this->showItems = ($this->options['range'] + 1);
			
			// Génération de la pagination suivant les paramètres définis
			$this->GeneratePagination();
		}
		
		// Génère le code de la pagination
		private function GeneratePagination() {
			// Vérification si le nombre de pages est supérieur à 1
			if ($this->totalPages != 1) {
				$this->output = '
		<nav aria-label="Pagination">
			<ul class="pagination pagination-sm">';
				
				// Gestion du lien vers la première page
				if ($this->options['text_first_page']) {
					if ($this->currentPage > 2 && $this->currentPage > $this->options['range'] + 1 && $this->showItems < $this->totalPages) {
						$this->output .= '
				<li class="page-item">
					<a href="'.sprintf($this->pageName, 1).'" class="page-link" aria-label="Première page">
						<span aria-hidden="true">'.$this->options['text_first_page'].'</span>
					</a>
				</li>';
					}
				}
				
				// Gestion du lien vers la page précédente
				if ($this->options['text_previous_page']) {
					if ($this->currentPage > 1 && $this->showItems < $this->totalPages) {
						$this->output .= '
				<li class="page-item">
					<a href="'.sprintf($this->pageName, $this->currentPage - 1).'" class="page-link" aria-label="Page précédente">
						<span aria-hidden="true">'.$this->options['text_previous_page'].'</span>
					</a>
				</li>';
					}
				}
				
				// Gestion des liens de la pagination
				for ($i = 1; $i <= $this->totalPages; $i++) {
					if (($i >= $this->currentPage - $this->options['range'] && $i <= $this->currentPage + $this->options['range']) || $this->totalPages <= $this->showItems) {
						// Si on est sur la page courante, le numéro n'est pas un lien
						$this->output .= ($this->currentPage == $i) ? '
				<li class="page-item active">
					<a href="'.sprintf($this->pageName, $i).'" class="page-link">'.$i.'</a>
				</li>' : '
				<li class="page-item">
					<a href="'.sprintf($this->pageName, $i).'" class="page-link">'.$i.'</a>
				</li>';
					}
				}
				
				// Gestion du lien vers la page suivante
				if ($this->options['text_next_page']) {
					if ($this->currentPage < $this->totalPages - 1 && $this->showItems < $this->totalPages) {
						$this->output .= '
				<li class="page-item">
					<a href="'.sprintf($this->pageName, $this->currentPage + 1).'" class="page-link" aria-label="Page suivante">
						<span aria-hidden="true">'.$this->options['text_next_page'].'</span>
					</a>
				</li>';
					}
				}
				
				// Gestion du lien vers la dernière page
				if ($this->currentPage < $this->totalPages - 1 && $this->currentPage + $this->options['range'] < $this->totalPages && $this->showItems < $this->totalPages) {
					$this->output .= '
				<li class="page-item">
					<a href="'.sprintf($this->pageName, $this->totalPages).'" class="page-link" aria-label="Dernière page">
						<span aria-hidden="true">'.$this->options['text_last_page'].'</span>
					</a>
				</li>';
				}
				
				$this->output .= '
			</ul>
		</nav>';
			}
		}
		
		// Retourne le contenu HTML de la pagination
		public function Display() {
			return $this->output;
		}
	}
?>