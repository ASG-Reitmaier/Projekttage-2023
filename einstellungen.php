
<?php session_start();
if(isset($_SESSION['id'])){
require_once 'search.php';
$db = new DB();
$titel = "Einstellungen";
?>

<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/asg-logo.jpg" type="image/x-icon" />

    <title>Einstellungen</title>

    <!-- CSS von Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>

<body>
<?php include "header.php";?>
    
    <div class="container">
        <h2>Hier kannst du dein Passwort ändern</h2>
        <br>
        <?php if(isset($_GET['error'])){?>
            <div class="alert alert-danger" role="alert">
                <?=$_GET['error']?>
            </div>
            <?php } 
        if(isset($_GET['succes'])){?>
            <div class="alert alert-success" role="alert">
                <?=$_GET['succes']?>
            </div>
        <?php } ?>
        <form class="border shadow p-3" action="check-passwort.php" method="post" enctype="multipart/form-data">
            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="name" class="col col-form-label">Altes Passwort</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="altespasswort" placeholder="">
                </div>
            </div>
            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="name" class="colcol-form-label">Neues Passwort</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="neuespasswort" placeholder="">
                </div>
            </div>
            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="name" class="col-form-label">Neues Passwort wiederholen</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="wiederholung" placeholder="">
                </div>
            </div>
            <div style="text-align: center;">
                <button type="submit" class="btn btn-success" style="font-size:21px;">Bestätigen</button>
            </div>
        </form>
    </div>
    




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>

</html>

<?php } else { header("Location: logout.php"); } ?>

