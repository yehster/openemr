<?php

/**
 * mechanism to use "super user" for SQL queries related to password operations
 * 
 * @param type $sql
 * @param type $params
 * @return type
 */
function privStatement($sql,$params)
{
    return sqlStatement($sql,$params);
}

function privQuery($sql,$params)
{
    return sqlQuery($sql,$params); 
}
?>
