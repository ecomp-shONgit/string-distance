#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import time, math, copy, sys, os

'''

STRING DIST EVALUATION PROJECT 

Prof. Charlotte Schubert, Alte Geschichte Leipzig 2019

    # GPLv3 copyrigth
    # This program is free software: you can redistribute it and/or modify
    # it under the terms of the GNU General Public License as published by
    # the Free Software Foundation, either version 3 of the License, or
    # (at your option) any later version.
    # This program is distributed in the hope that it will be useful,
    # but WITHOUT ANY WARRANTY without even the implied warranty of
    # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    # GNU General Public License for more details.
    # You should have received a copy of the GNU General Public License
    # along with this program.  If not, see <http:#www.gnu.org/licenses/>. 

'''


'''PYTHON3'''

Infinity = math.inf

def cooppyy( atocopy ):
    #PYTHON
    return copy.deepcopy( atocopy )

def doarrayfrom( a ):
    #JS #Array.from( vecA )
    #PYTHON
    if isinstance( a, str ):
        return cooppyy( list( a ) )
    else:
        return cooppyy( a )

def parseFloat( f ):
    return float( f )        

'''------------------------------------------------------------------------------
            SET OPERATIONS 
------------------------------------------------------------------------------'''


def SetSymDiff( setA, setB ): 
    #JS 
    #let AB = [...setA].filter(x => !setB.has( x ));
    #let BA = [...setB].filter(x => !setA.has( x ));
    #/*print(BA instanceof Array, AB instanceof Array);
    #for( let item of AB ){
    #    BA.add( item );
    #}*/
    #let symDiff = new Set( AB.concat( BA ) );
    #return symDiff;
    #Python:^, In A und nicht in B in B und nicht in A
    return setA ^ setB 


def SetDiff( setA, setB ):
    #JS    
    #return [...setA].filter(x => !setB.has( x ));
    #Python:-, in A und nicht in B
    return setA - setB

def SetUnsion( setA, setB ):
    #JS    
    #let union = new Set( [...setA, ...setB] );
    #return union;
    #Python: |, in A und B
    return setA | setB


def SetIntersection( setA, setB ):
    #JS
    #return [...setA].filter( x => setB.has( x ));
    #Python: &
    return setA & setB



'''------------------------------------------------------------------------------
            generalized comparison: DISTANCES
------------------------------------------------------------------------------'''

#NOTE: all dist/containedness/common defs take two arrays as first input, the array could be any representation of text (string, sequence, gram, selected words)

def WLEV( s1, s2, Ws={"hk": 2, "ui": 1}, Wv=[1, 1, 1, 2] ): 
    '''
        NAME: weighted levenshtein, 
        INPUT: - s1 and s2 as representations, 
               - Wv a weight for pairs in A and B, 
               - Ws a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: Number of edited Letters / sum of editweights,
    '''
    lens1 = len( s1 )+1
    lens2 = len( s2 )+1
    
    if( lens1 == 0 or lens2 == 0 ): 
        return Infinity
        
    
    if( lens1 < lens2 ):
        return WLEV( s2, s1 )
    
    
    m = [[0 for col in range(lens1)] for row in range(lens2)] # is matrix

    # increment along the first column of each row
    for i in range( lens2 ):
      m[ i ][ 0 ] = i
    
    # increment each column in the first row
    for j in range( lens1 ):
      
      m[0][j] = j
    
    # fill in the rest of the matrix
    for i in range(1, lens2 ):
      for j in range(1, lens1 ):
        if( s2[ i-1 ] == s1[ j-1 ] ):
          m[i][j] = m[i-1][j-1]
        else:
            charsum = s2[ i-1 ]+""+s1[ j-1 ]
            weightofdigram = 0
            try:
                weightofdigram = Ws[ charsum ]
            except:
                1+1
            
            if( 1 < i and 1 < j ):
                m[i][j] = min( min(m[i-1][j-1] + Wv[0], min(m[i][j-1] + Wv[1], m[i-1][j] + Wv[2])), m[i-2][j-2] + Wv[3] ) + weightofdigram 
            else:
                m[i][j] = min(m[i-1][j-1] + Wv[0], min(m[i][j-1] + Wv[1], m[i-1][j] + Wv[2])) + weightofdigram 
    return m[ lens2-1 ][ lens1-1 ] #returns distnace similarity is 1 - (d/max(len(A,B)))


