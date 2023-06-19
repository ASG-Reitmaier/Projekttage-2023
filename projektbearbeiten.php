<?php
session_start();
if(isset($_SESSION['rolle']) && $_SESSION['rolle'] != "schueler"){
require_once('search.php');
$db = new DB();
$titel = "Projektverwaltung";
//Kontrolle der Eingaben!
?>

<html lang="de">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/asg-logo.jpg" type="image/x-icon" />

    <title>Erstellung</title>

    <!-- CSS von Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


</head>

<body>

    <?php include "header.php"; ?>

    <div class="container">

    <?php  if (isset($_POST['bestätigen'])){
            $ergebnis = $db->zeigeKurs($_POST["IDK"]);
            $kurs = $ergebnis[0];
            if (strlen($_POST['name'])){
                $name = $_POST['name'];
            }
            else {
                $name = $kurs['name'];
            }

            $beschreibung = $_POST['beschreibung'];

            $kursleiter1 = $_POST["kursleiter1"]; 
            $kursleiter2 = $_POST["kursleiter2"];
            $kursleiter3 = $_POST["kursleiter3"];
    
            if (strlen($_POST['teilnehmerbegrenzung'])){
                $teilnehmerzahl = $_POST['teilnehmerbegrenzung'];
            }
            else { 
                $teilnehmerzahl = $kurs['teilnehmerbegrenzung']; 
            }
    
            if (strlen($_POST['jahrgangsstufen_beschraenkung'])){
                $beschraenkung = $_POST['jahrgangsstufen_beschraenkung'];
            }
            else { 
                $beschraenkung = $kurs['jahrgangsstufen_beschraenkung']; 
            }
    
            if (strlen($_POST['ort'])){
                $ort = $_POST['ort'];
            }
            else { 
                $ort = $kurs['ort']; 
            }
    
            if(isset($_POST["tag1"])){$tag1=1;} else{$tag1=0;};
            if(isset($_POST["tag2"])){$tag2=1;} else{$tag2=0;};
            if(isset($_POST["tag3"])){$tag3=1;} else{$tag3=0;};
    
            if($_POST["zeitraum_von"]!="" && $_POST["zeitraum_bis"]!=""){
                $von = $_POST["zeitraum_von"].":00";
                $bis = $_POST["zeitraum_bis"].":00";
            }
            else{
                $von = $kurs["zeitraum_von"];
                $bis = $kurs["zeitraum_bis"];
            }
    
    
    
            if (strlen($_POST['kosten'])){
                $kosten = $_POST['kosten'];
            }
            else { 
                $kosten = $kurs['kosten']; 
            }
    
            if (!file_exists($_FILES['datei']['tmp_name']) || !is_uploaded_file($_FILES['datei']['tmp_name'])){
          
            //Altes Bild bleibt bestehen.
            $bild = $kurs["bild"];    
            
            }
            else{
                //Altes Bild löschen, wenn es nicht das Beispielbild ist.
                if($kurs["bild"] != "uploads/projekt.png"){
                    @unlink($kurs["bild"]);
                }
                
                //Neues Bild hochladen.
                $upload_folder = 'uploads/'; //Das Upload-Verzeichnis
                $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
                $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
            
            
                //Überprüfung der Dateiendung
                $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                if(!in_array($extension, $allowed_extensions)) {
                    header("Location: projektbearbeiten.php?error=Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt!");
                }
            
                //Pfad zum Upload
                $new_path = $upload_folder.$filename.'.'.$extension;
            
                //Neuer Dateiname falls die Datei bereits existiert
                if(file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
                    $id = 1;
                    do {
                    $new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
                    $id++;
                    } while(file_exists($new_path));
                }
                $bild = $new_path;
                //Alles okay, verschiebe Datei an neuen Pfad
                move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);
          }



        $db->updateKurs($_POST["IDK"], $name, $beschreibung, $kursleiter1, $kursleiter2, $kursleiter3, $teilnehmerzahl, $beschraenkung, $ort, $tag1, $tag2, $tag3, $von, $bis, $kosten, $bild);
    }  
    
    
    if(isset($_POST["IDK"])){ 
        $ergebnis = $db->zeigeKurs($_POST["IDK"]);
        $kurs = $ergebnis[0]; 
        ?>

        <form class="border shadow p-3" action="projektbearbeiten.php" method="post" enctype="multipart/form-data">
            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="name" class="col col-form-label">Projektname</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="<?php echo $kurs["name"]; ?>">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="beschreibung" class="col col-form-label">Beschreibung</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="beschreibung" rows="3"> <?php echo $kurs["beschreibung"]; ?> </textarea>
                </div>
            </div>


  
            <div style=" padding-left: 3%; padding-right: 18%" class="mb-3">
                <label for="kursleiter" class="col col-form-label">Kursleiter</label>

                <div class="input-group form-control">
                    <div class="input-group-prepend" style="width:150px">
                        <label class="input-group-text" for="kursleiter1">Erster Kursleiter</label>
                    </div>
                    <select class="custom-select" id="kursleiter1" name="kursleiter1">
                        <?php            
                            if($kurs["kursleiter1"] == 0){ ?>
                                <option value="0" selected><?php echo "..."; ?></option>
                                <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                    <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                                    <?php }
                            } else {
                                    $leiter1 = $db->zeigeEinLehrer($kurs["kursleiter1"]);?>
                                    <option value="<?php echo $leiter1['0']['benutzer_id']?>" selected><?php echo $leiter1["0"]["name"]; ?></option>
                                    <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                        <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                                    <?php } ?>
                            <?php } ?>
                    </select>
                </div>

                <div class="input-group form-control">
                    <div class="input-group-prepend" style="width:150px">
                        <label class="input-group-text" for="kursleiter2">Zweiter Kursleiter</label>
                    </div>
                    <select class="custom-select" id="kursleiter2" name="kursleiter2">
                        <?php            
                            if($kurs["kursleiter2"] == 0){ ?>
                                <option value="0" selected><?php echo "..."; ?></option>
                                <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                    <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                                    <?php }
                            } else {
                                    $leiter2 = $db->zeigeEinLehrer($kurs["kursleiter2"]);?>
                                    <option value="<?php echo $leiter2['0']['benutzer_id']?>" selected><?php echo $leiter2["0"]["name"]; ?></option>
                                    <option value="0">...</option>
                                    <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                        <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                                    <?php } ?>
                            <?php } ?>
                    </select>
                </div>

                <div class="input-group form-control">
                    <div class="input-group-prepend" style="width:150px">
                        <label class="input-group-text" for="kursleiter3">Dritter Kursleiter</label>
                    </div>
                    <select class="custom-select" id="kursleiter3" name="kursleiter3">
                        <?php            
                            if($kurs["kursleiter3"] == 0){ ?>
                                <option value="0" selected><?php echo "..."; ?></option>
                                <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                    <option value="<?php echo $row['benutzer_id']; ?>"><?php echo $row['name'] ?></option>
                                    <?php }
                            } else {
                                    $leiter3 = $db->zeigeEinLehrer($kurs["kursleiter3"]);?>
                                    <option value="<?php echo $leiter3['0']['benutzer_id']; ?>" selected><?php echo $leiter3["0"]["name"]; ?></option>
                                    <option value="0">...</option>
                                    <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                        <option value="<?php echo $row['benutzer_id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php } ?>
                            <?php } ?>
                    </select>
                </div>

            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="teilnehmerbegrenzung" class="col col-form-label">Maximale Teilnehmerzahl</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="teilnehmerbegrenzung" min="1" max="100" step="1" value="<?php echo $kurs["teilnehmerbegrenzung"]; ?>" placeholder="<?php echo $kurs["teilnehmerbegrenzung"]; ?>">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="jahrgangsstufen_beschraenkung" class="col col-form-label">Auswahl der Jahrgangsstufen</label>
                <div class="col-sm-10">
                    <input class="form-control" name="jahrgangsstufen_beschraenkung" placeholder="<?php echo $kurs["jahrgangsstufen_beschraenkung"]; ?>">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="ort" class="col col-form-label">Ort</label>
                <div class="col-sm-10">
                    <input class="form-control" name="ort" placeholder="<?php echo $kurs["ort"]; ?>">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="zeit" class="col col-form-label">Zeit</label>
                <div style="padding-bottom: 2%; width: 70%" class="mb-3 form-control">
                    <div class="row align-items-end">
                        <div class="col-sm-9">
                            <div style=" padding-left: 3%; padding-right: 3%" class="col-8">
                                <label for="zeitraum_von" class="col-form-label">Beginn:</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="zeitraum_von" value="<?php echo substr($kurs["zeitraum_von"],0,5); ?>">
                                </div>
                            </div>

                            <div style=" padding-left: 3%; padding-right: 3%" class="col-8">
                                <label for="zeitraum_bis" class="col-form-label">Ende: </label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="zeitraum_bis" value="<?php echo substr($kurs["zeitraum_bis"],0,5); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="col-sm">
                                <label for="ort" class="col-form-label">Montag</label> <input type="checkbox" name="tag1" placeholder="" <?php if($kurs["Tag_1"] == 1){ ?> checked <?php } ?>>
                            </div>
                            <div class="col-sm">
                                <label for="ort" class="col-form-label">Dienstag</label> <input type="checkbox" name="tag2" placeholder="" <?php if($kurs["Tag_2"] == 1){ ?> checked <?php } ?>>
                            </div>
                            <div class="col-sm">
                                <label for="ort" class="col-form-label">Mittwoch</label> <input type="checkbox" name="tag3" placeholder="" <?php if($kurs["Tag_3"] == 1){ ?> checked <?php } ?>>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="kosten" class="col col-form-label">Kosten</label>
                <div class="col-sm-10">
                    <input class="form-control" name="kosten" placeholder="<?php echo $kurs["kosten"]; ?>">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="datei" class="col col-form-label">Gegebenenfalls neues Bild auswählen</label>
                <div class="col-sm-10">
                    <input type="file" name="datei" id="name">
                </div>
            </div>

            <div class="row" style="padding-left: 3%; padding-right: 3%">
                <div class="col">
                    <input type='hidden' name='IDK' value="<?php echo $kurs["kurs_id"] ?>">
                    <input formmethod='post' type='submit' class='btn btn-success' name='bestätigen' value='Änderungen bestätigen'>
                </div>
                <div class="col">
                    <?php if($_SESSION["rolle"] == "admin"){ ?>
                    <a href='verwaltung.php'>
                        <button type='button' class='btn btn-outline-secondary'>Zurück zur Projektübersicht</button>
                    </a>
                    <?php } else { ?>
                    <a href='meineprojekte.php'>
                        <button type='button' class='btn btn-outline-secondary'>Zurück zur Verwaltungsübersicht</button>
                    </a>
                    <?php } ?>
                </div>
            </div>

        </form>

        <?php } ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>

</html>
<?php } else { header("Location: logout.php"); } ?>
