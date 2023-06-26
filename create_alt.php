<?php
session_start();
if(isset($_SESSION['rolle']) && $_SESSION['rolle'] != "schueler"){
require_once('search.php');
$db = new DB();
$titel = "Erstellung";
   
if(isset($_POST["name"]) && isset($_POST["beschreibung"]) && isset($_POST["kursleiter1"]) && isset($_POST["kursleiter2"]) && isset($_POST["kursleiter3"]) && isset($_POST["teilnehmerbegrenzung"]) && isset($_POST["ort"]) && isset($_POST["raum"]) && isset($_POST["tag"])&& isset($_POST["zeitraum_von"]) && isset($_POST["zeitraum_bis"]) && isset($_POST["kosten"])) {
    if($_POST["name"]!="" && $_POST["beschreibung"]!="" && $_POST["kursleiter1"]!="" && $_POST["teilnehmerbegrenzung"]!="" && $_POST["ort"]!=""  && $_POST["zeitraum_von"]!="" && $_POST["zeitraum_bis"]!="" && $_POST["kosten"]!=""){ 
        $name                   = $_POST["name"];
        $beschreibung           = $_POST["beschreibung"];
        $kursleiter1            = $_POST["kursleiter1"];
        if($_POST["kursleiter2"]=="..."){$kursleiter2 = 0;} else{$kursleiter2 = $_POST["kursleiter2"];};
        if($_POST["kursleiter3"]=="..."){$kursleiter3 = 0;} else{$kursleiter3 = $_POST["kursleiter3"];};
        $teilnehmerbegrenzung   = $_POST["teilnehmerbegrenzung"];
        $ort                    = $_POST["ort"];
        $raum                   = $_POST["raum"];
        if($_POST["tag"] == "Tag_1"){$tag1 = 1; $tag2 = 0; $tag3 = 0;}
        elseif($_POST["tag"] == "Tag_2"){$tag1 = 0; $tag2 = 1; $tag3 = 0;}
        elseif($_POST["tag"] == "Tag_3"){$tag1 = 0; $tag2 = 0; $tag3 = 1;};
        if(isset($_POST["jgst5"])){$jgst5=1;} else{$jgst5=0;};
        if(isset($_POST["jgst6"])){$jgst6=1;} else{$jgst6=0;};
        if(isset($_POST["jgst7"])){$jgst7=1;} else{$jgst7=0;};
        if(isset($_POST["jgst8"])){$jgst8=1;} else{$jgst8=0;};
        if(isset($_POST["jgst9"])){$jgst9=1;} else{$jgst9=0;};
        if(isset($_POST["jgst10"])){$jgst10=1;} else{$jgst10=0;};
        if(isset($_POST["jgst11"])){$jgst11=1;} else{$jgst11=0;};
        $zeitraum_von           = $_POST["zeitraum_von"];
        $zeitraum_bis           = $_POST["zeitraum_bis"];
        $kosten                 = $_POST["kosten"];
        
      
        if($kursleiter1 == "...")
        {
            header("Location: create.php?error=Der Kurs benötigt einen Kursleiter!");
        } 
        else if($jgst5==0 && $jgst6==0 && $jgst7==0 && $jgst8==0 && $jgst9==0 && $jgst10==0 && $jgst11==0)
        {
            header("Location: create.php?error=Es muss mindestens eine Jahrgangsstufe ausgewählt werden!");
        }
        else if(!empty($db->pruefeRaum($_POST["tag"],$_POST["raum"])))
        {
            header("Location: create.php?error=Raum bereits vergeben!");
        }
        else{
            if (!file_exists($_FILES['datei']['tmp_name']) || !is_uploaded_file($_FILES['datei']['tmp_name'])){
                $bild = "uploads/projekt.png";
            }
            else{
                $upload_folder = 'uploads/'; //Das Upload-Verzeichnis
                $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
                $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
            
            
                //Überprüfung der Dateiendung
                $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                if(!in_array($extension, $allowed_extensions)) {
                    header("Location: create.php?error=Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt!");
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
  
            if($db->namePruefen($name))
            {
                $db->kursEinfuegen($name, $beschreibung, $kursleiter1, $kursleiter2, $kursleiter3, $teilnehmerbegrenzung, $ort, $raum, $tag1, $tag2, $tag3, $jgst5, $jgst6, $jgst7, $jgst8, $jgst9, $jgst10, $jgst11,$zeitraum_von, $zeitraum_bis, $kosten, $bild);
                header("Location: create.php?erfolg=Der Kurs wurde erfolgreich angelegt!");
            }
            else{
                header("Location: create.php?error=Dieser Kursname existiert bereits! Bitte wählen Sie einen anderen!");
            }
        }
    } 
    else{
          header("Location: create.php?error=Bitte füllen Sie das Formular vollständig aus!");  
    }
} ?>

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

<?php include "header.php";?>

    <?php if(isset($_GET['error'])){?>
    <div class="alert alert-danger fade show" style="margin: auto; width:50%" role="alert">
        <?=$_GET['error']?>
    </div>
    <br>
    <?php } ?>

    <?php if(isset($_GET['erfolg'])){?>
    <div class="alert alert-success fade show" style="margin: auto; width:50%" role="alert">
        <?=$_GET['erfolg']?>
    </div>
    <br>
    <?php } ?>

    <div class="container">
        <form class="border shadow p-3" action="create.php" method="post" enctype="multipart/form-data">
            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="name" class="col col-form-label">Projektname</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="beschreibung" class="col col-form-label">Beschreibung</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="beschreibung" placeholder="" rows="3"></textarea>
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 18%" class="mb-3">
                <label class="col col-form-label">Kursleiter</label>

                <div class="input-group form-control">
                    <div class="input-group-prepend" style="width:150px">
                        <label class="input-group-text" for="kursleiter1">Erster Kursleiter</label>
                    </div>
                    <select class="custom-select" id="kursleiter1" name="kursleiter1">
                        <option selected>...</option>
                        <?php $lehrer = $db->zeigeLehrer();
                        foreach($lehrer AS $row) { ?>
                        <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="input-group form-control">
                    <div class="input-group-prepend" style="width:150px">
                        <label class="input-group-text" for="kursleiter2">Zweiter Kursleiter</label>
                    </div>
                    <select class="custom-select" id="kursleiter2" name="kursleiter2">
                        <option selected>...</option>
                        <?php $lehrer = $db->zeigeLehrer();
                        foreach($lehrer AS $row) { ?>
                        <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="input-group form-control">
                    <div class="input-group-prepend" style="width:150px">
                        <label class="input-group-text" for="kursleiter3">Dritter Kursleiter</label>
                    </div>
                    <select class="custom-select" id="kursleiter3" name="kursleiter3">
                        <option selected>...</option>
                        <?php $lehrer = $db->zeigeLehrer();
                        foreach($lehrer AS $row) { ?>
                        <option value="<?php echo $row['benutzer_id']?>"><?php echo $row['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>

            </div>


            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="teilnehmerbegrenzung" class="col col-form-label">Maximale Teilnehmerzahl</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="teilnehmerbegrenzung" min="1" max="100" step="1" value="10">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="jahrgangsstufen_beschraenkung" class="col col-form-label">Auswahl der Jahrgangsstufen</label>
                <div class="col-sm-10">                 
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst5" value="option1">
                        <label class="form-check-label" for="jgst5">5</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst6" value="option2">
                        <label class="form-check-label" for="jgst6">6</label>
                    </div>
                     <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst7" value="option3">
                        <label class="form-check-label" for="jgst7">7 </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst8" value="option4">
                        <label class="form-check-label" for="jgst8">8 </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst9" value="option5">
                        <label class="form-check-label" for="jgst9">9 </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst10" value="option6">
                        <label class="form-check-label" for="jgst10">10 </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="jgst11" value="option7">
                        <label class="form-check-label" for="jgst11">11 </label>
                    </div>

                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="ort" class="col col-form-label">Ort</label>
                <div class="col-sm-10">
                    <input class="form-control" name="ort" value="ASG">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="zeit" class="col col-form-label">Zeit</label>
                <div style="padding-bottom: 2%; width: 70%" class="mb-3 form-control">
                    <div class="row align-items-end">
                        <div class="col-sm-6">
                            <div style=" padding-left: 3%; padding-right: 3%" class="col-8">
                                <label for="zeitraum_von" class="col-form-label">Beginn:</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="zeitraum_von" value="07:55">
                                </div>
                            </div>

                            <div style=" padding-left: 3%; padding-right: 3%" class="col-8">
                                <label for="zeitraum_bis" class="col-form-label">Ende: </label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="zeitraum_bis" value="12:00">
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-sm-6">
                            <div class="input-group form-control" style="width:220px">
                                    <div class="input-group-prepend" style="width:80px">
                                        <label class="input-group-text" for="Tag">Tag</label>
                                    </div>
                                    <select class="custom-select" id="tagDropdown" name="tag">
                                        <option selected value="Tag_1">Montag</option>                                      
                                        <option value="Tag_2">Dienstag</option>  
                                        <option value="Tag_3">Mittwoch</option>  
                                    </select>
                            </div> 

                            <div class="input-group form-control" style="width:220px">
                                    <div class="input-group-prepend" style="width:80px">
                                        <label class="input-group-text" for="Raum">Raum</label>
                                    </div>
                                    <select class="custom-select" id="raum" name="raum">
                                        <option selected value = 0>ohne</option>
                                        <?php $raeume = $db->zeigeRaeume();
                                        foreach($raeume AS $row) { ?>
                                        <option value="<?php echo $row['raum_id']?>"><?php echo $row['bezeichnung'] ?></option>
                                        <?php } ?>
                                    </select>
                            </div>
                                
                        </div>
                    </div>
                </div>
            </div>


            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="kosten" class="col col-form-label">Kosten</label>
                <div class="col-sm-10">
                    <input class="form-control" name="kosten" placeholder="Bitte eingeben.">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="datei" class="col col-form-label">Bild</label>
                <div class="col-sm-10">
                    <input type="file" name="datei" id="name">
                </div>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn btn-success" style="font-size:21px;">Senden</button>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>

</html>
<?php }else{ header("Location: logout.php"); } ?>