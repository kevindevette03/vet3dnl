<?php
/**
 * Script om Formidable Forms "Offerte" formulier aan te maken
 * Voer uit via: wp eval-file create-offerte-form.php
 * Of upload en bezoek via browser (eenmalig)
 */

// WordPress laden als het nog niet geladen is
if (!defined('ABSPATH')) {
    require_once('/Users/kevin/Development/vet3dnl/app/public/wp-load.php');
}

// Check of Formidable geïnstalleerd is
if (!class_exists('FrmForm')) {
    die('Formidable Forms is niet geïnstalleerd of geactiveerd.');
}

// Check of formulier al bestaat
$existing = FrmForm::getAll(['form_key' => 'offerte'], 'id');
if (!empty($existing)) {
    die('Formulier "offerte" bestaat al met ID: ' . $existing[0]);
}

// Maak het formulier aan
$form_data = array(
    'name' => 'Offerte aanvraag',
    'form_key' => 'offerte',
    'description' => '3D-print offerte formulier met bestand upload',
    'status' => 'published',
    'options' => array(
        'submit_value' => 'Verstuur aanvraag',
        'success_msg' => 'Bedankt! Je aanvraag is ontvangen. We nemen binnen 1 werkdag contact met je op.',
        'before_html' => '',
        'after_html' => '',
    )
);

$form_id = FrmForm::create($form_data);

if (!$form_id) {
    die('Fout bij aanmaken formulier.');
}

echo "Formulier aangemaakt met ID: {$form_id}\n\n";

// Nu de velden aanmaken
$fields = array(
    array(
        'field_key' => 'naam',
        'name' => 'Naam',
        'type' => 'text',
        'required' => '1',
        'field_order' => 1,
    ),
    array(
        'field_key' => 'email',
        'name' => 'E-mailadres',
        'type' => 'email',
        'required' => '1',
        'field_order' => 2,
    ),
    array(
        'field_key' => 'telefoon',
        'name' => 'Telefoon (optioneel)',
        'type' => 'phone',
        'required' => '0',
        'field_order' => 3,
    ),
    array(
        'field_key' => 'aantal',
        'name' => 'Aantal',
        'type' => 'number',
        'required' => '1',
        'default_value' => '1',
        'field_order' => 4,
        'field_options' => array(
            'minnum' => '1',
        ),
    ),
    array(
        'field_key' => 'type_aanvraag',
        'name' => 'Type aanvraag',
        'type' => 'select',
        'required' => '1',
        'field_order' => 5,
        'options' => "Ik heb een 3D-bestand (STL, 3MF, OBJ)\nIk heb een foto van wat ik wil printen\nIk heb een idee/schets\nIk wil een bestaand product aanpassen",
    ),
    array(
        'field_key' => 'bestand',
        'name' => 'Upload bestand(en)',
        'type' => 'file',
        'required' => '0',
        'field_order' => 6,
        'description' => '3D-bestanden (.stl, .3mf, .obj), foto\'s (.jpg, .png), of schetsen (.pdf). Meerdere bestanden mogelijk.',
        'field_options' => array(
            'ftypes' => 'stl,obj,3mf,jpg,jpeg,png,pdf,heic',
            'multiple' => '1',
        ),
    ),
    array(
        'field_key' => 'materiaal',
        'name' => 'Materiaal & kleur (indien bekend)',
        'type' => 'select',
        'required' => '0',
        'field_order' => 7,
        'options' => "Weet ik nog niet / advies gewenst\nPLA – standaard (meest gebruikt)\nPETG – sterk en flexibel\nABS – hittebestendig\nResin – hoog detail en glad",
    ),
    array(
        'field_key' => 'bericht',
        'name' => 'Omschrijving & wensen',
        'type' => 'textarea',
        'required' => '1',
        'field_order' => 8,
        'description' => '',
        'placeholder' => 'Bijv: Gewenste afmetingen, kleur, aantal, deadline, specifieke wensen of vragen...',
        'field_options' => array(
            'size' => '5',
        ),
    ),
);

foreach ($fields as $field_data) {
    $field_data['form_id'] = $form_id;
    $field_id = FrmField::create($field_data);
    if ($field_id) {
        echo "Veld aangemaakt: {$field_data['name']} (ID: {$field_id})\n";
    } else {
        echo "FOUT bij aanmaken veld: {$field_data['name']}\n";
    }
}

echo "\n✅ Formulier 'Offerte aanvraag' succesvol aangemaakt!\n";
echo "Je kunt het nu gebruiken met shortcode: [formidable id=\"offerte\"]\n\n";
echo "Vergeet niet om e-mail notificaties in te stellen in Formidable > Forms > Offerte aanvraag > Settings > Actions & Notifications\n";
