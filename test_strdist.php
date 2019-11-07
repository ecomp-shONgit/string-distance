<?php  
mb_internal_encoding("UTF-8");
require_once('strdist.php');
$testfile = 'test_strdist.csv';
$rows = readCSVFile($testfile,';');
$html='';
$z=count($rows);
$i=0;
for( $y=0;$y<$z;$y++ )
  {
  $s1=$rows[$y][0];
  $s2=$rows[$y][1];

  $sd_wlev = WLEV($s1,$s2);
  $sd_levdam = LEVDAM($s1,$s2);
  $sd_lev = levenshteinMein($s1,$s2);
  $sd_lcs = LCS($s1,$s2);
  $sd_lcf = LCF($s1,$s2);
  $sd_clcs = containednessLCS($s1,$s2);
  $sd_slcf = containednessLCF($s1,$s2);
  $sd_lcp = LCP($s1,$s2);
  $sd_bd = bagdist($s1,$s2);
  $sd_ja = JA($s1,$s2);
  $sd_jawi = JAWI($s1,$s2);
  $sd_nb = notbaire($s1,$s2);
  $sd_gc = generalizedcantor($s1,$s2);
  $sd_ngc = notgeneralizedcantor($s1,$s2);
  $sd_jac2 = jaccardMASZzwei($s1,$s2);
  $sd_jac = jaccardMASZ($s1,$s2);
  $sd_coma = cosineMASZ($s1,$s2);
  $sd_quama = quadradiffMASZ($s1,$s2);
  $sd_dice = diceMASZ($s1,$s2);
  $sd_mm = markingmetric($s1,$s2);
  $sd_sm = setdiffmetric($s1,$s2);
  $sd_ham = '';
 

  $css=($y%2==1)?'':' lightgrey';  
  $html.='
  <tr class="tablerow'.$css.'" data-rownumber="'.$i.'">
    <td id="tdc_'.$i.'_0">'.$s1.'</td> 
    
    <td id="tdc_'.$i.'_1">'.$s2.'</td> 

    <td class="p" id="tdc_'.$i.'_3">'.$sd_wlev.'</td> 
    
    <td class="j" id="tdc_'.$i.'_4">&nbsp;</td>    

    <td class="p" id="tdc_'.$i.'_5">'.$sd_levdam.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_6">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_7">'.$sd_lev.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_8">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_9">'.$sd_lcs.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_10">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_11">'.$sd_lcf.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_12">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_13">'.$sd_clcs.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_14">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_15">'.$sd_slcf.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_16">&nbsp;</td>        
    
    <td class="p" id="tdc_'.$i.'_17">'.$sd_lcp.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_18">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_19">'.$sd_bd.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_20">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_21">'.$sd_ja.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_22">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_23">'.$sd_jawi.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_24">&nbsp;</td>        
    
    <td class="p" id="tdc_'.$i.'_25">'.$sd_nb.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_26">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_27">'.$sd_gc.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_28">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_29">'.$sd_ngc.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_30">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_31">'.$sd_jac2.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_32">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_33">'.$sd_jac.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_34">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_35">'.$sd_coma.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_36">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_37">'.$sd_quama.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_38">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_39">'.$sd_dice.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_40">&nbsp;</td>    
    
    <td class="p" id="tdc_'.$i.'_41">'.$sd_mm.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_42">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_43">'.$sd_sm.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_44">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_45">'.$sd_ham.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_46">&nbsp;</td>                             
  </tr>'; 
   
  $i+=1;
  $s11 = translitString($s1,"NFD; [:Nonspacing Mark:] Remove; NFC;");
  $s22 = translitString($s2,"NFD; [:Nonspacing Mark:] Remove; NFC;"); 
  
  $sd_wlev = WLEV($s11,$s22);
  $sd_levdam = LEVDAM($s11,$s22);
  $sd_lev = levenshteinMein($s11,$s22);
  $sd_lcs = LCS($s11,$s22);
  $sd_lcf = LCF($s11,$s22);
  $sd_clcs = containednessLCS($s11,$s22);
  $sd_slcf = containednessLCF($s11,$s22);
  $sd_lcp = LCP($s11,$s22);
  $sd_bd = bagdist($s11,$s22);
  $sd_ja = JA($s11,$s22);
  $sd_jawi = JAWI($s11,$s22);
  $sd_nb = notbaire($s11,$s22);
  $sd_gc = generalizedcantor($s11,$s22);
  $sd_ngc = notgeneralizedcantor($s11,$s22);
  $sd_jac2 = jaccardMASZzwei($s11,$s22);
  $sd_jac = jaccardMASZ($s11,$s22);
  $sd_coma = cosineMASZ($s11,$s22);
  $sd_quama = quadradiffMASZ($s11,$s22);
  $sd_dice = diceMASZ($s11,$s22);
  $sd_mm = markingmetric($s11,$s22);
  $sd_sm = setdiffmetric($s11,$s22);  
  $sd_ham = '';
    
  $html.='
  <tr class="tablerow'.$css.'" data-rownumber="'.$i.'">
    <td id="tdc_'.$i.'_0">'.$s11.'</td> 
    
    <td id="tdc_'.$i.'_1">'.$s22.'</td> 
    
    <td class="p" id="tdc_'.$i.'_3">'.$sd_wlev.'</td> 
    
    <td class="j" id="tdc_'.$i.'_4">&nbsp;</td>    

    <td class="p" id="tdc_'.$i.'_5">'.$sd_levdam.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_6">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_7">'.$sd_lev.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_8">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_9">'.$sd_lcs.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_10">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_11">'.$sd_lcf.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_12">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_13">'.$sd_clcs.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_14">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_15">'.$sd_slcf.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_16">&nbsp;</td>        
    
    <td class="p" id="tdc_'.$i.'_17">'.$sd_lcp.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_18">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_19">'.$sd_bd.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_20">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_21">'.$sd_ja.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_22">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_23">'.$sd_jawi.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_24">&nbsp;</td>        
    
    <td class="p" id="tdc_'.$i.'_25">'.$sd_nb.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_26">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_27">'.$sd_gc.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_28">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_29">'.$sd_ngc.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_30">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_31">'.$sd_jac2.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_32">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_33">'.$sd_jac.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_34">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_35">'.$sd_coma.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_36">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_37">'.$sd_quama.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_38">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_39">'.$sd_dice.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_40">&nbsp;</td>    
    
    <td class="p" id="tdc_'.$i.'_41">'.$sd_mm.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_42">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_43">'.$sd_sm.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_44">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_45">'.$sd_ham.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_46">&nbsp;</td>                             
  </tr>'; 
   
  $i+=1;
  
  $s111 = translitString($s1,"Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC;");  
  $s222 = translitString($s2,"Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC;");  
    
  $sd_wlev = WLEV($s111,$s222);
  $sd_levdam = LEVDAM($s111,$s222);
  $sd_lev = levenshteinMein($s111,$s222);
  $sd_lcs = LCS($s111,$s222);
  $sd_lcf = LCF($s111,$s222);
  $sd_clcs = containednessLCS($s111,$s222);
  $sd_slcf = containednessLCF($s111,$s222);
  $sd_lcp = LCP($s111,$s222);
  $sd_bd = bagdist($s111,$s222);
  $sd_ja = JA($s111,$s222);
  $sd_jawi = JAWI($s111,$s222);
  $sd_nb = notbaire($s111,$s222);
  $sd_gc = generalizedcantor($s111,$s222);
  $sd_ngc = notgeneralizedcantor($s111,$s222);
  $sd_jac2 = jaccardMASZzwei($s111,$s222);
  $sd_jac = jaccardMASZ($s111,$s222);
  $sd_coma = cosineMASZ($s111,$s222);
  $sd_quama = quadradiffMASZ($s111,$s222);
  $sd_dice = diceMASZ($s111,$s222);
  $sd_mm = markingmetric($s111,$s222);
  $sd_sm = setdiffmetric($s111,$s222);  
  $sd_ham = '';
      
  $html.='
  <tr class="tablerow'.$css.'" data-rownumber="'.$i.'">
    <td id="tdc_'.$i.'_0">'.$s111.'</td> 
    
    <td id="tdc_'.$i.'_1">'.$s222.'</td> 
    
    <td class="p" id="tdc_'.$i.'_3">'.$sd_wlev.'</td> 
    
    <td class="j" id="tdc_'.$i.'_4">&nbsp;</td>    

    <td class="p" id="tdc_'.$i.'_5">'.$sd_levdam.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_6">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_7">'.$sd_lev.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_8">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_9">'.$sd_lcs.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_10">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_11">'.$sd_lcf.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_12">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_13">'.$sd_clcs.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_14">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_15">'.$sd_slcf.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_16">&nbsp;</td>        
    
    <td class="p" id="tdc_'.$i.'_17">'.$sd_lcp.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_18">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_19">'.$sd_bd.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_20">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_21">'.$sd_ja.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_22">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_23">'.$sd_jawi.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_24">&nbsp;</td>        
    
    <td class="p" id="tdc_'.$i.'_25">'.$sd_nb.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_26">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_27">'.$sd_gc.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_28">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_29">'.$sd_ngc.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_30">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_31">'.$sd_jac2.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_32">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_33">'.$sd_jac.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_34">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_35">'.$sd_coma.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_36">&nbsp;</td>   

    <td class="p" id="tdc_'.$i.'_37">'.$sd_quama.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_38">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_39">'.$sd_dice.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_40">&nbsp;</td>    
    
    <td class="p" id="tdc_'.$i.'_41">'.$sd_mm.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_42">&nbsp;</td>       
    
    <td class="p" id="tdc_'.$i.'_43">'.$sd_sm.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_44">&nbsp;</td>   
    
    <td class="p" id="tdc_'.$i.'_45">'.$sd_ham.'</td> 
    
    <td class="j"  id="tdc_'.$i.'_46">&nbsp;</td>                             
  </tr>'; 
         

  $i+=1;
    
  }
