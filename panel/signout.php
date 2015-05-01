<?php

include 'core/core.php';


  $_SESSION['MM_ID']=NULL;
  unset($_SESSION['MM_ID']);
  header("Location: $prefix/");

?>