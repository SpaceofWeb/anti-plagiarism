<?php

session_start();
ini_set('display_errors', 1);

// Конфиг
$cfg = [];

$cfg['title'] = 'AntiPlagiarism';
$cfg['rowsPerPage'] = 5;
$cfg['uploadDir'] = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
$cfg['dbhost'] = 'localhost';
$cfg['dbuser'] = 'root';
$cfg['dbpass'] = 'root';
$cfg['dbname'] = 'ap2';
$cfg['dbprefix'] = 'ap';
