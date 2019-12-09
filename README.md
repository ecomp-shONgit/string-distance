# String Distance Implementation JS / PYTHON / PHP
A set of (string) distance functions written in JavaScript. Every function could be called with two representations of text to compute a distance, no matter if a bag of words represenations or a ngram represenation, string represenation is used. The implementation is derived from 

Elena Deza / Michel-Marie Deza "Encyclopedia of Distances", Elsevier Science, 2009 (http://www.uco.es/users/ma1fegan/Comunes/asignaturas/vision/Encyclopedia-of-distances-2009.pdf)

# Examples
http://ecomparatio.net/~khk/string-distance-master/test_strdist.php
http://ecomparatio.net/~khk/string-distance-master/test_strdist_rulesets.php

# Functions
## 1. Weighted Levenshtein
*INPUT:*
* s1 and s2 as representations, 
* Wv a weight for pairs in A and B, 
* Ws a list of 4 weights related to the operations substitution, insertion, deletion, exchange,
       
*RETURN:* Number of edited Letters / sum of editweights,

*CALL:* WLEV( A, B, Wv, Ws )

## 2. Damerau Levenshtein
*INPUT:* 
* a text representation s1 and s2,
* Ws a list of 4 weights related to the operations substitution, insertion, deletion, exchange,
       
*RETURN:* sum of editweights,

*CALL:* LEVDAM( s1, s2, Wv )

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
|1|2|WLEV|LEVDAM|levenshtein|LCS|LCF|containednessLCS|containednessLCF|LCP|bagdist|JA|JAWI|baire|generalizedcantor|jaccardMASZzwei|jaccardMASZ|cosineMASZ|quadradiffMASZ|diceMASZ|markingmetric|setdiffmetric|
|--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |--- |
|abcdefg|abcdefg|0|0|0|7|7|1|1|7|0|0.4523809523809524|0.8357142857142857|0.125|0.0003354626279025119|1|0|1.1102230246251565e-16|0|0|0|0|
|abcdefg|hijklm|7|7|7|0|0|0|0|0|7|7|4.6|1|0.36787944117144233|0|1|1|3.605551275463989|1|4.962844630259907|4.02535169073515|
|abcdefg||Infinity|Infinity|Infinity|0|0|0|0|0|7|Infinity|Infinity|Infinity|Infinity|Infinity|Infinity|Infinity|Infinity|Infinity|Infinity|Infinity|
|abcdefg|abc|4|4|4|3|3|0.42857142857142855|0.42857142857142855|3|4|0.6428571428571429|0.7857142857142858|0.25|0.018315638888734182|0.4285714285714286|0.5714285714285714|0.3453463292920228|2|0.4|2.0794415416798357|1.6094379124341003|
|abcdefg|dfg|4|4|4|3|2|0.42857142857142855|0.2857142857142857|0|4|0.15873015873015872|0.49523809523809526|1|0.36787944117144233|0.4285714285714286|0.5714285714285714|0.3453463292920228|2|0.4|2.0794415416798357|1.6094379124341003|
|abcdefg|acg|4|4|4|2|1|0.2857142857142857|0.14285714285714285|1|4|0.40079365079365076|0.6404761904761904|0.5|0.1353352832366127|0.4285714285714286|0.5714285714285714|0.3453463292920228|2|0.4|2.1972245773362196|1.6094379124341003|
|abcdefg|cdef|3|3|3|4|4|0.5714285714285714|0.5714285714285714|0|3|0.39285714285714285|0.6357142857142857|1|0.36787944117144233|0.5714285714285714|0.4285714285714286|0.2440710539815456|1.7320508075688772|0.2727272727272727|1.6094379124341003|1.3862943611198906|
|abcdefg|cdeui|4|4|4|3|3|0.42857142857142855|0.42857142857142855|0|4|0.3428571428571428|0.6057142857142856|1|0.36787944117144233|0.33333333333333337|0.6666666666666667|0.49290744716289014|2.449489742783178|0.5|3.332204510175204|2.70805020110221|
|abcdefg|acihef|4|4|4|3|2|0.42857142857142855|0.2857142857142857|1|3|0.2896825396825397|0.5738095238095238|0.5|0.1353352832366127|0.4444444444444444|0.5555555555555556|0.38278660015163235|2.23606797749979|0.3846153846153846|3.4011973816621555|2.4849066497880004|
|TIME|ING:|1.4200000017881393|0.905000003054738|0.6749999988824129|0.7299999948590994|0.5699999984353781|0.4899999927729368|0.41000000573694706|0.21000000089406967|0.35500000230968|0.6250000074505806|0.5399999991059303|0.25499999709427357|0.27000000327825546|0.8100000023841858|0.5399999972432852|0.9249999988824129|0.6900000013411045|0.41499999538064003|0.4449999947100878|0.4950000047683716|


# NOTE
## PHP Version
If mbstring extension is installed, every string is treated as array. That gives better distance results than PHP levenshtein.

