
/*
    STRING DIST EVALUATION PROJECT
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
"use strict";

/*------------------------------------------------------------------------------
                     Programming Helper
------------------------------------------------------------------------------*/

function len( aA ){
    if( aA ){
        if( aA instanceof Set ){
            return aA.size;
        } else {
            let li = aA.length;
            if( li == undefined ){
                return Object.keys( aA ).length;
            }
            return li;
        }
    } else {
        return 0;
    }
}

function set( aA ){
    return new Set( aA );
}

function list( aS ){
    return Array.from( aS );
}

function print(  ){//call with arbitrary arguments and print the argumnet array
    for( let a in arguments ){
        console.log( a, arguments[a].toString() );
    }
}

function max( a, b ){
    return Math.max(a, b);
}

function min(a, b){
    return Math.min(a, b);
}

const True = true;
const False = false;


/*------------------------------------------------------------------------------
            SET OPERATIONS 
------------------------------------------------------------------------------*/

function SetSymDiff( setA, setB ){ 
    let AB = [...setA].filter(x => !setB.has( x ));
    let BA = [...setB].filter(x => !setA.has( x ));
    /*print(BA instanceof Array, AB instanceof Array);
    for( let item of AB ){
        BA.add( item );
    }*/
    let symDiff = set( AB.concat( BA ) );
    return symDiff;
}

function SetDiff( setA, setB ){ 
    return [...setA].filter(x => !setB.has( x ));
}

function SetUnsion( setA, setB ){
    let union = set( [...setA, ...setB] );
    return union;
}

function SetIntersection( setA, setB ){
    return [...setA].filter( x => setB.has( x ));
}


/*------------------------------------------------------------------------------
            generalized comparison: DISTANCES
------------------------------------------------------------------------------*/

//NOTE: all dist/Containedness/common functions take two arrays as first input, the array could be any representation of text (string, sequence, gram, selected words)

function WLEV( s1, s2, Wv, Ws ){ 
    /*
        NAME: weighted levenshtein, 
        INPUT: - s1 and s2 as representations, 
               - Wv a weight for pairs in A and B, 
               - Ws a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: Number of edited Letters / sum of editweights,

    */
    const lens1 = len( s1 );
    const lens2 = len( s2 );
    
    if( lens1 === 0 || lens2 === 0 ){ 
        return Infinity; 
    }
    
    if( Ws === undefined ){ //optional param
        Ws = {"hk": 2, "ui": 1}; //default values, to be extended
    }
    if( Wv === undefined ){ //optional param
        Wv = [1, 1, 1, 2]; //default values
    } 
    
    if( lens1 < lens2 ){
        return WLEV( s2, s1 );
    }
    
    let m = []; // is matrix
    let i;
    let j;
    // increment along the first column of each row
    for( i = 0; i <= lens2; i+=1 ){
      m[ i ] = [i];
    }
    // increment each column in the first row
    for( j = 0; j <= lens1; j+=1 ){
      m[0][j] = j;
    }
    // fill in the rest of the matrix
    for( i = 1; i <= lens2; i+=1 ){
      for( j = 1; j <= lens1; j+=1 ){
        if( s2[ i-1 ] === s1[ j-1 ] ){
          m[i][j] = m[i-1][j-1];
        } else {
            let charsum = s2[ i-1 ]+""+s1[ j-1 ];
            let weightofdigram = Ws[ charsum ];
            if( weightofdigram === undefined ){
                weightofdigram = 0;
            }
            if( 1 < i && 1 < j ){
                m[i][j] = min( 
                            min(
                                m[i-1][j-1] + Wv[0], //substitution
                                min(
                                    m[i][j-1] + Wv[1], //insertion
                                    m[i-1][j] + Wv[2])), //deletion
                            m[i-2][j-2] + Wv[3] ) //exchange
                         + weightofdigram; //digram weight
            } else {
                m[i][j] = min(m[i-1][j-1] + Wv[0], // substitution
                    min(m[i][j-1] + Wv[1], // insertion
                    m[i-1][j] + Wv[2])) // deletion
                        + weightofdigram; //digram weight
            }
        }
      }
    }
    return m[ lens2 ][ lens1 ]; //returns distnace similarity is 1 - (d/max(len(A,B)))
}

