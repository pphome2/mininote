# MiniNote

## Jegyzetkezelő program

Fejlesztő: [pphome2](https:/github.com/pphome2)

**Aktuális verzió: 2020.**

**Első megjelenés: 2019.**


A program felhasználóhoz kötött kategorizált jegyzeteket tárol.


Egyszerű:
- nem szükséges CMS a működéshez
- felhasználókezelés, felasználónevet és jelszót tárol a `config` fájlban
- nem kell telepíteni
- nem használ SQL adatbázist
- használhat cookie-kat

### Telepítés

- felmásolni az összes fájlt a webserver megfelelő könyvtárába
- `config` könyvtár `config.php` fájlátnézése, a beállítások itt taláhatók
- írási jog kell a `config.php` fájlban megadott jegyzet tároló könyvtárra
- `config` könyvtárban találhatók a nyelvi fájlok, ha szükséges a módosítható


### Működés

Az adatok a `config.php` könyvtárban megadott jehyzet könyvtárban tárolódnak,
külön alkönyvtárakban, felhasználóként. Ezalatt jönnek létre a kategóriák
könyvtárként, ahol a jegyzetek tárolódnak.

Indítás:
- felhasználó bejelentkezés: `index.html`

