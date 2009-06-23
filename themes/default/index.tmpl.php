<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto			*/
/* http://atutor.ca														*/
/*																		*/
/* This program is free software. You can redistribute it and/or		*/
/* modify it under the terms of the GNU General Public License			*/
/* as published by the Free Software Foundation.						*/
/************************************************************************/
if (!defined('AT_INCLUDE_PATH')) { exit; }
global $db;
$count_modules=1; 			// inizia da 1 in quanto indica la prima posizione. non dar� mai problemi in quanto nel caso in cui non saranno presenti moduli nella home non saranno eseguiti i cicli di controllo.
$num_modules=0;				//numero dei moduli presenti e visibilio nella home per un certo corso
$column = "left";			//il conteggio delle colonne parte dalla sinistra

//quesry di lettura del tipo di home visualizzabile. 0: classic view   1: modern view
$sql = "SELECT home_type FROM ".TABLE_PREFIX."courses WHERE course_id = $_SESSION[course_id]";
$result = mysql_query($sql,$db);
$row= mysql_fetch_assoc($result);
$swid = $row['home_type'];

if ($this->banner): ?><?php echo $this->banner; ?><?php endif;

if(authenticate(AT_PRIV_ADMIN,AT_PRIV_RETURN)){			//posizionamento switch della home SOLO PER ISTRUTTORI . verranno utilizzate due icone identificative per distinguere le due diverse visualizzazioni della home.
	if($swid==0)
		echo '<a href ="../tools/switch_home_type.php?swid='.$swid.'" style="background-color:#FFFFFF;"><img src="../images/modern.png"  alt ="modern view" border="0"/></a>';
	else
		echo '<a href ="../tools/switch_home_type.php?swid='.$swid.'" style="background-color:#FFFFFF;"><img src="../images/classic.png"  alt ="classic view" border="0"/></a>';
}	

