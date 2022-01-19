<?php
error_reporting(E_ALL);

/* Get the port for the WWW service. */
$service_port = getservbyname('www', 'tcp');

/* Get the IP address for the target host. */
$address = gethostbyname('lms.seamolec.org');

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . 
         socket_strerror(socket_last_error()) . "\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . 
          socket_strerror(socket_last_error($socket)) . "\n";
}

$in = "HEAD / HTTP/1.1\r\n";
$in .= "Host: lms.seamolec.org\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';

echo "Sending HTTP HEAD request...";
echo "<pre>";
socket_write($socket, $in, strlen($in));
echo "</pre>";
echo "OK.<br>";

echo "\nReading response:\n\n";
echo "<pre>";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}
echo "</pre>";

socket_close($socket);
?>