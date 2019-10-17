# String Distance Implementation JS
A set of (string) distance functions written in JavaScript. Every function could be called with two representations of text to compute a distance, no matter if a bag of words represenations or a gram represenation, string represenation is used. The implementation is derived from 

Elena Deza / Michel-Marie Deza "Encyclopedia of Distances", Elsevier Science, 2009 (http://www.uco.es/users/ma1fegan/Comunes/asignaturas/vision/Encyclopedia-of-distances-2009.pdf)


# Functions
## 1. Weighted Levenshtein
INPUT: * s1 and s2 as representations, 
       * Wv a weight for pairs in A and B, 
       * Ws a list of 4 weights related to the operations substitution, insertion, deletion, exchange,
       
RETURN: Number of edited Letters / sum of editweights,

CALL: WLEV( A, B, Wv, Ws )

## 2. Dornau Levenshtein
INPUT: 
* a text representation s1 and s2,
* Ws a list of 4 weights related to the operations substitution, insertion, deletion, exchange,
       
RETURN: sum of editweights,

CALL: LEVDOR( s1, s2, Wv )

levenshtein( s1, s2, Wv )
LCS( vecA, vecB )
LCF( vecA, vecB )
containednessLCS( a, b )
containednessLCF( a, b )
LCP( vecA, vecB )
bagdist( vecA, vecB )
hamming( s1, s2, Wv )
JA( vecA, vecB )
JAWI( vecA, vecB )
baire( vecA, vecB )
generalizedcantor( vecA, vecB )
jaccardMASZzwei( vecA, vecB )
jaccardMASZ( vecA, vecB )
cosineMASZ( vecA, vecB )
quadradiffMASZ( vecA, vecB )
diceMASZ( vecA, vecB )
markingmetric( vecA, vecB )
setdiffmetric( vecA, vecB )

# Minimal Example