//visualizzazione classica, $swid=0. naturalmente potranno essere apportate delle modifiche alle icone per ripristinare le icone classiche.
if($swid==0){ ?>
	<div style="width: 100%; margin-top: -5px; float:left;">
		<ul id="home-links">
		<br>
		<?php foreach ($this->home_links as $link): ?>
			<li><a href="<?php echo $link['url']; ?>"><img src="<?php echo $link['img']; ?>" alt="" class="img-size-home" border="0" /><?php echo $link['title']; ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div> <?php
} else {		//visualizzazione moderna, $swid=1.?>
	
	<div style="width: 100%; margin-top: -5px; float: left; ">
		<ul id="home-links">
		<br><br>
		<?php 				//creazione tabella contenitore divisa in due colonne per il posizionamento dei moduli
		foreach ($this->home_links as $link)				// conteggio numero dei moduli presenti nella home per lo studente. necessario per i controlli sul posizionamento delle frecce dei vari moduli.
			$num_modules++;
			
		if(authenticate(AT_PRIV_ADMIN,AT_PRIV_RETURN)){		//nel caso in cui l'utente corrisponda all'istruttore dovranno essere visualizzati TUTTI moduli disponibili per il corso. proprio per questo viene utilizzata la var all_home_links. si veda index.php.
			foreach ($this->all_home_links as $link){ ?>
						<div id="home_box"> 
							<div id="home_toolbar"><?php
							if($num_modules!=0){													//si controlla se sono presenti moduli visibili nella home.
								if($num_modules != 1 && $link['check'] == 'visible'){ 				//se check � impostato 'visible' significa che il modulo sar� presente nella home e potrebbe necessitare delle frecce direzionali
									if($count_modules <= 2 ){ 
										if($num_modules >2 && ($num_modules-$count_modules)>1) 								//significa che ci sono possibili moduli sottostanti quindi sar� da stampare la freccia "down"
											echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=down"><img src="../images/arrow-mini-down.png" alt="move down" border="0"/></a>&nbsp';
										if($column=="left")
											echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=right"><img src="../images/arrow-mini-right.png" alt="move right" border="0"/></a>&nbsp';
										else 
											echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=left"><img src="../images/arrow-mini-left.png" alt="move left" border="0"/></a>&nbsp';
									} else if($count_modules>2 && ($num_modules-$count_modules)>1){	//moduli intermedi, dovranno essere stampate obbligatoriamente le frecce 'up', 'down' e a seconda della colonna anche 'sx' o 'dx'
										echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=down"><img src="../images/arrow-mini-down.png" alt="move down" border="0"/></a>&nbsp';
										echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=up"><img src="../images/arrow-mini-up.png" alt="move up" border="0"/></a>&nbsp';
										if($column=="left")
											echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=right"><img src="../images/arrow-mini-right.png" alt="move right" border="0"/></a>&nbsp';
										else
											echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=left"><img src="../images/arrow-mini-left.png" alt="move left" border="0"/></a>&nbsp';	
									}else if($count_modules>2 && ($num_modules-$count_modules)==1){
										echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=up"><img src="../images/arrow-mini-up.png" alt="move up" border="0"/></a>&nbsp';
										if($column=="left")
											echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=right"><img src="../images/arrow-mini-right.png" alt="move right" border="0"/></a>&nbsp';
										else 
											echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=left"><img src="../images/arrow-mini-left.png" alt="move left" border="0"/></a>&nbsp';
									} else {														//caso in cui la differenza sia pari a zero. se l 'ultimo modulo � nella posizione di sx sar� stampata solo la freccia 'up' altrim se nella posizione di destra: freccia 'up' e 'sx'
										echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=up"><img src="../images/arrow-mini-up.png" alt="move up" border="0"/></a>&nbsp';
										if($column=="right")
											echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'&move=left"><img src="../images/arrow-mini-left.png" alt="move left" border="0"/></a>&nbsp';
									}
									echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'"><img src="../images/eye-mini-close.png" alt="set invisible" border="0"/></a>&nbsp';
								} else if($num_modules == 1 && $link['check'] == 'visible'){ //
									echo '<a href ="../tools/modules_home.php?mid='.$count_modules.'"><img src="../images/eye-mini-close.png" alt="set invisible" border="0"/></a>&nbsp';
								} else{
									echo '<a href ="../tools/modules_home.php?home_url='.$link['home_url'].'"><img src="../images/eye-mini-open.png" alt="set visible" border="0"/></a>&nbsp';
								}
							} else {	//nel caso in cui non siano presenti moduli visibili nella home, su ogni modulo dovr� essere data la possibilit� di renderlo 'visible'.
								echo '<a href ="../tools/modules_home.php?home_url='.$link['home_url'].'"><img src="../images/eye-mini-open.png" alt="set visible" border="0"/></a>&nbsp';
							} ?>
							</div> <?php
							print_modules($link); 						//chiamata alla funzione di stampa dei moduli?>
						</div> <?php
				if($column=='left'){									//caso in cui sia appena stata definita la prima cella della riga (posizione left)
					$column='right';									//$column impostato a right per definire l'eventuale seconda cella
				} 
				else if($column=='right'){ 								//caso in cui sia stata definita la seconda cella all'interno della riga corrente.				
					$column='left';
				}
				$count_modules++;										//aggiornamento del numero dei moduli sinora posizionati nella home
			}
		} else {														//caso in cui debbano essere visualizzati i moduli per lo studente nella modern view
			foreach ($this->home_links as $link){?>
				<div id="home_box">
					<div id="home_toolbar">
						<br>
					</div><?php
						print_modules($link); 							//chiamata alla funzione di stampa dei moduli (si ricorda che nel ciclo sono trattati solo quelli visibili nella home)?>
					
				</div>
				<?php
				if($column=='left'){									//caso in cui sia appena stata definita la prima cella della riga (posizione left)
					$column='right';									//$column impostato a right per definire l'eventuale seconda cella
				} 
				else if($column=='right' ){ 							//caso in cui sia stata definita la seconda cella all'interno della riga.
					$column="left";										
				}
			}
		} ?>														<!-- chiusura tabella contenitore esterno -->
		</ul>
	</div> 
	<?php
}  

