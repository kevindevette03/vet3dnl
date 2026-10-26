# vet3d.nl – thema en eigen plugins

Deze repo staat in `wp-content/` van de LocalWP-site en bevat alleen eigen code:

- `themes/vet3d` – het blokthema
- `plugins/vet3d-custom-product-fields`, `plugins/vet3d-print-calculator`

WordPress-core, plugins van derden, uploads en de database staan niet in git (zie `.gitignore`).

## Deploy

Elke push naar `main` deployt via FTPS naar SiteGround (`.github/workflows/deploy.yml`).
Handmatig starten kan via *Actions → Deploy naar SiteGround → Run workflow*; standaard is dat een dry-run.

Verhoog bij elke themawijziging `Version` in `themes/vet3d/style.css`. WordPress cachet de lijst met
patterns tot de versie verandert, en de versie zorgt ook dat browsers nieuwe CSS/JS ophalen.

## Scroll-video vervangen

```bash
./bin/encode-video.sh ~/Downloads/nieuwe-video.MOV
```

Vereist macOS en `ffmpeg` (`brew install ffmpeg`). Het script overschrijft
`themes/vet3d/assets/video/scroll-video.mp4` en de poster.
