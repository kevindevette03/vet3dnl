<?php
/**
 * Template Name: Offerte Pagina
 * Description: Pagina met werkend offerte formulier
 */

get_header();

// Formulier verwerking
$form_submitted = false;
$form_errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['offerte_submit'])) {
    // Validatie
    if (empty($_POST['naam'])) {
        $form_errors[] = 'Naam is verplicht';
    }
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $form_errors[] = 'Geldig e-mailadres is verplicht';
    }
    if (empty($_POST['bericht'])) {
        $form_errors[] = 'Omschrijving is verplicht';
    }

    // Als geen errors, verstuur email
    if (empty($form_errors)) {
        $to = get_option('admin_email'); // Of een specifiek e-mailadres
        $subject = 'Nieuwe offerte aanvraag van ' . sanitize_text_field($_POST['naam']);
        
        $message = "Nieuwe offerte aanvraag:\n\n";
        $message .= "Naam: " . sanitize_text_field($_POST['naam']) . "\n";
        $message .= "E-mail: " . sanitize_email($_POST['email']) . "\n";
        $message .= "Telefoon: " . sanitize_text_field($_POST['telefoon']) . "\n";
        $message .= "Aantal: " . intval($_POST['aantal']) . "\n";
        $message .= "Type aanvraag: " . sanitize_text_field($_POST['type_aanvraag']) . "\n";
        $message .= "Materiaal: " . sanitize_text_field($_POST['materiaal']) . "\n\n";
        $message .= "Omschrijving:\n" . sanitize_textarea_field($_POST['bericht']) . "\n";
        
        $headers = array(
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
            'Reply-To: ' . sanitize_email($_POST['email'])
        );
        
        // Bestanden uploaden
        $uploaded_files = array();
        if (!empty($_FILES['bestand']['name'][0])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            
            foreach ($_FILES['bestand']['name'] as $key => $value) {
                if ($_FILES['bestand']['name'][$key]) {
                    $file = array(
                        'name' => $_FILES['bestand']['name'][$key],
                        'type' => $_FILES['bestand']['type'][$key],
                        'tmp_name' => $_FILES['bestand']['tmp_name'][$key],
                        'error' => $_FILES['bestand']['error'][$key],
                        'size' => $_FILES['bestand']['size'][$key]
                    );
                    
                    $upload = wp_handle_upload($file, array('test_form' => false));
                    if (isset($upload['file'])) {
                        $uploaded_files[] = $upload['file'];
                    }
                }
            }
        }
        
        wp_mail($to, $subject, $message, $headers, $uploaded_files);
        $form_submitted = true;
    }
}
?>

