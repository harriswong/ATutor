<script type="text/javascript"><!--
  function queryPopUp(width,height,q,wname,url) {
  var winopts = "resizable=yes,scrollbars=yes,toolbar=no,location=no,height=" + height + ",width=" + width;
  var query = url + "&query=" + q; 
  return window.open(query,wname,winopts);
}
--></script>  <script type="text/javascript"><!--
      function popUp(width,height,url,wname,smallwindow) {
  var windowWidth = 0, windowHeight = 0;
  if (smallwindow) {
    windowWidth = width;
    windowHeight = height;
  }
  else {
      if (screen && screen.width && screen.height) {
        // Desktop
        windowWidth = screen.width;
        windowHeight = screen.height * 0.85;
      }
      else if (window.innerWidth && window.innerHeight) {
        //Non-IE
        windowWidth = window.innerWidth;
        windowHeight = window.innerHeight;
      }
      else if (document.documentElement &&
               (document.documentElement.offsetWidth &&
                document.documentElement.offsetHeight)) {
        //IE 6+ in 'standards compliant mode'
        windowWidth = document.documentElement.offsetWidth;
        windowHeight = document.documentElement.offsetHeight;
      }
      else if (document.body &&
               (document.body.offsetWidth && document.body.offsetHeight)) {
        //IE 4 compatible
        windowWidth = document.body.offsetWidth;
        windowHeight = document.body.offsetHeight;
      }
      else {
        windowWidth = width;
        windowHeight = height;
      }
  }
  windowWidth *= 0.95;
  var winopts = "resizable=yes,scrollbars=yes,toolbar=yes,location=no,height=" + windowHeight + ",width=" + windowWidth;
  var helpWindowzzz = window.open(url,wname,winopts)
  helpWindowzzz.focus();
  helpWindowzzz.moveTo(0, 0);

}
      function changeColour(c1, c2, c3) {
  var frm = document.forms[0];
  var c1Num = frm.elements[c1].selectedIndex;
  var c2Num = frm.elements[c2].selectedIndex;
  var c3Num = frm.elements[c3].selectedIndex;
  if (c1Num == c2Num) {
    if (c1Num == 0) {
      frm.elements[c2].selectedIndex = 1;
      c2Num = 1;
    } else {
      frm.elements[c2].selectedIndex = 0;
      c2Num = 0;
    }
  } else if (c1Num == c3Num) {
    if (c1Num == 0) {
      frm.elements[c3].selectedIndex = 1;
      c3Num = 1;
    } else {
      frm.elements[c3].selectedIndex = 0;
      c3Num = 0;
    }
  }

  if (c2Num == c3Num) {
    if (c1Num == 0 || c1Num == 1) {
      frm.elements[c3].selectedIndex = 2;
    } else if (c2Num == 0) {
      frm.elements[c3].selectedIndex = 1;
    } else if (c2Num == 1) {
      frm.elements[c3].selectedIndex = 0;
    }
  }
}

function setPreviewSize(fontVal) {
	var fontSet = document.getElementById('fontsize');
	var docSize = document.getElementById('fontsize').value+'pt';
	var docBase = document.getElementById('previewText');
	docBase.style.fontSize = docSize;
	docBase = document.getElementById('highlightedPreview');
	docBase.style.fontSize = docSize;
}
function setPreviewFace() {
	var faceSet = document.getElementById('fontface');
	var faceVal = document.getElementById('fontface').value;
	var docBase = document.getElementById('previewText');
	docBase.style.fontFamily = faceVal;
	docBase = document.getElementById('highlightedPreview');
	docBase.style.fontFamily = faceVal;
}
function setPreviewColours() {
	var fgSet = document.getElementById('fg');
	var fgVal = document.getElementById('fg').value;
	var bgSet = document.getElementById('bg');
	var bgVal = document.getElementById('bg').value;
	var hlSet = document.getElementById('hl');
	var hlVal = document.getElementById('hl').value;

        fgVal = '\#'+fgVal.substr(0,6);
        bgVal = '\#'+bgVal.substr(0,6);
        hlVal = '\#'+hlVal.substr(0,6);
        
	var docBase = document.getElementById('previewText');
	docBase.style.color = fgVal;
	docBase.style.backgroundColor = bgVal;

	docBase = document.getElementById('highlightedPreview');
	docBase.style.backgroundColor = hlVal;
}
      function checkATTSignLang() {
  var frm = document.forms[0];
  var value = null;
  if (frm.attSignLang[0].checked)
    value = frm.attSignLang[0].value;
  else if (frm.attSignLang[1].checked)
    value = frm.attSignLang[1].value;

  if (value == "false")
    frm.attSignLangVal.disabled=true;
  else if (value == "true")
    frm.attSignLangVal.disabled=false;
}
      function checkAudioDesc() {
  var frm = document.forms[0];
  var value = null;
  if (frm.audioDesc[0].checked)
    value = frm.audioDesc[0].value;
  else if (frm.audioDesc[1].checked)
    value = frm.audioDesc[1].value;

  if (value == "false") {
    frm.audioDescLang.disabled=true;
    frm.audioDescType[0].disabled=true;
    frm.audioDescType[1].disabled=true;
  }
  else if (value == "true") {
    frm.audioDescLang.disabled=false;
    frm.audioDescType[0].disabled=false;
    frm.audioDescType[1].disabled=false;
  }
}