function LEVDAM( s1, s2, Wv ){ 
    /*
        NAME: damerau levenshtein,
        INPUT: - a text representation s1 and s2,
               - Wv a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: sum of editweights,

    */
    const lens1 = len( s1 );
    const lens2 = len( s2 );
    if( lens1 === 0 || lens2 === 0 ){ 
        return Infinity; 
    }
    if( Wv === undefined ){ //optional param
        Wv = [1, 1, 1, 2];
    }
    
    if( lens1 < lens2 ){
        return LEVDAM( s2, s1 );
    }
    
    let m = []; // is matrix
    let i;
    let j;
    
    // increment along the first column of each row
    for( i = 0; i <= lens2; i+=1 ){
      m[ i ] = [i];
    }
    // increment each column in the first row
    for( j = 0; j <= lens1; j+=1 ){
      m[0][j] = j;
    }
    // Fill in the rest of the matrix
    for( i = 1; i <= lens2; i+=1 ){
      for( j = 1; j <= lens1; j+=1 ){
        if( s2[ i-1 ] === s1[ j-1 ] ){
          m[i][j] = m[i-1][j-1];
        } else {
            if( 1 < i && 1 < j ){
                m[i][j] = min( 
                            min(
                                m[i-1][j-1] + Wv[0], //substitution
                                min(
                                    m[i][j-1] + Wv[1], //insertion
                                    m[i-1][j] + Wv[2])), //deletion
                            m[i-2][j-2] + Wv[3] ); //exchange
            } else {
                m[i][j] = min(m[i-1][j-1] + Wv[0], // substitution
                    min(m[i][j-1] + Wv[1], // insertion
                    m[i-1][j] + Wv[2])); // deletion
            }
        }
      }
    }
    return m[ lens2 ][ lens1 ]; //returns distnace similarity is 1 - (d/max(len(A,B)))
}


function levenshtein( s1, s2, Wv ){ 
    /*
        NAME: Levenshtein wie immer, weightable,
        INPUT: - s1 and s2 text representations,
               - Wv a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: number of edits,
    */
    
    const lens1 = len( s1 );
    const lens2 = len( s2 );
    if( lens1 === 0 || lens2 === 0 ){ 
        return Infinity; 
    }
    

    if( Wv === undefined ){ //optional param
        Wv = [1, 1, 1];
    }
    
    if( lens1 < lens2 ){
        return levenshtein( s2, s1 );
    }
    
    let m = []; // is matrix
    
    let i;
    let j;
    // increment along the first column of each row
    for( i = 0; i <= lens2; i+=1 ){
      m[ i ] = [i];
    }
    // increment each column in the first row
    for( j = 0; j <= lens1; j+=1 ){
      m[ 0 ][ j ] = j;
    }
    // Fill in the rest of the matrix
    for( i = 1; i <= lens2; i+=1 ){
      for( j = 1; j <= lens1; j+=1 ){
        if( s2[ i-1 ] === s1[ j-1 ] ){
          m[i][j] = m[i-1][j-1];
        } else {
          m[i][j] = min(
                        (m[i-1][j-1] + Wv[0]), // substitution
                        (min(
                            (m[i][j-1] + Wv[1]), // insertion
                            (m[i-1][j] + Wv[2])
                            )
                        )
                    ); // deletion
        }
      }
    }
    return m[ lens2 ][ lens1 ]; //returns distance; similarity is: 1 - (d/max(len(A,B)))
}

