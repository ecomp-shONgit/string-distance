# String Distance Implementation JS
A set of (string) distance functions written in JavaScript. Every function could be called with two representations of text to compute a distance, no matter if a bag of words represenations or a ngram represenation, string represenation is used. The implementation is derived from 

Elena Deza / Michel-Marie Deza "Encyclopedia of Distances", Elsevier Science, 2009 (http://www.uco.es/users/ma1fegan/Comunes/asignaturas/vision/Encyclopedia-of-distances-2009.pdf)


# Functions
## 1. Weighted Levenshtein
*INPUT:*
* s1 and s2 as representations, 
* Wv a weight for pairs in A and B, 
* Ws a list of 4 weights related to the operations substitution, insertion, deletion, exchange,
       
*RETURN:* Number of edited Letters / sum of editweights,

*CALL:* WLEV( A, B, Wv, Ws )

## 2. Dornau Levenshtein
*INPUT:* 
* a text representation s1 and s2,
* Ws a list of 4 weights related to the operations substitution, insertion, deletion, exchange,
       
*RETURN:* sum of editweights,

*CALL:* LEVDOR( s1, s2, Wv )

## 3. Levenshtein
*INPUT:* 
* s1 and s2 text representations,
* Ws a list of 4 weights related to the operations substitution, insertion, deletion,
        
*RETURN:* number of edits,

*CALL:* levenshtein( s1, s2, Wv )

## 4. Longest Common Subsequence 
*NOTE:* sequnece is not substring, it is like sequencial but not next to eachother,

*INPUT:* vecA and vecB text representations,
        
*RETURN:* 0 (distant) and  max(len(A),len(B)) (not distant),

*CALL:* LCS( vecA, vecB )

## 5. Longest Common Substring 
*NOTE:* factor, sequential and next to each other members of a vector,
            
*INPUT:* vecA and vecB text representations,

*RETURN:* 0 (distant, nothing in common) and  max(len(A),len(B)) (not distant),

*CALL:* LCF( vecA, vecB )

## 6. containednessLCS
*NOTE:* according to LCS the containedness of a in b or b in a,

*INPUT:* a and b text representations,

*RETURN:* 1 (contained) and 0 (not contained),

*CALL:* containednessLCS( a, b )

## 7. containednessLCF
*NOTE:* according to LCF the containedness of a in b or b in a,

*INPUT:* a and b text representations,

*RETURN:* 1 (contained) and 0 (not contained),

*CALL:* containednessLCF( a, b )

## 8. Longest Commen Prefix,
*INPUT:* vecA and vecB text representations,

*RETURN:* 0 (distant) and  max(len(A),len(B)) (not distant),

*CALL:* LCP( vecA, vecB )

## 9. Bag Distance
*NOTE:* vecA/vecB is a bag is a sequencial, and next to eachother, redundant vector, aproximation of levensthein,

*INPUT:* vecA and vecB text representations,

*RETURN:* max(len(A),len(B)) (distant) and 0 (not distant),

*CALL:* bagdist( vecA, vecB )

## 10. Hamming Dist
*NOTE:* special case of levenshstein,
*INPUT:*
* s1, s2 representations of txt              
* Wv a 3 array of operation weights, CAN BE NUMBER,

*CALL:* hamming( s1, s2, Wv )

## 11. Jaro Distance
*INPUT:* vecA, vecB text represenations,

*RETURN:* Inf (distant) and 0.0 (not distant) (?),

*CALL:* JA( vecA, vecB )

## 12. Jaro-Winkler Distance
*INPUT:* vecA, vecB text represenations,

*RETURN:* Inf (distant) and 0.0 (not distant) (?),

*CALL:* JAWI( vecA, vecB )

## 13. Baire Distance
*INPUT:* vecA, vecB text represenations,
       
*RETURN:* Inf/1 (distant) and 0.0 (not distant),

*CALL:* baire( vecA, vecB )

## 14. Gen. Cantor Distance
*INPUT:* vecA, vecB text represenations,
       
*RETURN:* Inf/1 (distant) and 0.0 (not distant),

*CALL:* generalizedcantor( vecA, vecB )

## 15. JaccardZwei
*NOTE:* inverted Jaccard

*INPUT:* vecA, vecB text represenations,
       
*RETURN:* Inf/1 (distant) and 0.0 (not distant),

*CALL:* jaccardMASZzwei( vecA, vecB )

## 16. Jaccard Distance
*INPUT:* vecA, vecB text represenations,
       
*RETURN:* Inf/1 (distant) and 0.0 (not distant),

*CALL:* jaccardMASZ( vecA, vecB )

## 17. Cosine Distance
*INPUT:* vecA, vecB text represenations,
       
*RETURN:* Inf/1 (distant) and 0.0 (not distant),

*CALL:* cosineMASZ( vecA, vecB )

## 18. Quadratic Difference Distance 
*NOTE:* vec A and B are arrays of ngrams, quadra diff is a messure taken from the haufigkeitsvektor of A and B

*INPUT:* vecA, vecB text represenations,

*RETURN:* Inf (distant) and 0.0 (not distant) (?),

*CALL:* quadradiffMASZ( vecA, vecB )

## 19. Dice Coefficent Distance
*INPUT:* vecA, vecB text represenations,
       
*RETURN:* Inf/1 (distant) and 0.0 (not distant),

*CALL:* diceMASZ( vecA, vecB )

## 20. Marking Distance
*INPUT:* vecA, vecB text represenations,

*RETURN:* Inf (distant) and 0.0 (not distant) (?),

*CALL:* markingmetric( vecA, vecB )

## 21. Set Difference Distance 
*INPUT:* vecA, vecB text represenations,

*RETURN:* Inf (distant) and 0.0 (not distant) (?),

*CALL:* setdiffmetric( vecA, vecB )

# Minimal Example



