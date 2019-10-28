<?php

/*
    STRING DIST EVALUATION PROJECT (PHP Version)
    Prof. Charlotte Schubert, Alte Geschichte Leipzig 2019
    # GPLv3 copyrigth
    # This program is free software: you can redistribute it and/or modify
    # it under the terms of the GNU General Public License as published by
    # the Free Software Foundation, either version 3 of the License, or
    # (at your option) any later version.
    # This program is distributed in the hope that it will be useful,
    # but WITHOUT ANY WARRANTY; without even the implied warranty of
    # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    # GNU General Public License for more details.
    # You should have received a copy of the GNU General Public License
    # along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*------------------------------------------------------------------------------
                     Programming Helper
------------------------------------------------------------------------------*/
$Infinity = INF;
$Undefined = NULL;

function len( $athing ){
    if( is_array( $athing ) ){
        return sizeof( $athing );
    } elseif( is_string( $athing ) ){
        return strlen( $athing );
    } else {
        return NULL; //hm will this do
    }

}

function Arrayfrom( $anything ){
    if( is_array( $anything ) ){
        return $anything->getArrayCopy( );
    } elseif( is_string( $anything ) ){
        return str_split( $anything );
    } else {
        return NULL; //hm will this do
    }
}

function Setfrom( $anything ){
    if( is_array( $anything ) ){
        return array_unique( $anything->getArrayCopy( ) );
    } elseif( is_string( $anything ) ){
        return array_unique( str_split( $anything ));
    } else {
        return NULL; //hm will this do
    }
}

/*------------------------------------------------------------------------------
            SET OPERATIONS 
------------------------------------------------------------------------------*/


function SetDiff( $setA, $setB ){ 
    return array_diff( $setA, $setB );
}

function SetUnsion( $setA, $setB ){
    return array_unique( array_merge( $setA, $setB ) );
}

function SetIntersection( $setA, $setB ){
    return array_intersect( $setA, $setB );
}

function SetSymDiff( $setA, $setB ){ 
    $AB = SetDiff( $setA, $setB );
    $BA = SetDiff( $setB, $setA );    
    return SetUnsion( $AB, $BA );
}

/*
echo "Test Sets: [a, b], [b, c]<br>";
echo "Union: <br>";
print_r( SetUnsion(["a", "b"], ["b", "c"]));
echo "<br>Intersect: <br>";
print_r( SetIntersection(["a", "b"], ["b", "c"]));
echo "<br> Diff: <br>";
print_r( SetDiff(["a", "b"], ["b", "c"]));
echo "<br> SymDiff: <br>";
print_r( SetSymDiff(["a", "b"], ["b", "c"]));
*/

/*------------------------------------------------------------------------------
            generalized comparison: DISTANCES
------------------------------------------------------------------------------*/

function WLEV( $s1, $s2, $Wv = [1, 1, 1, 2], $Ws = ["hk" => 2, "ui" => 1] ){ 
    /*
        NAME: weighted levenshtein, 
        INPUT: - s1 and s2 as representations, 
               - Wv a weight for pairs in A and B, 
               - Ws a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: Number of edited Letters / sum of editweights,
    */
    $lens1 = len( $s1 );
    $lens2 = len( $s2 );
    
    if( $lens1 === 0 || $lens2 === 0 ){ 
        return $Infinity; 
    }
    
    if( $lens1 < $lens2 ){
        return WLEV( $s2, $s1 );
    }
    
    $m = array(); // is matrix
    
    // increment along the first column of each row
    for( $i = 0; $i <= $lens2; $i+=1 ){
      $m[ $i ] = array( $i );
    }
    // increment each column in the first row
    for( $j = 0; $j <= $lens1; $j+=1 ){
      $m[ 0 ][ $j ] = $j;
    }
    // fill in the rest of the matrix
    for( $i = 1; $i <= $lens2; $i+=1 ){
      for( $j = 1; $j <= $lens1; $j+=1 ){
        if( $s2[ $i-1 ] === $s1[ $j-1 ] ){
          $m[$i][$j] = $m[$i-1][$j-1];
        } else {
            $charsum = $s2[ $i-1 ]."".$s1[ $j-1 ];
            $weightofdigram = 0;
            if( isset( $Ws[ $charsum ] ) ){
                $weightofdigram = $Ws[ $charsum ];
            }
            if( 1 < $i && 1 < $j ){
                $m[$i][$j] = min( 
                            min(
                                $m[$i-1][$j-1] + $Wv[0], //substitution
                                min(
                                    $m[$i][$j-1] + $Wv[1], //insertion
                                    $m[$i-1][$j] + $Wv[2])), //deletion
                            $m[$i-2][$j-2] + $Wv[3] ) //exchange
                         + $weightofdigram; //digram weight
            } else {
                $m[$i][$j] = min($m[$i-1][$j-1] + $Wv[0], // substitution
                    min($m[$i][$j-1] + $Wv[1], // insertion
                    $m[$i-1][$j] + $Wv[2])) // deletion
                        + $weightofdigram; //digram weight
            }
        }
      }
    }
    return $m[ $lens2 ][ $lens1 ]; //returns distnace similarity is 1 - (d/max(len(A,B)))
}