function LCS( vecA, vecB ){ 
    /*
        NAME: longest common subsequence (sequence is not substring, it is like sequencial but not next to eachother),
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant) and  max(len(A),len(B)) (not distant),
    */
    const lenA = len(vecA);
    const lenB = len(vecB);
    if( lenA === 0 || lenB === 0 ){ 
        return 0; 
    }

    let C = new Array( lenA ).fill( 0 ).map( () => new Array( lenB ).fill( 0 ));//[[0 for i in range(len(vecB))] for i in range(len(vecA))]
    for( let i = 0; i < lenA; i+=1 ){
        for( let j = 0; j < lenB; j+=1 ){
            if( vecA[i] === vecB[j] ){
                if( i !== 0 && j !== 0 ){
                    C[i][j] = C[i-1][j-1] + 1;
                } else {
                    C[i][j] = 1;
                }
            } else {
                if( i !== 0 && j !== 0 ){
                    C[i][j] = max( C[i][j-1], C[i-1][j] );
                }
            }
        }
    }
    return C[lenA-1][lenB-1]; //SEE containedness: LCS/len(A) for B contained A or LCS/len(B) for A contained B
}

function LCF( vecA, vecB ){ 
    /*
        NAME: longest common substring (factor, sequential and next to each other members of a vector),
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant, nothing in common) and  max(len(A),len(B)) (not distant),
    */
    const lenA = len(vecA);
    const lenB = len(vecB);
    if( lenA === 0 || lenB === 0 ){ 
        return 0; 
    }
    
    let C = new Array( lenA ).fill( 0 ).map( () => new Array( lenB ).fill( 0 ));
    let maxlen = 0;
    for( let i = 0; i < lenA; i+=1 ){
        for( let j = 0; j < lenB; j+=1 ){
            if( vecA[i] === vecB[j] ){
                if( i !== 0 && j !== 0 ){
                    C[i][j] = C[i-1][j-1] + 1;
                    if( maxlen < C[i][j] ){
                        maxlen = C[i][j];
                    }
                } else {
                    C[i][j] = 1;
                }
            } else {
                if( i !== 0 && j !== 0 ){
                    if( maxlen < C[i-1][j-1]){
                        maxlen = C[i-1][j-1];  
                    }
                }
                C[i][j] = 0;
            }
        }
    }
    
    return maxlen; 
} 

function containednessLCS( a, b ){
    /*
        NAME: according to LCS the containedness of a in b or b in a,
        INPUT: a and b text representations,
        RETURN: 1 (contained) and 0 (not contained),
    */
    const lenb = len(b);
    const lena = len(a);
    if( lena === 0 || lenb === 0 ){
        return 0;
    }
    let lcsab = LCS(a,b);
    if( lcsab === 0 ){
        return lcsab;
    } else {
        return max( lcsab/lena, lcsab/lenb );
    }
}

function containednessLCF( a, b ){
    /*
        NAME: according to LCF the containedness of a in b or b in a
        INPUT: a and b text representations
        RETURN: 1 (contained) and 0 (not contained),
    */
    const lenb = len(b);
    const lena = len(a);
    if( lena === 0 || lenb === 0 ){
        return 0;
    }
    let lcfab = LCF(a,b);
    if( lcfab === 0 ){
        return lcfab;
    } else {
        return max( lcfab/lena, lcfab/lenb);
    }
}

function LCP( vecA, vecB ){
    /*
        NAME: longest commen prefix,
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant) and  max(len(A),len(B)) (not distant),
    */
    let sizeofcommenprefix = 0;
    let lenMIN = min( len(vecA), len(vecB) );
    if( lenMIN === 0 ){ 
        return 0; 
    }
    let i;
    for( i = 0; i < lenMIN; i+=1 ){
        if( vecA[i] === vecB[i] ){
            sizeofcommenprefix += 1;
        } else {
            break;
        }
    }
    return sizeofcommenprefix;
}

