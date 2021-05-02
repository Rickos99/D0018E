# En enkel webbsida för e-handel
Projekt för obligatorisk laboration i kursen [D0018E, Databasteknik](https://www.ltu.se/edu/course/D00/D0018E/) som gavs i november till januari 2021.

I detta projekt har vi skapat en enkel sida för e-handel som är kopplad till en MySQL databas. Detta projekt har utförts över 4 sprint perioder. Hemsidan är till för tre typer av användare, kunden som handlar via hemsidan, supportpersonal som hanterar support tickets och eventuella problem som uppstår för kunderna och administratören som kan hantera vilka produkter som erbjuds, t.ex. lagersaldo och beskrivningar av produkter. Vi har skapat en relationsdatabas som ligger på [LUDD](https://www.ludd.ltu.se/)s servrar. I databasen lagras användarinformation, produkter, beställningar, kundvagnar och recensioner som används på hemsidan.

## Grundläggande funktioner
I nedanstående lista finns de funktioner som är eller var tänkta att bli implementerade, ej färdiga funktioner är skrivna som kursiva.
- Huvudsida
  - Visa/bläddra bland produkter
  - Kommentera och betygsätta produkter
  - Skapa/redigera kundvagn
- Admin Sida
  - Lägga till/ta bort produkt
  - Visa och hantera support tickets
  - *Se info om användare och ordrar*
- Användarkonto
  - Önskelista
  - Byt lösenord/email
  - *Ändra leveransinformation*
- Support
  - Skicka in/få svar på support tickets

## Bibliotek som används
- [Mustache](https://github.com/bobthecow/mustache.php), fördefinierade logikfria mallar för PHP
- [Bootstrap](https://github.com/twbs/bootstrap), CSS framework för att designa front-end snabbare
- [Material.io](https://github.com/google/material-design-icons), ikoner...
- [jQuery](https://github.com/jquery/jquery), javascript bibliotek som fungerar bra med Bootstrap

## Komma igång
### Krav på mjukvara
1. [PHP](https://www.php.net/) (version 7.4 eller högre)
1. [MySQL server](https://www.mysql.com/).

### Installation
1. Skapa databasen genom att köra filen `/sql/generateDB.sql`.
1. Konfiguera anslutning till MySQL servern genom att ange inloggningsuppgifter i filen `/php/sql/db.conn.php`.
