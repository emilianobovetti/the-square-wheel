---
title: 'Dostupno parte 2: l''analisi'
lang: it
date: 2019-02-22T19:23:19.474Z
---
C'è sempre una valore nel commettere un errore, c'è sempre l'opportunità di imparare qualcosa. Senza una buona analisi stiamo sprecando un'occasione utile. 

Questo è il motivo per cui vorrei mettere a fuoco che cosa non funziona con [Dostupno](https://play.google.com/store/apps/details?id=com.devilapp.ring), l'app che promette di inviare messaggi in totale riservatezza e senza utilizzare server.

Come funziona questo sistema? Il concetto è molto semplice, diciamo che ho intenzione di comunicare con te:
- Prima di cominciare devo definire una lista di dieci messaggi che voglio poterti inviare e inserire nell'app sul mio telefono questa lista insieme al tuo numero di telefono.
- Dopo che questa lista è stata caricata su un server e scaricata dall'app sul tuo telefono, possiamo iniziare a scambiare messaggi.
- Quando chiederò alla mia applicazione di inviarti il terzo messaggio della mia lista, questa farà sul tuo telefono uno squillo della durata prestabilita per il terzo messaggio. Se la lista è sincronizzata correttamente il tuo telefono ti comunicherà il testo corrispondente a questo segnale.

Ora, questa idea nasconde diverse classi di problemi:

### Sicurezza

Gli autori promettono il più totale anonimato, dal momento che i messaggi vengono codificati attraverso gli squilli, non hanno modo di venire a conoscere il contenuto delle conversazioni.

Allo stato attuale delle cose, però, il server che usano per sincronizzare i messaggi non utilizza alcuna misura di sicurezza<sup>[[1]](https://www.reddit.com/r/ItalyInformatica/comments/aqqaav/dostupno_perch%C3%A9_devilapp/)</sup>, questo significa che chiunque può scaricare le liste di messaggi degli utenti e se qualche sventurato si è fidato di questo servizio e ha caricato dati sensibili nel sistema questi sono completamente esposti, almeno per il momento.

Esiste una seconda versione dell'applicazione che si chiama [Dostupno code](https://play.google.com/store/apps/details?id=com.devilapp.dostupnocode), questa non fa uso del sistema di sincronizzazione perché i messaggi che si possono inviare sono preimpostati dagli sviluppatori. <br>
Quindi è più sicura? In realtà no perché le infrastrutture di rete le usano eccome! Non le loro, ma quelle degli operatori telefonici, che *ovviamente* sanno chi e quando state chiamando e potenzialmente possono sapere che messaggi "Dostupno code" vi state scambiando. Fortunatamente i messaggi sono "buongiorno" e simili, quindi non è una grosso problema.

Però rimane un errore pensare che sia un sistema sicuro. Gli operatori possono decodificare questi messaggi. Non essendo un esperto di telecomunicazioni non posso affermarlo con precisione, ma - oltretutto - non escludo che sia possibile intercettare la durata di uno squillo anche da parte di terzi.

Senza dubbio i servizi di messaggistica istantanea che usano la crittografia end-to-end sono più sicuri.

### Efficienza

Secondo un utente di reddit, la durata di uno squillo nel caso peggiore è di 50 secondi<sup>[[2]](https://www.reddit.com/r/ItalyInformatica/comments/aq0iea/dostupno_lanti_whatsapp_una_storia_surreale/)</sup>. In questo tempo viene trasmesso, fondamentalmente, un numero che va da 1 a 10, ossia l'indice del messaggio in questione. Un numero da 1 a 10 sono *4 bit* di informazione. Con questo metodo vengono trasmessi, nel caso peggiore 4 bit in 50 secondi: *0.08 bit/s*.

Per dare un termine di paragone: la New Horizons nel caso peggiore trasmette a *1000 bit/s*<sup>[[3]](https://www.nasa.gov/pdf/513840main_Signals_and_Noise_3-5.pdf)</sup>. Da Plutone. La comunicazione di una sonda a circa 6 miliardi di km da qui<sup>[[4]](https://www.quora.com/What%E2%80%99s-the-average-distance-between-Earth-and-Pluto-in-kilometres)</sup> è *diecimila* volte più efficiente in termini di banda.

### Usabilità

Quando un utente riceve una telefonata non ha nessun modo per distinguire una chiamata vera da un segnale Dostupno. È un normale squillo, in fondo. Ora rispondo? Aspetto un minuto? E se era una chiamata vera che è stata interpetata erroneamente come messaggio?

Se avete una risposta ad una di queste domande fatemelo sapere, mi raccomando.

Secondo la mia modestissima opinione questo rimane un esperimento divertente, ma inutilizzabile; un errore di valutazione che valeva sicuramente la pena analizzare, che ci può insegnare qualcosa, ma che rimane comunque un errore.

Una piccola riflessione per concludere: in un periodo in cui viviamo tutti costantemente connessi (con tutti i problemi che ne derivano), in cui paghiamo abbonamenti da 50GB per il nostro smartphone per usarne spesso meno di un decimo e soprattutto in un periodo in cui forse avremmo davvero bisogno di staccarci un po' più spesso dal telefono ci serve davvero qualcosa per mandare messaggi senza internet? È proprio questo il problema?

Oppure l'euforia un po' eccessiva nei confronti di qualcosa che promette di "fregare il sistema" ha una spiegazione più complessa.

Ai posteri l'ardua sentenza.
