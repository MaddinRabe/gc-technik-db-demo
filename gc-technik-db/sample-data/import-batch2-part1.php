<?php
/**
 * Batch 2 Part 1: Aus-/Einbau Anleitungen (17 Artikel)
 * Aufruf: /wp-admin/?gc_import_batch2a=1
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class GC_Batch2a_Import {
    public static function run() {
        if ( get_option( 'gc_batch2a_imported' ) ) { return 'Batch 2a bereits importiert.'; }
        $count = 0;
        foreach ( self::get_articles() as $a ) {
            $pid = wp_insert_post(['post_type'=>'gc_article','post_title'=>$a['title'],'post_content'=>$a['content'],'post_excerpt'=>$a['excerpt'],'post_status'=>'publish']);
            if ( is_wp_error($pid) ) continue;
            if (!empty($a['meta'])) foreach($a['meta'] as $k=>$v) update_post_meta($pid,$k,$v);
            if (!empty($a['categories'])) wp_set_object_terms($pid,$a['categories'],'gc_category');
            if (!empty($a['models'])) wp_set_object_terms($pid,$a['models'],'gc_model');
            if (!empty($a['years'])) wp_set_object_terms($pid,$a['years'],'gc_model_year');
            if (!empty($a['components'])) wp_set_object_terms($pid,$a['components'],'gc_component');
            $count++;
        }
        update_option('gc_batch2a_imported',true);
        return sprintf('%d Aus-/Einbau Artikel (Batch 2a) importiert.',$count);
    }

    private static function get_articles() {
        $gc = ['Grand California 600','Grand California 680'];
        $all_years = ['2019','2020','2021','2022','2023','2024','2025 (Facelift)'];
        return [
            self::ausbau('Abwasserbehälter aus- und einbauen','Anleitung zum Aus- und Einbau des Abwasserbehälters. Benötigt einen Getriebeheber zum Absenken des Tanks.',
                'Abwasserbehälter','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmomentschlüssel 6–50 Nm (V.A.G 1331)</li><li>Motor-/Getriebeheber (VAS 6931)</li></ul>
<h2>Ausbau</h2><ol><li>Abwassertank vollständig entleeren</li><li>Elektrischen Stecker abziehen</li><li>Kabelbaum aus den Halterungen lösen</li><li>Schläuche abziehen</li><li>Getriebeheber VAS 6931 unter dem Tank positionieren und Kontakt herstellen</li><li>Befestigungsschrauben lösen</li><li>Tank mit dem Heber absenken</li><li>Tank vom Heber nehmen</li></ol>
<h2>Einbau</h2><p>Der Einbau erfolgt in umgekehrter Reihenfolge. Anzugsdrehmomente der Befestigungsschrauben beachten.</p>',
                ['gc_location'=>'Fahrzeugunterseite','gc_tools_needed'=>"Drehmomentschlüssel V.A.G 1331\nGetriebeheber VAS 6931"],
                ['Abwasserbehälter','Abwassertank'],$gc,$all_years),

            self::ausbau('Ausströmer (Warmluft) aus- und einbauen','Ausbau des Warmluft-Ausströmers im Wohnbereich. Nur ein Demontagekeil wird benötigt.',
                'Ausströmer','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Demontagekeil (3409)</li></ul>
<h2>Ausbau</h2><ol><li>Warmluftschlauch vom Ausströmer abziehen</li><li>Ausströmer mit dem Demontagekeil 3409 vorsichtig heraushebeln — wenn möglich von der Rückseite drücken</li></ol>
<h2>Einbau</h2><p>Einbau in umgekehrter Reihenfolge. Im Bereich der Nasszelle: Dichtmasse um den Ausströmer auftragen.</p>',
                ['gc_location'=>'Wohnbereich / Nasszelle','gc_tools_needed'=>'Demontagekeil 3409'],
                ['Ausströmer','Warmluft','Heizung'],$gc,$all_years),

            self::ausbau('Bedieneinheit Campingausrüstung (E153) aus- und einbauen','Aus- und Einbau der zentralen Bedieneinheit E153 für die Campingausstattung. Schrauben mit nur 1 Nm anziehen.',
                'Bedieneinheit E153','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Demontagekeil (3409)</li></ul>
<h2>Ausbau</h2><ol><li>Zündung ausschalten</li><li>Blenden mit dem Demontagekeil 3409 lösen</li><li>Befestigungsschrauben lösen und Bedieneinheit E153 herausnehmen</li><li>Elektrische Stecker abziehen</li></ol>
<h2>Anzugsdrehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Befestigungsschrauben</td><td><strong>1 Nm</strong></td></tr></tbody></table>',
                ['gc_vw_code'=>'E153','gc_location'=>'Mittelbereich links','gc_tools_needed'=>'Demontagekeil 3409','gc_torque_specs'=>'Schrauben: 1 Nm'],
                ['E153 Bedieneinheit','Campingsteuerung'],$gc,$all_years),

            self::ausbau('Befestigungshalter der Markise aus- und einbauen','Aus- und Einbau der Markisen-Befestigungshalter. Klebstoff muss 24 Stunden aushärten bevor die Markise montiert wird.',
                'Befestigungshalter Markise','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Stufen-Stehleiter (VAS 6292/4)</li><li>Drehmomentschlüssel (V.A.G 1783)</li><li>Handkartuschenpistole (V.A.G 1628)</li><li>Elektro-Messer (V.A.G 1561A)</li></ul>
<h2>Ausbau</h2><ol><li>Markise nach separater Anleitung ausbauen</li><li>Einbaumaße der Halter notieren</li><li>Schrauben lösen</li><li>Halter mit dem Elektro-Messer von der Kleberaupe trennen</li><li>Klebstoffreste vollständig entfernen</li></ol>
<h2>Einbau</h2><ol><li>Lackierung prüfen und ggf. ausbessern</li><li>Klebeflächen mit Reinigungslösung säubern (Ablüftzeit beachten)</li><li>Primer auftragen (Ablüftzeit beachten)</li><li>1K-Scheibenklebstoff mit Kartuschenpistole auftragen (Dreiecksraupe, 5 mm Höhe, 6 mm Breite)</li><li>Halter positionieren und verschrauben</li><li>Überschüssigen Klebstoff entfernen</li></ol>
<h3>Wichtig</h3><p><strong>24 Stunden warten</strong> bevor die Markise wieder montiert wird!</p>
<h2>Anzugsdrehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Halter-Schrauben</td><td><strong>1,5 Nm</strong></td></tr></tbody></table>',
                ['gc_location'=>'Dach, Außen','gc_tools_needed'=>"Stehleiter VAS 6292/4\nDrehmomentschlüssel V.A.G 1783\nKartuschenpistole V.A.G 1628\nElektro-Messer V.A.G 1561A",'gc_torque_specs'=>'Halter-Schrauben: 1,5 Nm'],
                ['Markise','Befestigungshalter','Klebstoff'],$gc,$all_years),

            self::ausbau('Dachhaube aus- und einbauen','Aus- und Einbau der Dachhaube (Dachluke). Der Rahmen muss mit Heißluft erwärmt werden um ihn zu lösen.',
                'Dachhaube','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmoment-Schraubendreher (V.A.G 1624)</li><li>Demontagekeil (3409)</li><li>Heißluftgebläse (V.A.G 1416)</li><li>Klebestreifenentferner (VAS 6349)</li></ul>
<h2>Ausbau</h2><ol><li>Schutzgitter entfernen (falls vorhanden)</li><li>Plissee (Faltstore) ausbauen</li><li>Befestigungsschrauben lösen</li><li>Montagewinkel von der Dachhaube abnehmen</li><li>Rahmen von außen gleichmäßig mehrere Minuten mit dem Heißluftgebläse V.A.G 1416 erwärmen</li><li>Rahmen abnehmen</li></ol>
<h2>Einbau</h2><ol><li>Klebstoffreste mit Klebestreifenentferner VAS 6349 entfernen</li><li>Klebefläche mit Reinigungslösung säubern</li><li>Butyl-Dichtschnur einlegen (Durchmesser 8 mm, Enden mind. 50 mm überlappen)</li></ol>
<h3>Wichtig</h3><p>Verarbeitungstemperatur für Butylschnur: +18°C bis +35°C</p>',
                ['gc_location'=>'Dach','gc_tools_needed'=>"Schraubendreher V.A.G 1624\nDemontagekeil 3409\nHeißluftgebläse V.A.G 1416\nKlebestreifenentferner VAS 6349"],
                ['Dachhaube','Dachluke','Butylschnur'],$gc,$all_years),

            self::ausbau('Dachhaube Nasszelle aus- und einbauen','Aus- und Einbau der Dachhaube über der Nasszelle.',
                'Dachhaube Nasszelle','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmoment-Schraubendreher (V.A.G 1624)</li><li>Demontagekeil (3409)</li></ul>
<h2>Ausbau</h2><ol><li>Formhimmel der Nasszelle ausbauen (separate Anleitung)</li><li>Befestigungsschrauben lösen</li><li>Dachhaube vom Rahmen abnehmen</li></ol>
<h2>Einbau</h2><ol><li>Klebefläche mit Reinigungslösung säubern</li><li>Butyl-Dichtschnur einlegen (8 mm Durchmesser, Enden mind. 50 mm überlappen)</li></ol>
<p>Verarbeitungstemperatur: +18°C bis +35°C</p>',
                ['gc_location'=>'Dach über Nasszelle','gc_tools_needed'=>"Schraubendreher V.A.G 1624\nDemontagekeil 3409"],
                ['Dachhaube','Nasszelle'],$gc,$all_years),

            self::ausbau('Dachrahmen (Nasszelle) aus- und einbauen','Aus- und Einbau des Dachrahmens in der Nasszelle. Schrauben nur handfest anziehen.',
                'Dachrahmen Nasszelle','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmoment-Schraubendreher (V.A.G 1624)</li></ul>
<h2>Ausbau</h2><ol><li>Formhimmel der Nasszelle ausbauen</li><li>Alle 8 Schrauben lösen</li><li>Klemmen um 90° drehen</li><li>Schrauben herausdrehen</li><li>Dachrahmen abnehmen</li></ol>
<h2>Einbau</h2><p>Einbau in umgekehrter Reihenfolge.</p>
<h3>Anzugsdrehmoment</h3><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Klemm-Schrauben</td><td><strong>1,5 Nm (handfest)</strong></td></tr></tbody></table>
<p>Anzahl der Schrauben kann je nach Ausstattung variieren. Der Rahmen kann aus Kunststoff oder Aluminium bestehen.</p>',
                ['gc_location'=>'Nasszelle, Dach','gc_tools_needed'=>'Schraubendreher V.A.G 1624','gc_torque_specs'=>'Klemm-Schrauben: 1,5 Nm (handfest)'],
                ['Dachrahmen','Nasszelle','Klemmen'],$gc,$all_years),

            self::ausbau('Dachstaukasten links aus- und einbauen','Aus- und Einbau des linken Dachstaukastens. Zweitperson benötigt.',
                'Dachstaukasten links','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmoment-Schraubendreher (V.A.G 1624)</li></ul>
<h2>Ausbau</h2><ol><li>Verkleidungen des linken Dachstaukastens ausbauen</li><li>Schrauben lösen</li><li>Elektrischen Stecker abziehen</li><li>Kabelbaum freimachen</li><li>Stützstange entfernen</li><li>Klappe öffnen, weitere Schrauben lösen</li><li>Klappe schließen</li><li><strong>Mit einer zweiten Person</strong> den Dachstaukasten abnehmen</li></ol>
<h2>Anzugsdrehmomente</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Dachstaukasten-Schrauben</td><td><strong>2 Nm</strong></td></tr><tr><td>Blenden-Schrauben</td><td><strong>1 Nm</strong></td></tr></tbody></table>
<h3>Hinweis</h3><p>Die Profilschiene muss nach dem Herausdrehen der Schrauben erneuert werden.</p>',
                ['gc_location'=>'Dach links','gc_tools_needed'=>'Schraubendreher V.A.G 1624','gc_torque_specs'=>"Dachstaukasten: 2 Nm\nBlenden: 1 Nm"],
                ['Dachstaukasten','Profilschiene'],$gc,$all_years),

            self::ausbau('Dachstaukasten rechts aus- und einbauen','Aus- und Einbau des rechten Dachstaukastens. Zweitperson benötigt.',
                'Dachstaukasten rechts','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmoment-Schraubendreher (V.A.G 1624)</li></ul>
<h2>Ausbau</h2><ol><li>Verkleidungen des rechten Dachstaukastens ausbauen</li><li>Schiebetür-Dachschienen-Verkleidung entfernen</li><li>Schrauben lösen, Stecker abziehen, Kabelbaum freimachen</li><li>Klappen öffnen, Schrauben in allen Staukästen lösen</li><li>Klappen schließen</li><li><strong>Mit einer zweiten Person</strong> den Dachstaukasten abnehmen</li></ol>
<h2>Anzugsdrehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Dachstaukasten-Schrauben</td><td><strong>2 Nm</strong></td></tr></tbody></table>',
                ['gc_location'=>'Dach rechts','gc_tools_needed'=>'Schraubendreher V.A.G 1624','gc_torque_specs'=>'Dachstaukasten: 2 Nm'],
                ['Dachstaukasten','Profilschiene'],$gc,$all_years),

            self::ausbau('Dachstaukasten hinten Mitte aus- und einbauen','Aus- und Einbau des hinteren mittleren Dachstaukastens. Erfordert umfangreiche Vorarbeiten.',
                'Dachstaukasten hinten Mitte','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmomentschlüssel (V.A.G 1783)</li></ul>
<h2>Vorarbeiten</h2><p>Vor dem Ausbau müssen folgende Teile entfernt werden:</p><ul><li>Blende des hinteren Dachstaukastens</li><li>Lautsprecher (falls vorhanden)</li><li>Linker Dachstaukasten</li><li>Rechter Dachstaukasten</li><li>Eckverkleidungen links und rechts</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben am Dachstaukasten lösen</li><li>Schrauben an den Blechhaltern beidseitig lösen</li><li><strong>Mit einer zweiten Person</strong> den Dachstaukasten abnehmen</li></ol>
<h2>Einbau</h2><p>Beim Einbau den Dachstaukasten mittig ausrichten.</p>',
                ['gc_location'=>'Dach hinten Mitte','gc_tools_needed'=>'Drehmomentschlüssel V.A.G 1783'],
                ['Dachstaukasten','Blechhalter'],$gc,$all_years),

            self::ausbau('Duschwanne aus- und einbauen','Aus- und Einbau der Duschwanne. Achtung: Die Duschwanne wird beim Ausbau zerstört!',
                'Duschwanne','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Handkartuschenpistole (V.A.G 1628)</li></ul>
<h2>Vorarbeiten</h2><ul><li>Schrank ausbauen</li><li>Einstiegsleiste entfernen</li><li>Toilette ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben am Siphon lösen</li><li>Umlaufende Dichtung lösen</li><li>Klebeverbindung vorsichtig trennen, Duschwanne herausnehmen</li><li>Klebstoffreste vollständig entfernen</li></ol>
<div style="background:#fffbeb;border:1px solid #f6e05e;border-left:4px solid #ecc94b;border-radius:8px;padding:1rem 1.25rem;margin:1.5rem 0;"><strong style="color:#975a16;">Achtung</strong><p style="margin:0.25rem 0 0;font-size:0.9rem;color:#744210;">Die Duschwanne wird beim Ausbau zerstört und muss immer durch eine neue ersetzt werden!</p></div>
<h2>Einbau</h2><ol><li>Klebeflächen an der neuen Duschwanne mit Reinigungslösung säubern</li><li>Primer auf die Duschwannen-Klebeflächen auftragen</li><li>Montageflächen mit Reinigungslösung säubern</li><li>1K-Scheibenklebstoff mit Kartuschenpistole auftragen (Dreiecksraupe, 5 mm Höhe, 5 mm Breite)</li><li>Fugen umlaufend mit Dichtmasse versiegeln (D.476.MS1.A2)</li><li>Dichtmasse sauber glattstreichen</li></ol>
<h2>Anzugsdrehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>1 Nm (handfest)</strong></td></tr></tbody></table>',
                ['gc_location'=>'Nasszelle','gc_tools_needed'=>"Kartuschenpistole V.A.G 1628",'gc_torque_specs'=>'Schrauben: 1 Nm (handfest)'],
                ['Duschwanne','Siphon','Dichtmasse'],$gc,$all_years),

            self::ausbau('Eckverkleidungen hinten rechts aus- und einbauen','Aus- und Einbau der hinteren rechten Eckverkleidungen (oben und unten).',
                'Eckverkleidungen hinten rechts','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmoment-Schraubendreher (V.A.G 1624)</li></ul>
<h2>Vorarbeiten</h2><ul><li>Blende des hinteren Dachstaukastens entfernen</li><li>Ambiente-/Innenbeleuchtung ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Klappen der angrenzenden Dachstaukästen links und rechts öffnen</li><li>Schrauben beidseitig an der oberen und unteren Verkleidung lösen</li><li>Obere Eckverkleidung abziehen</li><li>Untere Eckverkleidung abziehen</li></ol>
<h2>Anzugsdrehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Verkleidungsschrauben</td><td><strong>1 Nm</strong></td></tr></tbody></table>',
                ['gc_location'=>'Innenraum hinten rechts','gc_tools_needed'=>'Schraubendreher V.A.G 1624','gc_torque_specs'=>'Schrauben: 1 Nm'],
                ['Eckverkleidung','Dachstaukasten'],$gc,$all_years),

            self::ausbau('Einstiegsleiste (Nasszelle) aus- und einbauen','Aus- und Einbau der Einstiegsleiste im Bereich der Nasszelle. Rein verklebtes Bauteil.',
                'Einstiegsleiste','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Demontagekeil (3409)</li><li>Handkartuschenpistole (V.A.G 1628)</li></ul>
<h2>Ausbau</h2><ol><li>Klebeverbindung der Einstiegsleiste lösen</li><li>Einstiegsleiste entfernen</li><li>Klebstoffreste vollständig entfernen</li></ol>
<h2>Einbau</h2><ol><li>Klebeflächen mit Reinigungslösung säubern</li><li>Primer auftragen</li><li>1K-Montageklebstoff mit Kartuschenpistole auftragen (Dreiecksraupe, 5 mm Höhe, 5 mm Breite)</li><li>Einstiegsleiste gleichmäßig positionieren und fest andrücken</li><li>Überschüssigen Klebstoff entfernen</li></ol>
<p><strong>Hinweis:</strong> Rein verklebtes Bauteil — keine Schrauben.</p>',
                ['gc_location'=>'Nasszelle, Eingang','gc_tools_needed'=>"Demontagekeil 3409\nKartuschenpistole V.A.G 1628"],
                ['Einstiegsleiste','Nasszelle','Klebstoff'],$gc,$all_years),

            self::ausbau('Folierung aus- und einbauen','Aus- und Einbau der Karosserie-Folierung. Am besten in staubfreier Umgebung bei 8–25°C.',
                'Folierung','Karosserie',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Rakel mit Filzkante</li><li>Handschuhe</li><li>Applikationsflüssigkeit</li><li>Heißluftgebläse oder IR-Strahler</li><li>Schneidwerkzeug</li></ul>
<h2>Ausbau</h2><ol><li>Betroffene Scheibe/Fenster ausbauen</li><li>Alte Folie und Klebstoffreste vollständig entfernen</li></ol>
<h2>Einbau</h2><ol><li>Oberflächen reinigen und entfetten</li><li>Schutzfolie von der neuen Folie abziehen</li><li>Fahrzeugoberfläche und Folie-Klebseite mit Applikationsflüssigkeit einsprühen</li><li>Folie faltenfrei positionieren</li><li>Folie mit Applikationsflüssigkeit einsprühen</li><li>Bei großen Folien: zweite Person zum Halten</li><li>Rakel mit gleichmäßigem Druck führen, Flüssigkeit herausarbeiten</li><li>Kanten und Sicken vor dem Umschlagen trocknen</li><li>Kanten mit Heißluft nacharbeiten (80–95°C)</li><li>Scheibe wieder einbauen</li></ol>
<h3>Wichtig</h3><p>Staubfreie Umgebung, wenig Luftbewegung, geschlossener Raum, 8–25°C, gute Beleuchtung.</p>',
                ['gc_location'=>'Karosserie außen','gc_tools_needed'=>"Rakel mit Filzkante\nHeißluftgebläse\nApplikationsflüssigkeit"],
                ['Folierung','Folie','Rakel'],$gc,$all_years),

            self::ausbau('Formhimmel Nasszelle aus- und einbauen','Aus- und Einbau der Deckenverkleidung in der Nasszelle. Befestigt mit Dual-Lock Klettstreifen.',
                'Formhimmel Nasszelle','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Spachtelset (VAS 6845)</li></ul>
<h2>Ausbau</h2><ol><li>Dachfenster-Blende abziehen (Spachtel aus VAS 6845 verwenden)</li><li>Umlaufende Dichtung vom Deckenpanel lösen</li><li>Deckenpanel nach unten von den Dual-Lock Klettstreifen abziehen</li></ol>
<h2>Einbau</h2><ol><li>Klettstreifen erneuern falls nötig (6 Streifen: 220, 300, 170, 400, 480, 780 mm)</li><li>Deckenpanel umlaufend abdichten</li><li>Klebeflächen mit Reinigungslösung säubern</li><li>1K-Montageklebstoff umlaufend auftragen (Dreiecksraupe, 5 mm Höhe, 5 mm Breite)</li><li>Dichtmasse sauber glattstreichen</li></ol>',
                ['gc_location'=>'Nasszelle, Decke','gc_tools_needed'=>'Spachtelset VAS 6845'],
                ['Formhimmel','Nasszelle','Dual-Lock','Klettstreifen'],$gc,$all_years),

            self::ausbau('Frontwand (Trennwand) aus- und einbauen','Aus- und Einbau der Frontwand / Trennwand zwischen Wohn- und Nassbereich. Klebeflächen müssen sauber vorbereitet werden.',
                'Frontwand / Trennwand','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmoment-Schraubendreher (V.A.G 1624)</li><li>Handkartuschenpistole (V.A.G 1628)</li></ul>
<h2>Vorarbeit</h2><p>Dachrahmen der Nasszelle ausbauen.</p>
<h2>Ausbau</h2><ol><li>Zündung ausschalten</li><li>Elektrischen Stecker abziehen</li><li>Kabelbaum aus Halterung lösen</li><li>Abdeckkappen der unteren Schrauben entfernen, Schrauben lösen</li><li>Abdeckkappen der oberen Schrauben entfernen, Schrauben lösen</li><li>Klebeverbindung zwischen Front- und Rückseitenwand vorsichtig trennen</li><li>Frontwand herausnehmen</li><li>Klebstoffreste vollständig entfernen</li></ol>
<h2>Einbau</h2><ol><li>Klebeflächen mit Reinigungslösung säubern</li><li>Bei neuer Frontwand: Primer auftragen</li><li>1K-Montageklebstoff auftragen (Dreiecksraupe, 5 mm Höhe, 5 mm Breite)</li><li>Fugen von innen umlaufend mit Dichtmasse versiegeln (D.476.MS1.A2)</li><li>Dichtmasse glattstreichen</li></ol>
<h2>Anzugsdrehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>1 Nm (handfest)</strong></td></tr></tbody></table>',
                ['gc_location'=>'Nasszelle / Wohnbereich','gc_tools_needed'=>"Schraubendreher V.A.G 1624\nKartuschenpistole V.A.G 1628",'gc_torque_specs'=>'Schrauben: 1 Nm (handfest)'],
                ['Frontwand','Trennwand','Dichtmasse'],$gc,$all_years),

            self::ausbau('Blende Dachstaukasten hinten Mitte aus- und einbauen','Aus- und Einbau der Blende des hinteren mittleren Dachstaukastens.',
                'Blende Dachstaukasten Mitte','Campingausstattung Grand California',
                '<h2>Benötigtes Werkzeug</h2><ul><li>Drehmoment-Schraubendreher (V.A.G 1624)</li></ul>
<h2>Ausbau</h2><ol><li>Ambientebeleuchtung entfernen (falls vorhanden)</li><li>Staukastenklappe öffnen</li><li>Schrauben von innen lösen</li><li>Markisenkurbel entfernen (falls vorhanden)</li><li>Klebeverbindungen vorsichtig lösen</li><li>Blende in Pfeilrichtung abnehmen — Vorsicht: Nut an der Rückseite nicht abbrechen!</li></ol>
<h2>Einbau</h2><p>Klebeflächen reinigen. 20 mm Abstand ohne Klebstoff an beiden Seiten der Nut einhalten. 1K-Scheibenklebstoff nach Verarbeitungshinweisen auftragen.</p>',
                ['gc_location'=>'Dach hinten Mitte','gc_tools_needed'=>'Schraubendreher V.A.G 1624'],
                ['Blende','Dachstaukasten','Ambientebeleuchtung'],$gc,$all_years),
        ];
    }

    private static function ausbau($title,$excerpt,$component,$hauptkat,$content,$meta,$components,$models,$years) {
        return [
            'title'=>$title,
            'excerpt'=>$excerpt,
            'content'=>$content,
            'meta'=>$meta,
            'categories'=>['Reparaturanleitungen','Aus- und Einbau',$hauptkat],
            'models'=>$models,
            'years'=>$years,
            'components'=>$components,
        ];
    }
}

add_action('admin_init',function(){
    if(isset($_GET['gc_import_batch2a'])&&current_user_can('manage_options')){
        $r=GC_Batch2a_Import::run();
        add_action('admin_notices',function()use($r){echo '<div class="notice notice-success"><p>'.esc_html($r).'</p></div>';});
    }
});
