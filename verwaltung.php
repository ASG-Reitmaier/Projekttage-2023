<?php 
session_start();
if(isset($_SESSION['rolle']) && $_SESSION['rolle'] == "admin"){
require_once 'search.php';
$titel = "Verwaltung";
$db = new DB();
@$_SESSION['ExportAbfrage'];
$table = "Kurs";
//ob_start immer nach session_start(). Zum exportieren
ob_start();

if(isset($_POST["IDL"])){
    $db->loescheBenutzer($_POST["IDL"]);
}

if(isset($_POST["IDKL"])){
    $db->loescheKurs($_POST["IDKL"]);
}

?>

<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/asg-logo.jpg" type="image/x-icon" />

    <title>Verwaltung</title>

    <!-- CSS von Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="/path/to/cdn/bootstrap.min.css" />
    <script src="/path/to/cdn/bootstrap.min.js"></script>
    <link href="bootstrap5-dropdown-ml-hack-hover.css" rel="stylesheet" />
    <script src="bootstrap5-dropdown-ml-hack.js"></script>
</head>

<body class="body">
    <?php include 'header.php'?>

    <div class="container" style="margin-top: 5%;">

        <div class="dropdown" style="margin: auto;">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" style="float: left; width: 20%; margin: auto;"> Zeigen</button>
            <form class="dropdown-menu" method="get" action="verwaltung.php" style="width: 20%; margin: auto;">
                <input type="submit" name="Tabelle" class="button dropdown-item" value="Lehrer">
                <input type="submit" name="Tabelle" class="button dropdown-item" value="Schüler">
                <!-- <input type = "submit" name= "KlassenTabelle" class ="button dropdown-item" value ="Klasse"></input> -->
                <input type="submit" name="Tabelle" class="button dropdown-item" value="Kurse">
            </form>

            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" style="float: left; width: 20%; margin: auto;"> Klasse</button>
            <form class="dropdown-menu" method="get" action="verwaltung.php" style="float: left; width: 20%; margin: auto;">
                <input type="hidden" name="Tabelle" value="Klasse">
                <input type="submit" name="Klasse" class="button dropdown-item" value="5">
                <input type="submit" name="Klasse" class="button dropdown-item" value="6">
                <input type="submit" name="Klasse" class="button dropdown-item" value="7">
                <input type="submit" name="Klasse" class="button dropdown-item" value="8">
                <input type="submit" name="Klasse" class="button dropdown-item" value="9">
                <input type="submit" name="Klasse" class="button dropdown-item" value="10">
                <input type="submit" name="Klasse" class="button dropdown-item" value="11">
                <input type="submit" name="Klasse" class="button dropdown-item" value="12">
            </form>

            <form class="button" method="post" action="verwaltung.php" style="float: right; width: 20%; margin: auto; ">
                <input type="submit" name="TabelleExportieren" class="btn btn-success" value="Tabelle Exportieren">
            </form>

        </div>

        <br>
        <br>

        
    
    <?php
    if(isset($_GET['Tabelle']) && $_GET['Tabelle'] == "Schüler"){
        $ergebnis = $db->zeigeSchüler(); ?>
        <table class='table border shadow p-3' style='margin: auto;'>
            <tr>
                <th>Benutzer ID</th>
                <th>Name</th>
                <th>Klasse</th>
                <th>Rolle</th>
                <th></th>
                <th></th>
            </tr>
            <?php foreach($ergebnis AS $row){ ?>
            <tr>
                <td> <?php echo $row["benutzer_id"]; ?> </td>
                <td> <?php echo $row["name"]; ?> </td>
                <td> <?php echo $row["klasse"]; ?> </td>
                <td> <?php echo $row["rolle"]; ?></td>            
                <td>
                    <form method='post' action='benutzerbearbeiten.php'>
                        <input type='hidden' name='ID' value="<?php echo $row["benutzer_id"]; ?>">
                        <button type='submit' class='btn btn-outline-secondary' formmethod='post' id='bearbeiten'>bearbeiten</button>
                    </form>
                </td>
                <td>
                    <form method='post' action='verwaltung.php'>
                        <input type='hidden' name='IDL' value="<?php echo $row["benutzer_id"]; ?>">
                        <button type='submit' class='btn btn-outline-danger' formmethod='post' id='bearbeiten'>löschen</button>
                    </form>
                </td>
            </tr>
            <?php  } ?>
        </table>
        
        <?php  { 
        $_SESSION['ExportAbfrage'] ="SELECT * FROM benutzer WHERE rolle = 'schueler'ORDER BY upper(name)";
         } 
    }?>
        
    <?php
    if(isset($_GET['Tabelle']) && $_GET['Tabelle'] == "Lehrer"){
        $ergebnis = $db->zeigeLehrer(); ?>
        <table class='table border shadow p-3' style='margin: auto;'>
            <tr>
                <th>Benutzer ID</th>
                <th>Name</th>
                <th>Klasse</th>
                <th>Rolle</th>
                <th></th>
                <th></th>
            </tr>
            <?php foreach($ergebnis AS $row){ ?>
            <tr>
                <td> <?php echo $row["benutzer_id"]; ?> </td>
                <td> <?php echo $row["name"]; ?> </td>
                <td> <?php echo $row["klasse"]; ?> </td>
                <td> <?php echo $row["rolle"]; ?></td>
                <td>
                    <form method='post' action='benutzerbearbeiten.php'>
                        <input type='hidden' name='ID' value="<?php echo $row["benutzer_id"]; ?>">
                        <button type='submit' class='btn btn-outline-secondary' formmethod='post' id='bearbeiten'>bearbeiten</button>
                    </form>
                </td>
                <td>
                    <form method='post' action='verwaltung.php'>
                        <input type='hidden' name='IDL' value="<?php echo $row["benutzer_id"]; ?>">
                        <button type='submit' class='btn btn-outline-danger' formmethod='post' id='löschen'>löschen</button>
                    </form>
                </td>
            </tr>
            <?php  } ?>
        </table>
        
        <?php  
        //Wird das benötigt?
        $_SESSION['ExportAbfrage'] ="SELECT * FROM benutzer WHERE rolle = 'lehrer'ORDER BY upper(name)";
        }?>
        
    <?php 
    if(isset($_GET['Tabelle']) && $_GET['Tabelle'] == "Klasse"){
    $klasse = $_GET['Klasse'];
    $ergebnis = $db->zeigeBuchungenFurKlasse($klasse);
    ?>
        <table class='table border shadow p-3' style='margin: auto;'>
            <tr>
                <th>Benutzer ID</th>
                <th>Name</th>
                <th>Buchungen</th>
                <th></th>
                <th></th>
            </tr>
            
            <?php foreach($ergebnis AS $row){ ?>
            <tr>
                <td> <?php echo $row["benutzer_id"]; ?> </td>
                <td> <?php echo $row["name"]; ?> </td>
                <td> <?php echo $row["Buchungen"]; ?> </td>
                <td>
                    <form method='post' action='benutzerbearbeiten.php'>
                        <input type='hidden' name='ID' value="<?php echo $row["benutzer_id"]; ?>">
                        <button type='submit' class='btn btn-outline-secondary' formmethod='post' id='bearbeiten'>bearbeiten</button>
                    </form>
                </td>
                <td>
                    <form method='post' action='verwaltung.php'>
                        <input type='hidden' name='IDL' value="<?php echo $row["benutzer_id"]; ?>">
                        <button type='submit' class='btn btn-outline-danger' formmethod='post' id='löschen'>löschen</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
        
        <?php  
        $_SESSION['ExportAbfrage'] = "SELECT * FROM benutzer WHERE rolle = 'schueler'AND klasse = ".$klasse." ORDER BY lower(name)";
        }

        if (isset($_GET['Tabelle']) && $_GET['Tabelle'] == "Kurse"){
        $ergebnis = $db->zeigeKurse(); ?>
        <table class='table border shadow p-3' style='margin: auto;'>
            <tr>
                <th>Kurs ID</th>
                <th>Name</th>
                <th>Kursleiter</th>
                <th>Teilnehmer</th>
                <th>Jahrgangstufen</th>
                <th>Ort</th>
                <th>Tag 1</th>
                <th>Tag 2</th>
                <th>Tag 3</th>
                <th>Kosten</th>
                <th></th>
                <th></th>
            </tr>

            <?php foreach($ergebnis AS $row){ ?>
            <tr>
                <td><?php echo $row["kurs_id"]; ?></td>
                <td><?php echo $row["name"]; ?></td>
                <td><?php if($row["kursleiter1"]>0){ echo $db->zeigeEinLehrer($row["kursleiter1"])[0]["name"];} ?><br> 
                    <?php if($row["kursleiter2"]>0){ echo $db->zeigeEinLehrer($row["kursleiter2"])[0]["name"];} ?><br> 
                    <?php if($row["kursleiter3"]>0){ echo $db->zeigeEinLehrer($row["kursleiter2"])[0]["name"];} ?> 
                </td>
                <td><?php $teilnehmerzahl = $db->zeigeTeilnehmerzahl($row["kurs_id"]);
                      echo $teilnehmerzahl[0]["anzahl"]." von ".$row["teilnehmerbegrenzung"]; 
                     ?>
                </td> 
                <td><?php echo $row["jahrgangsstufen_beschraenkung"]; ?> </td>
                <td><?php echo $row["ort"]; ?> </td>
                <?php
                    if($row["Tag_1"]==1){echo"<td>Ja</td>";}else{echo "<td>Nein</td>";}
                    if($row["Tag_2"]==1){echo"<td>Ja</td>";}else{echo "<td>Nein</td>";}
                    if($row["Tag_2"]==1){echo"<td>Ja</td>";}else{echo "<td>Nein</td>";}
                 ?>
                <td><?php echo $row["kosten"]; ?> </td>
                <td>
                    <form method='post' action='projektbearbeiten.php'>
                        <input type='hidden' name='IDK' value="<?php echo $row["kurs_id"]; ?>">
                        <button type='submit' class='btn btn-outline-secondary' formmethod='post' id='bearbeiten'>bearbeiten</button>
                    </form>
                </td>
                <td>
                    <form method='post' action='verwaltung.php'>
                        <input type='hidden' name='IDKL' value="<?php echo $row["kurs_id"]; ?>">
                        <button type='submit' class='btn btn-outline-danger' formmethod='post' id='löschen'>löschen</button>
                    </form>
                </td>
            <tr>
                <?php } ?>
        </table>
        
            <?php  $_SESSION['ExportAbfrage'] = "SELECT * FROM kurse ORDER BY lower(kurse.name)"; 
        
        
             /*<?php $_SESSION['ExportAbfrage'] = "SELECT `kurs_id`, `name`, (SELECT `name` FROM `benutzer` WHERE `benutzer_id` = `kursleiter1`) AS KL1, (SELECT `name` FROM `benutzer` WHERE `benutzer_id` = `kursleiter2`) AS KL2, (SELECT `name` FROM `benutzer` WHERE `benutzer_id` = `kursleiter3`) AS KL3, (SELECT COUNT(*) FROM `benutzer_zu_kurse` WHERE `kurse`.kurs_id = `benutzer_zu_kurse`.kurs_id) AS Teilnehmer, `teilnehmerbegrenzung`, `jahrgangsstufen_beschraenkung`,`ort`,`Tag_1`,`Tag_2`,`Tag_3`,`zeitraum_von`,`zeitraum_bis`,`kosten` FROM `kurse`";*/
            
            } ?>
   
        

        <?php if(isset($_POST["TabelleExportieren"])){
            $db->exportieren($_SESSION['ExportAbfrage']);
        }
        ?>

    <?php
    function zeigeDaten($suchDaten){
    ?>
        <div class="container" data-scroll>
            <div class="clearfix">
                <div class="row">
                    <?php foreach($suchDaten AS $row){?>
                    <div class='col-lg-4 bg-transparent text-dark border-0' data-scroll>
                        <a href="projekt.php/?id=<?php echo $row['benutzer_id'];?>" class="d-block mb-4 h-100">
                            <h4> <?php echo $row['name'];?></h4>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
<br>

</html>

<?php }else{ header("Location: logout.php"); } ?>
