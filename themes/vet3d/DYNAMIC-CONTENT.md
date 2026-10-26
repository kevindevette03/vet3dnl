# Vet3D Thema - Dynamische Content Handleiding

Dit thema is dynamisch gemaakt, zodat alle content vanuit WordPress beheerd kan worden zonder code aan te passen.

## 📋 Overzicht

De volgende secties zijn nu volledig dynamisch:

1. **Hero Sectie** - De hoofdbanner op de homepage
2. **USP's** - De unieke verkooppunten onder de header
3. **Lookbook** - Galerij met project voorbeelden (inclusief 3D preview!)
4. **CTA Banner** - Call-to-action banner voor custom opdrachten
5. **3D Model Viewer** - Interactieve 3D preview bij hover (STL-bestanden)
6. **SEO Pagina's** - Landing pages met gekoppelde productcategorieën (nieuw!)

---

## 🎯 Hero Sectie Beheren

**Locatie:** `Weergave → Hero Sectie`

Hier kun je aanpassen:
- **Label (pill)** - Het kleine label bovenaan (bijv. "3D-printen op maat")
- **Hoofdtitel** - De grote kopregel
- **Ondertitel** - De beschrijvende tekst eronder
- **Knop 1** - Tekst en link voor de primaire knop
- **Knop 2** - Tekst en link voor de secundaire knop

---

## ✅ USP's Beheren

**Locatie:** `Weergave → USP's`

Bewerk de 4 USP's die in de horizontale balk getoond worden. Bijvoorbeeld:
- Snelle levering
- Volledig op maat
- Upload je ontwerp
- Veilig betalen

---

## 🖼️ Lookbook Beheren

**Locatie:** `Lookbook → Nieuw toevoegen`

### Lookbook Item Toevoegen

1. Ga naar `Lookbook` in het WordPress menu
2. Klik op `Nieuw toevoegen`
3. Voeg een **titel** toe (dit wordt het label op de tegel)
4. Upload een **uitgelichte afbeelding** (dit wordt de achtergrond van de tegel)
5. **(Nieuw!)** Upload een **3D Model (STL)** voor interactieve preview bij hover
6. Kies in de rechter sidebar:
   - **Tegel formaat:** Normaal, Groot, Hoog of Breed
   - **Gebruik donkere tekst:** Vink aan als de achtergrond licht is

### Tips voor Lookbook
- Als je **geen afbeelding** uploadt, wordt automatisch een gradient gebruikt
- Upload een **STL-bestand** om een 🔮 icoon te tonen en 3D preview te activeren
- De volgorde kun je aanpassen via **menu_order** (installeer plugin "Simple Page Ordering" voor drag & drop)
- Minimaal 4-6 items voor een mooi gevuld lookbook
- De CTA tegels worden automatisch tussen de items geplaatst

---

## 🚀 CTA Banner Beheren

**Locatie:** `Weergave → CTA Banner`

Pas de "Eigen ontwerp in je hoofd?" banner aan:
- **Titel** - Hoofdtekst van de banner
- **Ondertitel** - Beschrijving
- **Knop tekst** - Tekst op de knop
- **Knop URL** - Waar de knop naartoe linkt

---

## 🔮 3D Model Viewer (Nieuw!)

### WooCommerce Producten

**Locatie:** `Producten → [Product bewerken] → Productgegevens`

In de "Productgegevens" sectie vind je nu een nieuw veld:
- **3D Model (STL)** - Upload een STL-bestand
- Klik op "Upload" om een bestand te kiezen uit de mediabibliotheek
- Producten met 3D model krijgen een 🔮 icoon rechtsbovenin
- Bij hover wordt het 3D model interactief getoond

### Lookbook Items

Zie sectie "Lookbook Beheren" hierboven - werkt op dezelfde manier!

### Ondersteunde Bestandsformaten
- `.stl` - STL (STereoLithography) - meest gebruikelijk voor 3D printing
- `.3mf` - 3D Manufacturing Format - modern formaat met kleurenondersteuning
- `.obj` - Wavefront OBJ - universeel 3D formaat

### 3D Viewer Functies
- **Auto-rotate** - Model draait automatisch
- **Zoom** - Scroll om in/uit te zoomen
- **Draaien** - Klik en sleep om te roteren
- **Pan** - Rechtermuisknop + slepen om te verschuiven
- **Oranje kleur** - Automatisch in Vet3D huisstijl

---

## 📄 SEO Pagina's (Nieuw!)

**Locatie:** `SEO Pagina's → Nieuwe toevoegen`

### SEO Landing Pages Maken

Maak krachtige SEO-pagina's met automatisch gekoppelde producten:

1. Ga naar `SEO Pagina's` in het WordPress menu
2. Klik op `Nieuwe toevoegen`
3. Voeg een **titel** toe (bijv. "3D Printen op Maat in Amsterdam")
4. Schrijf je **SEO content** in de editor (intro, voordelen, uitleg)
5. Upload een **uitgelichte afbeelding** (optioneel)
6. Scroll naar beneden naar **SEO Pagina Instellingen**

### SEO Pagina Instellingen

In de meta box "SEO Pagina Instellingen" kun je:

1. **Gekoppelde Product Categorieën** - Vink de categorieën aan die getoond moeten worden
2. **Toon producten op deze pagina** - Schakel productweergave in/uit
3. **Producten sectie titel** - Geef de productsectie een titel (bijv. "Onze 3D Print Producten")
4. **Aantal producten per pagina** - Kies hoeveel producten per pagina (standaard 12)

### URL Structuur

