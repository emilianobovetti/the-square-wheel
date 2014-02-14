<?php

$site_title = 'Square Wheel';

$site_title .= (isset($title))
              ? ' · ' . $title
              : '';

$description = ( ! isset($description)) 
              ? 'Tipo un blog di informatica, ma non solo'
              : $description;
?>
    <!DOCTYPE html>

<html lang="it">
  <head>
      <meta charset="utf-8">
      
      <!--
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
      -->
      
      <title><?php echo $site_title; ?></title>

      <meta name="description" content="<?php echo $description; ?>" />
      
      <meta name="author" content="Emiliano Bovetti" />
      
      <base target="_self">
      <link rel="stylesheet" type="text/css" href="/css/base.css">
      <link rel="shortcut icon" href="/img/square.ico">
  </head>
  
  <body>
    <div class='centered'>
