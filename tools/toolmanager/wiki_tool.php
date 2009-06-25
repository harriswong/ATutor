<?php
if (!defined('AT_INCLUDE_PATH')) { exit; }

$ext=('/wiki');
$current_path = AT_CONTENT_DIR.$_SESSION['course_id'].$ext; 			//path contenente le pagine del wiki per il corso specifico
if(is_dir($current_path)){												//si controlla se la directory esiste!
	if($dir=opendir($current_path)){ 									//leggo il direttorio che contiene tutte le pagine wiki
		while (false !== ($file = readdir($dir)) ) 						//esamino il contenuto del direttorio wiki
		{ 
			if( ($file == '.') || ($file == '..') ){ 					// if the name is not a directory 				
				continue;
			} else {
				$explode_string = explode(".",$file);					//eseguo un explode sulla stringa completa di numeri di revisione per selezionare solo il nome della pagina
				$wiki_page = $explode_string[0];						//prelevo solo il nome della pagina tralasciando i numeri di revisione
				if($wiki_page != $prev_wiki_page){						//verifico se la pagina in esame è = alla precedente. in tal caso significa che si sta esaminando una revisione successiva.la scarto!
					$path = 'mods/wiki/page.php?page='; 				// costruisco l link di riferimento alla cartella che contiene le pagine del wiki
					$file_path = $path.$wiki_page;						//costruisco il path da usare come link che verrà messo in text body
					
					$content_list[] = array('title' => $wiki_page, 'path' => $file_path, 'image' => AT_BASE_HREF.'mods/wiki/tlogo_icon.png');
					
					$prev_wiki_page = $wiki_page;
				}
			}
		}
			closedir($dir); //funzione di chiusura della directory
			return $content_list;
		} else {															//chiusura if di controllo sull'avvenuta apertura della directory
			echo 'open directory error';	/*DEFINIRE DEGLI ERRORI SENSATI*/
			return 0;
		}
	} else { 																//chiusura if di controllo sull'esistenza della directory
		echo 'no wiki initialization';		/*DEFINIRE DEGLI ERRORI SENSATI*/
		return 0;
	}








?>