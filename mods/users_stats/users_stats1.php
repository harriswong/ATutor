<?php
/************************************************************************/
/* ATutor								*/
/************************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay, Joel Kronenberg & Heidi Hazelton*/
/* Adaptive Technology Resource Centre / University of Toronto		*/
/* http://atutor.ca							*/
/*									*/
/* This program is free software. You can redistribute it and/or	*/
/* modify it under the terms of the GNU General Public License		*/
/* as published by the Free Software Foundation.			*/
/************************************************************************/
// $Id: users_stats1.php 7208 2008-01-09 16:07:24Z greg $

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
authenticate(AT_PRIV_CONTENT,AT_PRIV_RETURN);

/* Getting member id from page that reffered (from user_stats.php */
if($member_id = intval($_GET['id']))
$member_id = intval($_GET['id']);
else 
$member_id = $_SESSION['member_id'];

// seleziona e visualizza il nome dello studente come titolo in base al valore che prende in ingresso
$sql = "SELECT CONCAT(first_name, ' ', second_name, ' ', last_name) AS Name
        FROM ".TABLE_PREFIX."members
        WHERE member_id = $member_id";

$title = mysql_query($sql, $db);
$title1 = mysql_fetch_assoc($title);

$_pages['mods/users_stats/users_stats1.php']['title'] = $title1['Name'];
$_pages['mods/users_stats/users_stats1.php']['parent'] = 'mods/users_stats/index.php';

require(AT_INCLUDE_PATH.'header.inc.php');

//selezione di tutti i contentuti di una determinata lezione.
   //escludi le pagine che includono solo il titolo (content_parent_id > 0).
    $sql ="SELECT title,content_id,content_parent_id
           FROM ".TABLE_PREFIX."content 
           WHERE course_id = $_SESSION[course_id]
           AND content_parent_id > 0
           ORDER BY content_id ASC";
    $result = mysql_query($sql, $db);
    
    //selezione degli accessi di un determinato studente per una determinata lezione.
    //escludi le pagine che includono solo il titolo (content_parent_id > 0).
    $sql = "SELECT M.counter,M.content_id, SEC_TO_TIME(duration) AS total 
	        FROM ".TABLE_PREFIX."member_track M , ".TABLE_PREFIX."content C
	        WHERE M.content_id = C.content_id
	        AND M.member_id=$member_id
	        AND M.course_id=$_SESSION[course_id]
	        AND C.content_parent_id > 0
	        ORDER BY C.content_id ASC";
    $result1 = mysql_query($sql, $db);
    $row1 = mysql_fetch_assoc($result1);
?>
	<table style="width:600px"align="left" class="data static" rules="cols" summary="">
	<thead>
	<tr>
		<th width="66%"scope="col"><?php echo _AT('content'); ?></th>
		<th width="8%"scope="col"><?php echo _AT('visits'); ?></th>
		<th width="18%"scope="col"><?php echo _AT('total_hits'); ?></th>
		<th width="8%"scope="col"><?php echo _AT('duration'); ?></th>
	</tr>
	</thead>
	<tbody>
	<!--Effettua un confronto fra i dati ottenuti dalle query precedenti.
        Vengono visualizzate le visite dei contenuti di una determinata lezione.
        Checkmark: accesso di un contenuto avvenuto
        Red cross: accesso di un contenuto non avvenuto 
    -->
	<?php while ($row = mysql_fetch_assoc($result))
	{ ?>
	  <tr>
	   	
		<td> <center> <?php echo htmlspecialchars($row['title']) ;   ?></td>
		 <?php
		   if( $row1['content_id'] == $row['content_id'])
		    {?>
			 <td> <center> <img src="mods/users_stats/images/checkmark.gif"?></td> 
			 <td> <center> <?php echo $row1['counter'];   ?></td>
		     <td> <center> <?php echo $row1['total'];   ?></td>
			 <?php $row1 = mysql_fetch_assoc($result1); 
			}
		   else 
			{?>
			 <td> <center> <img src="mods/users_stats/images/x.gif"?></td>
			 <td> <center> <?php echo ('0');?></td>
			 <td> <center> <?php echo ('00:00:00');   ?></td> <?php
			}?>
      </tr><?php 
	} ?>

	
</tbody>
</table>


<!--TABELLA STATISTICHE QUESTIONARI ESEGUITI-->

<table style="width:600px"align="left" class="data static" rules="cols" summary="">
<thead>
<tr>
	<th width="57%" scope="col"><?php echo _AT('students_tests'); ?></th>
	<th width="17%" scope="col"><?php echo _AT('attempts'); ?></th>
	<th width="17%" scope="col"><?php echo _AT('issue'); ?></th>
	
</tr>
</thead>
<tbody>

<?php $sql = "SELECT T.title,T.test_id,COUNT(T.title) AS num FROM ".TABLE_PREFIX."tests T,".TABLE_PREFIX."tests_results TR
              WHERE TR.test_id = T.test_id AND T.course_id = $_SESSION[course_id] AND TR.member_id = $member_id AND TR.status = 1
              GROUP BY T.title ORDER BY T.test_id";
	  $result3 = mysql_query($sql, $db);
	  
$flag2=0;	
$flag3=0;	
$flag4=0;	
  
if ($row3 = mysql_fetch_assoc($result3)): 
 do 
  { 
  	$flag4=1;
	$flag2=1;?>
	      <tr>
          <tr onmousedown="document.location='<?php echo AT_BASE_HREF; ?>mods/users_stats/tests.php?id=<?php echo $member_id; ?>&t_id=<?php echo $row3['test_id']; ?><?php echo $row3['title'];?>'">
	      <td align="center"><a  href="<?php echo AT_BASE_HREF; ?>mods/users_stats/tests.php?id=<?php echo $member_id;?>&t_id=<?php echo $row3['test_id']; ?>"><?php echo ($row3['title']); ?></a></td>
	      <td align="center"> <?php echo $row3['num'];   ?></td><?php 
	      
	      //seleziona il punteggio massimo ottenibile e il punteggio ottenuto da uno studente in un test
	      $sql = "SELECT TR.final_score , T.out_of FROM ".TABLE_PREFIX."tests_results TR , ".TABLE_PREFIX."tests T
                  WHERE TR.test_id = T.test_id AND TR.member_id = $member_id AND TR.test_id = $row3[test_id] AND TR.status = 1";
	      $result4 = mysql_query($sql, $db);
	      //calcolo della percentuale del punteggio ottenuto per il controllo dell'esito finale di un test
	      while ($row4 = mysql_fetch_assoc($result4))
	       {
	        if ($row4['final_score'] == 0)
	        {
	  	      $rate = 0;
	        }
	        else 
	        {	
	          $rate=($row4['final_score']/$row4['out_of'])*100;
	        }
	      //soglia minima di superamento di un test
	       if($rate >= 60)
	       { 
	        $flag3=1; ?>
	        <td align="center"><img src="mods/users_stats/images/checkmark.gif">    </td>  <?php
	        break; 	
	       } 
	      }   
	
	    //se tutti i test hanno dato esito negativo
        if($flag3 == 0 && $flag4 == 1)
        { ?>
	     <td align="center"><img src="mods/users_stats/images/x.gif">    </td> <?php
        }?>
        </tr> <?php
	   
  }while ($row3 = mysql_fetch_assoc($result3));
	 
  
else : ?>
<tr>
  <td colspan="6"><?php echo _AT('none_found'); ?></td>
  </tr> <?php
endif; 

?>

</tbody>
</table>





<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>