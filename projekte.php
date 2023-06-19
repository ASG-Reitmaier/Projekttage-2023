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
        <nav class=" navbar navbar-light bg-light">
            <div class="container-fluid">
                <form class="d-flex" action="projekte.php" method="POST">
                    <input class="form-control me-2" type="search" placeholder="Suche..." aria-label="Search" name="search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                    <div class="icons-c">
                        <div class="icon-search"></div>
                        <div class="icon-close" onclick="document.getElementById('search').value = ''">
                            <div class="x-up"></div>
                            <div class="x-down"></div>
                        </div>
                    </div>
                </form>
            </div>
        </nav>
        <br>



    <?php
    if(isset($_POST["search"]))
    {
        $suchbegriff = $_POST["search"];
        $suchDaten = $db->suche($suchbegriff);
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

