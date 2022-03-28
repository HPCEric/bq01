<?php
include_once "../base.php";


if ($Admin->math('count', '*', ['acc' => $_POST['acc'], 'pw' => $_POST['pw']]) > 0) {


    $_SESSION['login'] = $_POST['acc'];


    to("../back.php");
} else {


    echo "<script>";
    echo "alert('帳號或密碼錯誤');";
    echo "location.href='../index.php?do=login';";
    echo "</script>";
}
