# 📥 Importazione dati geografici

Questa guida spiega **come reperire, organizzare e importare** i dati geografici per il bundle `geo-localita-bundle`.

---

## 📑 Quali file servono?

### 🇮🇹 **Per l'Italia (DatabaseComuni.it)**

Acquista i file aggiornati su [https://www.databasecomuni.it/](https://www.databasecomuni.it/)  
I file richiesti (in formato `.xlsx` o `.csv`):

- `italy_regions.xlsx`
- `italy_provincies.xlsx`
- `italy_cities.xlsx`
- `italy_cap.xlsx`
- `italy_multicap.xlsx`
- `italy_geo.xlsx`

**Nota:** I dati italiani sono protetti da licenza, _non vanno condivisi pubblicamente_.

---

### 🌍 **Per l'Estero (GeoNames)**

Scarica gratuitamente da [https://download.geonames.org/export/dump/](https://download.geonames.org/export/dump/):

- `countryInfo.txt` (lista nazioni)
- `cities5000.txt` (città principali mondiali)

---

## 📂 Dove mettere i file?

Copia tutti i file dati all’interno della seguente cartella del bundle:

/var/data/content

*(La cartella è già presente nel pacchetto, con un file README.txt di esempio)*

---

## 🚦 **Import finale**

Dopo aver posizionato i file:

```bash
php bin/console geo:import:all

# Il comando importerà in sequenza: nazioni, regioni, province, comuni, CAP, coordinate e città estere.

## ⚠️ Attenzione

- **Non includere mai i file dati (DatabaseComuni.it o altri dati protetti) nel repository pubblico!**
- Assicurati di acquistare o scaricare i dati SOLO dai fornitori ufficiali e rispettare le relative licenze.
- Dopo ogni aggiornamento ai dati, controlla sempre la compatibilità tra la versione dei file e quella del bundle.
- Se riscontri errori durante l’importazione:
    - Verifica che la struttura dei file corrisponda alle aspettative (header, colonne, formato).
    - Controlla i permessi di scrittura/lettura sulla cartella `var/data/`.
    - Consulta la documentazione del bundle per eventuali requisiti aggiuntivi.

---

## ℹ️ Supporto

- Per dubbi, richieste di aiuto o segnalazione bug, **apri una Issue su GitHub**:  
  [https://github.com/pellicanipasquale/geo-localita-bundle/issues](https://github.com/pellicanipasquale/geo-localita-bundle/issues)
- Puoi anche inviare una Pull Request per proporre miglioramenti o nuove funzionalità.
- Per segnalazioni riservate: usa la mail collegata all’account Packagist/GitHub del maintainer.