def LEVDAM( s1, s2, Wv = [1, 1, 1, 2] ):
    '''
        NAME: damerau levenshtein,
        INPUT: - a text representation s1 and s2,
               - Ws a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: sum of editweights,
    '''
    lens1 = len( s1 )+1
    lens2 = len( s2 )+1
    if( lens1 == 0 or lens2 == 0 ):
        return Infinity 
    
    if( lens1 < lens2 ):
        return LEVDAM( s2, s1 )

    
    
    m = [[0 for col in range(lens1)] for row in range(lens2)] # is matrix
    
    # increment along the first column of each row
    for i in range( lens2 ):
      m[ i ][ 0 ] = i
    
    # increment each column in the first row
    for j in range( lens1 ):
      m[0][j] = j
    
    # Fill in the rest of the matrix
    for i in range(1,lens2):
      for j in range(1, lens1):
        if( s2[ i-1 ] == s1[ j-1 ] ):
          m[i][j] = m[i-1][j-1]
        else:
            if( 1 < i and 1 < j ):
                m[i][j] = min( 
                            min(
                                m[i-1][j-1] + Wv[0], #substitution
                                min(
                                    m[i][j-1] + Wv[1], #insertion
                                    m[i-1][j] + Wv[2])), #deletion
                            m[i-2][j-2] + Wv[3] ) #exchange
            else:
                m[i][j] = min(m[i-1][j-1] + Wv[0], # substitution
                    min(m[i][j-1] + Wv[1], # insertion
                    m[i-1][j] + Wv[2])) # deletion
    return m[ lens2-1 ][ lens1-1 ] #returns distnace similarity is 1 - (d/max(len(A,B)))



def levenshtein( s1, s2, Wv = [1, 1, 1] ):
    '''
        NAME: Levenshtein wie immer, weightable,
        INPUT: - s1 and s2 text representations,
               - Ws a list of 4 weights related to the operations 
                 substitution, insertion, deletion, exchange,
        RETURN: number of edits,
    '''
    
    lens1 = len( s1 )+1
    lens2 = len( s2 )+1
    if( lens1 == 0 or lens2 == 0 ):
        return Infinity 

    
    if( lens1 < lens2 ):
        return levenshtein( s2, s1 )
    
    
    m = [[0 for col in range(lens1)] for row in range(lens2)] # is matrix
    
    
    # increment along the first column of each row
    for i in range( lens2 ):
      m[ i ][ 0 ] = i
    
    # increment each column in the first row
    for j in range( lens1 ):
      m[ 0 ][ j ] = j
    
    # Fill in the rest of the matrix
    for i in range( 1, lens2 ):
      for j in range( 1, lens1  ):
        if( s2[ i-1 ] == s1[ j-1 ] ):
          m[i][j] = m[i-1][j-1]
        else:
          m[i][j] = min(
                        (m[i-1][j-1] + Wv[0]), # substitution
                        (min(
                            (m[i][j-1] + Wv[1]), # insertion
                            (m[i-1][j] + Wv[2])
                            )
                        )
                    ) # deletion
    
    return m[ lens2-1 ][ lens1-1 ] #returns distance similarity is: 1 - (d/max(len(A,B)))


def LCS( vecA, vecB ):
    '''
        NAME: longest common subsequence (sequence is not substring, it is like sequencial but not next to eachother),
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant) and  max(len(A),len(B)) (not distant),
    '''
    lenA = len(vecA)
    lenB = len(vecB)
    if( lenA == 0 or lenB == 0 ):
        return 0 
    
    C = [[0 for col in range(lenB)] for row in range(lenA)]#new Array( lenA ).fill( 0 ).map( () => new Array( lenB ).fill( 0 ))#[[0 for i in range(len(vecB))] for i in range(len(vecA))]
    
    for i in range( lenA ):
        for j in range( lenB ):
            if( vecA[i] == vecB[j] ):
                if( i != 0 and j != 0 ):
                    C[i][j] = max( max( C[i][j-1]+1, C[i-1][j]+1 ), C[i-1][j-1] + 1)
                else:
                    C[i][j] = 1
                
            else:
                if( i != 0 and j != 0 ):
                    C[i][j] = max( C[i][j-1], C[i-1][j] ) 
                    
    return C[lenA-1][lenB-1] #SEE containedness: LCS/len(A) for B contained A or LCS/len(B) for A contained B


