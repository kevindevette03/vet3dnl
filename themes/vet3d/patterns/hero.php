<?php
/**
 * Title: Hero
 * Slug: vet3d/hero
 * Categories: vet3d
 * Inserter: true
 */

$pill = get_option( 'vet3d_hero_pill', '3D-printen op maat' );
$title = get_option( 'vet3d_hero_title', 'Vette prints, precies zoals jij ze wilt.' );
$subtitle = get_option( 'vet3d_hero_subtitle', 'Kant-en-klare ontwerpen of jouw eigen STL-bestand. Wij printen het scherp, snel en in elke kleur.' );
$btn1_text = get_option( 'vet3d_hero_btn1_text', 'Bekijk het lookbook' );
$btn1_url = get_option( 'vet3d_hero_btn1_url', '#lookbook' );
$btn2_text = get_option( 'vet3d_hero_btn2_text', 'Vraag jouw print aan' );
$btn2_url = get_option( 'vet3d_hero_btn2_url', '/offerte/' );
$usps = get_option( 'vet3d_usps', array( 'Snelle levering', 'Volledig op maat', 'Upload je ontwerp', 'Veilig betalen' ) );

// Het deel van de titel na de eerste komma krijgt de accentkleur
$title_parts = explode( ', ', $title, 2 );

// Het logo dat de printer laag voor laag opbouwt
$logo = preg_replace( '#</?svg[^>]*>#', '', file_get_contents( get_template_directory() . '/assets/vet3d-logo-dark.svg' ) );
?>
<!-- wp:html -->
<section class="vet-hero">
<svg class="vet-hero__shapes" viewBox="0 0 640 760" preserveAspectRatio="xMaxYMid slice" aria-hidden="true">
<rect x="330" y="30" width="110" height="52" fill="#1FBFC4"/>
<circle cx="520" cy="62" r="24" fill="#FF6B35"/>
<rect x="560" y="120" width="80" height="150" fill="#FF8C42"/>
<rect x="420" y="190" width="90" height="46" fill="#1B1B22"/>
<circle cx="600" cy="320" r="26" fill="#1FBFC4"/>
<rect x="470" y="300" width="58" height="120" fill="#FDE3D6"/>
<path d="M560 420h80v80a80 80 0 0 1-80-80z" fill="#D44A1A"/>
<rect x="450" y="520" width="70" height="44" fill="#FF6B35"/>
<circle cx="600" cy="570" r="30" fill="#1B1B22"/>
<rect x="540" y="630" width="100" height="56" fill="#1FBFC4"/>
<rect x="300" y="640" width="150" height="50" fill="#FF8C42"/>
<circle cx="490" cy="664" r="24" fill="#FDE3D6"/>
<rect x="250" y="710" width="90" height="50" fill="#1B1B22"/>
</svg>
<div class="vet-hero__inner">
<div class="vet-hero__text">
<p class="vet-hero__eyebrow"><?php echo esc_html( $pill ); ?></p>
<h1 class="vet-hero__title"><?php echo esc_html( $title_parts[0] ); ?><?php if ( isset( $title_parts[1] ) ) : ?>, <span><?php echo esc_html( $title_parts[1] ); ?></span><?php endif; ?></h1>
<p class="vet-hero__sub"><?php echo esc_html( $subtitle ); ?></p>
<div class="vet-hero__btns">
<a class="vet-hero__btn is-primary" href="<?php echo esc_url( $btn2_url ); ?>"><?php echo esc_html( $btn2_text ); ?></a>
<a class="vet-hero__btn is-outline" href="<?php echo esc_url( $btn1_url ); ?>"><?php echo esc_html( $btn1_text ); ?></a>
</div>
<ul class="vet-hero__usps">
<?php foreach ( $usps as $usp ) : ?>
<li><?php echo esc_html( $usp ); ?></li>
<?php endforeach; ?>
</ul>
</div>
<div class="vet-hero__device" aria-hidden="true">
<div class="vet-hero__screen">
<p class="vet-hero__status">Op het printbed</p>
<p class="vet-hero__job">Vette print</p>
<svg class="vet-printer" viewBox="0 24 240 126">
<defs>
<clipPath id="vet-print-clip"><rect class="vet-printer__reveal" x="0" y="78" width="240" height="48"/></clipPath>
<pattern id="vet-print-lines" width="240" height="3" patternUnits="userSpaceOnUse" y="78"><rect width="240" height=".7" fill="#26262F"/></pattern>
</defs>
<path d="M22 144V34h196v110" fill="none" stroke="#3A3A45" stroke-width="6" stroke-linejoin="round"/>
<g clip-path="url(#vet-print-clip)">
<g transform="translate(24.5 73.28) scale(.17835)"><?php echo $logo; ?></g>
<rect x="28" y="78" width="184" height="48" fill="url(#vet-print-lines)"/>
</g>
<rect x="30" y="126" width="180" height="10" rx="3" fill="#1FBFC4"/>
<g transform="translate(0 -53)">
<g class="vet-printer__head">
<rect x="22" y="160" width="196" height="5" fill="#5F5A6A"/>
<g class="vet-printer__nozzle">
<rect x="106" y="150" width="28" height="22" rx="4" fill="#F4EFE8"/>
<path d="M114 172h12l-6 7z" fill="#FF6B35"/>
</g>
</g>
</g>
</svg>
<div class="vet-hero__progress"><span></span></div>
</div>
</div>
</div>
</section>
<!-- /wp:html -->