//echo "<br> WLEV: <br>";
//echo  WLEV("Hallo", "gallim");

function LEVDAM( $s1, $s2, $Wv = [1, 1, 1, 2] ){ 
    /*
        NAME: damerau levenshtein,
        INPUT: - a text representation s1 and s2,
               - Ws a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: sum of editweights,
    */
    $lens1 = len( $s1 );
    $lens2 = len( $s2 );
    if( $lens1 === 0 || $lens2 === 0 ){ 
        return Infinity; 
    }
    
    if( $lens1 < $lens2 ){
        return LEVDAM( $s2, $s1 );
    }
    
    $m = array( ); // is matrix
    
    // increment along the first column of each row
    for( $i = 0; $i <= $lens2; $i+=1 ){
      $m[ $i ] = array( $i );
    }
    // increment each column in the first row
    for( $j = 0; $j <= $lens1; $j+=1 ){
      $m[ 0 ][ $j ] = $j;
    }
    // Fill in the rest of the matrix
    for( $i = 1; $i <= $lens2; $i+=1 ){
      for( $j = 1; $j <= $lens1; $j+=1 ){
        if( $s2[ $i-1 ] === $s1[ $j-1 ] ){
          $m[ $i ][ $j ] = $m[ $i-1 ][ $j-1 ];
        } else {
            if( 1 < $i && 1 < $j ){
                $m[ $i ][ $j ] = min( 
                            min(
                                $m[ $i-1 ][ $j-1 ] + $Wv[0], //substitution
                                min(
                                    $m[ $i ][ $j-1 ] + $Wv[1], //insertion
                                    $m[ $i-1 ][ $j ] + $Wv[2])), //deletion
                            $m[ $i-2 ][ $j-2 ] + $Wv[3] ); //exchange
            } else {
                $m[ $i ][ $j ] = min( $m[ $i-1 ][ $j-1 ] + $Wv[0], // substitution
                    min( $m[ $i ][ $j-1 ] + $Wv[1], // insertion
                    $m[ $i-1 ][ $j ] + $Wv[2])); // deletion
            }
        }
      }
    }
    return $m[ $lens2 ][ $lens1 ]; 
}

//echo "<br> LEVDAM: <br>";
//echo  LEVDAM("Hallo", "gallim");

function levenshteinMein( $s1, $s2, $Wv = [1, 1, 1] ){ 
    /*
        NAME: Levenshtein wie immer, weightable,
        INPUT: - s1 and s2 text representations,
               - Ws a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: number of edits,
    */
    
    $lens1 = len( $s1 );
    $lens2 = len( $s2 );
    if( $lens1 === 0 || $lens2 === 0 ){ 
        return $Infinity; 
    }
    
    if( $lens1 < $lens2 ){
        return levenshteinMein( $s2, $s1 );
    }
    
    $m = array( ); // is matrix
    
    // increment along the first column of each row
    for( $i = 0; $i <= $lens2; $i+=1 ){
      $m[ $i ] = array( $i );
    }
    // increment each column in the first row
    for( $j = 0; $j <= $lens1; $j+=1 ){
      $m[ 0 ][ $j ] = $j;
    }
    // Fill in the rest of the matrix
    for( $i = 1; $i <= $lens2; $i+=1 ){
      for( $j = 1; $j <= $lens1; $j+=1 ){
        if( $s2[ $i-1 ] === $s1[ $j-1 ] ){
          $m[ $i ][ $j ] = $m[ $i-1 ][ $j-1 ];
        } else {
          $m[ $i ][ $j ] = min(
                        ( $m[ $i-1 ][ $j-1 ] + $Wv[0] ), 
                        (min(
                            ( $m[ $i ][ $j-1 ] + $Wv[1] ), 
                            ( $m[ $i-1 ][ $j ] + $Wv[2] )
                            )
                        )
                    ); 
        }
      }
    }
    return $m[ $lens2 ][ $lens1 ]; 
}

