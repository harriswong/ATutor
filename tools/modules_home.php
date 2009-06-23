<?php
/* Il file viene richiamato da index.tmpl.php per le seguenti operazioni:
* 1) Spostamento dei moduli direttamente da home-page nelle 4 direzioni: sx, dx, dw, up
* 2) Aggiunta di moduli nella lista di coloro che dovranno essere visualizzati nell' home-page del corso
* 3) Rimozione di moduli precedentemente visualizzati nella home. 
*/
define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');

$home_string = $system_courses[$_SESSION['course_id']]['home_links']; 
$home_links = explode('|',$home_string);						//scomposizione della sequenza dei moduli presenti nella home

if(isset($_GET['move']) && isset($_GET['mid'])){				//verifica dei valori passati tramite GET. Se soddisfatto il controllo sarà eseguita la procedura di riordino dei moduli. (richiesto uno spostamento)
	$mid = ($_REQUEST['mid'])-1; 								//-1 in quanto il conteggio dei moduli nella pagina precedente (index.tmpl.php) parte da 1 mentre tramite la funzione explode l'indice di partenza � zero.			

	$sup = $home_links[$mid];									//inserimento del modulo per il quale saràtato richiesto lo spostamento in una variabile di supporto.
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
		
} else if(isset($_GET['home_url'])){							//se settato 'home-url' significa che sarà stato richiesto l'inserimento di un nuovo modulo tra quelli già visibili per il corso.
	if($home_string != null)									//se la lista dei moduli non è vuota allora si andrà ad inserire il modulo richiesto in coda.
		$final_home_links = ($home_string.'|').$_GET['home_url'];
	else
		$final_home_links = $_GET['home_url'];					//se la lista è vuota, il modulo in esame sarà semplicemente inserito come primo modulo nella lista.
		
} else if(isset($_GET['mid'])){									//se settato 'mid' significa che è stata richiesta la rimozione di un modulo attualmente presente nella lista dei visualizzati nella home.
	$mid = ($_REQUEST['mid'])-1; 								//-1 in quanto il conteggio dei moduli nella pagina precedente parte da 1 mentre tramite la funzione explode parte da zero.
	
	unset($home_links[$mid]);									//unset dell'elemento richiesto nella posizione richiesta.
	
	if($home_links != null)										//se l'array dopo l'operazione di unset non è vuoto, allora si procederà con la creazione della nuova sequenza.
		$final_home_links = implode('|', $home_links);				
	else														//se nell'array non sono più presenti elementi allora significa che nessun elemento sarà visualizzato nella home del corso corrente.
		$final_home_links = '';
}
	
//query di aggiornamento interna al DB dove sarà inserita la nuova stringa aggiornata.
$sql    = "UPDATE ".TABLE_PREFIX."courses SET home_links='$final_home_links' WHERE course_id=$_SESSION[course_id]";
$result = mysql_query($sql, $db);

//redirect alla pagina iniziale del corso (home) dove saranno ricaricati i moduli aggiornati.
header('Location:'.AT_BASE_HREF.'index.php');

?>