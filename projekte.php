<?php 
session_start();
if(isset($_SESSION['id'])){
require_once 'search.php';
$db = new DB();
$titel = "Projektübersicht";
if(isset($_POST["kursloeschen"])){
    $db->loescheKurs($_POST["kursloeschen"]);
}

?>

<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/asg-logo.jpg" type="image/x-icon" />

    <title>Projekte</title>

    <!-- CSS von Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


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

    </style>

</head>

<body>

    <?php include "header.php";?>
    <div>
        <form class="d-flex" action="projekte.php" method="POST">
            <div class="container p-3">
                <div class="row justify-content-md-center align-items-center">
                <div class="col mr-5">
                    <button class="btn btn-success  mt-2" type="submit">Anzeigen</button>
                    <div class="icons-c">
                        <div class="icon-search"></div>
                        <div class="icon-close" onclick="document.getElementById('search').value = ''">
                            <div class="x-up"></div>
                            <div class="x-down"></div>
                        </div>
                    </div>
                </div>
                <div class="col mr-5 ">
                    <label for="suche" class="col col-form-label">Textinhalt:</label>
                    <input class="form-control ml-2" type="search" placeholder="Suche..." aria-label="Search" name="suche">             
                </div>
                <div class="col mr-5">
                <label for="jahrgangsstufen_beschraenkung" class="col col-form-label">Jahrgangsstufen:</label>
                    <div class="d-flex">                 
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="jgst5" value="option1"  <?php if(isset($_POST["jgst5"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="jgst5">5</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="jgst6" value="option2" <?php if(isset($_POST["jgst6"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="jgst6">6</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="jgst7" value="option3" <?php if(isset($_POST["jgst7"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="jgst7">7 </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="jgst8" value="option4" <?php if(isset($_POST["jgst8"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="jgst8">8 </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="jgst9" value="option5" <?php if(isset($_POST["jgst9"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="jgst9">9 </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="jgst10" value="option6" <?php if(isset($_POST["jgst10"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="jgst10">10 </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="jgst11" value="option7" <?php if(isset($_POST["jgst11"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="jgst11">11 </label>
                        </div>

                    </div>
                </div>
                <div class="col mr-5">
                <label for="jahrgangsstufen_beschraenkung" class="col col-form-label">Wochentage:</label>
                    <div class="d-flex" >                 
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="Montag" value="option1" <?php if(isset($_POST["Montag"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="Montag">Montag</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="Dienstag" value="option2" <?php if(isset($_POST["Dienstag"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="Dienstag">Dienstag</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="Mittwoch" value="option3" <?php if(isset($_POST["Mittwoch"])){ ?> checked <?php }; ?> >
                            <label class="form-check-label" for="Mittwoch">Mittwoch </label>
                        </div>
                    </div>
                    </div>
                    </div>
            </div>
            </form>

            <hr class="hr" />
    <?php

    if(isset($_POST["jgst5"])){$jgst5=1;} else{$jgst5=0;};
    if(isset($_POST["jgst6"])){$jgst6=1;} else{$jgst6=0;};
    if(isset($_POST["jgst7"])){$jgst7=1;} else{$jgst7=0;};
    if(isset($_POST["jgst8"])){$jgst8=1;} else{$jgst8=0;};
    if(isset($_POST["jgst9"])){$jgst9=1;} else{$jgst9=0;};
    if(isset($_POST["jgst10"])){$jgst10=1;} else{$jgst10=0;};
    if(isset($_POST["jgst11"])){$jgst11=1;} else{$jgst11=0;};
    if(isset($_POST["Montag"])){$Montag=1;} else{$Montag=0;};
    if(isset($_POST["Dienstag"])){$Dienstag=1;} else{$Dienstag=0;};
    if(isset($_POST["Mittwoch"])){$Mittwoch=1;} else{$Mittwoch=0;};

    if(isset($_POST["suche"]) )
    {
        $suchbegriff = $_POST["suche"];
        $suchDaten = $db->suche($suchbegriff, $jgst5, $jgst6, $jgst7, $jgst8, $jgst9, $jgst10, $jgst11, $Montag, $Dienstag, $Mittwoch);
    ?>


        <div class="container" data-scroll>
            <div class="clearfix ">
                <div class="row">
                    <?php foreach($suchDaten AS $row) { ?>
                    <div class='col-lg-3 bg-transparent  border-0' data-scroll>
                        <a href="projekt.php/?id=<?php echo $row['kurs_id'];?>" class="d-block mb-4 h-100">
                            <h5> <?php echo  $row["name"]; ?> </h5>
                            <img src='<?php echo $row['bild'];?>' alt='Beispielbild' class='img-fluid w-100 shadow-1-strong rounded mb-4  img-thumbnail' style="height: 250px; width: 100% !important; object-fit: cover; object-position: center center;">
                        </a>
                    </div>
                    <?php }     ?>
                </div>
            </div>
        </div>
        
    <?php } else {
        $suchDaten = $db->zeigeKurse();
    ?>
        <div class="container" data-scroll>
            <div class="clearfix ">
                <div class="row">
                    <?php foreach($suchDaten AS $row) { ?>
                    <div class='col-lg-3 bg-transparent border-0' data-scroll>
                        <a href="projekt.php/?id=<?php echo $row['kurs_id'];?>" class="d-block mb-4 h-100">
                            <h5> <?php echo  $row["name"]; ?> </h5>
                            <img src='<?php echo $row['bild'];?>' alt='Beispielbild' class='img-fluid w-100 shadow-1-strong rounded mb-4  img-thumbnail' style="height: 250px; width: 100% !important; object-fit: cover; object-position: center center;">
                        </a>
                    </div>
                    <?php }     ?>
                </div>
            </div>
        </div>
        
        <?php } ?>

    </div>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>

</html>

<?php } else{ header("Location: index.php"); }?>

