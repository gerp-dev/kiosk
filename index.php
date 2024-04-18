<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>il Tacco di Bacco - i prossimi eventi</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="qrcode.min.js"></script>
    <link rel="stylesheet" href="style.css"<?php rand()?>>
</head>
<body>
<div class="mycontainer container-padding" style="background-color: #01a3e1;">
    <div>
        <img src="logo-bianco.png" class="logo">
        <span class="aff"> / Martina Franca e dintorni</span>
    </div>
    <div>
        <h2 id="orario" class="oraa"></h2>
    </div>
</div>

<div id="eventContainer" class="txt"></div>

<script>
    // Funzione per visualizzare l'orario
    function orario(){
        var oggi = new Date();
        var giorno = oggi.getDay();
        var numerog = oggi.getDate();
        var mese = oggi.getMonth();
        var anno = oggi.getFullYear();
        var ora = oggi.getHours();
        var minuti = oggi.getMinutes();
        var secondi = oggi.getSeconds();

        switch(giorno){
            case(0):
                giorno = "Domenica";
                break;
            case(1):
                giorno = "Lunedì";
                break;
            case(2):
                giorno = "Martedì";
                break;
            case(3):
                giorno = "Mercoledì";
                break;
            case(4):
                giorno = "Giovedì";
                break;
            case(5):
                giorno = "Venerdì";
                break;
            case(6):
                giorno = "Sabato";
                break;
        }
        switch(mese){
            case(0):
                mese = "Gennaio";
                break;
            case(1):
                mese = "Febbraio";
                break;
            case(2):
                mese = "Marzo";
                break;
            case(3):
                mese = "Aprile";
                break;
            case(4):
                mese = "Maggio";
                break;
            case(5):
                mese = "Giugno";
                break;
            case(6):
                mese = "Luglio";
                break;
            case(7):
                mese = "Agosto";
                break;
            case(8):
                mese = "Settembre";
                break;
            case(9):
                mese = "Ottobre";
                break;
            case(10):
                mese = "Novembre";
                break;
            case(11):
                mese = "Dicembre";
                break;
        }

        if(ora<10) ora = "0" + ora;
        if(minuti<10) minuti = "0" + minuti;
        if(secondi<10) secondi = "0" + secondi;

        document.getElementById("orario").innerHTML = "Oggi è " + giorno + " " + numerog + " " + mese + " " + anno + "   " + ora + ":" + minuti + ":" + secondi;
    }
    // per aggiornare l'orario ogni secondo
    setInterval(orario, 1000);
    // Richiamo della funzione orario per visualizzare l'orario all'avvio
    orario();
</script>

<script>
    $(document).ready(function() {
        // Codice PHP per leggere gli eventi dal file JSON
        var events = <?php
            $json = file_get_contents("https://iltaccodibacco.it/martinafranca/events.json");
            $events = json_decode($json, true);
            $eventContainer = array();

            if ($events !== null && !empty($events)) {
                foreach ($events as $evento) {
                    $descrizione = $evento['des_evento'];

                    if(strlen($descrizione)>600)
                    {
                        // Taglia la descrizione esattamente ai primi 700 caratteri
                        $descrizione = mb_substr($evento['des_evento'], 0, 700);
                        // Trova l'ultima posizione di un punto all'interno della sottostringa
                        $ultimo_punto = mb_strrpos($descrizione, '.');

                        if ($ultimo_punto !== false) {
                            // Se c'è un punto nella sottostringa, taglia la descrizione fino a quel punto
                            $descrizione = mb_substr($descrizione, 0, $ultimo_punto + 1);
                        }
                        // Aggiungiamo due punti poiché uno è già compreso nella descrizione (l'ultimo punto individuato non viene rimosso)
                        $descrizione .= "..";
                    }
                    // Riempimento del vettore con lettura dai campi del file JSON 
                    $eventContainer[] = array(
                        'description' => $descrizione,
                        'luogo' => $evento['comune'],
                        'locale'=> $evento['des_locale'],
                        'data' => $evento['data_evento'],
                        'title' => $evento['nome_evento'],
                        'image' => $evento['img'],
                        'url' => $evento['url_evento'],
                    );
                }
            }

            echo json_encode($eventContainer);
        ?>;

        var currentIndex = 0;
        var eventContainer = $("#eventContainer");

        // Funzione per visualizzare i dettagli dell'evento corrente
        function displayEvent(index) {
            var event = events[index];
            var html =
                "<div class='event-details'>" +
                    "<div>" +
                        "<p class='small small_L'>" + event.locale + ", " + event.luogo + "</p>" +
                        "<p class='small_D'>" + event.data + "</p>" +
                        "<p class='tit'>" + event.title + "</p>" +
                    "</div>" +
                    "<div>" + 
                        "<img src='" + event.image + "' alt='Immagine evento' class='imd'>" +
                        "<p class='big description'>" + event.description +"</p>" +
                    "</div>" +
                    "<div class='qr-code'></div>" +
                "</div>";
            eventContainer.html(html);

            // Genera il codice QR per il link dell'evento
            var qrCode = new QRCode($(".qr-code")[0], {
                text: event.url, // Utilizza il campo URL dell'evento
                width: 150,
                height: 150,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        } 

        // Esegui la funzione displayEvent per visualizzare il primo evento all'avvio
        displayEvent(currentIndex);

        // Imposta l'intervallo per cambiare evento ogni 10 secondi (10000 millisecondi)
        setInterval(function() {
            currentIndex = (currentIndex + 1) % events.length;
            displayEvent(currentIndex);
        }, 10000);
    });
</script>
<p class='coll'> Realizzato da Cognita S.r.l in collaborazione con I.I.S.S E.Majorana Martina Franca (TA)</p> 
</body>
</html> 