SEO pagina's krijgen automatisch een mooie URL:
- **Slug:** `/info/jouw-pagina-titel`
- Voorbeeld: `/info/3d-printen-amsterdam`

### Voordelen van SEO Pagina's

✅ **Automatische product integratie** - Koppel meerdere categorieën  
✅ **Paginering** - Automatisch bij meer dan 12 producten  
✅ **SEO vriendelijk** - Volledige controle over content  
✅ **Flexibel** - Gebruik de block editor voor custom layouts  
✅ **WooCommerce integratie** - Producten tonen met standaard templates  

### Tips voor SEO Pagina's

- Gebruik de **vet3d/seo-content** pattern voor een kant-en-klare layout
- Combineer meerdere productcategorieën voor bredere dekking
- Voeg rijke content toe voor betere SEO (headings, lijsten, afbeeldingen)
- Gebruik het shortcode `[vet3d_products]` voor meer controle

---

## 🔧 Shortcode: Producten Tonen

Je kunt ook handmatig producten invoegen met de `[vet3d_products]` shortcode:

### Basis gebruik:
```
[vet3d_products category="sleutelhangers" limit="8" columns="4"]
```

### Alle parameters:

- **category** - Categorie slug of ID (verplicht)
  - Enkel: `category="sleutelhangers"`
  - Meerdere: `category="sleutelhangers,taarttoppings"`
  - ID's: `category="12,15"`
  
- **limit** - Aantal producten (standaard: 12)
  
- **columns** - Aantal kolommen (standaard: 4)
  - Opties: 2, 3, 4
  
- **orderby** - Sortering (standaard: date)
  - Opties: date, title, price, popularity, rating
  
- **order** - Volgorde (standaard: DESC)
  - Opties: ASC, DESC
  
- **title** - Sectie titel (optioneel)

### Voorbeelden:

**Nieuwste producten:**
```
[vet3d_products category="nieuw" limit="6" columns="3" title="Nieuw Binnen"]
```

**Beste sellers:**
```
[vet3d_products category="bestsellers" limit="8" orderby="popularity" title="Populaire Producten"]
```

**Goedkoopste eerst:**
```
[vet3d_products category="sleutelhangers" orderby="price" order="ASC"]
```

---

## 🛠️ Technische Details

### Custom Post Type
- **Lookbook** - Voor galerij items (`post_type: lookbook`)
- **SEO Pagina's** - Voor landing pages met product integratie (`post_type: seo_page`)

### Options (WordPress Settings)
- `vet3d_hero_*` - Hero sectie instellingen
- `vet3d_cta_*` - CTA banner instellingen
- `vet3d_usps` - Array met USP's

### Meta Fields
- `_lookbook_size` - Formaat van lookbook tegel
- `_lookbook_dark_text` - Boolean voor tekstkleur
- `_lookbook_stl_file` - URL naar STL-bestand voor lookbook
- `_product_stl_file` - URL naar STL-bestand voor WooCommerce producten
- `_seo_linked_categories` - Array met gekoppelde product categorie IDs
- `_seo_show_products` - Boolean om producten te tonen
- `_seo_products_title` - Titel voor producten sectie
- `_seo_products_per_page` - Aantal producten per pagina

### Patterns
Alle patterns zijn dynamisch:
- `vet3d/hero` - Haalt data uit opties
- `vet3d/usp` - Haalt USP's uit opties
- `vet3d/lookbook` - Query lookbook items uit database + 3D viewer
- `vet3d/custom-cta` - Haalt CTA data uit opties
- `vet3d/seo-content` - Template voor SEO pagina content
- `vet3d/seo-products` - Shortcode block voor producten

### Shortcodes
- `[vet3d_products]` - Toon producten uit specifieke categorieën
  - Parameters: category, limit, columns, orderby, order, title

### JavaScript Libraries
- **Three.js (v0.160.0)** - 3D rendering engine
- **STLLoader** - Voor het laden van STL-bestanden
- **OrbitControls** - Voor camera besturing

---

## 📝 Bestaande Functionaliteit

Het thema bevat ook:
- **Product categorie SEO tekst** - WYSIWYG editor voor tekst onder producten
- **WooCommerce support** - Volledige integratie
- **Sticky cart** - Op productpagina's
- **Review tab verwijderd** - Van productpagina's
- **3D Model Viewer** - Interactieve STL preview op hover

---

## 🔄 Front-Page Template

De homepage (`templates/front-page.html`) gebruikt deze patterns:
1. Header
2. Hero (dynamisch)
3. USP (dynamisch)
4. Lookbook (dynamisch + 3D viewer)
5. CTA Banner (dynamisch)
6. Footer

Alle content is nu vanuit WordPress te beheren!

---

## 💡 Tips voor STL-bestanden

### Bestandsgrootte
- Houd STL-bestanden **onder de 5MB** voor snelle laadtijden
- Gebruik tools zoals Meshmixer of Blender om modellen te optimaliseren
- Reduceer het aantal polygonen als het model te groot is

### Upload Locatie
- Upload via **Media → Nieuwe toevoegen**
- STL-bestanden worden automatisch herkend door WordPress
- Bewaar alle STL's in de mediabibliotheek voor gemakkelijk hergebruik

### Optimale Weergave
- Modellen worden automatisch gecentreerd en geschaald
- De oranje Vet3D kleur (#FF8C42) wordt automatisch toegepast
- Auto-rotate zorgt voor een mooie presentatie

### Browser Compatibiliteit
- Werkt in alle moderne browsers (Chrome, Firefox, Safari, Edge)
- Gebruikt WebGL voor hardware-versnelde rendering
- Mobiele apparaten ondersteunen touch controls

