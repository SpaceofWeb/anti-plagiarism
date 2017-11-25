<?php

session_start();
ini_set('display_errors', 1);


$cfg = [];

$cfg['title'] = 'AntiPlagiat';
$cfg['rowsPerPage'] = 5;
$cfg['uploadDir'] = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
$cfg['dbhost'] = 'localhost';
$cfg['dbuser'] = 'root';
$cfg['dbpass'] = 'root';
$cfg['dbname'] = 'antiplag';
$cfg['dbprefix'] = 'ap';



