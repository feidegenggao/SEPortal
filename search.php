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
    $bye_words = "Bye\n";
    if (!SocketWrite($socket, $bye_words))
        PrintSocketError("Socket Write error:", $socket);
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

        $PORTAL_SEARCH_QUERY_OK = 2;
        if ($cmd == $PORTAL_SEARCH_QUERY_OK)
        {
            $title = GetLine($socket);
            $url = GetLine($socket);
            $summary = GetLine($socket);

            echo "<p>";
            echo "<h1><a href = '$url' target='_blank'>" . $title. "</a></h1>";
            echo "<h2>" . $summary. "</h2>";
            echo "<h3>" . $url. "</h3>";
            echo "</p>";
        }
        else
        {
            echo "<p style='color:red'> Result is Empty</p>";
            die("Can't Anlysis the commond");
        }
        if ($arg1 == $arg2)
        {
            return;
        }
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