function checkVisualText() {
  var frm = document.forms[0];
  var value = null;
  if (frm.visualText[0].checked)
    value = frm.visualText[0].value;
  else if (frm.visualText[1].checked)
    value = frm.visualText[1].value;

  if (value == "false") {
    frm.altTextLang.disabled=true;
    frm.longDescLang.disabled=true;
  }
  else if (value == "true") {
    frm.altTextLang.disabled=false;
    frm.longDescLang.disabled=false;
  }
}
      function checkCaptions() {
  var frm = document.forms[0];
  var value = null;
  if (frm.caption[0].checked)
    value = frm.caption[0].value;
  else if (frm.caption[1].checked)
    value = frm.caption[1].value;

  if (value == "false") {
    frm.captionType[0].disabled=true;
    frm.captionType[1].disabled=true;
    frm.captionLang.disabled=true;
    frm.enhancedCaption[0].disabled=true;
    frm.enhancedCaption[1].disabled=true;
    frm.reducedSpeed[0].disabled=true;
    frm.reducedSpeed[1].disabled=true;
    frm.captionRate.disabled=true;
  }
  else if (value == "true") {
    frm.captionType[0].disabled=false;
    frm.captionType[1].disabled=false;
    frm.captionLang.disabled=false;
    frm.enhancedCaption[0].disabled=false;
    frm.enhancedCaption[1].disabled=false;
    frm.reducedSpeed[0].disabled=false;
    frm.reducedSpeed[1].disabled=false;
    frm.captionRate.disabled=false;
    checkCaptionRate();
  }
}

function checkCaptionRate() {
  var frm = document.forms[0];
  var value = null;
  if (frm.reducedSpeed[0].checked)
    value = frm.reducedSpeed[0].value;
  else if (frm.reducedSpeed[1].checked)
    value = frm.reducedSpeed[1].value;

  if (value == "false")
    frm.captionRate.disabled=true;
  else if (value == "true")
    frm.captionRate.disabled=false;
}

function checkATASignLang() {
  var frm = document.forms[0];
  var value = null;
  if (frm.ataSignLang[0].checked)
    value = frm.ataSignLang[0].value;
  else if (frm.ataSignLang[1].checked)
    value = frm.ataSignLang[1].value;

  if (value == "false")
    frm.ataSignLangVal.disabled=true;
  else if (value == "true")
    frm.ataSignLangVal.disabled=false;
}

function allDigits(str) {
  var digits = "0123456789";
  var result = true;
  for (var i = 0; i < str.length; i++) {
    if (digits.indexOf(str.substr(i, 1)) < 0 ) {
      result = false;
      break;
    }
  }
  return result;
}

