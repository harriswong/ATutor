
 <fieldset>
<legend><strong>Text Alternatives</strong> </legend>  
<table border="0" cellpadding="3" width="100%"> 
<tbody> 
<tr>
<td> <span class="formlabel"> <span class="spaced"> Use Alternate Text: </span> </span> 
<span class="formfield">   
<input id="vt1" name="visualText" onclick="checkVisualText()" onkeypress="checkVisualText()" value="true" type="radio">   
<label for="vt1">Yes</label>   
<input checked="checked" id="vt2" name="visualText" onclick="checkVisualText()" onkeypress="checkVisualText()" value="false" type="radio">   <label for="vt2">No</label> 
</span> 
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> "Alt text" Language: </span> </span> 
<span class="formfield">   
<select disabled="disabled" id="altTextLang" name="altTextLang">       
<option selected="selected" value="en">English</option>       
<option value="fr">français</option>       
<option value="de">Deutsche</option>       
<option value="es">Española</option>       
<option value="it">Italiano</option>       
<option value="ta">Tamil</option>       
<option value="ur">Urdu</option>   
</select> 
</span> 
</td>
</tr> 
<tr>
<td> <span class="formlabel"> <span class="spaced"> Long Description Language: </span> </span> 
<span class="formfield">   
<select disabled="disabled" id="longDescLang" name="longDescLang">       
<option selected="selected" value="en">English</option>       
<option value="fr">français</option>       
<option value="de">Deutsche</option>       
<option value="es">Española</option>       
<option value="it">Italiano</option>       
<option value="ta">Tamil</option>       
<option value="ur">Urdu</option>   
</select> 
</span> 
</td>
</tr> 

 <tr>
<td> 
<span class="formlabel"> <span class="spaced"> Use Graphic Alternative: </span> </span> 
<span class="formfield">   
<input id="ga1" name="graphicAlt" value="true" type="radio">   
<label for="ga1">Yes</label>   
<input checked="checked" id="ga2" name="graphicAlt" value="false" type="radio">   
<label for="ga2">No</label> 
</span>
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Use Sign Language: </span> </span> 
<span class="formfield">   
<input id="tsl1" name="attSignLang" onclick="checkATTSignLang()" onkeypress="checkATTSignLang()" value="true" type="radio">  
<label for="tsl1">Yes</label>   
<input checked="checked" id="tsl2" name="attSignLang" onclick="checkATTSignLang()" onkeypress="checkATTSignLang()" value="false" type="radio">   
<label for="tsl2">No</label> 
</span>
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> <label for="signlangtype">Sign Language:</label> </span> </span> 
<span class="formfield">   
<select disabled="disabled" id="signlangtype" name="attSignLangVal">     
<option selected="selected" value="American-ASL">American-ASL</option>     
<option value="Australian-Auslan">Australian-Auslan</option>     
<option value="Austrian">Austrian</option>     
<option value="British-BSL">British-BSL</option>     
<option value="Danish-DSL">Danish-DSL</option>     
<option value="French-LSF">French-LSF</option>     
<option value="German-DGS">German-DGS</option>     
<option value="Irish-ISL">Irish-ISL</option>     
<option value="Italian-LIS">Italian-LIS</option>     
<option value="Japanese-JSL">Japanese-JSL</option>     
<option value="Malaysian-MSL">Malaysian-MSL</option>     
<option value="Mexican-LSM">Mexican-LSM</option>     
<option value="Native-American">Native-American</option>     
<option value="Norwegian-NSL">Norwegian-NSL</option>     
<option value="Russian-RSL">Russian-RSL</option>     
<option value="Quebec-LSQ">Quebec-LSQ</option>     
<option value="Singapore-SLS">Singapore-SLS</option>     
<option value="Netherlands-NGT">Netherlands-NGT</option>     
<option value="Spanish-LSE">Spanish-LSE</option>     
<option value="Swedish">Swedish</option>     
<option value="other">other</option>   
</select> 
</span>
</td>
</tr> 
</tbody>
</table>
</fieldset>
 <fieldset>
