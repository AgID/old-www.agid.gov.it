Deploy su Docker di www.agid.gov.it
-----------------------------------

# Prerequisiti

* [Docker](https://www.docker.com/) > 17+
* [Docker compose](https://docs.docker.com/compose/)

# Installazione

1. Clonare il repository sul server di destinazione
2. Verificare sul server che le porte TCP 80 e 443 siano libere
3. Copiare all'interno della cartella `certs` il certificato HTTPS e la sua chiave privata, rinominado i file,
    rispettivamente `traefik.cert` e `traefik.key`
4. Duplicare il file `.env.example` in un nuovo file `.env`
5. Verificare lo uid/gid del proprio utente su Linux (con `id -u` e `id -g`) e scegliere l'immagine corretta per il 
  container PHP tra quelle elencate [qua](https://hub.docker.com/r/wellnetimages/wodby-php/tags/).
5. Avviare lo stack: `make up`
6. Caricare un dump del database
    ```
    $> source .aliases
    $> dsqlc < dump.sql
    ```
7. Copiare il filesystem di Drupal in `docroot/web/sites/default/files`

# Architettura

Il sistema usa [Traefik](https://traefik.io/) per instradare il traffico verso il container di Apache e per gestire la
terminazione HTTPS. In questo modo l'unico container che necessita di esporre porte TCP sull'host è quello in cui gira
Traefik. Le configurazioni di Traefik per il container di Apache sono inserite come `labels` all'interno del file
docker-compose.yml.