function checkCaptionRateValue() {
  var frm = document.forms[0];
  var value = null;
  var result = true;
  if (!frm.captionRate.disabled) {
    if (!allDigits(frm.captionRate.value)) {
      alert('Please enter a number for the "Caption Rate" field.');
      frm.captionRate.focus();
      result = false;
    }
    else {
      value = parseInt(frm.captionRate.value);
      if (isNaN(value) || value < 1 || value > 300) {
        alert('Please enter a number between 1 and 300 for the "Caption Rate" field.');
        frm.captionRate.focus();
        result = false;
      }
    }
  } 
  return result;
}
      var cssFilter=/^http:\/\/.+\..{2,3}\/.+/;
function checkCSS() {
  var theForm = document.forms[0];
  if (!cssFilter.test(theForm.ssURL.value)) {
    alert('Please enter a valid URL to a CSS file.');
    return false;
  }
  return true;
}
  --></script>


<fieldset> 
<legend><strong>Text</strong> </legend>   
<table border="0" cellpadding="3" width="100%"> 
<tbody> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> <label for="fontface">Font Face:</label> </span> </span> 
<span class="formfield"> 
<select id="fontface" name="genericFace" onchange="setPreviewFace(); return false;">   
<option value="serif">Serif</option>   
<option value="sans-serif">Sans Serif</option>   
<option value="monospace">Monospaced</option>   
<option value="cursive">Cursive</option>   
<option value="fantasy">Fantasy</option> 
</select> </span>
 </td> 
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> <label for="fontsize">Font Size:</label> </span> </span> 
<span class="formfield"> 
<select id="fontsize" name="fontSize" onchange="setPreviewSize(); return false;">   
<option value="10">10pt</option>   
<option value="11">11pt</option>   
<option selected="selected" value="12">12pt</option>   
<option value="13">13pt</option>   
<option value="14">14pt</option>   
<option value="15">15pt</option>   
<option value="16">16pt</option>   
<option value="17">17pt</option>   
<option value="18">18pt</option>   
<option value="19">19pt</option>   
<option value="20">20pt</option>   
<option value="21">21pt</option>   
<option value="22">22pt</option>   
<option value="23">23pt</option>   
<option value="24">24pt</option>   
<option value="25">25pt</option>   
<option value="26">26pt</option>   
<option value="27">27pt</option>   
<option value="28">28pt</option>   
<option value="29">29pt</option>   
<option value="30">30pt</option> 
</select> </span> 
</td> 
</tr> 
<tr> 
<td> 
<span class="formlabel"> <span class="spaced"> <label for="fg">Foreground Colour:</label> </span> </span>
 <span class="formfield"> 
<select id="fg" name="fgColour" onchange="changeColour('fgColour', 'bgColour', 'hlColour'); setPreviewColours()">     
<option value="ffffffff">White</option>     
<option selected="selected" value="000000ff">Black</option>     
<option value="ff0000ff">Red</option>     
<option value="ffff00ff">Yellow</option>     
<option value="0000ffff">Blue</option>     
<option value="00ff00ff">Green</option>     
<option value="999999ff">Gray</option>     
<option value="ccccccff">Light Gray</option>     
<option value="666666ff">Dark Gray</option>     
<option value="ffccccff">Pink</option>     
<option value="00ffffff">Cyan</option>     
<option value="ff00ffff">Magenta</option> 
</select> </span> 
</td> 
</tr> 
<tr> 
<td> <span class="formlabel"> <span class="spaced"> <label for="bg">Background Colour:</label> </span> </span> 
<span class="formfield"> 
<select id="bg" name="bgColour" onchange="changeColour('bgColour', 'hlColour', 'fgColour'); setPreviewColours()">     
<option selected="selected" value="ffffffff">White</option>     
<option value="000000ff">Black</option>     
<option value="ff0000ff">Red</option>     
<option value="ffff00ff">Yellow</option>     
<option value="0000ffff">Blue</option>     
<option value="00ff00ff">Green</option>     
<option value="999999ff">Gray</option>     
<option value="ccccccff">Light Gray</option>     
<option value="666666ff">Dark Gray</option>     
<option value="ffccccff">Pink</option>     
<option value="00ffffff">Cyan</option>     
<option value="ff00ffff">Magenta</option> 
</select> </span> 
</td> 
</tr> 
<tr> 
<td> 
<span class="formlabel"> <span class="spaced"> <label for="hl">Highlight Colour:</label> </span> </span>
 <span class="formfield"> 
