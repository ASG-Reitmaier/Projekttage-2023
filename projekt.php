<?php 
session_start();
if(isset($_SESSION['id'])){ 
require_once 'search.php';
$db = new DB();
if(isset($_GET['id'])){

$titel = " ";
$ausgabe = "";
$benutzername = $_SESSION['name'];
$benutzer_id = $_SESSION['id'];
$klassen_id = $_SESSION['klasse'];
$benutzerrolle = $_SESSION['rolle'];
$kurs_id = $_GET["id"];
$projektDaten = $db->zeigeKurs($kurs_id);
$titel = $projektDaten['0']['name'] ;
$bildurl = $projektDaten['0']['bild'];


if(isset($_POST["kursloeschen"])){
    $db->loescheKurs($kurs_id); 
}
else{
    
    if(isset($_POST["anmelden"])){   
        $ausgabe = $db->benutzerAnmelden($kurs_id,  $benutzer_id);
    }
    if(isset($_POST["abmelden"])){   
        $ausgabe = $db->benutzerAbmelden($kurs_id,  $benutzer_id);
    }
    if(isset($_POST["ID"])){   
        $ausgabe = $db->benutzerAbmelden($kurs_id,  $_POST["ID"]);
    }
    if(isset($_POST["schulereinfuegen"])){
        $id_neu = $db->zeigeBenutzerId($_POST["schulereinfuegen"]);
        if($id_neu > 0){
            $ausgabe = $db->benutzerAnmelden($kurs_id,  $id_neu);
        }
        else{
            $ausgabe = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Der angegebene Name wurde nicht gefunden.   </div>";
        }
    }

    
$teilnehmerzahl = $db->zeigeTeilnehmerzahl($kurs_id);



?>


<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../img/asg-logo.jpg" type="image/x-icon" />

    <title>Projekte</title>

    <!-- CSS von Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- JQuery laden -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#printButton").click(function() {
                window.print();
            });
        });

    </script>

    <!-- Links anpassen -->
    <style>
        a {
            text-decoration: none;
        }

        a:link {
            color: #000;
        }

        a:visited {
            color: #a3a3a3;
        }

        a:hover {
            color: #32a852;
        }

        @media print {

            html,
            body {
                height: 99%;
            }

            .noprint {
                display: none;
            }
        }

    </style>

</head>