//echo "<br> levenshtein: <br>";
//echo  levenshteinMein("Hallo", "gallim");
//echo "<br>levenshtein (PHP Version) <br>";
//echo  levenshtein("Hallo", "gallim");

function LCS( $vecA, $vecB ){ 
    /*
        NAME: longest common subsequence (sequence is not substring, it is like sequencial but not next to eachother),
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant) and  max(len(A),len(B)) (not distant),
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return 0; 
    }
    $C = array_fill( 0, $lenA, array_fill( 0, $lenB, 0) );
    for( $i = 0; $i < $lenA; $i+=1 ){
        for( $j = 0; $j < $lenB; $j+=1 ){
            if( $vecA[$i] === $vecB[$j] ){
                if( $i !== 0 && $j !== 0 ){
                    $C[$i][$j] = max( max( $C[$i][$j-1]+1, $C[$i-1][$j]+1 ), $C[$i-1][$j-1] + 1);
                } else {
                    $C[$i][$j] = 1;
                }
            } else {
                if( $i !== 0 && $j !== 0 ){
                    $C[$i][$j] = max( $C[$i][$j-1], $C[$i-1][$j] ); 
                }
            }
        }
    }
    return $C[$lenA-1][$lenB-1]; 
}

//echo "<br> LCS: <br>";
//echo  LCS("Halo", "galimo");

function LCF( $vecA, $vecB ){ 
    /*
        NAME: longest common substring (factor, sequential and next to each other members of a vector),
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant, nothing in common) and  max(len(A),len(B)) (not distant),
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return 0; 
    }
    
    $C = array_fill( 0, $lenA, array_fill( 0, $lenB, 0) );
    $maxlen = 0;
    for( $i = 0; $i < $lenA; $i+=1 ){
        for( $j = 0; $j < $lenB; $j+=1 ){
            if( $vecA[$i] === $vecB[$j] ){
                if( $i !== 0 && $j !== 0 ){
                    $C[$i][$j] = $C[$i-1][$j-1] + 1;
                    if( $maxlen < $C[$i][$j] ){
                        $maxlen = $C[$i][$j];
                    }
                } else {
                    $C[$i][$j] = 1;
                }
            } else {
                if( $i !== 0 && $j !== 0 ){
                    if( $maxlen < $C[$i-1][$j-1]){
                        $maxlen = $C[$i-1][$j-1];  
                    }
                }
                $C[$i][$j] = 0;
            }
        }
    }
    
    return $maxlen; 
} 

//echo "<br> LCF: <br>";
//echo  LCF("Halo", "galimo");

function containednessLCS( $a, $b ){
    /*
        NAME: according to LCS the containedness of a in b or b in a,
        INPUT: a and b text representations,
        RETURN: 1 (contained) and 0 (not contained),
    */
    $lenb = len( $b );
    $lena = len( $a );
    if( $lena === 0 || $lenb === 0 ){
        return 0;
    }
    $lcsab = LCS( $a, $b );
    return min( $lcsab/$lena, $lcsab/$lenb );
}

//echo "<br> containednessLCS: <br>";
//echo  containednessLCS("Halo", "galimo");

function containednessLCF( $a, $b ){
    /*
        NAME: according to LCF the containedness of a in b or b in a
        INPUT: a and b text representations
        RETURN: 1 (contained) and 0 (not contained),
    */
    $lenb = len( $b );
    $lena = len( $a );
    if( $lena === 0 || $lenb === 0 ){
        return 0;
    }
    $lcfab = LCF( $a, $b);
    return min( $lcfab/$lena, $lcfab/$lenb);
}

//echo "<br> containednessLCF: <br>";
//echo  containednessLCF("Halo", "galimo");