def LCF( vecA, vecB ):
    '''
        NAME: longest common substring (factor, sequential and next to each other members of a vector),
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant, nothing in common) and  max(len(A),len(B)) (not distant),
    '''
    lenA = len(vecA)
    lenB = len(vecB)
    if( lenA == 0 or lenB == 0 ):
        return 0 
    
    
    C = [[0 for col in range(lenB)] for row in range(lenA)]#new Array( lenA ).fill( 0 ).map( () => new Array( lenB ).fill( 0 ))
    maxlen = 0
    for i in range( lenA ):
        for j in range( lenB ):
            
            if( vecA[i] == vecB[j] ):
                if( i != 0 and j != 0 ):
                    C[i][j] = C[i-1][j-1] + 1
                    if( maxlen < C[i][j] ):
                        maxlen = C[i][j]
                    
                else:
                    C[i][j] = 1
                
            else:
                if( i != 0 and j != 0 ):
                    if( maxlen < C[i-1][j-1] ):
                        maxlen = C[i-1][j-1]  
                C[i][j] = 0
    return maxlen 


def containednessLCS( a, b ):
    '''
        NAME: according to LCS the containedness of a in b or b in a,
        INPUT: a and b text representations,
        RETURN: 1 (contained) and 0 (not contained),
    '''
    lenb = len(b)
    lena = len(a)
    if( lena == 0 or lenb == 0 ):
        return 0
    
    lcsab = LCS(a,b)
    if lcsab == 0:
        return lcsab
    else:
        return max( lcsab/lena, lcsab/lenb )


def containednessLCF( a, b ):
    '''
        NAME: according to LCF the containedness of a in b or b in a
        INPUT: a and b text representations
        RETURN: 1 (contained) and 0 (not contained),
    '''
    lenb = len(b)
    lena = len(a)
    if( lena == 0 or lenb == 0 ):
        return 0
    
    lcfab = LCF(a,b)
    if lcfab == 0:
        return lcfab
    else:
        return max( lcfab/lena, lcfab/lenb)


def LCP( vecA, vecB ):
    '''
        NAME: longest commen prefix,
        INPUT: vecA and vecB text representations,
        RETURN: 0 (distant) and  max(len(A),len(B)) (not distant),
    '''
    sizeofcommenprefix = 0
    lenMIN = min( len(vecA), len(vecB) )
    if( lenMIN == 0 ):
        return 0 
    
    
    for i in range( lenMIN ):
        if( vecA[i] == vecB[i] ):
            sizeofcommenprefix += 1
        else:
            break
    return sizeofcommenprefix


def bagdist( vecA, vecB ):
    '''
        NAME: bag distance (vecA is a bag is a sequencial, and next to eachother, redundant vector), aproximation of levensthein,
        INPUT: vecA and vecB text representations,
        RETURN: max(len(A),len(B)) (distant) and 0 (not distant),
    '''
    
    eraseA = doarrayfrom( vecA )
    lenA = len( vecA )
    eraseB = doarrayfrom( vecB )
    lenB = len( vecB )
    
    sliceindex = -1
    i = 0
    for i in range( lenA ):
        try:
            sliceindex = eraseB.index( vecA[ i ] )
            eraseB[ sliceindex ] = False
        except:
            pass
        
    countinB = 0
    for i in range( lenB ):
        if( eraseB[ i ] ):
            countinB+=1
     
    sliceindex = -1
    for i in range( lenB ):
        try:
            sliceindex = eraseA.index( vecB[ i ] )
        
            eraseA[ sliceindex ] = False
        except:
            pass
    countinA = 0
    for i in range( lenA ):
        if( eraseA[ i ] ):
            countinA+=1
    return max( countinA, countinB )

