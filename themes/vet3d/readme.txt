=== Vet!3D ===
Block-thema voor de Vet!3D 3D-print webshop.

== Installeren (LocalWP) ==
1. Maak in LocalWP een nieuwe site aan en open de WP-admin.
2. Ga naar Weergave > Thema's > Nieuw toevoegen > Thema uploaden.
3. Upload vet3d-theme.zip en activeer het thema.

== Homepage instellen ==
1. Maak een pagina "Home" (leeg) en een pagina "Blog".
2. Instellingen > Lezen > "Een statische pagina" > Voorpagina: Home, Berichtenpagina: Blog.
   Het thema gebruikt dan automatisch het Homepage-template (front-page).
3. Instellingen > Permalinks > kies "Berichtnaam" en sla op.

== WooCommerce ==
1. Plugins > Nieuwe plugin > installeer en activeer WooCommerce; doorloop de wizard.
2. Voeg producten toe (Producten > Nieuw).
3. Open de homepage in de Site-editor (Weergave > Editor), selecteer het blok
   "Populaire producten" en vervang de placeholder door een "Productcollectie"-blok.
4. WooCommerce levert zelf de templates voor shop, product, winkelwagen en kassa.

== Logo ==
Het logo zit al ingebouwd in de header en footer (assets/vet3d-logo-dark.svg).
Losse bestanden staan in /assets. Wil je het via de native Site-logo-knop beheren,
installeer dan de plugin "Safe SVG" (WordPress blokkeert SVG-upload standaard) of
gebruik de meegeleverde PNG.

== Huisstijl ==
Kleuren, lettertype (Outfit) en knopstijlen staan in theme.json en zijn aan te
passen via Weergave > Editor > Stijlen.

== Productpagina ==
Het thema bevat een opgemaakte single-product template (templates/single-product.html)
met galerij, titel, prijs, in-winkelwagen-knop en gerelateerde producten. Deze wordt
automatisch gebruikt zodra WooCommerce actief is en je een product opent.

== Offerte / upload-pagina ==
1. Maak een nieuwe pagina met de titel "Offerte" (slug: offerte).
   Het template page-offerte wordt dan automatisch gebruikt; je kunt het ook handmatig
   kiezen onder Pagina > Sjabloon > "Offerte / Upload".
2. Het formulier is opmaak. Koppel het aan een formulier-plugin met bestandsupload
   (WPForms / Contact Form 7) of een WooCommerce-product met upload-addon.

== Winkelwagen (Mini-Cart) ==
De header bevat een WooCommerce Mini-Cart-knop. Die verschijnt zodra WooCommerce
actief is; zonder WooCommerce wordt de knop simpelweg niet getoond.

== Dynamische producten op de homepage (v1.2) ==
Het "Populair"-blok op de homepage is nu een WooCommerce "Productcollectie"-blok
(nieuwste producten, 4 in een rij). Zodra WooCommerce actief is en je producten hebt,
laden die hier automatisch met afbeelding, titel, prijs en in-winkelwagen-knop.
Wil je een andere selectie (bestsellers, aanbieding, categorie)? Open de homepage in
de Site-editor, selecteer het blok en pas onder "Instellingen" de collectie/filters aan.

== Menu ==
De header gebruikt een Navigatie-blok met handmatige links (bewerkbaar via de Site-editor).
Het vult zich NIET automatisch met productcategorieen. Voeg daar zelf de Shop-pagina en
gewenste categorieen aan toe (of plaats een "Productcategorieen"-blok). De knop
"Bekijk de hele shop" en het beoogde Shop-menu-item wijzen naar /shop/ (de standaard
WooCommerce-winkelpagina).
