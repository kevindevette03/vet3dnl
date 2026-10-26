<?php
/**
 * Title: Offerte formulier (handmatig)
 * Slug: vet3d/offerte-form-manual
 * Categories: vet3d
 * Inserter: true
 */
?>
<!-- wp:group {"className":"vet-offerte","layout":{"type":"constrained"}} -->
<div class="wp-block-group vet-offerte">
  <!-- wp:html -->
  <div style="background:#FFF3CD;border:1px solid #FFC107;padding:14px;border-radius:8px;margin-bottom:16px;">
    <strong>💡 Tips voor een snelle offerte:</strong>
    <ul style="margin:8px 0 0 20px;font-size:14px;line-height:1.6;">
      <li>Vermeld gewenste afmetingen (lengte x breedte x hoogte)</li>
      <li>Bij foto's: meerdere hoeken helpen ons beter</li>
      <li>Geef aan of haast hebt of wanneer je het nodig hebt</li>
      <li>Twijfel over materiaal? Beschrijf waarvoor je het gebruikt</li>
    </ul>
  </div>
  
  <?php
  // Probeer het Formidable formulier te laden
  if (function_exists('FrmFormsController')) {
      // Zoek het formulier op key
      $form = FrmForm::getOne('offerte');
      if ($form) {
          echo FrmFormsController::get_form_shortcode(array('id' => $form->id));
      } else {
          // Als form key niet werkt, probeer op naam
          $forms = FrmForm::getAll(array(), 'name');
          foreach ($forms as $f) {
              if (stripos($f->name, 'offerte') !== false) {
                  echo FrmFormsController::get_form_shortcode(array('id' => $f->id));
                  break;
              }
          }
      }
  } else {
      echo '<p><strong>Formidable Forms is niet actief.</strong> Activeer de plugin om het formulier te zien.</p>';
  }
  ?>
  
  <p class="vet-note">Je ontvangt binnen 1 werkdag een reactie met prijs, materiaaladvies en levertijd. Wij nemen contact op via e-mail.</p>
  <!-- /wp:html -->
</div>
<!-- /wp:group -->