function LCP( $vecA, $vecB ){
    /*
        NAME: longest commen prefix,
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant) and  max(len(A),len(B)) (not distant),
    */
    $sizeofcommenprefix = 0;
    $lenMIN = min( len( $vecA ), len( $vecB ) );
    if( $lenMIN === 0 ){ 
        return 0; 
    }
    for( $i = 0; $i < $lenMIN; $i+=1 ){
        if( $vecA[$i] === $vecB[$i] ){
            $sizeofcommenprefix += 1;
        } else {
            break;
        }
    }
    return $sizeofcommenprefix;
}

//echo "<br> LCP: <br>";
//echo  LCP("Halo", "galimo");

function bagdist( $vecA, $vecB ){
    /*
        NAME: bag distance (vecA is a bag is a sequencial, and next to eachother, redundant vector), aproximation of levensthein,
        INPUT: vecA and vecB text representations,
        RETURN: max(len(A),len(B)) (distant) and 0 (not distant),
    */
    $eraseA = Arrayfrom( $vecA );
    $lenA = len( $vecA );
    $eraseB = Arrayfrom( $vecB );
    $lenB = len( $vecB );
    
    $sliceindex = -1;
    for( $i = 0; $i < $lenA; $i+=1 ){
        $sliceindex = array_search( $vecA[ $i ], $eraseB );
        if( $sliceindex !== False ){
            $eraseB[ $sliceindex ] = False;
        }
    }
    $countinB = 0;
    for( $i = 0; $i < $lenB; $i+=1 ){
        if( $eraseB[ $i ] ){
            $countinB+=1;
        }
    }
    $sliceindex = -1;
    for( $i = 0; $i < $lenB; $i+=1 ){
        $sliceindex = array_search( $vecB[ $i ], $eraseA );
        if( $sliceindex !== False ){
            $eraseA[ $sliceindex ] = False;
        }
    }
    $countinA = 0;
    for( $i = 0; $i < $lenA; $i+=1 ){
        if( $eraseA[ $i ] ){
            $countinA+=1;
        }
    }
    
    return max( $countinA, $countinB );
}

//echo "<br> bagdist: <br>";
//echo  bagdist("Halo", "galimo");

function JA( $vecA, $vecB ){ 
    /* 
        NAME: jaro distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */

    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }
    if( $lenB < $lenA ){
        return JA( $vecB, $vecA );
    }
    $maAB = max( $lenB, $lenA );
    
    
    $matchDist = ($maAB/2)-1;
    $Amatches = array_fill( 0, $lenA, False );
    $Bmatches = array_fill( 0, $lenB, False );
    $matchcount = 0;
    $traspocount = 0;

    for( $i = 0; $i < $lenA; $i+=1 ){
        $sta = round(max( 0, $i - $matchDist ));
        $en = round(min( $i + $matchDist + 1, $lenB ));

        for( $j = $sta; $j < $en; $j+= 1 ){
            if( $Bmatches[$j] ){
                continue;
            }
            if( $vecA[$i] !== $vecB[$j] ){ //String Offset cast???
                continue;
            }
            $Amatches[$i] = True;
            $Bmatches[$j] = True;
            $matchcount+=1;
            break;
        }
    }
    if( $matchcount === 0 ){
        return $maAB; 
    }
    $j = 0;
    for( $i = 0; $i < $lenA; $i+=1 ){
        if( !$Amatches[$i] ){
            continue;
        }
        while( !$Bmatches[$j] ){
            $j+=1;
        }
        if( $vecA[$i] !== $vecB[$i] ){
            $traspocount += 1;
        }
        $j+=1;
    }
    return ( ( ($matchcount/$lenA) + ($matchcount/$lenB) + ((($matchcount-$traspocount)/2)/$matchcount)) / 3 );  
}

//echo "<br> JA: <br>";
//echo  JA("Halo", "galimo"); //NOT WORKING PROP TESTING THAN

