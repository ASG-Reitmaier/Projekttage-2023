<?php

require_once('search.php');
$db = new DB();
session_start();

if(isset($_POST['username'])&&isset($_POST['password'])){
  
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    
    if(empty($username)){
        header("Location: index.php?error=Bitte Benutzername eingeben!");
    }else if(empty($password)){
        header("Location: index.php?error=Bitte Passwort eingeben!");
    }else{
        
        $suchDaten = $db->loginPruefen($username, $password);
        if(count($suchDaten) === 1 && $suchDaten['0']['name']===$username && $suchDaten['0']['passwort']===md5($password)){  
            $_SESSION['name'] = $suchDaten['0']['name'];
            $_SESSION['id'] = $suchDaten['0']['benutzer_id'];
            $_SESSION['klasse'] = $suchDaten['0']['klasse'];
            $_SESSION['rolle'] = $suchDaten['0']['rolle'];
            header("Location: projekte.php");
        }
        else{
            header("Location: index.php?error=Falscher Benutzername oder falsches Passwort!");
        }
    }
    
}else{
    header("Location: index.php");
}
  
?>
