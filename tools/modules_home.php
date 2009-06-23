<?php
define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

$home_string = $system_courses[$_SESSION['course_id']]['home_links']; 
$home_links = explode('|',$home_string);						//scomposizione della sequenza dei moduli presenti nella home

if(isset($_GET['move']) && isset($_GET['mid'])){				//verifica dei valori passati tramite GET. Se soddisfatto il controllo sar� eseguita la procedura di riordino dei moduli. (richiesto uno spostamento)
	$mid = ($_REQUEST['mid'])-1; 								//-1 in quanto il conteggio dei moduli nella pagina precedente (index.tmpl.php) parte da 1 mentre tramite la funzione explode l'indice di partenza ?ero.			

	$sup = $home_links[$mid];									//inserimento del modulo per il quale sar�tato richiesto lo spostamento in una variabile di supporto.
																//l'operazione viene eseguita una volta sola in quando dovrebbe essere altrim ripetuta per ogni condizione successiva.
	if (!(strcmp($_GET['move'],"down"))){						//"DOWN". Spostamento verso il basso. 
		$home_links[$mid] = $home_links[$mid+2];				//viene eseguito lo scambio di valori basandosi su due posizioni successive rispetto a quella attuale.
		$home_links[$mid+2] = $sup;
		
	} else if(!(strcmp($_GET['move'],"up"))) {					//"UP". Spostamento verso l'alto.
		$home_links[$mid] = $home_links[$mid-2];				//viene eseguito lo scambio di valori basandosi su due posizioni precedenti rispetto a quella attuale.
		$home_links[$mid-2] = $sup;
		
	} else if(!(strcmp($_GET['move'],"right"))){				//"RIGHT" Spostamento verso destra.
		$home_links[$mid] = $home_links[$mid+1];				//viene eseguito lo scambio di valori basandosi sulla posizione successiva a quella attuale.
		$home_links[$mid+1] = $sup;

	} else if(!(strcmp($_GET['move'],"left"))){					//"LEFT" Spostamento verso destra.
		$home_links[$mid] = $home_links[$mid-1];				//viene eseguito lo scambio di valori basandosi sulla posizione precedente a quella attuale.
		$home_links[$mid-1] = $sup;
	}
	
	$final_home_links = implode('|', $home_links);				//creazione della seuenza finale da riscrivere sul DB.
		
} else if(isset($_GET['home_url'])){							//se settato 'home-url' significa che sar� stato richiesto l'inserimento di un nuovo modulo tra quelli gi� visibili per il corso.
	if($home_string != null)									//se la lista dei moduli non � vuota allora si andr� ad inserire il modulo richiesto in coda.
		$final_home_links = ($home_string.'|').$_GET['home_url'];
	else
		$final_home_links = $_GET['home_url'];					//se la lista � vuota, il modulo in esame sar� semplicemente inserito come primo modulo nella lista.
		
} else if(isset($_GET['mid'])){									//se settato 'mid' significa che � stata richiesta la rimozione di un modulo attualmente presente nella lista dei visualizzati nella home.
	$mid = ($_REQUEST['mid'])-1; 								//-1 in quanto il conteggio dei moduli nella pagina precedente parte da 1 mentre tramite la funzione explode parte da zero.
	
	unset($home_links[$mid]);									//unset dell'elemento richiesto nella posizione richiesta.
	
	if($home_links != null)										//se l'array dopo l'operazione di unset non � vuoto, allora si proceder� con la creazione della nuova sequenza.
		$final_home_links = implode('|', $home_links);				
	else														//se nell'array non sono pi� presenti elementi allora significa che nessun elemento sar� visualizzato nella home del corso corrente.
		$final_home_links = '';
}
	
//query di aggiornamento interna al DB dove sar� inserita la nuova stringa aggiornata.
$sql    = "UPDATE ".TABLE_PREFIX."courses SET home_links='$final_home_links' WHERE course_id=$_SESSION[course_id]";
$result = mysql_query($sql, $db);

//redirect alla pagina iniziale del corso (home) dove saranno ricaricati i moduli aggiornati.
header('Location:'.AT_BASE_HREF.'index.php');
?>