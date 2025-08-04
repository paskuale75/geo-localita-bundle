# 🇮🇹🗺️ geo-localita-bundle

> **Pacchetto Symfony per la gestione avanzata delle località, comuni, province e regioni d'Italia (e del mondo)!**

---

## 🏁 Configurazione rapida

1. Installa il bundle via Composer:

```bash
composer require pellicanipasquale/geo-localita-bundle
```

2. Aggiungi il bundle al file `config/bundles.php`:

  ```php
  PasqualePellicani\GeoLocalitaBundle\GeoLocalitaBundle::class => ['all' => true],
  ```

3. Aggiungi questa configurazione al file `config/packages/doctrine.yaml` del tuo progetto Symfony:

```yaml
doctrine:
    orm:
        mappings:
            GeoLocalitaBundle:
                is_bundle: false
                type: attribute # oppure "annotation" se usi ancora le annotation
                dir: '%kernel.project_dir%/vendor/pasqualepellicani/geo-localita-bundle/src/Entity'
                prefix: 'PasqualePellicani\GeoLocalitaBundle\Entity'
                alias: GeoLocalitaBundle
```
4. Mappare i servizi, nel `config/services.yaml` aggiungi:

```yaml
services:
    PasqualePellicani\GeoLocalitaBundle\:
        resource: '../vendor/pasqualepellicani/geo-localita-bundle/src/*'
        exclude: '../vendor/pasqualepellicani/geo-localita-bundle/src/{Entity,Tests,Migrations,Kernel.php}'
        autowire: true
        autoconfigure: true
```

5. Generazione delle migration

Dopo aver configurato Doctrine come sopra, puoi generare la migration per creare le tabelle del bundle:

```bash
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

---

## ✨ Funzionalità

- 📦 **Import automatico** di tutti i dati geografici italiani da DatabaseComuni.it (*dati NON inclusi per motivi di licenza*).
- 🌍 **Supporto città estere** via GeoNames.
- 🔗 **Relazioni** Nazione ↔ Regione ↔ Provincia ↔ Città ↔ CAP ↔ Coordinate
- 🚀 **Command pronto** per import sequenziale e rollback.
- 🧩 **Compatibile con Api Platform**: Entity già serializzabili come API REST.
- 🛠️ **Riutilizzabile**: installa questo pacchetto in qualsiasi progetto Symfony via Composer.

---

## 📂 Dati richiesti

> 📥 **I dati geografici italiani (premium) NON sono inclusi!**  
> Copia i file `.xlsx` o `.csv` che hai acquistato nella cartella `/var/data/` secondo istruzioni.  
> Sono supportati anche i dati GeoNames per città estere.

---

## 🚦 Licenza

- Codice: MIT License.
- **Dati**: l’utente deve possedere regolare licenza DatabaseComuni.it.  
  (Vedi [www.databasecomuni.it](https://www.databasecomuni.it) per l’acquisto).

---

## Importazione dei dati

Per informazioni dettagliate su come importare i file di DatabaseComuni.it nel database del tuo progetto, consulta la guida dedicata:

👉 [Guida all’importazione dei dati](IMPORT.md)

All’interno troverai istruzioni passo-passo su:
- Dove posizionare i file scaricati dal sito DatabaseComuni.it
- Come lanciare il comando di importazione Symfony
- Opzioni avanzate e troubleshooting