<?php
session_start();
if(isset($_SESSION['rolle']) && $_SESSION['rolle'] != "schueler"){
require_once('search.php');
$db = new DB();
$titel = "Erstellung";
   
    if(isset($_POST["name"]) && isset($_POST["beschreibung"]) && isset($_POST["kursleiter1"]) && isset($_POST["kursleiter2"]) && isset($_POST["kursleiter3"]) && isset($_POST["teilnehmerbegrenzung"]) && isset($_POST["jahrgangsstufen_beschraenkung"]) && isset($_POST["ort"]) && isset($_POST["zeitraum_von"]) && isset($_POST["zeitraum_bis"]) && isset($_POST["kosten"])) {
      if($_POST["name"]!="" && $_POST["beschreibung"]!="" && $_POST["kursleiter1"]!="" && $_POST["teilnehmerbegrenzung"]!="" && $_POST["jahrgangsstufen_beschraenkung"]!="" && isset($_POST["ort"]) && $_POST["zeitraum_von"]!="" && $_POST["zeitraum_bis"]!="" && $_POST["kosten"]!=""){ 
        $name                   = $_POST["name"];
        $beschreibung           = $_POST["beschreibung"];
        $kursleiter1            = $_POST["kursleiter1"];
        if($_POST["kursleiter2"]=="..."){$kursleiter2 = 0;} else{$kursleiter2 = $_POST["kursleiter2"];};
        if($_POST["kursleiter3"]=="..."){$kursleiter3 = 0;} else{$kursleiter3 = $_POST["kursleiter3"];};
        $teilnehmerbegrenzung   = $_POST["teilnehmerbegrenzung"];
        $beschraenkung          = $_POST["jahrgangsstufen_beschraenkung"];
        $ort                    = $_POST["ort"];
        if(isset($_POST["tag1"])){$tag1=1;} else{$tag1=0;};
        if(isset($_POST["tag2"])){$tag2=1;} else{$tag2=0;};
        if(isset($_POST["tag3"])){$tag3=1;} else{$tag3=0;};
        $zeitraum_von           = $_POST["zeitraum_von"];
        $zeitraum_bis           = $_POST["zeitraum_bis"];
        $kosten                 = $_POST["kosten"];
        

      
        if($kursleiter1 == "...")
        {
          header("Location: create.php?error=Der Kurs benötigt einen Kursleiter!");
        } 
          
        if($tag1==0 && $tag2==0 && $tag3==0)
        {
          header("Location: create.php?error=Es muss mindestens ein Tag ausgewählt werden!");
        }
          

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
            
            
          $db->kursEinfuegen($name, $beschreibung, $kursleiter1, $kursleiter2, $kursleiter3, $teilnehmerbegrenzung, $beschraenkung, $ort, $tag1, $tag2, $tag3, $zeitraum_von, $zeitraum_bis, $kosten, $bild);
            
                

         
        header("Location: create.php?erfolg=Der Kurs wurde erfolgreich angelegt!");
        }
        else
        {
            header("Location: create.php?error=Dieser Kursname existiert bereits! Bitte wählen Sie einen anderen!");
          }

         
      } else{
          header("Location: create.php?error=Bitte füllen Sie das Formular vollständig aus!");
    }
  }
    
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
                <label for="kursleiter2" class="col col-form-label">Kursleiter</label>

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
                    <input class="form-control" name="jahrgangsstufen_beschraenkung" placeholder="z.B. 6, 7, 8">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="ort" class="col col-form-label">Ort</label>
                <div class="col-sm-10">
                    <input class="form-control" name="ort" placeholder="z.B. Raum 326">
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
                        <div class="col-sm-3">
                            <div class="col-sm">
                                <label for="ort" class="col-form-label">Montag</label> <input type="checkbox" name="tag1" placeholder="">
                            </div>
                            <div class="col-sm">
                                <label for="ort" class="col-form-label">Dienstag</label> <input type="checkbox" name="tag2" placeholder="">
                            </div>
                            <div class="col-sm">
                                <label for="ort" class="col-form-label">Mittwoch</label> <input type="checkbox" name="tag3" placeholder="">
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