<?php

/**
 *
 * included by all other files, calls functions for them
 *
 *ok
 **/

if (isset($pagetitle) == "") {
  $pagetitle = 'MFS Inventory';
  }

/**
 * beginning of each html page
 *ok
 **/

echo <<<_END
<!DOCTYPE html>
<html lang="en">
<!-- for responsive design -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./style.css">
  <title>$pagetitle</title>
</head>

<body>

<div class="header">
<h1>Marshall Frame Shop Inventory</h1>
</div> <!-- end header -->

<div> 
<ul class="topnav">
  <!-- <li><a class="active" href="#home">Home</a></li>  -->

  <li class="right"><a href="Reset.php">Start Over</a></li> 
</ul>

</div> <!-- end topnav -->

_END;