<body>
    <!--<div style="float: right; background-color:#fb4400; height: 100% ; width:4%; " data-scroll></div>-->

    <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light">
        <div class="container-fluid">
            <img src="../img/asg-logo.jpg" class="rounded float-right mg-fluid" style="width: 80px; height: auto">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a <?php if($titel == "Projektübersicht"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="../projekte.php">Übersicht</a>
                    </li>
                    <?php  if(($_SESSION['rolle'] == "lehrer") || ($_SESSION['rolle'] == "schueler")){ ?>
                    <li class="nav-item">
                        <a <?php if($titel == "Projektverwaltung"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="../meineprojekte.php">Meine Projekte</a>
                    </li>
                    <?php } ?>
                    <?php  if(($_SESSION['rolle'] == "lehrer") || ($_SESSION['rolle'] == "admin")){ ?>
                    <li class="nav-item">
                        <a <?php if($titel == "Erstellung"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="../create.php">Projekt erstellen</a>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a <?php if($titel == "Einstellungen"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="../einstellungen.php">Einstellungen</a>
                    </li>
                    <?php  if($_SESSION['rolle'] == "admin"){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" <?php if($titel == "Verwaltung"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="../#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Administration
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="../upload.php">Benutzer hochladen</a></li>
                            <li><a class="dropdown-item" href="../verwaltung.php">Verwaltung</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li>
                        <a class="btn btn-outline-secondary" href="../logout.php" role="button">Abmelden</a>
                    </li>
                </ul>
            </div>
            <a class="navbar-brand">
                <?php echo $titel ?>
            </a>
        </div>
    </nav>



    <div class="container" style="margin-top: 5%">
        <div class="row">
            <div class="col">
                <img src='<?php echo "../".$bildurl ?>' alt='Beispielbild' class='img-thumbnail' alt='Responsive image'>
            </div>
            <div class="col">
                <div class="container" style="float: left; width:96%">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Kursleiter</th>
                                <th scope="col">Kursleiter</th>
                                <th scope="col">Kursleiter</th>
                                <th scope="col">Teilnehmer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> <?php if($projektDaten[0]["kursleiter1"]>0){
                                            echo str_replace(".", " ", $db->zeigeEinLehrer($projektDaten[0]["kursleiter1"])[0]['name']);
                                        }else{
                                            echo"";
                                        }?>
                                </td>
                                <td> <?php if($projektDaten[0]["kursleiter2"]>0){
                                            echo str_replace(".", " ", $db->zeigeEinLehrer($projektDaten[0]["kursleiter2"])[0]['name']);
                                        }else{
                                            echo"";
                                        }?>
                                </td>
                                <td> <?php if($projektDaten[0]["kursleiter3"]>0){
                                            echo str_replace(".", " ", $db->zeigeEinLehrer($projektDaten[0]["kursleiter3"])[0]['name']);
                                        }else{
                                            echo"";
                                        }?>
                                </td>
                                <td> <?php echo $teilnehmerzahl[0]["anzahl"]." von ".$projektDaten[0]["teilnehmerbegrenzung"]; ?> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="container" style="float: left; width:96%">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tage</th>
                                <th scope="col">Beginn</th>
                                <th scope="col">Ende</th>
                                <th scope="col">Ort</th>
                                <th scope="col">Jahrgangsstufen</th>
                                <th scope="col">Kosten</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> <?php if ($projektDaten[0]["Tag_1"]){echo " Mo"; } if ($projektDaten[0]["Tag_2"]){echo " Di";} if ($projektDaten[0]["Tag_3"]){echo " Mi";}?></td>
                                <td> <?php echo substr($projektDaten[0]["zeitraum_von"], 0, -3); ?></td>
                                <td> <?php echo substr($projektDaten[0]["zeitraum_bis"], 0, -3); ?></td>
                                <td> <?php echo $projektDaten[0]["ort"]; if($projektDaten[0]["raum"]!= NULL){echo " (".$db->nenneRaum($projektDaten[0]["raum"])[0]["bezeichnung"].")";};?></td>
                                <?php $jgst = "";
                                    if($projektDaten[0]["jgst5"]==1){$jgst = $jgst."5, ";} 
                                    if($projektDaten[0]["jgst6"]==1){$jgst = $jgst."6, ";} 
                                    if($projektDaten[0]["jgst7"]==1){$jgst = $jgst."7, ";} 
                                    if($projektDaten[0]["jgst8"]==1){$jgst = $jgst."8, ";} 
                                    if($projektDaten[0]["jgst9"]==1){$jgst = $jgst."9, ";} 
                                    if($projektDaten[0]["jgst10"]==1){$jgst = $jgst."10, ";} 
                                    if($projektDaten[0]["jgst11"]==1){$jgst = $jgst."11, ";} 
                                    $jgst = substr($jgst,0,-2);
                                ?>
                                <td> <?php echo $jgst; ?></td>
                                <td> <?php echo $projektDaten[0]["kosten"]; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="noprint container border shadow p-3" style="float: left;  margin-top: 30px;">
                    <h5> Beschreibung:</h5>
                    <?php echo $projektDaten[0]["beschreibung"];?>
                </div>

                <div class="container" style="float: left; width: 100%; margin-top: 30px;">


                    <?php $timestamp = time(); if($benutzerrolle == "schueler" && $timestamp >= 1688486400 && $timestamp<=1688745600){
                    
                    if($db->zeigeAngemeldet($kurs_id , $benutzer_id)) { ?>

                    <form action=" ../projekt.php/?id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data">
                        <button type="submit" class="btn btn-danger" name="abmelden">Abmelden</button>
                    </form>

                    <?php } else { ?>

                    <form action="../projekt.php/?id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data">
                        <button type="submit" class="btn btn-success" name="anmelden">Anmelden</button>
                    </form>


                    <?php } 
                    
                    
                    echo $ausgabe;
                    }
                    elseif(($benutzer_id == $projektDaten[0]["kursleiter1"])||($benutzerrolle=="admin")) { ?>
                    <div class="container ">
                        <div class="row border shadow p-3">
                            <div class="col">
                                <form action=" ../projektbearbeiten.php" method="post" enctype="multipart/form-data">
                                    <input type='hidden' name='IDK' value="<?php echo $kurs_id; ?>">
                                    <button type="submit" class="noprint btn btn-secondary" name="bearbeiten">Bearbeiten</button>
                                </form>
                            </div>
                            <div class="col">
                                <button id="printButton" class="noprint btn btn-success">Drucken</button>
                            </div>
                            <div class="col">
                                <form <?php if($benutzerrolle=="admin"){?> action="../projekte.php" <?php } else { ?> action="../meineprojekte.php" <?php } ?> method="post" enctype="multipart/form-data">
                                    <input type='hidden' name='kursloeschen' value="<?php echo $kurs_id; ?>">
                                    <button type="submit" class="noprint btn btn-danger" formmethod='post' id="kursloeschen">Kurs löschen</button>
                                </form>
                            </div>

                        </div>
                        <?php if($benutzerrolle=="admin"){?>
                        <br>
                        <div class="row">
                            <div class="col">
                                <h5>Schüler einfügen</h5>
                                <form action=" ../projekt.php/?id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data">
                                    <input type='text' name='schulereinfuegen'>
                                    <button type="submit" class="noprint btn btn-secondary" formmethod='post' id="schulereinfuegen">Einfuegen</button>
                                </form>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <?php 
                                                                                                         
                    echo $ausgabe; } ?>

                </div>
            </div>

        </div>

    </div>

    <?php if(($benutzer_id == $projektDaten[0]["kursleiter1"])||($benutzerrolle=="admin")){ ?>
    <div class="container" style="margin-top: 5%">
        <?php $ergebnis = $db->zeigeSchülerVonKurs($kurs_id); ?>
        <table class='table border shadow p-3' style='margin: auto;'>
            <tr>
                <th>Benutzer ID</th>
                <th>Name</th>
                <th>Klasse</th>
                <th>Rolle</th>
                <th></th>
            </tr>
            <?php foreach($ergebnis AS $row){ ?>
            <tr>
                <td> <?php echo $row["benutzer_id"]; ?></td>
                <td> <?php echo $row["name"]; ?></td>
                <td> <?php echo $row["klasse"]; ?></td>
                <td> <?php echo $row["rolle"]; ?></td>
                <td>
                    <form method='post' action="../projekt.php/?id=<?php echo $_GET['id'];?>">
                        <input type='hidden' name='ID' value="<?php echo $row["benutzer_id"]?>">
                        <button type='submit' class='btn btn-outline-danger' formmethod='post' id='löschen'>löschen</button>
                    </form>
                </td>
            </tr>
            <?php }?>
        </table>
    </div>




    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>

</html>

<?php 
    }

} else {    
    header("Location: index.php");
}

?>

<?php } else{ header("Location: index.php"); }?>