function JAWI( $vecA, $vecB ){ 
    /* 
        NAME: jaro winkler distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }
    $onlyJaro = JA( $vecA, $vecB );
    return $onlyJaro + ((max(4, LCP($vecA, $vecB))/10)*(1-$onlyJaro));
}

//echo "<br> JAWI: <br>";
//echo  JAWI("Halo", "galimo"); 

function baire( $vecA, $vecB ){
    /* 
        NAME: baire distance, (commen prefix)
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }
    return ( 1 / (1 + LCP( $vecA, $vecB ) ) );
}

//echo "<br> baire: <br>";
//echo  baire("Halo", "galimo");

function notbaire( $vecA, $vecB ){
    /* 
        NAME: not baire distance, just same notation
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }
    return ( 1 / (1 + LCF( $vecA, $vecB) ) );
}

//echo "<br> notbaire: <br>";
//echo  notbaire("Halo", "galimo");

function generalizedcantor( $vecA, $vecB ){
    /* 
        NAME: gen. cantor distance, 
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }
    return pow( (1/M_E), (1 + LCP( $vecA, $vecB ) ) ); 
}

//echo "<br> generalizedcantor: <br>";
//echo  generalizedcantor("Halo", "galimo");

function notgeneralizedcantor( $vecA, $vecB ){
    /* 
        NAME: gen. cantor distance, 
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }
    return pow( (1/M_E), (1 + LCF( $vecA, $vecB ) ) );
}

//echo "<br> notgeneralizedcantor: <br>";
//echo  notgeneralizedcantor("Halo", "galimo");

function jaccardMASZzwei( $vecA, $vecB ){
    /* 
        NAME: derived from jaccard distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (not distant) and 0.0 (distant) ???,
    */ 
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }
    $setA = Setfrom( $vecA );
    $setB = Setfrom( $vecB );
    
    return  (1.0 -  ( len( SetSymDiff($setA, $setB) ) /  len( SetUnsion( $setB, $setA ) ) )  );
}

//echo "<br> jaccardMASZzwei: <br>";
//echo  jaccardMASZzwei("Halo", "galimo");

function jaccardMASZ( $vecA, $vecB ){
    /* 
        NAME: jaccard distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */ 
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }
    $setA = Setfrom( $vecA );
    $setB = Setfrom( $vecB );
    return  (1.0 - ( len( SetIntersection( $setA, $setB ) ) / len( SetUnsion( $setB, $setA ) ) ) );
}

//echo "<br> jaccardMASZ: <br>";
//echo  jaccardMASZ("Halo", "galimo");

