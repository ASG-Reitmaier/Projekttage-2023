<?php

if(count(get_included_files()) ==1) exit("Direct access not permitted.");

class DB
{
    // Connection to MySQL database. Trying connection. Exception on Failure
    private $con;
    private $host;
    private $dbname;
    private $user;
    private $password;

    public function __construct()
    {

     $this->host = 'vmd48086.contaboserver.net';
     $this->dbname = 'projekttage';
     $this->user = 'Protage';
     $this->password = 'protage2020';
     $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8";

        try {
            $this->con = new PDO($dsn, $this->user, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Failure" . $e->getMessage();
        }
    }
    
    //Ändert das Benutzerpasswort.
    public function aenderePasswort($benutzer_id, $passwort)
    {   $passwort = md5($passwort);
        $query = "UPDATE benutzer SET passwort = '$passwort' WHERE benutzer_id = $benutzer_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
    }
    
    // Gibt Alles von Benutzer aus via MySQL query (+ Prevention of SQL Injection)
    public function gibVerbindung()
    {
        return $this->con;
    }
    
    public function exportieren($query)
    {
        $result = $this->con->prepare($query);
        $result->execute();

        //kann man ändern
        $filename = 'Tabelle.csv';
        
        //entleert den Outputstream und erstellt eine CSV-Datei
        ob_clean();
        header("Content-Type: text/csv; charset: utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $fp = fopen('php://output', 'w');
        ob_clean();

        // utf-8 Unterstützung in csv
        $arr = array();
        //fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); geht nicht mehr???

        // Spaltennamen aus der SQL-Tabelle einfügen
        for ($i = 0; $i < $result->columnCount(); $i++){
            $arr = array_merge($arr, array($result->getColumnMeta($i)["name"]));
        }
        fputcsv($fp, $arr, ";");

        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            fputcsv($fp, $row, ";");
        }
        
        //schließt die Datei
        fclose($fp);
        exit();
    }
    
    public function importiereBenutzer($fileName)
    {
        $file = fopen($fileName, 'r');
        //1000 entspricht der maximalen Zeichenlänge und ; dem Trennzeichen der csv-Datei. In Deutschland üblicherweise ; statt ,
        while($row = fgetcsv($file, 1000, ';')){#
            //implode verknüpft alle Arrayelemente zu einem String.
            //print_r($row);
            $row[3]=md5($row[3]);
            $value = "'" . implode("','",$row)."'";
            $query ="INSERT INTO benutzer (name, klasse, rolle, passwort) VALUES (".$value.")";
            $statement = $this->con->prepare($query);
            $statement->execute();
        }
        echo "<br><div class='alert alert-success alert-dismissible fade show' role='alert'> Schülerdaten erfolgreich hochgeladen!   </div>";
    }
    
    //Löscht den angegebenen Benutzer (Lehrer oder Schüler).
    public function loescheBenutzer($benutzer_id)
    {   
        $benutzername = str_replace(".", " ", $this->zeigeEinLehrer($benutzer_id)[0]['name']);

        //Falls der Benutzer (Lehrer) als 1. Kursleiter eingetragen war, darf der Benutzer nicht gelöscht werden.
        $query = "SELECT kurse.name, kursleiter1 FROM kurse WHERE kursleiter1 = $benutzer_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $kurse = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($kurse))
        {
            //Fehlermeldung, falls die Kursliste nicht leer ist.
            $kurs = $kurse[0]['name'];
            $ausgabe = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Der Benutzer $benutzername kann nicht entfernt werden, da er im Kurs $kurs als 1. Kursleiter eingetragen ist</div>";
        }
        else{
            //Falls der Benutzer (Schüler) in einem Kurs angemeldet war, wird hier der Eintrag gelöscht.
            $query = "DELETE FROM benutzer_zu_kurse WHERE kurs_id = $benutzer_id";
            $statement = $this->con->prepare($query);
            $statement->execute();
            //Falls der Benutzer (Lehrer) als 2. Kursleiter eingetragen war, wird der eintrag entfernt.
            $query = "UPDATE kurse SET kursleiter2 = 0 WHERE kursleiter2 = $benutzer_id";
            $statement = $this->con->prepare($query);
            $statement->execute();
            //Falls der Benutzer (Lehrer) als 3. Kursleiter eingetragen war, wird der eintrag entfernt.
            $query = "UPDATE kurse SET kursleiter3 = 0 WHERE kursleiter2 = $benutzer_id";
            $statement = $this->con->prepare($query);
            $statement->execute();
            //Benutzer wird entfernt.
            $query = "DELETE FROM benutzer WHERE benutzer_id = $benutzer_id";
            $statement = $this->con->prepare($query);
            $statement->execute();
            $ausgabe = "<div class='alert alert-success alert-dismissible fade show' role='alert'> Der Benutzer $benutzername wurde gelöscht!   </div>";
        }
        return $ausgabe;
    }

    
    //Löscht den angegebenen Kurs.
    public function loescheKurs($kurs_id)
    {   //Bild löschen.
        $kurs = $this->zeigeKurs($kurs_id);
        $titel = $kurs[0]["name"];
        $bildurl = $kurs[0]["bild"];
        if($bildurl != "projekt.png"){
           @unlink($bildurl);
        }
        //Daten aus der Datenbank löschen.
        $query = "DELETE FROM benutzer_zu_kurse WHERE kurs_id = $kurs_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $query = "DELETE FROM kurse WHERE kurs_id = $kurs_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        return "<div class='col'><div class='alert alert-success alert-dismissible fade show' role='alert'> Der Kurs $titel wurde gelöscht!   </div>";
    }
    
     //Löscht einen Schüler im Kurs.
    public function loescheKursteilnehmer($kurs_id, $benutzer_id)
    {   
        $query = "DELETE FROM benutzer_zu_kurse WHERE b_id = $benutzer_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        return "<div class='col'><div class='alert alert-success alert-dismissible fade show' role='alert'> Der Schüler $titel wurde vom Kurs abgemeldet!   </div>";
    }
    

    //Verbindet den Benutzer mit einem bestimmten Kurs.
    public function setzeBenutzerZuKurse($kurs_id, $benutzer_id)
    {
        $query = "INSERT INTO benutzer_zu_kurse (b_id,kurs_id) VALUES ($benutzer_id, $kurs_id)";
        $statement = $this->con->prepare($query);
        $statement->execute();
        
        return "<div class='col'><div class='alert alert-success alert-dismissible fade show' role='alert'> Anmeldung erfolgreich! </div>";
    }
    
    // Gibt das verschlüsselte Passwort eines Benutzers zurück. (+ Prevention of SQL Injection)
    public function nennePasswort($benutzer_id)
    {
        $query = "SELECT passwort FROM benutzer WHERE benutzer_id = $benutzer_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    // Änderung von Benutzerdaten via MySQL query (+ Prevention of SQL Injection)
    public function updateBenutzer($id, $name, $klasse, $rolle)
    {
        $query = "UPDATE benutzer SET name = '$name', klasse = $klasse, rolle = '$rolle' WHERE benutzer.benutzer_id = $id";
        
        try {
            $statement = $this->con->prepare($query);
            $statement->execute();
        } catch (exception $e){
            echo "$e";
        }
    }
    
    // Änderung von Kursdaten via MySQL query (+ Prevention of SQL Injection)
    public function updateKurs($id, $name, $beschreibung, $kursleiter1, $kursleiter2, $kursleiter3, $teilnehmerbegrenzung, $beschraenkung, $ort, $tag1, $tag2, $tag3, $zeitraum_von, $zeitraum_bis, $kosten, $bild)
    {
        $query = "UPDATE kurse SET name = '$name', beschreibung = '$beschreibung', kursleiter1 = '$kursleiter1', kursleiter2 = '$kursleiter2', kursleiter3 = '$kursleiter3', teilnehmerbegrenzung =  '$teilnehmerbegrenzung', jahrgangsstufen_beschraenkung = '$beschraenkung', ort = '$ort', Tag_1 = '$tag1', Tag_2 = '$tag2', Tag_3 = '$tag3', kosten = '$kosten', bild ='$bild', zeitraum_von = '$zeitraum_von', zeitraum_bis = '$zeitraum_bis' WHERE kurse.kurs_id = $id";
        
        try {
            $statement = $this->con->prepare($query);
            $statement->execute();
        } catch (exception $e){
            echo "$e";
        }
    }
    
        //Prüft, ob ein Schüler in einem gewissen Kurs angemeldet ist.
    public function zeigeAngemeldet($kurs_id, $benutzer_id)
    {
        $query = "SELECT * FROM benutzer_zu_kurse WHERE kurs_id = $kurs_id AND b_id = $benutzer_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
              
        if( empty($data))
        {
            return false;
        }
        return true;
    }
    
    // Gibt Alles von Benutzer aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeBenutzer()
    {
        $query = "SELECT * FROM benutzer ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
	
	    // Gibt die Id des Benutzers zurück oder 0 via MySQL query (+ Prevention of SQL Injection)
    public function zeigeBenutzerId($name)
    {
        $query = "SELECT benutzer_id FROM benutzer WHERE name = '$name'";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        if( empty($data))
        {
            return 0;
        }
        return $data[0]["benutzer_id"];
    }

    //Benutzer zu Kurse
    public function zeigeBenutzerZuKurse()
    {
        $query = "SELECT * 
                  FROM benutzer, kurse,  benutzer_zu_kurse
                  WHERE benutzer.benutzer_id = benutzer_zu_kurse.b_id
                  AND kurse.kurs_id = benutzer_zu_kurse.kurs_id
                  ORDER BY lower(kurse.name)";

        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
	
	    // Gibt die anzahl der Buchungen für einen Benutzer aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeBuchungen($benutzer_id)
    {
        $query = "SELECT COUNT(*) AS Anzahl FROM benutzer_zu_kurse WHERE b_id = $benutzer_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    // Gibt die anzahl der Buchungen für eine Klasse aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeBuchungenFurKlasse($klasse)
    {
        $query = "  SELECT benutzer.benutzer_id,  benutzer.name, COUNT(benutzer_zu_kurse.b_id  ) AS Buchungen
                    FROM benutzer LEFT JOIN benutzer_zu_kurse ON benutzer.benutzer_id = benutzer_zu_kurse.b_id
                    WHERE benutzer.rolle = 'schueler' AND benutzer.klasse = $klasse 
                    GROUP BY benutzer.benutzer_id, benutzer.name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    
       // Gibt den Schüler mit dem $id aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeEinSchueler($id)
    {
        $query = "SELECT * FROM benutzer WHERE benutzer_id = $id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    // Gibt den Lehrer mit dem $id aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeEinLehrer($id)
    {
        $query = "SELECT * FROM benutzer WHERE benutzer_id = $id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    // Gibt Alle Schüler aus einer bestimmten Klasse via MySQL query (+ Prevention of SQL Injection)
    public function zeigeKlasse($klasse)
    {
        $query = "SELECT * FROM benutzer WHERE rolle = 'schueler' AND klasse = " . $klasse. " ORDER BY lower(name)";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
            // Gibt Alles von Kurse aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeKurs($kurs_id)
    {
        $query = "SELECT * FROM kurse WHERE kurs_id = $kurs_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function zeigeKursNamen()
    {
        $query = "SELECT name FROM kurse ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
        // Gibt Alles von Kurse aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeKurse()
    {
        $query = "SELECT * FROM kurse ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
	
	
	    // Gibt Alles von Kurse aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeKursFurSchueler($schueler)
    {
        $query = "  SELECT kurse.name
                    FROM benutzer_zu_kurse, kurse
                    WHERE benutzer_zu_kurse.kurs_id = kurse.kurs_id
                    AND benutzer_zu_kurse.b_id = $schueler";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    

    // Gibt Alles Lehrer aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeLehrer()
    {
        $query = "SELECT * FROM benutzer WHERE rolle='lehrer' ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
        
        // Gibt alle Daten eines Kurses fuer den angegebenen Benutzer (Lehrer oder Schueler) aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeLehrerKurse($benutzer_id)
    {
        $query = "SELECT * FROM kurse WHERE $benutzer_id = kursleiter1 
        OR $benutzer_id = kursleiter2 OR $benutzer_id = kursleiter3 ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
        
    // Gibt alle Daten eines Kurses fuer den angegebenen Benutzer (Lehrer oder Schueler) aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeMeineKurse($benutzer_id)
    {
        $query = "SELECT * FROM kurse, benutzer_zu_kurse WHERE $benutzer_id = benutzer_zu_kurse.b_id
                  AND kurse.kurs_id = benutzer_zu_kurse.kurs_id ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    // Gibt Alles von Raeume aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeRaeume()
    {
        $query = "SELECT * FROM raeume ORDER BY bezeichnung";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

   
    // Gibt Alle Schüler aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeSchüler()
    {
        $query = "SELECT * FROM benutzer WHERE rolle='schueler' ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
        // Gibt Alle Schüler eines Kurses aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeSchülerVonKurs($kurs_id)
    {
        $query = "SELECT * FROM benutzer, benutzer_zu_kurse WHERE kurs_id='$kurs_id' AND benutzer.benutzer_id = benutzer_zu_kurse.b_id ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    // Gibt Alles Schüler aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeTeilnehmerzahl($kurs_id)
    {
        $query = "SELECT COUNT(*) AS anzahl FROM benutzer_zu_kurse  WHERE kurs_id = $kurs_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    // Gibt Alles von Veranstaltungen aus via MySQL query (+ Prevention of SQL Injection)
    public function zeigeVeranstaltungen()
    {
        $query = "SELECT * FROM veranstaltungen ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }



    //Methode zum einfuegen von neuen Kursen
    public function kursEinfuegen($name, $beschreibung, $kursleiter1, $kursleiter2, $kursleiter3, $teilnehmerbegrenzung, $beschraenkung, $ort, $tag1, $tag2, $tag3, $zeitraum_von, $zeitraum_bis, $kosten, $bild)
    {
/*      Mit Datum:  
        $zeitraum_von=mb_substr($zeitraum_von, 0, 10) ." ".mb_substr($zeitraum_von, 11)."-00";
        $zeitraum_von=mb_substr($zeitraum_von, 0, 13)."-".mb_substr($zeitraum_von, 14);

        $zeitraum_bis=mb_substr($zeitraum_bis, 0, 10) ." ".mb_substr($zeitraum_bis, 11)."-00";
        $zeitraum_bis=mb_substr($zeitraum_bis, 0, 13)."-".mb_substr($zeitraum_bis, 14);*/

        $zeitraum_von=$zeitraum_von.":00";
        $zeitraum_bis=$zeitraum_bis.":00";
        
        $eintrag = "INSERT INTO `kurse` (`name`, `bild`, `beschreibung`, `kursleiter1`, `kursleiter2`, `kursleiter3`, `teilnehmerzahl` ,`teilnehmerbegrenzung`, `jahrgangsstufen_beschraenkung`, `ort`, `Tag_1`, `Tag_2`, `Tag_3`, `zeitraum_von`, `zeitraum_bis`, `kosten`) VALUES ('$name', '$bild', '$beschreibung', '$kursleiter1', '$kursleiter2', '$kursleiter3', 0, '$teilnehmerbegrenzung', '$beschraenkung', '$ort' , '$tag1', '$tag2', '$tag3', '$zeitraum_von', '$zeitraum_bis', '$kosten');";

        $statement = $this->con->prepare($eintrag);
        $statement->execute();

/*
        if($date == true) {

            return ("Kurs erfolgreich eingetragen!");
        } else {
            return("Fehler beim Eintragen des Kurses!");
        }
*/
    }

    public function namePruefen($name)
    {
        $query = "SELECT * FROM kurse WHERE name='$name' ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        if( empty($data))
        {
            return true;
        }
        return false;
    }
    
    public function loginPruefen($username, $passwort)
    {
        $passwort = md5($passwort);
        $query = "SELECT * FROM benutzer WHERE name='$username' AND passwort = '$passwort' ORDER BY name";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function console_log($data)
    {
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }


    public function suche($suchbegriff){
        $query = "  SELECT DISTINCT kurse.kurs_id, kurse.name, kurse.bild
                    FROM kurse
                    WHERE (LOWER(kurse.beschreibung) LIKE LOWER(:begriff) OR LOWER(kurse.name) LIKE LOWER(:begriff))";
        $statement = $this->con->prepare($query);
        $statement->execute(["begriff"=>"%".$suchbegriff."%"]);
        $date = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $date;

    }
    
    //Meldet den Benutzer ab.
    public function benutzerAbmelden($kurs_id, $benutzer_id){
        //Daten aus der Datenbank löschen.
        $kursteilnehmer = $this->zeigeEinSchueler($benutzer_id);
        $name = $kursteilnehmer[0]["name"];
        $query = "DELETE FROM benutzer_zu_kurse WHERE kurs_id=$kurs_id AND b_id = $benutzer_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($this->zeigeAngemeldet($kurs_id, $benutzer_id) == false){
            $query = "UPDATE kurse SET teilnehmerzahl = teilnehmerzahl - 1 WHERE kurs_id=$kurs_id";
            $statement = $this->con->prepare($query);
            $statement->execute();
        return "<div class='alert alert-success alert-dismissible fade show' role='alert'> $name wurde erfolgreich abgemeldet!  </div>";
        }
        else{
            return "<div class='alert alert-danger alert-dismissible fade show' role='alert'> $name konnte nicht abgemeldet werden!  </div>";
        }
    }

    //Prüft, ob der Benutzer sich anmelden kann und fügt ihn dann ein.
    public function benutzerAnmelden($kurs_id, $benutzer_id)
    {
        $ausgabe="";
        $query = "SELECT COUNT(*) FROM benutzer_zu_kurse WHERE kurs_id=$kurs_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT teilnehmerbegrenzung FROM kurse WHERE kurs_id=$kurs_id";
        $statement = $this->con->prepare($query);
        $statement->execute();
        $datu = $statement->fetchAll(PDO::FETCH_ASSOC);

       

        if($data[0]["COUNT(*)"]>=$datu[0]["teilnehmerbegrenzung"])
        {
            $ausgabe = $ausgabe."<div class='alert alert-danger alert-dismissible fade show' role='alert'> Der Kurs ist voll!   </div>";
        }

        if($data[0]["COUNT(*)"]<$datu[0]["teilnehmerbegrenzung"])
        {
            //Liefert alle Kurse des Schülers
            $query = "SELECT kurs_id FROM benutzer_zu_kurse WHERE b_id=$benutzer_id";
            $statement = $this->con->prepare($query);
            $statement->execute();
            $b_kurse = $statement->fetchAll(PDO::FETCH_ASSOC);

            //Liefert, ob der gewählte Kurs am 1. Tag stattfindet (0 oder 1)
            $query = "SELECT Tag_1 FROM kurse WHERE kurs_id=$kurs_id";
            $statement = $this->con->prepare($query);
            $statement->execute();
            $tag1_pr = $statement->fetchAll(PDO::FETCH_ASSOC);

            //Liefert, ob der gewählte Kurs am 2. Tag stattfindet (0 oder 1)
            $query = "SELECT Tag_2 FROM kurse WHERE kurs_id=$kurs_id";
            $statement = $this->con->prepare($query); 
            $statement->execute();
            $tag2_pr = $statement->fetchAll(PDO::FETCH_ASSOC);

            //Liefert, ob der gewählte Kurs am 3. Tag stattfindet (0 oder 1)
            $query = "SELECT Tag_3 FROM kurse WHERE kurs_id=$kurs_id";
            $statement = $this->con->prepare($query);
            $statement->execute();
            $tag3_pr = $statement->fetchAll(PDO::FETCH_ASSOC);

            

            $test=true;

            //Prüft, ob der Schüler am Tag des gewählten Kurses bereits einen anderen Kurs gebucht hat. Hierzu werden alle gebuchten Kurse des Schülers (aus $b_kurse) durchlaufen.
            foreach ($b_kurse AS $row) 
            {     
                $b=$row["kurs_id"];
               
                //Liefert, ob der bereits gebuchte Kurs am 1. Tag stattfindet (0 oder 1)
                $query = "SELECT Tag_1 FROM kurse WHERE kurs_id=$b";
                $statement = $this->con->prepare($query);
                $statement->execute();
                $tag1 = $statement->fetchAll(PDO::FETCH_ASSOC);

                //Liefert, ob der bereits gebuchte Kurs am 2. Tag stattfindet (0 oder 1)
                $query = "SELECT Tag_2 FROM kurse WHERE kurs_id=$b";
                $statement = $this->con->prepare($query);
                $statement->execute();
                $tag2 = $statement->fetchAll(PDO::FETCH_ASSOC);

                //Liefert, ob der bereits gebuchte Kurs am 3. Tag stattfindet (0 oder 1)
                $query = "SELECT Tag_3 FROM kurse WHERE kurs_id=$b";
                $statement = $this->con->prepare($query);
                $statement->execute();
                $tag3 = $statement->fetchAll(PDO::FETCH_ASSOC);

               
                //Testet, ob es Überschneidungen gibt. Falls ja, wird der test auf false gesetzt, damit der Benutzer am Ende nicht eingefügt wird.
                if($tag1_pr[0]["Tag_1"]==1 AND $tag1[0]["Tag_1"]==1){
                    $test=false;
                    $ausgabe = $ausgabe."<div class='alert alert-danger alert-dismissible fade show' role='alert'>An Tag 1 hast du bereits einen Kurs gebucht!   </div>";
                }
                if($tag2_pr[0]["Tag_2"]==1 AND $tag2[0]["Tag_2"]==1){
                    $test=false;
                    $ausgabe = $ausgabe."<div class='alert alert-danger alert-dismissible fade show' role='alert'>An Tag 2 hast du bereits einen Kurs gebucht!   </div>";
                }
                if($tag3_pr[0]["Tag_3"]==1 AND $tag3[0]["Tag_3"]==1){
                    $test=false;
                    $ausgabe = $ausgabe."<div class='alert alert-danger alert-dismissible fade show' role='alert'>An Tag 3 hast du bereits einen Kurs gebucht!   </div>";
                }
            }

            
            //Falls noch genügend Plätze frei sind und der Schüler an diesem Tag noch nichts gebucht hat, wir die Teilnehmerzahl erhöht und der Schüler dem Kurs zugeordnet
            if($test){
                $query = "UPDATE kurse SET teilnehmerzahl = teilnehmerzahl + 1 WHERE kurs_id=$kurs_id";
                $statement = $this->con->prepare($query);
                $statement->execute();
                $ausgabe = $ausgabe.$this->setzeBenutzerZuKurse($kurs_id, $benutzer_id);
            }
        }  
        return $ausgabe;
    }
}
?>