def JA( vecA, vecB ):
    ''' 
        NAME: jaro distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    '''

    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ): 
        return Infinity 
    
    if( lenB < lenA ):
        return JA( vecB, vecA )
    
    maAB = max( lenB, lenA )
    
    matchDist = (maAB/2)-1
    Amatches = [False for col in range(lenA)]#new Array( lenA ).fill( False )
    Bmatches = [False for col in range(lenB)]#new Array( lenB ).fill( False )
    matchcount = 0
    traspocount = 0
    for i in range( lenA ):
        sta = round(max( 0, i - matchDist ))
        en = round(min( i + matchDist + 1, lenB ))
        #print(sta, en, math.floor( sta), math.floor( en) )
        for j in range( sta, en ):
            if( Bmatches[j] ):
                continue
            
            if( vecA[i] != vecB[j] ):
                continue
            
            Amatches[i] = True
            Bmatches[j] = True
            matchcount+=1
            break
        
    
    if( matchcount == 0 ):
        return maAB #ist das der richtige rückgabewert 
    

    j = 0
    for i in range( lenA ):
        if( not Amatches[i] ):
            continue
        
        while( not Bmatches[j] ):
            j+=1
        
        if( vecA[i] != vecB[i] ):
            traspocount += 1
        
        j+=1
    
    return ( ( (matchcount/lenA) + (matchcount/lenB) + (((matchcount-traspocount)/2)/matchcount)) / 3 )  


def JAWI( vecA, vecB ): 
    ''' 
        NAME: jaro winkler distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    onlyJaro = JA(vecA, vecB)
    return onlyJaro + ((max(4, LCP(vecA, vecB))/10)*(1-onlyJaro))


def baire( vecA, vecB ):
    ''' 
        NAME: baire distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    return ( 1 / (1 + LCP(vecA, vecB)) )

def notbaire( vecA, vecB ):
    ''' 
        NAME: not baire distance, just same notation
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    return ( 1 / (1 + LCP(vecA, vecB)) )


def generalizedcantor( vecA, vecB ):
    ''' 
        NAME: gen. cantor distance, 
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    return math.pow( (1/math.e), (1 + LCP(vecA, vecB)) ) #a 1/Math.E can also be 1/2

def notgeneralizedcantor( vecA, vecB ):
    ''' 
        NAME: not gen. cantor distance, 
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    return math.pow( (1/math.e), (1 + LCF(vecA, vecB)) ) 


def jaccardMASZzwei( vecA, vecB ):
    ''' 
        NAME: derived from jaccard distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (not distant) and 0.0 (distant) ???,
    ''' 
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    setA = set( vecA )
    setB = set( vecB )
    return  (1.0 - parseFloat( parseFloat( len( SetSymDiff(setA, setB) )) / parseFloat( len( SetUnsion( setB, setA ) ) ) ) )


def jaccardMASZ( vecA, vecB ):
    ''' 
        NAME: jaccard distance, transpositions,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    ''' 
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    setA = set( vecA )
    setB = set( vecB )
    return  (1.0 - parseFloat( parseFloat( len( SetIntersection(setA, setB) )) / parseFloat( len( SetUnsion( setB, setA ) ) ) ) )


def cosineMASZ( vecA, vecB ):
    ''' 
        NAME: cosine distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    #müsste doch so klappen, oder was????
    setA = set( vecA )
    setB = set( vecB )
    unionAB = list( SetUnsion( setA, setB ) )

    #occurenz count of gram in A or B
    x = [] #A
    y = [] #B
    lenAB = len( unionAB )
    for i in range( lenAB ):
        currcount = 0
        for j in range( lenA ):
            if( unionAB[ i ] == vecA[ j ] ):
                currcount += 1       
        x.append( currcount )
        currcount = 0
        for j in range( lenB ):
            if( unionAB[ i ] == vecB[ j ] ):
                currcount += 1
        y.append( currcount )
    
    summederquadrateA = 0
    summederquadrateB = 0
    scalarprod = 0
    lenx = len(x)
    for u in range( lenx ):
        summederquadrateA += x[ u ] * x[ u ] 
        summederquadrateB += y[ u ] * y[ u ]
        scalarprod += x[ u ] * y[ u ]
    
    vecnormA = math.sqrt( summederquadrateA )
    vecnormB = math.sqrt( summederquadrateB )
    return 1 - ( scalarprod/ ( vecnormA*vecnormB ) ) 