function bagdist( vecA, vecB ){
    /*
        NAME: bag distance (vecA is a bag is a sequencial, and next to eachother, redundant vector), aproximation of levensthein,
        INPUT: vecA and vecB text representations,
        RETURN: max(len(A),len(B)) (distant) and 0 (not distant),
    */
    let eraseA = Array.from( vecA );
    const lenA = len( vecA );
    let eraseB = Array.from( vecB );
    const lenB = len( vecB );
    
    let sliceindex = -1;
    let i = 0;
    for( i = 0; i < lenA; i+=1 ){
        sliceindex = eraseB.indexOf( vecA[ i ] );
        if( sliceindex !== -1 ){
            eraseB[ sliceindex ] = false;
        }
    }
    let countinB = 0;
    for( i = 0; i < lenB; i+=1 ){
        if( eraseB[ i ] ){
            countinB+=1;
        }
    }
    sliceindex = -1;
    for( i = 0; i < lenB; i+=1 ){
        sliceindex = eraseA.indexOf( vecB[ i ] );
        if( sliceindex !== -1 ){
            eraseA[ sliceindex ] = false;
        }
    }
    let countinA = 0;
    for( i = 0; i < lenA; i+=1 ){
        if( eraseA[ i ] ){
            countinA+=1;
        }
    }
    
    return max( countinA, countinB );
}

function JA( vecA, vecB ){ 
    /* 
        NAME: jaro distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */

    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    }
    if( lenB < lenA ){
        return JA( vecB, vecA );
    }
    let maAB = max( lenB, lenA );
    /*if( lenA != lenB ){
        return maAB; //ist das der richtige rückgabewert
    }HÄ ????????????*/
    
    let matchDist = (maAB/2)-1;
    let Amatches = new Array( lenA ).fill( False );
    let Bmatches = new Array( lenB ).fill( False );
    let matchcount = 0;
    let traspocount = 0;

    let i;
    let j;
    for( i = 0; i < lenA; i+=1 ){
        let sta = Math.round(max( 0, i - matchDist ));
        let en = Math.round(min( i + matchDist + 1, lenB ));

        for( j = sta; j < en; j+= 1 ){
            if( Bmatches[j] ){
                continue;
            }
            if( vecA[i] !== vecB[j] ){
                continue;
            }
            Amatches[i] = True;
            Bmatches[j] = True;
            matchcount+=1;
            break;
        }
    }
    if( matchcount === 0 ){
        return maAB; //ist das der richtige rückgabewert 
    }

    j = 0;
    for( i = 0; i < lenA; i+=1 ){
        if( !Amatches[i] ){
            continue;
        }
        while( !Bmatches[j] ){
            j+=1;
        }
        if( vecA[i] !== vecB[i] ){
            traspocount += 1;
        }
        j+=1;
    }
    return ( ( (matchcount/lenA) + (matchcount/lenB) + (((matchcount-traspocount)/2)/matchcount)) / 3 );  
}

function JAWI( vecA, vecB ){ 
    /* 
        NAME: jaro winkler distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    }
    let onlyJaro = JA(vecA, vecB);
    return onlyJaro + ((max(4, LCP(vecA, vecB))/10)*(1-onlyJaro));
}

function baire( vecA, vecB ){
    /* 
        NAME: baire distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    }
    return ( 1 / (1 + LCP(vecA, vecB)) );
}

function generalizedcantor( vecA, vecB ){
    /* 
        NAME: gen. cantor distance, 
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    }
    return Math.pow( (1/Math.E), (1 + LCP(vecA, vecB)) ); //a 1/Math.E can also be 1/2
}

function notgeneralizedcantor( vecA, vecB ){
    /* 
        NAME: not gen. cantor distance, 
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    }
    return Math.pow( (1/Math.E), (1 + LCF(vecA, vecB)) ); 
}

function jaccardMASZzwei( vecA, vecB ){
    /* 
        NAME: derived from jaccard distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (not distant) and 0.0 (distant) ???,
    */ 
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    }
    let setA = set( vecA );
    let setB = set( vecB );
    return  (1.0 - parseFloat( parseFloat( len( SetSymDiff(setA, setB) )) / parseFloat( len( SetUnsion( setB, setA ) ) ) ) );
}

