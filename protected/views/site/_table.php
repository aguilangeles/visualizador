<?php 
    $resultSet = $group['data']["retval"];
    $cantidad = $group['data']["keys"];
    $conditions = $group['keys'];
    $results = Image::writeImageData($resultSet);
    foreach($results as $result)
    {
        foreach( $result as $key => $value )
        {
            echo $value;
        }
    }
?>