def quadradiffMASZ( vecA, vecB ):
    ''' 
        NAME: quadratic difference distance,
              # vec A and B are arrays of ngrams or silben, quadraDiff is a messure taken from the haufigkeitsvektor of A and B
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    #müsste doch so klappen, oder was????
    setA = set(vecA)
    setB = set(vecB)
    unionAB = list( SetUnsion(setA, setB) )
    #occurenz count of gram in A or B
    x = [] #A
    y = [] #B  
    lenAB = len( unionAB )
    for i in range( lenAB ):
        currcount = 0
        for j in range( lenA ):
            if( unionAB[ i ] == vecA[ j ] ):
                currcount += 1
            
        
        x.append( currcount )
        currcount = 0
        for j in range( lenB ):
            if( unionAB[ i ] == vecB[ j ] ):
                currcount += 1
        
        y.append( currcount )
      
    sumitup = 0
    lenx = len( x )
    for u in range( lenx ):
        sumitup += ( abs(x[ u ] - y[ u ]) )*( abs(x[ u ] - y[ u ]) )
    
    return math.sqrt( sumitup )


def diceMASZ( vecA, vecB ):
    ''' 
        NAME: dice coefficent distance,
        INPUT: vecA, vecB text represenations,
        RETURN: Inf/1 (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ): 
        return Infinity 
    
    setA = set(vecA)
    setB = set(vecB)
    return 1.0-parseFloat((2.0*parseFloat(len( SetIntersection(setA, setB) )))/parseFloat(len(setA)+len(setB)))


