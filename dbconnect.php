<?php

DEFINE ('DB_SERVER','collycomp.uk');
DEFINE ('DB_USER','15ritchingd03');
DEFINE ('DB_PASSWORD','SY156675');
DEFINE ('DB_NAME', '15ritchingd03_3');

$dbc=@mysqli_connect (DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME) OR die ('Connection failed: '.mysqli_connect_error());


mysqli_set_charset($dbc, 'utf8');
?>