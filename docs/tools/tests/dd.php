<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2007 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: preview.php 6695 2006-12-18 20:00:27Z joel $

define('AT_INCLUDE_PATH', '../../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
session_write_close();
$_GET['qid'] = intval($_GET['qid']);
$sql = "SELECT * FROM ".TABLE_PREFIX."tests_questions WHERE question_id=$_GET[qid]";
$result = mysql_query($sql, $db);
$row = mysql_fetch_assoc($result);

$_letters = array(_AT('A'), _AT('B'), _AT('C'), _AT('D'), _AT('E'), _AT('F'), _AT('G'), _AT('H'), _AT('I'), _AT('J'));
$_letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G');

$num_options = 0;
for ($i=0; $i < 10; $i++) {
	if ($row['option_'. $i] != '') {
		$num_options++;
	}
}
?>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="<?php echo $_base_href; ?>jscripts/jquery.js"></script>
	<script type="text/javascript" src="<?php echo $_base_href; ?>jscripts/interface.js"></script>
<style type="text/css">
* {
	margin: 0px;
	padding: 0px;
}
option {
	padding-right: 5px;
}
li {
	padding: 5px;
	border: 1px solid #ccc;
	margin: 5px;
}
li.question {
	width: 200px;
	overflow: auto;
}
li.question:hover {
	cursor: move;
}
li.answer {
	width: 200px;
	overflow: auto;
}
.dropactive {
	background-color: #fc9;
}
.drophover {
	background-color: #ffc;
}
</style>
</head>
<body>

<form method="get">
	<ul style="position: absolute; top: 10px; left: 5px" id="q">
		<?php for ($i=0; $i < 10; $i++): ?>
			<?php if ($row['choice_'. $i] != ''): ?>
				<li class="question" id="q<?php echo $i; ?>">
					<select name="sq<?php echo $i; ?>" onchange="selectLine(this.value, 'q<?php echo $i; ?>');" id="sq<?php echo $i; ?>">
						<option value="-1">-</option>
						<?php for ($j=0; $j < $num_options; $j++): ?>
							<option value="<?php echo $j; ?>"><?php echo $_letters[$j]; ?></option>
						<?php endfor; ?>
					</select>
				
				<?php echo $row['choice_'.$i]; ?></li>
			<?php endif; ?>
		<?php endfor; ?>
	</ul>

	<ol style="position: absolute; list-style-type: upper-alpha; top: 10px; left: 300px" id="a">
		<?php for ($i=0; $i < 10; $i++): ?>
			<?php if ($row['option_'. $i] != ''): ?>
				<li class="answer" id="a<?php echo $i; ?>"><?php echo $_letters[$i]; ?>. <?php echo $row['option_'.$i]; ?></li>
			<?php endif; ?>
		<?php endfor; ?>
	</ol>
</form>

<img alt="" id="imgq0" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq1" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq2" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq3" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq4" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq5" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq6" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq7" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq8" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>
<img alt="" id="imgq9" src="<?php echo $_base_href; ?>images/jslines/1up.gif" style="position: absolute;"/>

<script type="text/javascript">

if($.browser.msie) {
	var padding = 5;
} else {
	var padding = 15;
}
$(document).ready(
	function() {
		$('#q>li').Draggable(
			{
				containment: "document",
				zIndex: 	1000,
				ghosting:	true,
				opacity: 	1,
				revert:     true,
				fx: 0 // doesn't update in FF if > 0
			}
		); // end draggable

		$('#a>li').Droppable(
			{
				accept : 'question', 
				activeclass: 'dropactive', 
				hoverclass:	'drophover',
				tolerance: "pointer",
				ondrop:	function (drag)  {
					var lx = drag.offsetLeft + $("#" + drag.id).width() + padding;
					var ly = drag.offsetTop  + $("#" + drag.id).height()/2 + 10;
					var rx = this.offsetLeft + 300;
					var ry = this.offsetTop  + $("#" + this.id).height()/2 + 10;

					if (this.id == 'a0') {
						document.getElementById('s' + drag.id).selectedIndex = 1;
					} else if (this.id == 'a1') {
						document.getElementById('s' + drag.id).selectedIndex = 2;
					} else if (this.id == 'a2') {
						document.getElementById('s' + drag.id).selectedIndex = 3;
					} else if (this.id == 'a3') {
						document.getElementById('s' + drag.id).selectedIndex = 4;
					} else if (this.id == 'a4') {
						document.getElementById('s' + drag.id).selectedIndex = 5;
					} else if (this.id == 'a5') {
						document.getElementById('s' + drag.id).selectedIndex = 6;
					} else if (this.id == 'a6') {
						document.getElementById('s' + drag.id).selectedIndex = 7;
					} else if (this.id == 'a7') {
						document.getElementById('s' + drag.id).selectedIndex = 8;
					} else if (this.id == 'a8') {
						document.getElementById('s' + drag.id).selectedIndex = 9;
					} else {
						document.getElementById('s' + drag.id).selectedIndex = 10;
					}

					window.top.document.getElementById("<?php echo $_GET['qid']; ?>" + drag.id).value = document.getElementById('s' + drag.id).selectedIndex - 1;

					drawLine(document.getElementById('img' + drag.id), lx, ly, rx, ry, '<?php echo $_base_href; ?>images/jslines/');

					return true;
				}
			}
		); // end droppable

	}
)

function selectLine(value, id) {
	if (value == -1) {
		document.getElementById('img' + id).style.width = "0px";
		document.getElementById('img' + id).style.height = "0px";
		document.getElementById('img' + id).src = "<?php echo $_base_href; ?>images/jslines/1up.gif";

		window.top.document.getElementById("<?php echo $_GET['qid']; ?>" + id).value = -1;

		return true;
	}

	var lx = document.getElementById(id).offsetLeft + $("#" + id).width() + padding;
	var ly = document.getElementById(id).offsetTop  + $("#" + id).height()/2 + 10;
	var rx = document.getElementById('a' + value).offsetLeft + 300;
	var ry = document.getElementById('a' + value).offsetTop + $("#a" + value).height()/2 + 10;

	window.top.document.getElementById("<?php echo $_GET['qid']; ?>" + id).value = value;

	drawLine(document.getElementById('img' + id), lx, ly, rx, ry, '<?php echo $_base_href; ?>images/jslines/');
	return true;
}

/*
From: http://www.p01.org/articles/DHTML_techniques/Drawing_lines_in_JavaScript/
*/
function drawLine( lineObjectHandle, Ax, Ay, Bx, By, lineImgPath ) {
	/*
	*	lineObjectHandle = an IMG tag with position:absolute
	*/
	var
		xMin		= Math.min( Ax, Bx ),
		yMin		= Math.min( Ay, By ),
		xMax		= Math.max( Ax, Bx ),
		yMax		= Math.max( Ay, By ),
		boxWidth	= Math.max( xMax-xMin, 1 ),
		boxHeight	= Math.max( yMax-yMin, 1 ),
		tmp		= Math.min( boxWidth, boxHeight ),
		smallEdge	= 1,
		newSrc;


	while( tmp>>=1 )
		smallEdge<<=1

	newSrc = lineImgPath+ smallEdge +( (Bx-Ax)*(By-Ay)<0?"up.gif":"down.gif" )
	if( lineObjectHandle.src.indexOf( newSrc )==-1 )
		lineObjectHandle.src = newSrc

	with( lineObjectHandle.style )
	{
		width	= boxWidth	+"px"
		height	= boxHeight	+"px"
		left	= xMin		+"px"
		top	= yMin		+"px"
	}
}
</script>

</body>