def markingmetric( vecA, vecB ):
    ''' 
        NAME: marking distance,
              # https:#www.sciencedirect.com/science/article/pii/0166218X88900765
              # wir untersuchen die Übergränge ist eine übergang nicht Teil des anderen, dann merke die position des buchstabens der in gelöscht werden muss, entweder einer oder beide
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    posesA = []
    
    for i in range( 1, lenA ):
        iba = -1
        try: 
            iba = vecB.index( vecA[i-1] )
        except:
            pass
        
        ibb = -1 
        try:
            ibb = vecB.index( vecA[i] )
        except:
            pass

        if( iba != -1 and ibb != -1 ):
            
            if( not (abs(iba-ibb) == 1) ):
                posesA.append( i ) 
            
        else:
            if( iba == -1 and ibb == -1 ):
                posesA.append( i-1 )
                posesA.append( i )
            else:
                posesA.append( i-1 )
          
        
    
    posesB = []
    for i in range( 1, lenB ):
        iaa = -1
        try:
            iaa = vecA.index( vecB[i-1] )
        except:
            pass
        iab = -1
        try:
            iab = vecA.index( vecB[i] )
        except:
            pass
        if( iaa != -1 and iab != -1 ):
            if( not (abs(iaa-iab) == 1) ):
                posesB.append( i ) 
            
        else:
            if( iaa == -1 and iab == -1 ):
                posesB.append( i-1 )
                posesB.append( i )
            else:
                posesB.append( i-1 )          
    return math.log( ( len(posesA)+1 )*( len(posesB)+1 ) )


def setdiffmetric( vecA, vecB ):
    ''' 
        NAME: set diff distance, derived from marking metric, containedness gedanken
        INPUT: vecA, vecB text represenations,
        RETURN: Inf (distant) and 0.0 (not distant) ???,
    '''
    lenA = len( vecA )
    lenB = len( vecB )
    if( lenA == 0 or lenB == 0 ):
        return Infinity 
    
    setA = set( vecA )
    setB = set( vecB )
    AB = SetDiff( setA, setB )
    BA = SetDiff( setB, setA )
    ABlen = len( AB )
    BAlen = len( BA )
    return math.log( ( ABlen+1 )*( BAlen+1 ) )

#markingmetric


def basictestMaze( t0 = ["abcdefg"], t1 = ["abcdefg", "hijklm", "", "abc", "dfg", "acg", "cdef", "cdeui", "acihef"] ):
    
    for p in range( len( t0 ) ):
        stritocomp = t0[ p ]
    
        for t in range( len(t1) ):
            print( "VERG: \n", stritocomp,"\n--\n", t1[t] )
            
            ti = time.time()
            print( "WLEV", WLEV( stritocomp, t1[t] ) )
            print( "WLEV t",  time.time()-ti )
            ti = time.time()
            print( "LEVDAM",  LEVDAM( stritocomp, t1[t] ) )
            print( "LEVDAM t",  time.time()-ti )
            ti = time.time()
            print( "levenshtein",levenshtein( stritocomp, t1[t] ) )
            print( "levenshtein t",  time.time()-ti )
            ti = time.time()
            print( "LCS",LCS( stritocomp, t1[t] ) )
            print( "LCS t",  time.time()-ti )
            ti = time.time()
            print( "LCF", LCF( stritocomp, t1[t] ) )
            print( "LCF t",  time.time()-ti )
            ti = time.time()
            print( "containednessLCS",containednessLCS( stritocomp, t1[t] ) )
            print( "containednessLCS t",  time.time()-ti )
            ti = time.time()
            print( "containednessLCF",containednessLCF( stritocomp, t1[t] ) )
            print( "containednessLCF t",  time.time()-ti )
            ti = time.time()
            print( "LCP",LCP( stritocomp, t1[t] ) )
            print( "LCP t",  time.time()-ti )
            ti = time.time()
            print( "bagdist",bagdist( stritocomp, t1[t] ) )
            print( "bagdist t",  time.time()-ti )
            ti = time.time()
            print( "JA",JA( stritocomp, t1[t] ) )
            print( "JA t",  time.time()-ti )
            ti = time.time()
            print( "JAWI",JAWI( stritocomp, t1[t] ) )
            print( "JAWI t",  time.time()-ti )
            ti = time.time()
            print( "baire",baire( stritocomp, t1[t] ) )
            print( "baire t",  time.time()-ti )
            ti = time.time()
            print( "generalizedcantor",generalizedcantor( stritocomp, t1[t] ) )
            print( "generalizedcantor t",  time.time()-ti )
            ti = time.time()
            print( "jaccardMASZzwei",jaccardMASZzwei( stritocomp, t1[t] ) )
            print( "jaccardMASZzwei t",  time.time()-ti )
            ti = time.time()
            print( "jaccardMASZ",jaccardMASZ( stritocomp, t1[t] ) )
            print( "jaccardMASZ t",  time.time()-ti )
            ti = time.time()
            print( "cosineMASZ",cosineMASZ( stritocomp, t1[t] ) )
            print( "cosineMASZ t",  time.time()-ti )
            ti = time.time()
            print( "quadradiffMASZ",quadradiffMASZ( stritocomp, t1[t] ) )
            print( "quadradiffMASZ t",  time.time()-ti )
            ti = time.time()
            print( "diceMASZ",diceMASZ( stritocomp, t1[t] ) )
            print( "diceMASZ t",  time.time()-ti )
            ti = time.time()
            print( "markingmetric",markingmetric( stritocomp, t1[t] ) )
            print( "markingmetric t",  time.time()-ti )
            ti = time.time()
            print( "setdiffmetric",setdiffmetric( stritocomp, t1[t] ) )
            print( "setdiffmetric t",  time.time()-ti )
 


if __name__ == "__main__":
    presi = 3
    #python3 strdist.py DISTTYPE str1 str2
    argvb = list(map(os.fsencode, sys.argv))
    
    sys.argv[ 2 ] = argvb[2].decode("utf-8")
    sys.argv[ 3 ] = argvb[3].decode("utf-8")
    if( len( sys.argv ) == 4 ):
        #print(list(a), len(a), "----", sys.argv[3], len(sys.argv[3]))
        if( sys.argv[1] == "WLEV" ):
            print( WLEV( sys.argv[ 2 ], sys.argv[ 3 ] ) );
        elif( sys.argv[1] == "LEVDAM" ):
            print( LEVDAM( sys.argv[ 2 ], sys.argv[ 3 ] ) );
        elif( sys.argv[1] == "levenshtein" ):
            print( levenshtein( sys.argv[ 2 ], sys.argv[ 3 ] ) );
        elif( sys.argv[1] == "LCS" ):
            print( LCS( sys.argv[ 2 ], sys.argv[ 3 ] ) );
        elif( sys.argv[1] == "LCF" ):
            print( LCF( sys.argv[ 2 ], sys.argv[ 3 ] ) );
        elif( sys.argv[1] == "containednessLCS" ):
            print( round(containednessLCS( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "containednessLCF" ):
            print( round(containednessLCF( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "LCP" ):
            print( LCP( sys.argv[ 2 ], sys.argv[ 3 ] ) );
        elif( sys.argv[1] == "bagdist" ):
            print( round(bagdist( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "JA" ):
            print( round(JA( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "JAWI" ):
            print( round(JAWI( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "baire" ):
            print( round(baire( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "notbaire" ):
            print( round(notbaire( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "generalizedcantor" ):
            print( round(generalizedcantor( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "notgeneralizedcantor" ):
            print( round(notgeneralizedcantor( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "jaccardMASZzwei" ):
            print( round(jaccardMASZzwei( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "jaccardMASZ" ):
            print( round(jaccardMASZ( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "cosineMASZ" ):
            print( round(cosineMASZ( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "quadradiffMASZ" ):
            print( round(quadradiffMASZ( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "diceMASZ" ):
            print( round(diceMASZ( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "markingmetric" ):
            print( round(markingmetric( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "setdiffmetric" ):
            print( round(setdiffmetric( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        elif( sys.argv[1] == "all" ):
            print( WLEV( sys.argv[ 2 ], sys.argv[ 3 ] ), ";", LEVDAM( sys.argv[ 2 ], sys.argv[ 3 ] ), ";", levenshtein( sys.argv[ 2 ], sys.argv[ 3 ] ), ";", LCS( sys.argv[ 2 ], sys.argv[ 3 ] ), ";", LCF( sys.argv[ 2 ], sys.argv[ 3 ] ), ";", round(containednessLCS( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(containednessLCF( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", LCP( sys.argv[ 2 ], sys.argv[ 3 ] ), ";", round(bagdist( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(JA( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(JAWI( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(baire( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(notbaire( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(generalizedcantor( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(notgeneralizedcantor( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(jaccardMASZzwei( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(jaccardMASZ( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(cosineMASZ( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(quadradiffMASZ( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(diceMASZ( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(markingmetric( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(setdiffmetric( sys.argv[ 2 ], sys.argv[ 3 ] ),presi), ";", round(setdiffmetric( sys.argv[ 2 ], sys.argv[ 3 ] ),presi) );
        else:
            print( "none" )
    elif( len( sys.argv ) == 3 ): 
        basictestMaze([sys.argv[1]],[sys.argv[2]]);
    else:
        print("String Dist Test")
        basictestMaze();
        basictestMaze(["καὶ τὸν αὑτοῦ πατέρα, ἐμὲ μὲν διδάσκοντι, ἐκεῖνον δὲ νουθε‐"], ["καὶ τὸν αὑτοῦ πατέρα, ἐμὲ μὲν διδάσκοντι, ἐκεῖνον δὲ νουθε‐"]);
        basictestMaze(["αὶ τὸν αὑτοῦ πατέρα, ἐμὲ μὲν διδάσκοντι, ἐκεῖνον δὲ νουθε‐"],["καὶ τὸν αὑτοῦ πατέρα, ἐμὲ μὲν διδάσκοντι, ἐκεῖνον δὲ νουθε‐"]);
        basictestMaze(["κα τὸν αὑτοῦ πατέρα, ἐμὲ μὲν διδάσκοντι, ἐκεῖνον δὲ νουθε‐"],["καὶ τὸν αὑτοῦ πατέρα, ἐμὲ μὲν διδάσκοντι, ἐκεῖνον δὲ νουθε‐"]);
        basictestMaze(["καὶ τὸν αὑτοῦ πατέρα, ἐμὲ μὲν διδάσκοντι, ἐκεῖνον δὲ νουθε‐"],["καὶ τὸν πατέρα, ἐμὲ μὲν διδάσκοντι, ἐκεῖνον δὲ νουθε‐"]);

    #print("----------")
'''
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
notbagdist( vecA, vecB )
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
'''