<select id="hl" name="hlColour" onchange="changeColour('hlColour', 'fgColour', 'bgColour'); setPreviewColours()">     
<option value="ffffffff">White</option>     
<option value="000000ff">Black</option>     
<option selected="selected" value="ff0000ff">Red</option>     
<option value="ffff00ff">Yellow</option>     
<option value="0000ffff">Blue</option>     
<option value="00ff00ff">Green</option>     
<option value="999999ff">Gray</option>     
<option value="ccccccff">Light Gray</option>     
<option value="666666ff">Dark Gray</option>     
<option value="ffccccff">Pink</option>     
<option value="00ffffff">Cyan</option>    
<option value="ff00ffff">Magenta</option> 
</select> </span> 
</td> 
</tr> 
<tr> 
<td> <input name="invertColours" value="no" type="hidden"> <!--
<span class="formlabel"> <span class="spaced">
Invert Colour Selection:
</span> </span>
<span class="formfield">
  <input type="radio" name="invertColours" id="ic1" value="true"
      />
  <label for="ic1">Yes</label>
  <input type="radio" name="invertColours" id="ic2" value="false"
      checked="checked"
      />
  <label for="ic2">No</label>
</span>
--> </td> </tr> </tbody> </table> <div id="previewArea" style="padding: 0em; border-bottom-width: 0px; margin-left: auto; margin-right: auto; font-weight: normal; width: 80%;"> <div id="previewText" style="border: 2px solid rgb(0, 0, 0); padding: 2em; width: 80%; color: rgb(255, 255, 255); background-color: rgb(0, 0, 0); font-family: monospace;">     Sample <span id="highlightedPreview" style="background-color: rgb(0, 255, 0); font-family: monospace;">Highlighted</span> Text  </div> </div>  </fieldset>
	

</fieldset>




  <p><a name="altToVisual"></a></p> 
<fieldset> <legend><strong>Color</strong></legend>  
<table border="0" cellpadding="3" width="100%"> 
<tbody> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Avoid Red: </span> </span> 
<span class="formfield">   
<input id="ar1" name="avoidRed" value="true" type="radio">   
<label for="ar1">Yes</label>   
<input checked="checked" id="ar2" name="avoidRed" value="false" type="radio">   
<label for="ar2">No</label> 
</span> 
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Avoid Red-Green: </span> </span> 
<span class="formfield">   
<input id="arg1" name="avoidRedGreen" value="true" type="radio">   
<label for="arg1">Yes</label>   
<input checked="checked" id="arg2" name="avoidRedGreen" value="false" type="radio">   
<label for="arg2">No</label> 
</span> 
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> 
<span class="spaced"> Avoid Blue-Yellow: </span> </span> 
<span class="formfield">   
<input id="aby1" name="avoidBlueYellow" value="true" type="radio">   
<label for="aby1">Yes</label>   
<input checked="checked" id="aby2" name="avoidBlueYellow" value="false" type="radio">   
<label for="aby2">No</label> </span> 
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Avoid Green-Yellow: </span> </span> 
<span class="formfield">   
<input id="agy1" name="avoidGreenYellow" value="true" type="radio">   
<label for="agy1">Yes</label>   
<input checked="checked" id="agy2" name="avoidGreenYellow" value="false" type="radio">   
<label for="agy2">No</label> 
</span> 
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Use Maximum Contrast Monochrome: </span> </span> 
<span class="formfield">   
<input id="umc1" name="useMaxContrast" value="true" type="radio">   
<label for="umc1">Yes</label>   
<input checked="checked" id="umc2" name="useMaxContrast" value="false" type="radio">   
<label for="umc2">No</label> 
</span> 
</td>
</tr> 
</tbody> 
</table>  
</fieldset> 

<fieldset> <legend><strong>Personal Stylesheet</strong>  </legend>  
<table border="0" cellpadding="3"> 
<tbody> 
<tr>
<td> 
<label for="ss"> <span style="color: red;">*</span> Personal Stylesheet URL: </label> 
</td>
<td> <input id="ss" name="ssURL" size="40" value="http://" type="file"  />
<input type="text" name="ssURL"  value="" />
</td>
</tr> 
</tbody> 
</table> 
</fieldset>
