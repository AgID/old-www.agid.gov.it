<a href="https://www.agid.gov.it"><img src="https://www.agid.gov.it/themes/custom/agid/logo.svg" title="Agenzia per l'Italia Digitale" alt="Agenzia per l'Italia Digitale" width="200"></a>

<!-- [![Agenzia per l'Italia Digitale](https://www.agid.gov.it/themes/custom/agid/logo.svg)](https://www.agid.gov.it/) -->

# Agenzia per l'Italia Digitale

> Repository principale per il sito [AgID](https://www.agid.gov.it).

Il portale è sviluppato in [Drupal8](https://www.drupal.org).
Nel repository sono presenti anche le configurazioni per poter avviare il sito
web attraverso l'uso di [Docker](https://www.docker.com/) con le immagini di
[Docker4Drupal](https://github.com/wodby/docker4drupal) fornite da [Wodby](https://wodby.com/).

[![Github Issues](http://githubbadges.herokuapp.com/AgID/www.agid.gov.it/issues.svg?style=flat-square)](https://github.com/AgID/www.agid.gov.it/issues) 
[![Pending Pull-Requests](http://githubbadges.herokuapp.com/AgID/www.agid.gov.it/pulls.svg?style=flat-square)](https://github.com/AgID/www.agid.gov.it/pulls)
[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

---

## Indice

- [Installazione](#installation)
- [Contribuire](#contributing)
- [Team](#team)
- [FAQ](#faq)
- [Supporto](#support)
- [Licenze](#license)

---

## Installazione in locale

### Prerequisiti

Per poter installare il progetto in un ambiente è necessario disporre dei 
seguenti strumenti/software:

- [GIT](https://git-scm.com/)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

Il progetto è predisposto per essere lanciato attraverso le immagini di 
[Docker4Drupal](https://github.com/wodby/docker4drupal).
E' possibile comunque utilizzare una piattaforma LAMP diversa rispettando i 
seguenti requisiti:

- [PHP](http://php.net/) `>= 7.2`
- [MariaDB](https://mariadb.org/) `>= 10.1` o [MySQL](https://www.mysql.com/) `>= 8.0`
- [Apache](http://httpd.apache.org/) `>= 2.4`
- [Solr](http://lucene.apache.org/solr/) `>= 7.4`
- [Redis](https://redis.io/) `>= 3.2`

In questo contesto è necessario inoltre avere installato i seguenti software:

- [Composer](https://getcomposer.org/)
- [Drush](https://www.drush.org/)
- [DrupalConsole](https://drupalconsole.com/)

Nel caso che si utilizzino le immagini di `Docker4Drupal` questi software sono
già presenti all'interno delle immagini stesse.

### Avviare il progetto

- Clonare il repository in locale 

    `git clone https://github.com/AgID/www.agid.gov.it`

- Copiare il file `.env.example` in `.env`
    
    > Nota: il file .env contiene le configurazioni utilizzate da Docker per 
    eseguire i container

- Per personalizzare ulteriormente l'ambiente docker utilizzato dal portale
è possibile sovrascriverne le configurazioni utilizzando `docker-compose.override.yml`
(vedi [documentazione](https://docs.docker.com/compose/extends/)).
Nel progetto sono già presenti dei template di `docker-compose.ovveride.[ENV].yml`
diversificati per ambienti di utilizzo `stage` e `local`

- Avviare i container con il comando `make up`
    > Nota: nel repository è presente un [Makefile](./Makefile) con all'interno,
     già pre-configurati per l'utilizzo con l'ambiente Docker fornito nel repository,
     una serie di comandi per l'interfacciamento sia con Docker che con il sito web Drupal8.

- Installare le dipendenze di composer
 
    `make exec "composer install --prefer-dist"`

#### Importare il dump del database in locale

- Copiare il file [default.settings.php](./docroot/web/sites/default/default.settings.php)
in `./docroot/web/sites/default/settings.php`

- Copiare il file [default.services.yml](./docroot/web/sites/default/default.services.yml)
in `./docroot/web/sites/default/services.yml`

- Importare il dump del database `make drush "sql-cli < path-to-dump.sql"`

### Gestire le configurazioni

E' possibile importare le configurazioni presenti nella cartella ./docroot/config/agid usando il comando:

    `make drush "cim -y"`
    
Viceversa per esportare le configurazioni usare:

    `make drush "cex -y"`

### Annotazioni

**Funzionalità**

Per l'attivazione automaticata di determinate funzionalità come [Config Split](https://www.drupal.org/project/config_split), 
la configurazione del re-indirizzamento delle mail in uscita verso il container
[Mailhog](http://mailhog.agid.docker.localhost), ecc... è necessario specificare
nell'ambiente PHP la variabile globale `ENV_TYPE` configurata con i seguenti 
possibili valori `PROD`, `STAGE` e `LOC`.

**Per l'ambiente di produzione**

E' necessario configurare direttamente nel `settings.php` alcune variabili/configurazioni
non esportabili nel repository pubblico, come ad esempio i dati di collegamento
verso il server mail.
