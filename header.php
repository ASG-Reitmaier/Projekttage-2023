<!-- Header. Kann eingebunden werden.-->

<?php 

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if(isset($_SESSION['id'])){
?>

<!--<div style="float: right; background-color:#fb4400; height: 100% ; width:4%; " data-scroll></div>-->

<nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light">
    <div class="container-fluid">
        <img src="img/asg-logo.jpg" class="rounded float-right mg-fluid" style="width: 80px; height: auto">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a <?php if($titel == "Projektübersicht"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="projekte.php">Übersicht</a>
                </li>
                <?php  if(($_SESSION['rolle'] == "lehrer") || ($_SESSION['rolle'] == "schueler")){ ?>
                <li class="nav-item">
                    <a <?php if($titel == "Projektverwaltung"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="meineprojekte.php">Meine Projekte</a>
                </li>
                <?php } ?>
                <?php  if(($_SESSION['rolle'] == "lehrer") || ($_SESSION['rolle'] == "admin")){ ?>
                <li class="nav-item">
                    <a <?php if($titel == "Erstellung"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="create.php">Projekt erstellen</a>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a <?php if($titel == "Einstellungen"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="einstellungen.php">Einstellungen</a>
                </li>
                <?php  if($_SESSION['rolle'] == "admin"){ ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" <?php if($titel == "Verwaltung"){echo 'class="nav-link active"';} else {echo 'class="nav-link"';} ?> href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Administration
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="upload.php">Benutzer hochladen</a></li>
                        <li><a class="dropdown-item" href="verwaltung.php">Verwaltung</a></li>
                    </ul>
                </li>
                <?php } ?>
                <li>
                    <a class="btn btn-outline-secondary" href="logout.php" role="button">Abmelden</a>
                </li>
            </ul>
        </div>
        <a class="navbar-brand">
			<h1><?php echo $titel ?></h1>
		</a>

    </div>
</nav>

<?php } ?>