$html.='
</table>
  </body>
</html>
';
?>

<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta charset="utf-8">
    <script src="strdist.js?v1=<?php echo time(); ?>"></script> 
    
    <script>
      document.addEventListener("DOMContentLoaded", function(event) {
        
        document.querySelectorAll(".tablerow").forEach(function(userItem) {
        
        var id = userItem.getAttribute("data-rownumber"); 
        var s1 = document.querySelector("#tdc_"+id+"_0").innerHTML;
        var s2 = document.querySelector("#tdc_"+id+"_1").innerHTML;        
        
        document.querySelector("#tdc_"+id+"_4").innerHTML=WLEV(s1,s2);
        document.querySelector("#tdc_"+id+"_6").innerHTML=LEVDAM(s1,s2);   
        document.querySelector("#tdc_"+id+"_8").innerHTML=levenshtein(s1,s2);  
        document.querySelector("#tdc_"+id+"_10").innerHTML=LCS(s1,s2);  
        document.querySelector("#tdc_"+id+"_12").innerHTML=LCF(s1,s2);    
        document.querySelector("#tdc_"+id+"_14").innerHTML=containednessLCS(s1,s2);
        document.querySelector("#tdc_"+id+"_16").innerHTML=containednessLCF(s1,s2);
        document.querySelector("#tdc_"+id+"_18").innerHTML=LCP(s1,s2);
        document.querySelector("#tdc_"+id+"_20").innerHTML=bagdist(s1,s2);
        document.querySelector("#tdc_"+id+"_22").innerHTML=JA(s1,s2);
        document.querySelector("#tdc_"+id+"_24").innerHTML=JAWI(s1,s2);
        document.querySelector("#tdc_"+id+"_26").innerHTML=baire(s1,s2);
        document.querySelector("#tdc_"+id+"_28").innerHTML=generalizedcantor(s1,s2);
        document.querySelector("#tdc_"+id+"_30").innerHTML='';   // notgeneralizedcantor ???
        document.querySelector("#tdc_"+id+"_32").innerHTML=jaccardMASZzwei(s1,s2);  
        document.querySelector("#tdc_"+id+"_34").innerHTML=jaccardMASZ(s1,s2);
        document.querySelector("#tdc_"+id+"_36").innerHTML=cosineMASZ(s1,s2);
        document.querySelector("#tdc_"+id+"_38").innerHTML=quadradiffMASZ(s1,s2);
        document.querySelector("#tdc_"+id+"_40").innerHTML=diceMASZ(s1,s2);
        document.querySelector("#tdc_"+id+"_42").innerHTML=markingmetric(s1,s2);
        document.querySelector("#tdc_"+id+"_44").innerHTML=setdiffmetric(s1,s2);
        document.querySelector("#tdc_"+id+"_46").innerHTML=hamming(s1,s2);        
      
        });

      });    
    </script>
    
    <style>
    .lightgrey {background-color: #ededed;}
    .p {color: blue;}
    .j {color: green;}
    
    </style>      
  </head>
  
  <body>
<span class="p">PHP</span>&nbsp;<span class="j">JS</span><br>
<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td>string 1</td>
    
    <td>string 2</td> 

    <td colspan="2">WLEV</td>  

    <td colspan="2">LEVDAM</td>

    <td colspan="2">levenshteinMein</td>   
      
    <td colspan="2">LCS</td>

    <td colspan="2">LFf</td>   
    
    <td colspan="2">containednessLCS</td>

    <td colspan="2">containednessLCF</td>

    <td colspan="2">LCP</td>

    <td colspan="2">bagdist</td>
    
    <td colspan="2">JA</td>
    
    <td colspan="2">JAWI</td>
    
    <td colspan="2">notbaire</td>
    
    <td colspan="2">generalizedcantor</td>

    <td colspan="2">notgeneralizedcantor</td>
    
    <td colspan="2">jaccardMASZzwei</td>
    
    <td colspan="2">jaccardMASZ</td>

    <td colspan="2">cosineMASZ</td>
    
    <td colspan="2">quadradiffMASZ</td>
    
    <td colspan="2">diceMASC</td>

    <td colspan="2">markingmetric</td>
    
    <td colspan="2">setdiffmetric</td>
    
    <td colspan="2">hamming</td>
           
  </tr>

<?php
echo $html;


#########################################
function readCSVFile($file,$deli=';')
  {
  $r = array();
  if( file_exists($file) )
    {
    if (($h = fopen($file, "r")) !== FALSE)
      {
      while (($d = fgetcsv($h, 10000, $deli)) !== FALSE)
        {
        $r[] = $d;           
        }//end while
      fclose($h); 
      }//end if
    }//end if 
 
  return $r;     
  }//end function readCSVFile


function translitString($str,$ruleset="Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC;")
  {
   # for more infos about the translitare see
   # ICU User Guide
   # http://userguide.icu-project.org/transforms/general#TOC-Guidelines-for-Script-Language-Transliterations
   # http://www.eki.ee/wgrs/
 
  $str = transliterator_transliterate($ruleset, $str);
  
  return $str;
  }//end function
?>