<?php
session_start();
  $_SESSION['MM_admin_first_name']=NULL;
  $_SESSION['MM_admin_last_name']=NULL;
  $_SESSION['MM_admin_id']=NULL;
  $_SESSION['MM_admin_mobile']=NULL;
  $_SESSION['permissions']=NULL;

  unset($_SESSION['MM_admin_first_name']);
  unset($_SESSION['MM_admin_last_name']);
  unset($_SESSION['MM_admin_id']);
  unset($_SESSION['MM_admin_mobile']);
  unset($_SESSION['permissions']);
  header("Location: index.php");
?>