<?php
/**
 * 
 */

/**
 * 
 * An approximation for erf(x) using this technique
 * http://en.wikipedia.org/wiki/Error_function#Numerical_approximation
 * 
 * the error is less than 1E-6
 * 
 * @param type $x
 * @return type
 */
function erf($x)
{
    $t=1/(1+(0.5)*abs($x));
    $tau=$t*exp(-($x*$x) -1.26551223 +
            $t*(1.00002368 +                                // $t
                $t*(0.37409196 +                            // $t^2
                    $t*(0.09678418 +                        // $t^3
                        $t*(-0.18628806 +                   // $t^4
                            $t*(0.27886807 +                // $t^5
                                $t*(-1.13520398 +           // $t^6
                                    $t*(1.48851587 +        // $t^7
                                        $t*(-0.82215223 +   // $t^8
                                            $t*(0.17087227))) ))) ))) // $t^9 (9 close parens to close the polynomial
            ); //close paren for exp
    if($x>=0)
    {
        return 1-$tau;
    }
    else
    {
        return $tau-1;
    }
}
/**
 * The cumulative distribution function computed in terms of erf
 * 
 * @param type $n
 * @return type
 */
function cdf($n)
{

    return (1+erf($n/sqrt(2)))/2;
} 

function x_to_z_lms($x,$l,$m,$s)
{
    $x=floatval($x);
    $l=floatval($l);
    $m=floatval($m);

    if($l===0)
    {
        return log($x/$m)/$s;
    }
    else
    {
        return (pow(($x/$m),$l)-1)/($l*$s);
    }
}
?>

