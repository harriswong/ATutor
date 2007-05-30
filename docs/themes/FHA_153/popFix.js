// JavaScript Document
var browserName=navigator.appName; 
// For Internet Explorer
	if (browserName=="Microsoft Internet Explorer"){ 
		var popUpWin=0;
		function popUpCap(chapter, swfFile){
		w = 1024;
		h = 768;
		LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
		TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
		settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=no,resizable=yes'
		win = window.open("",'popUpWin',settings)


		with (win.document) {
			writeln ('<html>');
			writeln ('<head>');
			writeln ('<style type="text/css"> html, body { margin: 0; padding: 0; height: 100%; width:100%;} </style>');
			writeln ('<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">');
			writeln ('<title>',chapter,'</title>');
			writeln ('</head>');
			writeln ('<body topmargin="0">');
			writeln ('<center>');
			writeln ('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"  scale="default" width="100%" height="100%" ID="Captivate1">');
			writeln ('<param name="movie" value="',swfFile,'">');
			writeln ('<param name="quality" value="high">');
			writeln ('<param name="menu" value="false">');
			writeln ('<param name="loop" value="2">');
			writeln ('<embed src="',swfFile,'" width="100%" height="100%" loop="2" quality="high" scale="default" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" menu="false"></embed>');
			writeln ('</object>');
			writeln ('</center>');
			writeln ('</body>');
			writeln ('</html>');
			close ();
			if (window.focus) {
				win.focus()
			}
		}
	}
// For All other browsers
	} else { 
	var popUpWin=0;
	function popUpCap(chapter, swfFile){
		w = 1024;
		h = 768;
		LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
		TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
		settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=no,resizable=yes'
		win = window.open("",'popUpWin',settings)
		
		with (win.document) {
			writeln ('<html>');
			writeln ('<head>');
			writeln ('<style type="text/css"> html, body { margin: 0; padding: 0; height: 100%; width:100%;} </style>');
			writeln ('<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">');
			writeln ('<title>',chapter,'</title>');
			writeln ('</head>');
			writeln ('<body topmargin="0">');
			writeln ('<center>');
			writeln ('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"  scale="default" width="100%" height="100%" ID="Captivate1">');
			writeln ('<param name="movie" value="',swfFile,'">');
			writeln ('<param name="quality" value="high">');
			writeln ('<param name="menu" value="false">');
			writeln ('<param name="loop" value="2">');
			writeln ('<embed src="',swfFile,'" width="100%" height="100%" loop="2" quality="high" scale="default" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" menu="false"></embed>');
			writeln ('</object>');
			writeln ('</center>');
			writeln ('</body>');
			writeln ('</html>');
			close ();
			if (window.focus) {
				win.focus()
			}
		}
	}
}