<main id="main" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
                
                <div class="vet-offerte">
                    <?php if ($form_submitted): ?>
                        <div style="background:#E7F5E7;border:1px solid #5C5;padding:20px;border-radius:8px;margin-bottom:20px;">
                            <strong>✅ Bedankt!</strong> Je aanvraag is ontvangen. We nemen binnen 1 werkdag contact met je op.
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($form_errors)): ?>
                        <div style="background:#FEE;border:1px solid #C33;padding:20px;border-radius:8px;margin-bottom:20px;">
                            <strong>⚠️ Fouten:</strong>
                            <ul style="margin:10px 0 0 20px;">
                                <?php foreach ($form_errors as $error): ?>
                                    <li><?php echo esc_html($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div style="background:#FFF3CD;border:1px solid #FFC107;padding:14px;border-radius:8px;margin-bottom:16px;">
                        <strong>💡 Tips voor een snelle offerte:</strong>
                        <ul style="margin:8px 0 0 20px;font-size:14px;line-height:1.6;">
                            <li>Vermeld gewenste afmetingen (lengte x breedte x hoogte)</li>
                            <li>Bij foto's: meerdere hoeken helpen ons beter</li>
                            <li>Geef aan of haast hebt of wanneer je het nodig hebt</li>
                            <li>Twijfel over materiaal? Beschrijf waarvoor je het gebruikt</li>
                        </ul>
                    </div>
                    
                    <form method="post" enctype="multipart/form-data">
                        <div class="grid2">
                            <label>
                                Naam *
                                <input type="text" name="naam" value="<?php echo isset($_POST['naam']) ? esc_attr($_POST['naam']) : ''; ?>" required>
                            </label>
                            <label>
                                E-mailadres *
                                <input type="email" name="email" value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>" required>
                            </label>
                            <label>
                                Telefoon (optioneel)
                                <input type="tel" name="telefoon" value="<?php echo isset($_POST['telefoon']) ? esc_attr($_POST['telefoon']) : ''; ?>">
                            </label>
                            <label>
                                Aantal *
                                <input type="number" name="aantal" min="1" value="<?php echo isset($_POST['aantal']) ? esc_attr($_POST['aantal']) : '1'; ?>" required>
                            </label>
                        </div>
                        
                        <label>
                            Type aanvraag *
                            <select name="type_aanvraag" required>
                                <option value="3d-bestand" <?php selected(isset($_POST['type_aanvraag']) ? $_POST['type_aanvraag'] : '', '3d-bestand'); ?>>Ik heb een 3D-bestand (STL, 3MF, OBJ)</option>
                                <option value="foto" <?php selected(isset($_POST['type_aanvraag']) ? $_POST['type_aanvraag'] : '', 'foto'); ?>>Ik heb een foto van wat ik wil printen</option>
                                <option value="idee" <?php selected(isset($_POST['type_aanvraag']) ? $_POST['type_aanvraag'] : '', 'idee'); ?>>Ik heb een idee/schets</option>
                                <option value="bestaand" <?php selected(isset($_POST['type_aanvraag']) ? $_POST['type_aanvraag'] : '', 'bestaand'); ?>>Ik wil een bestaand product aanpassen</option>
                            </select>
                        </label>
                        
                        <label>
                            Upload bestand(en)
                            <input type="file" name="bestand[]" accept=".stl,.obj,.3mf,.jpg,.jpeg,.png,.pdf,.heic" multiple>
                            <small style="display:block;margin-top:6px;color:#8A8378;">3D-bestanden (.stl, .3mf, .obj), foto's (.jpg, .png), of schetsen (.pdf). Meerdere bestanden mogelijk.</small>
                        </label>
                        
                        <label>
                            Materiaal & kleur (indien bekend)
                            <select name="materiaal">
                                <option <?php selected(isset($_POST['materiaal']) ? $_POST['materiaal'] : '', ''); ?>>Weet ik nog niet / advies gewenst</option>
                                <option <?php selected(isset($_POST['materiaal']) ? $_POST['materiaal'] : '', 'PLA'); ?>>PLA – standaard (meest gebruikt)</option>
                                <option <?php selected(isset($_POST['materiaal']) ? $_POST['materiaal'] : '', 'PETG'); ?>>PETG – sterk en flexibel</option>
                                <option <?php selected(isset($_POST['materiaal']) ? $_POST['materiaal'] : '', 'ABS'); ?>>ABS – hittebestendig</option>
                                <option <?php selected(isset($_POST['materiaal']) ? $_POST['materiaal'] : '', 'Resin'); ?>>Resin – hoog detail en glad</option>
                            </select>
                        </label>
                        
                        <label>
                            Omschrijving & wensen *
                            <textarea name="bericht" rows="5" placeholder="Bijv: Gewenste afmetingen, kleur, aantal, deadline, specifieke wensen of vragen..." required><?php echo isset($_POST['bericht']) ? esc_textarea($_POST['bericht']) : ''; ?></textarea>
                        </label>
                        
                        <button type="submit" name="offerte_submit" class="vet-submit">Verstuur aanvraag</button>
                        <p class="vet-note">Je ontvangt binnen 1 werkdag een reactie met prijs, materiaaladvies en levertijd. Wij nemen contact op via e-mail.</p>
                    </form>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
