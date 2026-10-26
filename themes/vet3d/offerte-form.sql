-- SQL script om Formidable Forms "Offerte" formulier aan te maken
-- Importeer via phpMyAdmin of voer uit met MySQL command line

-- Eerst het formulier zelf (ID 2)
INSERT INTO `wp_frm_forms` (`id`, `form_key`, `name`, `description`, `parent_form_id`, `logged_in`, `editable`, `is_template`, `default_template`, `status`, `options`, `created_at`)
VALUES (2, 'offerte', 'Offerte aanvraag', '3D-print offerte formulier met bestand upload', 0, 0, 0, 0, 0, 'published', 
'a:5:{s:12:"submit_value";s:17:"Verstuur aanvraag";s:11:"success_msg";s:88:"Bedankt! Je aanvraag is ontvangen. We nemen binnen 1 werkdag contact met je op.";s:10:"edit_value";s:6:"Update";s:8:"edit_msg";s:43:"Your submission was successfully updated.";s:11:"before_html";s:0:"";s:10:"after_html";s:0:"";}', 
NOW())
ON DUPLICATE KEY UPDATE 
  `form_key` = 'offerte',
  `name` = 'Offerte aanvraag',
  `description` = '3D-print offerte formulier met bestand upload';

-- Nu de velden toevoegen
-- Veld 1: Naam
INSERT INTO `wp_frm_fields` (`form_id`, `field_key`, `name`, `description`, `type`, `default_value`, `options`, `field_options`, `required`, `field_order`, `created_at`)
VALUES (2, 'naam', 'Naam', '', 'text', '', '', 
'a:2:{s:4:"size";s:5:"large";s:8:"required";s:1:"1";}', 
'1', 1, NOW());

-- Veld 2: E-mailadres
INSERT INTO `wp_frm_fields` (`form_id`, `field_key`, `name`, `description`, `type`, `default_value`, `options`, `field_options`, `required`, `field_order`, `created_at`)
VALUES (2, 'email', 'E-mailadres', '', 'email', '', '', 
'a:2:{s:4:"size";s:5:"large";s:8:"required";s:1:"1";}', 
'1', 2, NOW());

-- Veld 3: Telefoon (optioneel)
INSERT INTO `wp_frm_fields` (`form_id`, `field_key`, `name`, `description`, `type`, `default_value`, `options`, `field_options`, `required`, `field_order`, `created_at`)
VALUES (2, 'telefoon', 'Telefoon (optioneel)', '', 'phone', '', '', 
'a:2:{s:4:"size";s:5:"large";s:8:"required";s:1:"0";}', 
'0', 3, NOW());

-- Veld 4: Aantal
INSERT INTO `wp_frm_fields` (`form_id`, `field_key`, `name`, `description`, `type`, `default_value`, `options`, `field_options`, `required`, `field_order`, `created_at`)
VALUES (2, 'aantal', 'Aantal', '', 'number', '1', '', 
'a:3:{s:4:"size";s:5:"large";s:8:"required";s:1:"1";s:6:"minnum";s:1:"1";}', 
'1', 4, NOW());

-- Veld 5: Type aanvraag
INSERT INTO `wp_frm_fields` (`form_id`, `field_key`, `name`, `description`, `type`, `default_value`, `options`, `field_options`, `required`, `field_order`, `created_at`)
VALUES (2, 'type_aanvraag', 'Type aanvraag', '', 'select', '', 
'Ik heb een 3D-bestand (STL, 3MF, OBJ)\nIk heb een foto van wat ik wil printen\nIk heb een idee/schets\nIk wil een bestaand product aanpassen', 
'a:2:{s:4:"size";s:5:"large";s:8:"required";s:1:"1";}', 
'1', 5, NOW());

-- Veld 6: Upload bestand(en)
INSERT INTO `wp_frm_fields` (`form_id`, `field_key`, `name`, `description`, `type`, `default_value`, `options`, `field_options`, `required`, `field_order`, `created_at`)
VALUES (2, 'bestand', 'Upload bestand(en)', '3D-bestanden (.stl, .3mf, .obj), foto\'s (.jpg, .png), of schetsen (.pdf). Meerdere bestanden mogelijk.', 'file', '', '', 
'a:4:{s:4:"size";s:5:"large";s:8:"required";s:1:"0";s:6:"ftypes";s:33:"stl,obj,3mf,jpg,jpeg,png,pdf,heic";s:8:"multiple";s:1:"1";}', 
'0', 6, NOW());

-- Veld 7: Materiaal & kleur
INSERT INTO `wp_frm_fields` (`form_id`, `field_key`, `name`, `description`, `type`, `default_value`, `options`, `field_options`, `required`, `field_order`, `created_at`)
VALUES (2, 'materiaal', 'Materiaal & kleur (indien bekend)', '', 'select', 'Weet ik nog niet / advies gewenst', 
'Weet ik nog niet / advies gewenst\nPLA – standaard (meest gebruikt)\nPETG – sterk en flexibel\nABS – hittebestendig\nResin – hoog detail en glad', 
'a:2:{s:4:"size";s:5:"large";s:8:"required";s:1:"0";}', 
'0', 7, NOW());

-- Veld 8: Omschrijving & wensen
INSERT INTO `wp_frm_fields` (`form_id`, `field_key`, `name`, `description`, `type`, `default_value`, `options`, `field_options`, `required`, `field_order`, `created_at`)
VALUES (2, 'bericht', 'Omschrijving & wensen', '', 'textarea', '', '', 
'a:3:{s:4:"size";s:1:"5";s:8:"required";s:1:"1";s:11:"placeholder";s:91:"Bijv: Gewenste afmetingen, kleur, aantal, deadline, specifieke wensen of vragen...";}', 
'1', 8, NOW());
