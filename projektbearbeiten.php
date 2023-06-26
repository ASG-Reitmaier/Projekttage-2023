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
                    $teilnehmerbegrenzung = $_POST['teilnehmerbegrenzung'];
                }
                else { 
                    $teilnehmerbegrenzung = $kurs['teilnehmerbegrenzung']; 
                }
        
                if(isset($_POST["jgst5"])){$jgst5=1;} else{$jgst5=0;};
                if(isset($_POST["jgst6"])){$jgst6=1;} else{$jgst6=0;};
                if(isset($_POST["jgst7"])){$jgst7=1;} else{$jgst7=0;};
                if(isset($_POST["jgst8"])){$jgst8=1;} else{$jgst8=0;};
                if(isset($_POST["jgst9"])){$jgst9=1;} else{$jgst9=0;};
                if(isset($_POST["jgst10"])){$jgst10=1;} else{$jgst10=0;};
                if(isset($_POST["jgst11"])){$jgst11=1;} else{$jgst11=0;};
        
                if (strlen($_POST['ort'])){
                    $ort = $_POST['ort'];
                }
                else { 
                    $ort = $kurs['ort']; 
                }
        
                
                if(empty($db->pruefeRaum($_POST["tag"],$_POST["raum"]))){
                    if($_POST["tag"] == "Tag_1"){$tag1 = 1; $tag2 = 0; $tag3 = 0;}
                    elseif($_POST["tag"] == "Tag_2"){$tag1 = 0; $tag2 = 1; $tag3 = 0;}
                    elseif($_POST["tag"] == "Tag_3"){$tag1 = 0; $tag2 = 0; $tag3 = 1;};
                    $raum = $_POST["raum"]; 
                }
                else{
                    $tag1 = $kurs["Tag_1"]; 
                    $tag2 = $kurs["Tag_2"]; 
                    $tag3 = $kurs["Tag_3"]; 
                    $raum = $kurs["raum"]; 
                }


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

            $db->updateKurs($_POST["IDK"], $name, $beschreibung, $kursleiter1, $kursleiter2, $kursleiter3, $teilnehmerbegrenzung, $ort, $raum, $tag1, $tag2, $tag3, $jgst5, $jgst6, $jgst7, $jgst8, $jgst9, $jgst10, $jgst11, $von, $bis, $kosten, $bild);
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


                <div class="input-group mb-2">
                    <label class="input-group-text" for="kursleiter1" style="width:150px">Erster Kursleiter</label>
                    <select class="form-select" id="kursleiter1" name="kursleiter1">
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

                <div class="input-group mb-2">
                    <label class="input-group-text" for="kursleiter2" style="width:150px">Erster Kursleiter</label>
                    <select class="form-select" id="kursleiter2" name="kursleiter2">
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
                                    <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                    <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                                    <?php } ?>
                            <?php } ?>
                    </select>
                </div>

                <div class="input-group mb-2">
                    <label class="input-group-text" for="kursleiter3" style="width:150px">Erster Kursleiter</label>
                    <select class="form-select" id="kursleiter3" name="kursleiter3">
                    <?php            
                            if($kurs["kursleiter3"] == 0){ ?>
                                <option value="0" selected><?php echo "..."; ?></option>
                                <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                    <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                                    <?php }
                            } else {
                                    $leiter3 = $db->zeigeEinLehrer($kurs["kursleiter3"]);?>
                                    <option value="<?php echo $leiter3['0']['benutzer_id']?>" selected><?php echo $leiter3["0"]["name"]; ?></option>
                                    <?php $lehrer = $db->zeigeLehrer();
                                    foreach($lehrer AS $row) { ?>
                                    <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
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
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst5" value="option1" <?php if($kurs["jgst5"] == 1){ ?> checked <?php } ?>>
                        <label class="form-check-label" for="jgst5">5</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst6" value="option2" <?php if($kurs["jgst6"] == 1){ ?> checked <?php } ?>>
                        <label class="form-check-label" for="jgst6">6</label>
                    </div>
                     <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst7" value="option3" <?php if($kurs["jgst7"] == 1){ ?> checked <?php } ?>>
                        <label class="form-check-label" for="jgst7">7 </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst8" value="option4" <?php if($kurs["jgst8"] == 1){ ?> checked <?php } ?>>
                        <label class="form-check-label" for="jgst8">8 </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst9" value="option5" <?php if($kurs["jgst9"] == 1){ ?> checked <?php } ?>>
                        <label class="form-check-label" for="jgst9">9 </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst10" value="option6" <?php if($kurs["jgst10"] == 1){ ?> checked <?php } ?>>
                        <label class="form-check-label" for="jgst10">10 </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst11" value="option7" <?php if($kurs["jgst11"] == 1){ ?> checked <?php } ?>>
                        <label class="form-check-label" for="jgst11">11 </label>
                    </div>

                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="ort" class="col col-form-label">Ort</label>
                <div class="col-sm-10">
                    <input class="form-control" name="ort" placeholder="<?php echo $kurs["ort"]; ?>">
                </div>
            </div>


            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="zeit" class="col col-form-label">Zeit und Raum</label>
                <div style="padding-bottom: 2%; width: 83%" class="mb-3 form-control">
                    <div class="row">
                        <div class="col-sm-4">
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

                        
                        <div class="col-sm-4">
                            <label for="tag" class="col-form-label">Tag:</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="tag">Tag</label>
                                <select class="form-select" id="tag" name="tag">
                                    <?php   if($kurs["Tag_1"]==1){echo "<option selected value='Tag_1'>Montag</option>";}
                                            elseif($kurs["Tag_2"]==1){echo "<option selected value='Tag_2'>Dienstag</option>";}
                                            elseif($kurs["Tag_3"]==1){echo "<option selected value='Tag_3'>Mittwoch</option>";}
                                            ?>
                                    <option value="Tag_1">Montag</option>
                                    <option value="Tag_2">Dienstag</option>
                                    <option value="Tag_3">Mittwoch</option>
                                </select>
                            </div>

                            <label for="raum" class="col-form-label">Raum (optional):</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="raum">Raum</label>
                                <select class="form-select" id="raum" name="raum">
                                    <?php  $raum_id = $kurs["raum"];
                                            if($raum_id>0){echo "<option selected value=".$raum_id.">".$db->nenneRaum($raum_id)[0]["bezeichnung"]."</option>";}
                                            else{echo "<option selected value=0> ohne </option>";}
                                    ?>
                                    <?php $raeume = $db->zeigeRaeume();
                                        foreach($raeume AS $row) { ?>
                                        <option value="<?php echo $row['raum_id']?>"><?php echo $row['bezeichnung'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                                
                        </div>
                        
                        <div class="col-sm-4">

                            <label for="raumplanung" class="col-form-label">Raumplanung:</label>
                            <!-- Button trigger modal -->

                            <div style=" padding-left: 3%; padding-right: 3%" class="col">

                                <!-- ErsterButton -->
                                <button type="button" class="btn btn-success" style=" margin-bottom: 1%;" data-bs-toggle="modal" data-bs-target="#modalMontag">
                                    Montag
                                </button>

                                <!-- Modal Montag -->
                                <div class="modal fade" id="modalMontag" tabindex="-1" role="dialog" aria-labelledby="modalMontag" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalMontag">Raumplanung Montag</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <?php $freieRaeumeMo = $db->zeigeFreieRaeume("Tag_1");
                                                foreach($freieRaeumeMo AS $row) { ?>
                                                    <div class="col">
                                                        <?php if($row["raum"] > 0 ){?>
                                                        <div class="alert alert-danger"><?php echo $row['bezeichnung'] ?></div>
                                                        <?php } else { ?>
                                                        <div class="alert alert-secondary"><?php echo $row['bezeichnung'] ?></div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ZweiterButton -->
                                <button type="button" class="btn btn-success" style=" margin-bottom: 1%;" data-bs-toggle="modal" data-bs-target="#modalDienstag">
                                    Dienstag
                                </button>

                                <!-- Modal Dienstag -->
                                <div class="modal fade" id="modalDienstag" tabindex="-1" role="dialog" aria-labelledby="modalDienstag" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalDienstag">Raumplanung Montag</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <?php $freieRaeumeMo = $db->zeigeFreieRaeume("Tag_2");
                                                foreach($freieRaeumeMo AS $row) { ?>
                                                    <div class="col">
                                                        <?php if($row["raum"] > 0 ){?>
                                                        <div class="alert alert-danger"><?php echo $row['bezeichnung'] ?></div>
                                                        <?php } else { ?>
                                                        <div class="alert alert-secondary"><?php echo $row['bezeichnung'] ?></div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ErsterButton -->
                                <button type="button" class="btn btn-success" style=" margin-bottom: 1%;" data-bs-toggle="modal" data-bs-target="#modalMittwoch">
                                    Mittwoch
                                </button>

                                <!-- Modal Montag -->
                                <div class="modal fade" id="modalMittwoch" tabindex="-1" role="dialog" aria-labelledby="modalMittwoch" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalMittwoch">Raumplanung Montag</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <?php $freieRaeumeMo = $db->zeigeFreieRaeume("Tag_3");
                                                foreach($freieRaeumeMo AS $row) { ?>
                                                    <div class="col">
                                                        <?php if($row["raum"] > 0 ){?>
                                                        <div class="alert alert-danger"><?php echo $row['bezeichnung'] ?></div>
                                                        <?php } else { ?>
                                                        <div class="alert alert-secondary"><?php echo $row['bezeichnung'] ?></div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


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