<legend><strong>Described Video</strong> </legend>  

<table border="0" cellpadding="3" width="100%"> 
<tbody> 
<tr>
<td> <span class="formlabel"> <span class="spaced"> Use Described Video: </span> </span>
<span class="formfield">   
<input checked="checked" id="ad1" name="audioDesc" onclick="checkAudioDesc()" onkeypress="checkAudioDesc()" value="true" type="radio">   <label for="ad1">Yes</label>   
<input id="ad2" name="audioDesc" onclick="checkAudioDesc()" onkeypress="checkAudioDesc()" value="false" type="radio">   
<label for="ad2">No</label> 
</span> 
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Preferred Language: </span> </span> 
<span class="formfield">   
<select id="audioDescLang" name="audioDescLang">       
<option selected="selected" value="en">English</option>       
<option value="fr">français</option>       
<option value="de">Deutsche</option>       
<option value="es">Española</option>      
<option value="it">Italiano</option>       
<option value="ta">Tamil</option>       
<option value="ur">Urdu</option>   
</select> 
</span> 
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Description Type: </span> </span> 
<span class="formfield">   
<input checked="checked" id="adt1" name="audioDescType" value="standard" type="radio">   
<label for="adt1">Standard</label>   
<input id="adt2" name="audioDescType" value="expanded" type="radio">   
<label for="adt2">Expanded</label> 
</span> 
</td> 
</tr> 
</tbody>
</table>
</fieldset>
<fieldset> <legend><strong>Captioning</strong>  </legend>  
<table border="0" cellpadding="3" width="100%"> 
<tbody> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Enable Captions: </span> </span> 
<span class="formfield">   
<input checked="checked" id="c1" name="caption" onclick="checkCaptions()" onkeypress="checkCaptions()" value="true" type="radio">   
<label for="c1">Yes</label>   
<input id="c2" name="caption" onclick="checkCaptions()" onkeypress="checkCaptions()" value="false" type="radio">   
<label for="c2">No</label> 
</span>
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> Caption Type: </span> </span> 
<span class="formfield">  
<input checked="checked" id="ct1" name="captionType" value="verbatim" type="radio">   
<label for="ct1">Verbatim</label>   
<input id="ct2" name="captionType" value="reducedReadingLevel" type="radio">   
<label for="ct2">Reduced Reading Level</label> 
</span>
</td>
</tr> 
<tr>
<td> <span class="formlabel"> <span class="spaced"> Caption Language: </span> </span> 
<span class="formfield">   
<select id="captionLang" name="captionLang">       
<option selected="selected" value="en">English</option>       
<option value="fr">français</option>       
<option value="de">Deutsche</option>       
<option value="es">Española</option>       
<option value="it">Italiano</option>       
<option value="ta">Tamil</option>       
<option value="ur">Urdu</option>   
</select> 
</span>
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> 
<span class="spaced"> Enhanced Captions: </span> </span> 
<span class="formfield">   
<input id="ec1" name="enhancedCaption" value="true" type="radio">   
<label for="ec1">Yes</label>   
<input checked="checked" id="ec2" name="enhancedCaption" value="false" type="radio">   
<label for="ec2">No</label> </span>
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> <span style="color: red;">*</span> Request Caption Rate: </span> </span> 
<span class="formfield">   
<input id="rs1" name="reducedSpeed" onclick="checkCaptionRate()" onkeypress="checkCaptionRate()" value="true" type="radio">   
<label for="rs1">Yes</label>   
<input checked="checked" id="rs2" name="reducedSpeed" onclick="checkCaptionRate()" onkeypress="checkCaptionRate()" value="false" type="radio">  <label for="rs2">No</label> 
</span>
</td>
</tr> 
<tr>
<td> 
<span class="formlabel"> <span class="spaced"> 
<label for="cr"> <span style="color: red;">*</span> Caption Rate (1 - 300 WPM):</label> </span> </span> 
<span class="formfield"> 
<input disabled="disabled" id="cr" name="captionRate" value="150" type="text"> 
</span>
</td>
</tr> 

</tbody> 
</table> 
</fieldset>

