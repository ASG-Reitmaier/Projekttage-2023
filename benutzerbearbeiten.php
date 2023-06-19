<!DOCTYPE html>
<?php session_start();
    if(isset($_SESSION['rolle']) && $_SESSION['rolle'] == "admin"){
    require_once('search.php');
    $db = new DB();
    $titel = "Verwaltung";
?>

<html>

<head>

    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Anmeldung</title>
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

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <?php 
        if (isset($_POST['bestätigen'])){
            $ergebnis = $db->zeigeEinSchueler($_POST["ID"]);
            $benutzer = $ergebnis[0];
            if (strlen($_POST['name'])){
                $name = $_POST['name'];
            }
            else {
                $name = $benutzer['name'];
            }

            if (strlen($_POST['klasse'])){
                $klasse = $_POST['klasse'];
            }
            else { 
                $klasse = $benutzer['klasse']; 
            }
    
            if (strlen($_POST['rolle'])){ 
                $rolle = $_POST['rolle']; 
            }
            else {
                $rolle = $benutzer['rolle']; 
            }
            if (strlen($_POST['passwort'])){ 
                $db->aenderePasswort($_POST["ID"], $_POST['passwort']);
            }


        $db->updateBenutzer($_POST["ID"], $name, $klasse, $rolle);
}

if(isset($_POST["ID"])){ 
    $ergebnis = $db->zeigeEinSchueler($_POST["ID"]);
    $benutzer = $ergebnis[0];
    $kurse = $db->zeigeKursFurSchueler($benutzer['benutzer_id']);
        
        ?>
        <br>
        <br>
        <br>
        <form type='post' action='benutzerbearbeiten.php' style='width: 50%; margin: auto'>
            <table class='table border shadow p-3' style='margin: auto;'>
                <tr>
                    <th>Benutzer ID</th>
                    <td> <?php echo $benutzer['benutzer_id']; ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo $benutzer['name']; ?></td>
                    <td><input name='name' type='text' placeholder="<?php echo $benutzer["name"]; ?>"></td>
                </tr>
                <tr>
                    <th>Klasse</th>
                    <td><?php echo $benutzer['klasse']; ?></td>
                    <td><input name='klasse' type='text' placeholder="<?php echo $benutzer["klasse"]; ?>"></td>
                </tr>
                <tr>
                    <th>Rolle</th>
                    <td><?php echo $benutzer['rolle']; ?></td>
                    <td><input name='rolle' type='text' placeholder="<?php echo $benutzer["rolle"]; ?>"></td>
                </tr>
                 <tr>
                    <th>Passwort</th>
                    <td></td>
                    <td><input name='passwort' type='text' placeholder="Neues Passwort"></td>
                </tr>
                <tr>
                    <th>Kurse:</th>
                    <td></td>
                    <td>
                    <?php foreach($kurse AS $row){
                        echo $row["name"]."<br>";
                    }?>
                    </td>
                </tr>
            </table>
            <br>
            <div class="row">
                <div class="col">
                    <input type='hidden' name='ID' value="<?php echo $benutzer["benutzer_id"] ?>">
                    <input formmethod='post' type='submit' class='btn btn-success' name='bestätigen' value='Änderungen bestätigen'>
                </div>
                <div class="col">
                    <a href='verwaltung.php'>
                        <button type='button' class='btn btn-outline-secondary'>Zurück zur Verwaltungsübersicht</button>
                    </a>
                </div>
            </div>
        </form>

    </div>
    <?php  } ?>


</body>

</html>

<?php }else{ header("Location: logout.php"); } ?>
