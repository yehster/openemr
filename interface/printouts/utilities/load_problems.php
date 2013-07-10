<?php

function load_problems($pid,$type)
{
    $query = "SELECT title,diagnosis FROM lists WHERE pid = ? AND type = ? AND ";
    $query .= "(enddate is null or enddate = '' or enddate = '0000-00-00') ";
    $res=sqlStatement($query,array($pid,$type));
    return $res;
}

function problems_table(&$problems)
{
    $table_string;
    $table_string="<table><tbody>";
    while($problem=sqlFetchArray($problems))
    {
        $table_string.="<tr>";
        $table_string.="<td>".$problem['title']."</td>";
        $table_string.="<td>".$problem['diagnosis']."</td>";
        $table_string.="</tr>";
    }
    $table_string.="</tbody></table>";
    return $table_string;
}
?>
