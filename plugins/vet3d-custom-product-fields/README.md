# VET3D Custom Product Fields

Een WordPress/WooCommerce plugin waarmee je custom velden kunt toevoegen aan productpagina's voor gepersonaliseerde bestellingen.

## Features

- ✅ Voeg onbeperkt custom velden toe per product
- ✅ Verschillende veldtypes: text, textarea, nummer, datum, email
- ✅ Verplichte velden instellen
- ✅ Placeholders en max lengte configureren
- ✅ Velden worden getoond op productpagina
- ✅ Data wordt opgeslagen in winkelwagen
- ✅ Data wordt meegenomen in bestellingen
- ✅ Zichtbaar in admin orderoverzicht

## Installatie

1. Upload de plugin map naar `/wp-content/plugins/`
2. Activeer de plugin via WordPress admin
3. Ga naar een product en scroll naar "Custom Personalisatie Velden"

## Gebruik

### Product instellen:

1. Ga naar een product in WooCommerce
2. Scroll naar de "Custom Personalisatie Velden" metabox
3. Vink "Activeer custom velden voor dit product" aan
4. Klik op "+ Veld Toevoegen" om velden toe te voegen
5. Configureer elk veld:
   - **Veldnaam**: Label dat de klant ziet (bijv. "Naam", "Datum", "Tekst")
   - **Type**: Kies uit text, textarea, nummer, datum of email
   - **Verplicht**: Maak het veld verplicht of optioneel
   - **Placeholder**: Voorbeeldtekst in het veld
   - **Max lengte**: Maximaal aantal karakters

### Voorbeeld configuratie voor 3D print:

- Veld 1: "Jouw naam" (text, verplicht, max 50 karakters)
- Veld 2: "Speciale datum" (date, optioneel)
- Veld 3: "Extra wensen" (textarea, optioneel, max 200 karakters)

## Support

Voor vragen of problemen, neem contact op via https://vet3d.nl

## Changelog

### 1.0.0
- Eerste release
- Custom velden per product
- Validatie en opslag in orders
