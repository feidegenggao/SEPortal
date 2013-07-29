<?php
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

    while($recv_data = socket_read($socket, 1024, PHP_NORMAL_READ))
    {
        echo "recv_data:" . $recv_data;
    }
}

function PrintSocketError($error_title, $socket)
{
    $errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);

    die($error_title . "[$errorcode] $errormsg");
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
