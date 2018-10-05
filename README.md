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

## Installazione

### Prerequisiti

Per poter iniziallizare il progetto in un ambiente è necessario disporre dei 
seguenti strumenti/software:

- [GIT](https://git-scm.com/)
- [Docker](https://www.docker.com/)

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
già presenti all'interno delle immagini.

### Installazione

- Clona il repository nel tuo computer locale usando 
`git clone https://github.com/AgID/www.agid.gov.it`

### Docker

- Clona il file `.env.example` in `.env`
    > Note: il file .env contiene delle configurazioni utilizzate da Docker per 
    istanziare i container e le loro configurazioni

- Per personalizzare ulteriormente l'ambiente docker utilizzato dal portale
è possibile sovrascriverne le configurazioni utilizzando `docker-compose.override.yml`
(vedi [documentazione](https://docs.docker.com/compose/extends/)).
Nel progetto sono già presenti dei template di `docker-compose.ovveride.[ENV].yml`
diversificati per ambienti di utilizzo `stage` e `local`

- avviare i container con il comando `make up`
    > Note: nel repository è presente un [Makefile](./Makefile) con all'interno,
     già pre-configurati per l'utilizzo con l'ambiente Docker fornito nel repository,
     una serie di comandi per l'interfacciamento sia con Docker che con il sito web Drupal8.

- installare le dipendenze di composer `composer install --prefer-dist`,
se si utilizza la dockerizzazione del progetto `make exec "composer install --prefer-dist"`

#### @todo: clean installation


#### Install with dump of database

> Attenzione: i passaggi successivi fanno riferimento all'uso delle immagini di 
docker legate al progetto. In caso di piattaforme LAMP diverse sarà necessario
personalizzare i file `settings.php` per includere correttamente i dati di 
connessione ai servizi/server `database`, `solr` e `redis`; oltre al server di 
posta locale.

- copiare il file [default.settings.php](./docroot/web/sites/default/default.settings.php)
in `./docroot/web/sites/default/settings.php`

- copiare il file [default.services.yml](./docroot/web/sites/default/default.services.yml)
in `./docroot/web/sites/default/services.yml`

- importare il dump del database `make drush "sql-cli < path-to-dump.sql"`

### Allineamento configurazioni

A seguito di modifiche delle [configurazioni](./docroot/config/agid) del sito 
è possibile eseguire un allineamento tramite il commando `make drush "cim -y"`.


### Annotazioni

**Funzionalità**

Per l'attivazione automaticata di determinate funzionalità come [Config Split](https://www.drupal.org/project/config_split), 
la configurazione del re-indirizzamento delle mail in uscita verso il container
[Mailhog](http://mailhog.agid.docker.localhost), ecc... è necessario specificare
nell'ambiente PHP la variabile globale `ENV_TYPE` configurata con i seguenti 
possibili valori `PROD`, `STAGE` e `LOCAL`.
Se il progetto è inizializzato con le configurazioni/immagini Docker indicate 
nel progetto non sarà necessario; si potrà comunque variare a livello di 
`docker-compose.override.yml` oppure direttamente nel file `.env` generato.

**Per l'ambiente di produzione**

E' necessario configurare direttamente nel `settings.php` alcune variabili/configurazioni
non esportabili nel repository pubblico, come ad esempio i dati di collegamento
verso il server mail.

```php
/**
 * Custom configurations.
 *
 * @todo To be manually updated for the production environment!
 */
if (getenv('ENV_TYPE') == 'PROD') {

  // SMTP Settings.
  $config['smtp.settings']['smtp_on'] = TRUE;
  $config['smtp.settings']['smtp_password'] = NULL;
  $config['smtp.settings']['smtp_host'] = "";
  $config['smtp.settings']['smtp_port'] = "";

}
```

---

## Contributing

> Per iniziare..

### Step 1

- **Opzione 1**
    - 🍴 Fork questo repository!

- **Opzioone 2**
    - 👯 Clona questo repository nella tua macchina locale usando `git clone https://github.com/AgID/www.agid.gov.it`

### Step 2

- **HACK AWAY!** 🔨🔨🔨

### Step 3

- 🔃Crea una nuova "pull request" usando <a href="https://github.com/AgID/www.agid.gov.it/compare" target="_blank">`https://github.com/AgID/www.agid.gov.it/compare`</a>.

---

## Team

> Team AgID

@todo

> Collaboratori

@todo

---

## FAQ

- **How do I do *specifically* so and so?**
    - No problem! Just do this.

---

## Support

- Website <a href="https://www.agid.gov.it/" target="_blank">`www.agid.gov.it/`</a>
- Twitter <a href="https://twitter.com/agidgov" target="_blank">`@agidgov`</a>
- @todo: Insert more links here.


---

## License

@todo MIT or GNU GPLv3 ?

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

- **[MIT license](http://opensource.org/licenses/mit-license.php)**
- Copyright 2018 © <a href="https://www.agid.gov.i" target="_blank">AgID</a>.