# 🇮🇹🗺️ geo-localita-bundle

> **Pacchetto Symfony per la gestione avanzata delle località, comuni, province e regioni d'Italia (e del mondo)!**

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

## 🏁 Utilizzo rapido

```bash
composer require pellicanipasquale/geo-localita-bundle