function jaccardMASZ( vecA, vecB ){
    /* 
        NAME: jaccard distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */ 
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    }
    let setA = set( vecA );
    let setB = set( vecB );
    return  (1.0 - parseFloat( parseFloat( len( SetIntersection(setA, setB) )) / parseFloat( len( SetUnsion( setB, setA ) ) ) ) );
}

function cosineMASZ( vecA, vecB ){ 
    /* 
        NAME: cosine distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    }  
    //müsste doch so klappen, oder was????
    let setA = set( vecA );
    let setB = set( vecB );
    let unionAB = list( SetUnsion( setA, setB ) );

    //occurenz count of gram in A or B
    let x = []; //A
    let y = []; //B
    const lenAB = len( unionAB );
    for( let i = 0; i < lenAB; i+=1 ){
        let currcount = 0;
        for(let j = 0; j < lenA; j+=1 ){
            if( unionAB[ i ] === vecA[ j ] ){
                currcount += 1;
            }
        }
        x.push( currcount );
        currcount = 0;
        for( let j = 0; j < lenB; j+=1 ){
            if( unionAB[ i ] === vecB[ j ] ){
                currcount += 1;
            }
        }
        y.push( currcount );
    }   
    let summederquadrateA = 0;
    let summederquadrateB = 0;
    let scalarprod = 0;
    const lenx = len(x);
    for( let u = 0; u < lenx; u+=1 ){
        summederquadrateA += x[ u ] * x[ u ]; 
        summederquadrateB += y[ u ] * y[ u ];
        scalarprod += x[ u ] * y[ u ];
    }
    let vecnormA = Math.sqrt( summederquadrateA );
    let vecnormB = Math.sqrt( summederquadrateB );
    return 1 - ( scalarprod/ ( vecnormA*vecnormB ) ); 
}     

function quadradiffMASZ( vecA, vecB ){ 
    /* 
        NAME: quadratic difference distance,
              # vec A and B are arrays of ngrams or silben, quadraDiff is a messure taken from the haufigkeitsvektor of A and B
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    } 
    //müsste doch so klappen, oder was????
    let setA = set(vecA);
    let setB = set(vecB);
    let unionAB = list( SetUnsion(setA, setB) );
    //occurenz count of gram in A or B
    let x = []; //A
    let y = []; //B  
    const lenAB = len( unionAB );
    for( let i = 0; i < lenAB; i+=1 ){
        let currcount = 0;
        for(let j = 0; j < lenA; j+=1 ){
            if( unionAB[ i ] === vecA[ j ] ){
                currcount += 1;
            }
        }
        x.push( currcount );
        currcount = 0;
        for( let j = 0; j < lenB; j+=1 ){
            if( unionAB[ i ] === vecB[ j ] ){
                currcount += 1;
            }
        }
        y.push( currcount );
    }   
    let sumitup = 0;
    const lenx = len( x );
    for( let u = 0; u < lenx; u+=1 ){
        sumitup += ( Math.abs(x[ u ] - y[ u ]) )*( Math.abs(x[ u ] - y[ u ]) );
    }
    return Math.sqrt( sumitup );
}

function diceMASZ( vecA, vecB ){
    /* 
        NAME: dice coefficent distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    } 
    let setA = set(vecA);
    let setB = set(vecB);
    return 1.0-parseFloat((2.0*parseFloat(len( SetIntersection(setA, setB) )))/parseFloat(len(setA)+len(setB)))
}

function markingmetric( vecA, vecB ){
    /* 
        NAME: marking distance,
              # https://www.sciencedirect.com/science/article/pii/0166218X88900765
              # wir untersuchen die Übergränge ist eine übergang nicht Teil des anderen, dann merke die position des buchstabens der in gelöscht werden muss, entweder einer oder beide
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    } 
    let posesA = [];
    let i = 0;
    for(i = 1; i < lenA; i++ ){
        let iba = vecB.indexOf( vecA[i-1] );
        let ibb = vecB.indexOf( vecA[i] );
        if( iba !== -1 && ibb !== -1 ){
            if( !(Math.abs(iba-ibb) === 1) ){
                posesA.push( i ); //völlig egal welcher index aufgeschrieben wird
            }
        } else {
            if( iba === -1 && ibb === -1 ){
                posesA.push( i-1 );
                posesA.push( i );
            } else {
                posesA.push( i-1 );
            }
        }
    }
    let posesB = [];
    for( i = 1; i < lenB; i++ ){
        let iaa = vecA.indexOf( vecB[i-1] );
        let iab = vecA.indexOf( vecB[i] );
        if( iaa !== -1 && iab !== -1 ){
            if( !(Math.abs(iaa-iab) === 1) ){
                posesB.push( i ); //völlig egal welcher index aufgeschrieben wird
            }
        } else {
            if( iaa === -1 && iab === -1 ){
                posesB.push( i-1 );
                posesB.push( i );
            } else {
                posesB.push( i-1 );
            }
        }
    }
    
    return Math.log( ( len(posesA)+1 )*( len(posesB)+1 ) );
}

function setdiffmetric( vecA, vecB ){
    /* 
        NAME: set diff distance, derived from marking metric, containedness gedanken
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    */
    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    } 
    let setA = set( vecA );
    let setB = set( vecB );
    let AB = SetDiff( setA, setB );
    let BA = SetDiff( setB, setA );
    const ABlen = len( AB );
    const BAlen = len( BA );
    return Math.log( ( ABlen+1 )*( BAlen+1 ) );
}

