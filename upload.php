<?php
session_start();
if(isset($_SESSION['rolle']) && $_SESSION['rolle'] == "admin"){
require_once 'search.php';
$db = new DB();
$titel = "Verwaltung";
ob_start();
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

</head>

<body>
    <!-- Header-->
    <?php
    include "header.php";
    ?>

    <br>

    <div class="container">

        <form action="upload.php" method="post" name="uploadcsv" enctype="multipart/form-data" class="border shadow p-3">
            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label class="col-sm-2 col-form-label">Schülerdaten hochladen</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" multiple name="file" id="filename" accept=".csv">
                </div>
            </div>
            <div style="text-align: center;">
                <button type="submit" name="import" class="btn btn-success" style="font-size:21px;  width: 10%" id="submit" data-loading-text="Loading...">Upload</button>
            </div>
            <?php
                if(isset($_POST["import"])){
                        $fileName = $_FILES["file"]["tmp_name"];
                        try{
                            $db->importiereBenutzer($fileName);
                            } catch(ValueError $e) {
                            echo "<br> <div class='alert alert-danger alert-dismissible fade show' role='alert'> Keine Daten ausgewählt!  </div>";
                        }
                }
    ?>
        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>

</html>
<?php }else{ header("Location: logout.php"); } ?>