function cosineMASZ( $vecA, $vecB ){ 
    /* 
        NAME: cosine distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    }  
    //müsste doch so klappen, oder was????
    $setA = Setfrom( $vecA );
    $setB = Setfrom( $vecB );
    $unionAB = SetUnsion( $setA, $setB ) ;

    //occurenz count of gram in A or B
    $x = []; //A
    $y = []; //B
    $lenAB = len( $unionAB );
    for( $i = 0; $i < $lenAB; $i+=1 ){
        $currcount = 0;
        for( $j = 0; $j < $lenA; $j+=1 ){
            if( $unionAB[ $i ] == $vecA[ $j ] ){ //Undefined offset ???
                $currcount += 1;
            }
        }
        $x[] = $currcount; //array push
        $currcount = 0;
        for( $j = 0; $j < $lenB; $j+=1 ){
            if( $unionAB[ $i ] == $vecB[ $j ] ){
                $currcount += 1;
            }
        }
        $y[] = $currcount; //push
    }   
    $summederquadrateA = 0;
    $summederquadrateB = 0;
    $scalarprod = 0;
    $lenx = len( $x );
    for( $u = 0; $u < $lenx; $u+=1 ){
        $summederquadrateA += $x[ $u ] * $x[ $u ]; 
        $summederquadrateB += $y[ $u ] * $y[ $u ];
        $scalarprod += $x[ $u ] * $y[ $u ];
    }
    $vecnormA = sqrt( $summederquadrateA );
    $vecnormB = sqrt( $summederquadrateB );
    return 1 - ( $scalarprod/ ( $vecnormA * $vecnormB ) ); 
} 

//echo "<br> cosineMASZ: <br>";
//echo  cosineMASZ("Halo", "galimo");

function quadradiffMASZ( $vecA, $vecB ){ 
    /* 
        NAME: quadratic difference distance,
              # vec A and B are arrays of ngrams or silben, quadraDiff is a messure taken from the haufigkeitsvektor of A and B
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    } 
    //müsste doch so klappen, oder was????
    $setA = Setfrom( $vecA );
    $setB = Setfrom( $vecB );
    $unionAB = SetUnsion( $setA, $setB );
    //occurenz count of gram in A or B
    $x = []; //A
    $y = []; //B  
    $lenAB = len( $unionAB );
    for( $i = 0; $i < $lenAB; $i+=1 ){
        $currcount = 0;
        for( $j = 0; $j < $lenA; $j+=1 ){
            if( $unionAB[ $i ] === $vecA[ $j ] ){
                $currcount += 1;
            }
        }
        $x[] =  $currcount;
        $currcount = 0;
        for( $j = 0; $j < $lenB; $j+=1 ){
            if( $unionAB[ $i ] === $vecB[ $j ] ){
                $currcount += 1;
            }
        }
        $y[] = $currcount;
    }   
    $sumitup = 0;
    $lenx = len( $x );
    for( $u = 0; $u < $lenx; $u+=1 ){
        $sumitup += ( abs($x[ $u ] - $y[ $u ]) )*( abs($x[ $u ] - $y[ $u ]) );
    }
    return sqrt( $sumitup );
}

//echo "<br> quadradiffMASZ: <br>";
//echo  quadradiffMASZ("Halo", "galimo");

function diceMASZ( $vecA, $vecB ){
    /* 
        NAME: dice coefficent distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    } 
    $setA = Setfrom( $vecA );
    $setB = Setfrom( $vecB );
    return 1.0 - ((2.0 * len( SetIntersection( $setA, $setB ) )) / (len($setA)+len($setB)));
}

//echo "<br> diceMASZ: <br>";
//echo  diceMASZ("Halo", "galimo");

function markingmetric( $vecAl, $vecBl ){
    /* 
        NAME: marking distance,
              # https://www.sciencedirect.com/science/article/pii/0166218X88900765
              # wir untersuchen die Übergränge ist eine übergang nicht Teil des anderen, dann merke die position des buchstabens der in gelöscht werden muss, entweder einer oder beide
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */
    $vecA = Arrayfrom( $vecAl );
    $vecB = Arrayfrom( $vecBl );
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    } 
    $posesA = [];
    for( $i = 1; $i < $lenA; $i++ ){
        $iba = array_search( $vecA[ $i-1 ], $vecB );
        $ibb = array_search( $vecA[ $i ], $vecB );
        if( $iba !== False && $ibb !== False ){
            if( !(abs($iba-$ibb) === 1) ){
                $posesA[] = $i; //push
            }
        } else {
            if( $iba === False && $ibb === False ){
                $posesA[] = $i-1;
                $posesA[] =  $i;
            } else {
                $posesA[] = $i-1;
            }
        }
    }
    $posesB = [];
    for( $i = 1; $i < $lenB; $i++ ){
        $iaa = array_search( $vecB[ $i-1 ], $vecA );
        $iab = array_search( $vecB[ $i ], $vecA );
        if( $iaa !== False && $iab !== False ){
            if( !(abs($iaa-$iab) === 1) ){
                $posesB[] = $i; //push
            }
        } else {
            if( $iaa === False && $iab === False ){
                $posesB[] = $i-1;
                $posesB[] = $i;
            } else {
                $posesB[] = $i-1;
            }
        }
    }
    
    return log( ( len( $posesA )+1 ) * ( len( $posesB )+1 ) );
}

//echo "<br> markingmetric: <br>";
//echo  markingmetric("Halo", "galimo");

function setdiffmetric( $vecA, $vecB ){
    /* 
        NAME: set diff distance, derived from marking metric, containedness gedanken
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */
    $lenA = len( $vecA );
    $lenB = len( $vecB );
    if( $lenA === 0 || $lenB === 0 ){ 
        return $Infinity; 
    } 
    $setA = Setfrom( $vecA );
    $setB = Setfrom( $vecB );
    $AB = SetDiff( $setA, $setB );
    $BA = SetDiff( $setB, $setA );
    $ABlen = len( $AB );
    $BAlen = len( $BA );
    return log( ( $ABlen+1 )*( $BAlen+1 ) );
}

//echo "<br> setdiffmetric: <br>";
//echo  setdiffmetric("Halo", "galimo");

/*
    Usage Summary of distances:
WLEV( A, B, Wv, Ws )
LEVDAM( s1, s2, Wv )
levenshtein( s1, s2, Wv )
LCS( vecA, vecB )
LCF( vecA, vecB )
containednessLCS( a, b )
containednessLCF( a, b )
LCP( vecA, vecB )
bagdist( vecA, vecB )
JA( vecA, vecB )
JAWI( vecA, vecB )
baire( vecA, vecB )
notbaire( vecA, vecB )
generalizedcantor( vecA, vecB )
notgeneralizedcantor( vecA, vecB )
jaccardMASZzwei( vecA, vecB )
jaccardMASZ( vecA, vecB )
cosineMASZ( vecA, vecB )
quadradiffMASZ( vecA, vecB )
diceMASZ( vecA, vecB )
markingmetric( vecA, vecB )
setdiffmetric( vecA, vecB )

*/

