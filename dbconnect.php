<?php

DEFINE ('DB_SERVER','den1.mysql2.gear.host');
DEFINE ('DB_USER','schooltimetable');
DEFINE ('DB_PASSWORD','Fs6b0?RH?x37');
DEFINE ('DB_NAME', 'schooltimetable');

$dbc=@mysqli_connect (DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME) OR die ('Connection failed: '.mysqli_connect_error());


mysqli_set_charset($dbc, 'utf8');
?>
