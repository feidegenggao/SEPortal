<?php
//FIXME:
//How to gurantee the char-set is UTF-8 when query_str passed by the form ?
$query_str = isset($_POST['query_str']) ? $_POST['query_str'] : '';
echo "query_str:" . $query_str;
if (empty($query_str))
{
    exit('Please input query string');
}
else
{
    $server_ip = "127.0.0.1";
    $server_port = 19999;
    $PORTAL_SEARCH_QUERY = 1;
    $CAN_NOT_CONNECT_SEARCH = 1;
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!$socket)
        PrintSocketError("socket_create error:", $socket);
    $connection = socket_connect($socket, $server_ip, $server_port);
    if (!$connection)
        PrintSocketError("socket_connect error:", $socket);

    //start combination the data_gram

    $data_header = $PORTAL_SEARCH_QUERY . "\n";
    $data_header = $data_header . strlen($query_str) . "\n";
    $data_header = $data_header . "\n";
    $data_header = $data_header . "\n";
    $data_header = $data_header . "\n";
    $data_body = $query_str;

    $data = $data_header . $data_body;
    //end combination the data_gram

    if (!SocketWrite($socket, $data))
        PrintSocketError("Socket Write error:", $socket);
    ReadQueryResult($socket);
}

function PrintSocketError($error_title, $socket)
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);

    die($error_title . "[$errorcode] $errormsg");
}

function ReadQueryResult($socket)
{
    while(true)
    {
    $cmd = GetLine($socket);
    $datalen = GetLine($socket);
    $arg1 = GetLine($socket);
    $arg2 = GetLine($socket);
    $arg3 = GetLine($socket);
    $data_body = GetData($socket, $datalen);
    echo "<p>";
    echo "<h1>id:" . $arg2 . "</h1>";
    echo "<h2><a href = '$data_body' target='_blank'>url:" . $data_body . "</a></h2>";
    echo "</p>";
    }
}

function GetLine($socket)
{
    $temp_data = socket_read($socket, 1024, PHP_NORMAL_READ);
    if (!$temp_data)
        PrintSocketError($socket);
    return $temp_data;
}

function GetData($socket, $datalen)
{
    $temp_data = socket_read($socket, $datalen, PHP_BINARY_READ);
    if (!$temp_data)
        PrintSocketError($socket);
    return $temp_data;
}

function SocketWrite($sock, $msg)
{
    $msg = "$msg\0";
    $length = strlen($msg);
    while(true)
    {
        $sent = socket_write($sock,$msg,$length);
        if($sent === false)
        {
            return false;
        }
        if($sent < $length)
        {
            $msg = substr($msg, $sent);
            $length -= $sent;
            print("Message truncated: Resending: $msg");
        }
        else
        {
            return true;
        }
    }
    return false;
}
?>