function basictestMaze( $t0 = ["abcdefg"], $t1 = ["abcdefg", "hijklm", "", "abc", "dfg", "acg", "cdef", "cdeui", "acihef"] ){ 
    for( $p = 0;  $p < len( $t0 ); $p+=1 ){
        $stritocomp = $t0[ $p ];
        for( $t = 0; $t < len($t1); $t+=1 ){
            echo "<br><br><br><br>VERG: <br>"; 
            echo $stritocomp;
            echo "<br>--<br>";
            echo $t1[$t];
            $ti = microtime();
            echo "<br>WLEV: ";
            echo WLEV( $stritocomp, $t1[$t] );
            echo "<br>WLEV t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>LEVDAM: ";
            echo  LEVDAM( $stritocomp, $t1[$t] );
            echo "<br>LEVDAM t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>levenshtein: ";
            echo levenshtein( $stritocomp, $t1[$t] );
            echo "<br>levenshtein t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>levenshteinMein: ";
            echo levenshteinMein( $stritocomp, $t1[$t] );
            echo "<br>levenshteinMein t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>LCS: ";
            echo LCS( $stritocomp, $t1[$t] );
            echo "<br>LCS t: "; 
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>LCF: "; 
            echo LCF( $stritocomp, $t1[$t] );
            echo "<br>LCF t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>containednessLCS: ";
            echo containednessLCS( $stritocomp, $t1[$t] );
            echo "<br>containednessLCS t: ";
            echo  microtime()-$ti;
            $ti = microtime();
            echo "<br>containednessLCF: ";
            echo containednessLCF( $stritocomp, $t1[$t] );
            echo "<br>containednessLCF t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>LCP: ";
            echo LCP( $stritocomp, $t1[$t] );
            echo "<br>LCP t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>bagdist: ";
            echo bagdist( $stritocomp, $t1[$t] );
            echo "<br>bagdist t: ";
            echo  microtime()-$ti;
            $ti = microtime();
            echo "<br>JA: ";
            echo JA( $stritocomp, $t1[$t] );
            echo "<br>JA t: ";
            echo  microtime()-$ti;
            $ti = microtime();
            echo "<br>JAWI: ";
            echo JAWI( $stritocomp, $t1[$t] );
            echo "<br>JAWI t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>baire: ";
            echo baire( $stritocomp, $t1[$t] );
            echo "<br>baire t: ";
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>notbaire: ";
            echo notbaire( $stritocomp, $t1[$t] );
            echo "<br>notbaire t: ";
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>generalizedcantor: ";
            echo generalizedcantor( $stritocomp, $t1[$t] );
            echo "<br>generalizedcantor t: "; 
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>notgeneralizedcantor: ";
            echo notgeneralizedcantor( $stritocomp, $t1[$t] );
            echo "<br>notgeneralizedcantor t: "; 
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>jaccardMASZzwei: ";
            echo jaccardMASZzwei( $stritocomp, $t1[$t] );
            echo "<br>jaccardMASZzwei t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>jaccardMASZ: ";
            echo jaccardMASZ( $stritocomp, $t1[$t] );
            echo "<br>jaccardMASZ t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>cosineMASZ: ";
            echo cosineMASZ( $stritocomp, $t1[$t] );
            echo "<br>cosineMASZ t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>quadradiffMASZ: ";
            echo quadradiffMASZ( $stritocomp, $t1[$t] );
            echo "<br>quadradiffMASZ t: ";
            echo  microtime()-$ti;
            $ti = microtime();
            echo "<br>diceMASZ: ";
            echo diceMASZ( $stritocomp, $t1[$t] );
            echo "<br>diceMASZ t: ";  
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>markingmetric: ";
            echo markingmetric( $stritocomp, $t1[$t] );
            echo "<br>markingmetric t: "; 
            echo microtime()-$ti;
            $ti = microtime();
            echo "<br>setdiffmetric: ";
            echo setdiffmetric( $stritocomp, $t1[$t] );
            echo "<br>setdiffmetric t: ";
            echo microtime()-$ti;
            }
        }
    }

//basictestMaze();
?>



