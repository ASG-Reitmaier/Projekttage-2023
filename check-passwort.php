<?php

require_once('search.php');
$db = new DB();
session_start();


if(isset($_POST['altespasswort'])&&isset($_POST['neuespasswort'])&& isset($_POST["wiederholung"])){
  
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $altespasswort = test_input($_POST['altespasswort']);
    $neuespasswort = test_input($_POST['neuespasswort']);
    $wiederholung= test_input($_POST['wiederholung']);
    
    if(empty($altespasswort)){
        header("Location: einstellungen.php?error=Bitte altes Passwort eingeben!");
    }else if(empty($neuespasswort)){
        header("Location: einstellungen.php?error=Bitte neues Passwort eingeben!");
    }else if(empty($wiederholung)){
        header("Location: einstellungen.php?error=Bitte Passwort wiederholen!");
    }else{
        $benutzerPasswort = $db->nennePasswort($_SESSION['id']);
        if($benutzerPasswort['0']['passwort'] == md5($altespasswort)){
            if($_POST['neuespasswort'] == $_POST['wiederholung']){
                $db->aenderePasswort($_SESSION['id'], $_POST['wiederholung']);
                header("Location: einstellungen.php?succes=Passwort wurde geändert!"); 
            }
            else{
                header("Location: einstellungen.php?error=Passwörter stimmen nicht überein!"); 
            }
        }
        else{
            header("Location: einstellungen.php?error=Altes Passwort falsch eingegeben!");
        }
    }
    
}else{
    header("Location: einstellungen.php");
}



    
?>