/*------------------------------------------------------------------------------
                     SPEZIELLE MASZE
------------------------------------------------------------------------------*/

function weightedngram( n, vecA, vecB, known ){
    let distis = Infinity;
    //known a letter statistic of ALL the corpus or a selected target subset or a in general asymetric heuristics
    //str1 is how distant from str2 and vise versa

    //at fisrt we come a Setdiffence of n-gram rep of str1 and str2
    //let vecA = ngram( str1, n, False );
    //let vecB = ngram( str2, n, False );
    /*const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    } 
    let setA = set( vecA );
    let setB = set( vecB );
    let AB = SetDiff( setA, setB );
    let BA = SetDiff( setB, setA );
    const ABlen = len( AB );
    const BAlen = len( BA );
    let sd = Math.log( ( ABlen+1 )*( BAlen+1 ) );*/

    const lenA = len( vecA );
    const lenB = len( vecB );
    if( lenA === 0 || lenB === 0 ){ 
        return Infinity; 
    } 
    let setA = set(vecA);
    let setB = set(vecB);
    distis = 1.0-parseFloat((2.0*parseFloat(len( SetIntersection(setA, setB) )))/parseFloat(len(setA)+len(setB)))
    if( distis != 1 ){
    
        let I = SetIntersection( setA, setB );
        let lI = len(I);
    
        //let W = 0;
        let WW = 0;
        
       /* for( let t = 0; t < lI; t += 1){
            let gram = I[t];
            let wtemp = 0;
            for( let r = 0; r < n; r += 1 ){
                if( known[ gram[r] ] ){
                    wtemp += (1-known[ gram[r] ]);
                } else {
                    wtemp += 1;
                }
            }
            wtemp /= n;
            W += wtemp;
        }
        */
        
        let nB = {};
        for( let t = 0; t < lI; t += 1){
            let gram = I[t];
            for( let r = 0; r < n; r += 1 ){
                if( nB[ gram[r] ] ){
                    nB[ gram[r] ] += 1;
                } else {
                    nB[ gram[r] ] = 1;
                }
            }
        }
        let countnb = 0;
        for( let n in nB ){
            if( known[ n ] ){
                WW += (1-known[ n ]);
            } else {
                WW += 1;
            }
            countnb += 1;
        }
        WW /= countnb;

        //W /= lI;

        /*if(distis*W != distis*WW){
            console.log(W, distis*W, WW, distis*WW, distis);
        }*/

        distis = distis*WW;

        
        
    }
    return distis;  
} 



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

