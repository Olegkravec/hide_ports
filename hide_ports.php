<?php

$max_ports = $argv[1];
$ports = [];

$SERVERS = [
    "AOLserver",
    "Apache HTTP Server",
    "Apache Tomcat",
    "Boa",
    "BusyBox httpd",
    "Caddy",
    "Caudium",
    "Cherokee HTTP Server",
    "GlassFish",
    "Hiawatha",
    "HFS",
    "IBM HTTP Server",
    "Internet Information Services",
    "Jetty",
    "Jexus",
    "lighttpd",
    "LiteSpeed Web Server",
    "Mongoose",
    "Monkey HTTP Server",
    "NaviServer",
    "NCSA HTTPd",
    "Nginx",
    "OpenBSD httpd",
    "OpenLink Virtuoso",
    "OpenLiteSpeed Web Server",
    "Oracle HTTP Server",
    "Oracle iPlanet Web Server",
    "Oracle WebLogic Server",
    "Resin Open Source",
    "Resin Professional",
    "thttpd",
    "TUX web server",
    "Wakanda Server",
    "WEBrick",
    "Xitami",
    "Yaws",
    "Zeus Web Server",
    "Zope",
];

foreach ($SERVERS as $i_ => $SERVER){
    $SERVERS[$i_] .= "/" . random_int(1,5) . "." . random_int(1,5);
}


for ($i = 0; $i < $max_ports; $i++){
    $r_port_ = random_int(1,65534);
    if(!isset($ports[$r_port_]))
        $ports[$r_port_] = $r_port_;
}



$pids = [];
foreach ($ports as $port){
    echo "\nTrying port $port: ...";
    $connection = @fsockopen("127.0.0.1", $port, $er, $em, 1);

    if (is_resource($connection))
    {
        echo "\tIS ALREADY OPENED";
        fclose($connection);
        continue;
    }else{
        echo "\tnot opened, trying to hide";
    }


    $pids[$port] = pcntl_fork();

    if(!$pids[$port]) {
        echo "\nRunning {$port} PID: " . getmygid();
        hide($port);
        exit();
    }
}

foreach ($pids as $pid){
    pcntl_waitpid($pid, $status, WUNTRACED);
}

function hide($port){
    $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
    $result = socket_bind($socket, "0.0.0.0", $port) or die("Could not bind to socket\n");
    $result = socket_listen($socket, 3) or die("Could not set up socket listener\n");

    while(true){
        $spawn = socket_accept($socket) or die("Could not accept incoming connection\n");
        $output = "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\nServer: ".server()."\r\n\r\n{'status':'ok','next_step':'.application.authorization'}";
        socket_write($spawn, $output, strlen ($output)) or die("Could not write output\n");
        socket_close($spawn);
    }
}

function server(){
    global $SERVERS;
    return $SERVERS[random_int(0, count($SERVERS)-1)];
}
