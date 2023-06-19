<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Anmelden</title>

    <link rel="shortcut icon" href="img/asg-logo.jpg" type="image/x-icon" />
    <!-- CSS von Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">



</head>

<body>

    <!-- Header-->
    <div style="float: right; background-color:#fb4400; height: 200% ; width:4%" data-scroll></div>

    <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light">
        <div class="container-fluid">
            <img src="img/asg-logo.jpg" class="rounded float-right mg-fluid" style="width: 5%; height: auto">
            <a class="navbar-brand">
                <h1>Anmelden</h1>
            </a>
        </div>
    </nav>

    <br>

    <div class="container d-felx justify-content-center aligne-items-center">
        <form class="border shadow p-3" action="check-login.php" method="post">
            <?php if(isset($_GET['error'])){?>
            <div class="alert alert-danger" role="alert">
                <?=$_GET['error']?>
            </div>
            <?php } ?>
            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="username" class="col-sm-2 col-form-label">Benutzername</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="username" id="username" placeholder="...">
                </div>
            </div>

            <div style=" padding-left: 3%; padding-right: 3%" class="mb-3">
                <label for="password" class="col-sm-2 col-form-label">Passwort</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" id="password" placeholder="...">
                </div>
            </div>

            <div style="text-align: center;"> <button type="submit" class="btn btn-success" style="font-size:21px;  width: 150px">Anmelden</button></div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

</body>

</html>