if ($this->announcements): ?>
<h2 class="page-title"><?php echo _AT('announcements'); ?></h2>
	<?php foreach ($this->announcements as $item): ?>
		<div class="news">
			<h3><?php echo $item['title']; ?></h3>
			<p><span class="date"><?php echo $item['date'] .' '. _AT('by').' ' . $item['author']; ?></span></p> <?php echo $item['body']; ?>
		</div>
	<?php endforeach; ?>

	<?php if ($this->num_pages > 1): ?>
		<?php echo _AT('page'); ?>: | 
		<?php for ($i=1; $i<=$this->num_pages; $i++): ?>
			<?php if ($i == $this->current_page): ?>
				<strong><?php echo $i; ?></strong>
			<?php else: ?>
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?p=<?php echo $i; ?>"><?php echo $i; ?></a>
			<?php endif; ?>
			 | 
		<?php endfor; ?>
	<?php endif; ?>
<?php endif;


/*la funzione viene utilizzata per la stampa dei moduli e degli eventuali sottocontenuti per ogni modulo. ad ogni chiamata sar� passato il modulo interessato dal quale saranno estrapolati
* i dati necessari (preventivamente caricati) per la visualizzazione. in questo modo possono essere gestite le due distinte visualizzazioni: istruttore e studente
*/
function print_modules($link){ ?>
	<div id="home_icon_title">
		<div id="home_icon">
				<img src="<?php echo $link['img']; ?>" alt="" border="0"/>					
		</div>
		<div id="home_title">
				<font size="+1"> 
					<a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a>	<!-- inserimento link associato -->
				</font>
		</div>
	</div><?php
	if($link['icon']!=""){						//nel caso in cui sia settata una sottoicona per il modulo in esame allora saranno stampati gli eventuali sottocontenuti 
		$array = require($link['sub_file']);	//viene richiamato il file di controllo specifico per i sottocontenuti contenuto in include/html/sibmodules
		if(($array)==0){ 						//"0" è il valore di ritorno del file nel caso in cui non siano stati trovati dei sottocontenuti*/?>
			<div id="home_text">
				<i><?php echo _AT('none_found'); ?></i>
			</div><?php
		} else { ?>								<!-- stampa dei sottocontenuti, per ognuno verr� stampata la sub-icon relativa e il collegamento al sottocontenuto stesso -->
			<div id="home_content"><?php
				for($i=0; $array[$i]['sub_url']!=""; $i++){ 			//si esegue il ciclo di stampa fin quando saranno presenti sottocontenuti per il modulo corrente. il limite � impostato per default a 3?>
					<img src="<?php echo $link['icon']; ?>" border="0"/> <?php
					$text = validate_length($array[$i]['sub_text'], 38, VALIDATE_LENGTH_FOR_DISPLAY); //controllo della lunghezza del testo dei sub content
					if($text != $array[$i]['sub_text'])					//nel caso in cui la lunghezza sia superiore a quella prefissata viene visualizzato l' "alt" in modo da rapprsentare l'intera stringa
						$title = $array[$i]['sub_text'];
					else
						$title=''; 										//$title impostato '' nel caso in cui non sia necessario visualizzare l' "atl" ?>
					<a href="<?php echo $array[$i]['sub_url']; ?>" title="<?php echo $title; ?>"> <?php echo $text; ?> </a> 
					<br> <?php
				} ?>
			</div> <?php
		}						
	} else { 									//se non sono presenti sottocontenuti viene adattata la tabella di conseguenza e stampata una breve descrizione?>
		<div id="home_text">
		<?php echo $link['text']; ?> 
		</div><?php
	}
} ?>