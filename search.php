<?php
$query_str = isset($_POST['query_str']) ? $_POST['query_str'] : '';
echo "query_str:" . $query_str;
if (empty($query_str))
{
    exit('Please input query string');
}
else
{
    //$commond = "./search " . escapeshellcmd($query_str);
    $commond = "./search " . $query_str;
    echo "commond:" . $commond . "EOF";
    $return  = passthru($commond);
    //var_dump($return);
}
?>
