<?php
$accuity_constants=array(10,15,20,25,30,40,50,100,200);

function get_line_difference($left_1,$right_1,$left_2,$right_2)
{
    $left_1=intval($left_1);
    $left_2=intval($left_2);
    $right_1=intval($right_1);
    $right_2=intval($right_2);
    $left_index_1=-1;
    $left_index_2=-1;
    $right_index_1=-1;
    $right_index_2=-1;
    for($idx=0;$idx<count($GLOBALS['accuity_constants']);$idx++)
    {
        $curline=$GLOBALS['accuity_constants'][$idx];
        if($left_1===$curline)
        {
            $left_index_1=$idx;
        }
        if($left_2===$curline)
        {
            $left_index_2=$idx;
        }
        if($right_1===$curline)
        {
            $right_index_1=$idx;
        }
        if($right_2===$curline)
        {
            $right_index_2=$idx;
        }
    }
    if(($left_index_2!==-1) && ($right_index_2!==-1))
    {
        return abs($left_index_2-$right_index_2);
    }
    if(($left_index_1!==-1) && ($right_index_1!==-1))
    {
        return abs($left_index_1-$right_index_1);
    }
    return "Unable to determine: Missing Values";

}

function needs_referral($age_in_mos,$left_1,$right_1,$left_2,$right_2)
{
    $values=array($left_1,$left_2,$right_1,$right_2);
    $worst=0;
    foreach($values as $res)
    {
        if(is_numeric($res))
        {
            if($worst<=$res)
            {
                $worst=$res;
            }
        }
    }
    if($age_in_mos>=36 && $age_in_mos<72)
    {
        $threshold=40;
    }
    if($age_in_mos>=72)
    {
        $threshold=30;
    }
    if($worst>$threshold)
    {
        return true;
    }
    if(get_line_difference($left_1,$right_1,$left_2,$right_2)>=2)
    {
        return true;
    }
    return false;
}
?>
