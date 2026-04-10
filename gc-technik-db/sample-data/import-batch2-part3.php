<?php
/**
 * Batch 2 Part 3: Aus-/Einbau Anleitungen (18 Artikel)
 * Aufruf: /wp-admin/?gc_import_batch2c=1
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class GC_Batch2c_Import {
    public static function run() {
        if ( get_option( 'gc_batch2c_imported' ) ) { return 'Batch 2c bereits importiert.'; }
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
        update_option('gc_batch2c_imported',true);
        return sprintf('%d Aus-/Einbau Artikel (Batch 2c) importiert.',$count);
    }

    private static function get_articles() {
        $gc = ['Grand California 600','Grand California 680'];
        $ay = ['2019','2020','2021','2022','2023','2024','2025 (Facelift)'];
        $cats = ['Reparaturanleitungen','Aus- und Einbau','Campingausstattung Grand California'];
        return [

['title'=>'Seitenwand hinten aus- und einbauen','excerpt'=>'Aus- und Einbau der hinteren Seitenwand im Wohnbereich. Unterschiedliche Schritte je nach Radstand.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li></ul>
<h2>Vorarbeiten</h2><ul><li>Frontwand, Rückwand und Make-up-Spiegel W49 ausbauen</li><li>Kurzer Radstand: zusätzlich Stauschrank und Dachstaukasten</li><li>Langer Radstand: zusätzlich Stauschrank</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben an mehreren Stellen lösen (oben, Befestigungselement, außen)</li><li>Ggf. Halterungen entfernen</li><li>Seitenwand herausnehmen</li></ol>
<h2>Einbau</h2><p>Seitenwand ausrichten und alle Anbauteile auf Passung prüfen. Bei neuer Wand: Montageleisten übertragen. Fugen von innen mit Dichtmasse (D.476.MS1.A2) abdichten.</p>
<h2>Drehmomente</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>1 Nm</strong></td></tr><tr><td>Halterungsschrauben</td><td><strong>1,8 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Wohnbereich hinten','gc_torque_specs'=>"Schrauben: 1 Nm\nHalterung: 1,8 Nm"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Seitenwand hinten','Dichtmasse']],

['title'=>'Seitenwand vorn aus- und einbauen','excerpt'=>'Aus- und Einbau der vorderen Seitenwand. Temperaturfühler G18 und G1090 müssen ggf. vorher ausgebaut werden.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li></ul>
<h2>Vorarbeiten</h2><ul><li>Frontwand, Rückwand, Ausströmer und untere linke Seitenverkleidung ausbauen</li><li>Falls vorhanden: Temperaturfühler G1090 und/oder G18 ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben am Befestigungselement lösen</li><li>Halterungen lösen und entfernen</li><li>Weitere Schrauben lösen</li><li>Seitenwand herausnehmen</li></ol>
<h2>Einbau</h2><p>Ausschnitte für Sitzbankbefestigung im unteren Bereich prüfen. Seitenwand ausrichten. Fugen mit Dichtmasse (D.476.MS1.A2) abdichten.</p>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>1 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Wohnbereich vorn','gc_torque_specs'=>'Schrauben: 1 Nm'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Seitenwand vorn','G18 Temperaturfühler','G1090']],

['title'=>'Serviceklappe Toilette aus- und einbauen','excerpt'=>'Aus- und Einbau der Toiletten-Serviceklappe am Heck. Verklebte Klappe — 1K-Scheibenklebstoff und Primer erforderlich.','content'=>'
<h2>Werkzeug</h2><ul><li>Demontagekeil-Set VAS 895 015</li><li>Handkartuschenpistole V.A.G 1628</li><li>Scheibenausbau-Set VAS 861 001A</li></ul>
<h2>Ausbau</h2><ol><li>Stellelement der Serviceklappe ausbauen</li><li>Bereich um die Klappe mit Klebeband abkleben</li><li>Äußere und innere Schrauben lösen</li><li>Kleberaupe mit Schneidmesser aus VAS 861 001A trennen</li><li>Serviceklappe abnehmen, Klebstoffreste entfernen</li></ol>
<h2>Einbau</h2><ol><li>Lack prüfen, bei Bedarf ausbessern</li><li>Klappe zur Ausrichtung probeweise einsetzen, dann wieder abnehmen</li><li>Klebeflächen reinigen, Primer auftragen</li><li>1K-Scheibenklebstoff auftragen (Dreiecksraupe, 5 mm Höhe, 5 mm Breite, ca. 1450 mm Länge)</li><li>Klappe einsetzen und verschrauben</li><li>Umlaufend mit Dichtmasse abdichten</li></ol>
<h2>Drehmomente</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben außen</td><td><strong>1 Nm</strong></td></tr><tr><td>Schrauben innen</td><td><strong>2 Nm</strong></td></tr></tbody></table>
<p><strong>Tipp:</strong> Bei einer neuen Dichtmasse-Kartusche die ersten 10 cm verwerfen (mögliche Verfärbung).</p>',
'meta'=>['gc_location'=>'Heck','gc_torque_specs'=>"Außen: 1 Nm\nInnen: 2 Nm"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Serviceklappe','Toilette','Scheibenklebstoff']],

['title'=>'Sitzbank aus- und einbauen','excerpt'=>'Aus- und Einbau der Sitzbank im Wohnbereich. Abwassertank muss vorher entleert und Kraftstofftank abgesenkt werden.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmomentschlüssel V.A.G 1331</li></ul>
<h2>Vorarbeiten</h2><ul><li>Abwassertank entleeren und ausbauen</li><li>Kraftstofftank absenken</li><li>Sitzbankversteifung ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben lösen, dabei Gegenmuttern halten</li><li>Weitere Schrauben lösen</li><li>Sitzbank in Pfeilrichtung herausschieben, dabei Halteplatte entfernen</li><li>Falls nötig: Heizleitungen abklemmen</li></ol>
<h2>Einbau</h2><p>Halteplatte über den Zentrierstift am Fahrzeugboden ausrichten. Drehmomente beachten.</p>',
'meta'=>['gc_location'=>'Wohnbereich','gc_tools_needed'=>'Drehmomentschlüssel V.A.G 1331'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Sitzbank','Abwassertank','Kraftstofftank']],

['title'=>'Solarzellen (C20) aus- und einbauen','excerpt'=>'Aus- und Einbau der Dach-Solarzellen. Achtung: Die Solarzellen werden beim Ausbau zerstört! Dritte Person wird zum Führen des Kabelbaums benötigt.','content'=>'
<h2>Werkzeug</h2><ul><li>Arbeitsplattform</li><li>Elektro-Messer V.A.G 1561A</li><li>Klebestreifenentferner VAS 6349</li><li>Handkartuschenpistole V.A.G 1628</li></ul>
<h2>Vorarbeiten (je nach Position)</h2><ul><li>Vordere Solarzelle: Dachhimmel Mitte absenken, ggf. vorderen Dachstaukasten ausbauen</li><li>Mittlere Solarzelle: Mittleren und hinteren Dachhimmel ausbauen</li><li>Dachdämmung im Arbeitsbereich entfernen</li></ul>
<h2>Ausbau</h2><ol><li>Stecker aus Schaumstoffhülle lösen und trennen</li><li>Kontakte aus Steckergehäuse auspinnen (Pin-Belegung notieren!)</li><li>Umlaufende Dichtung mit Elektro-Messer durchtrennen</li><li>Klebeverbindung zwischen Solarzelle und Fahrzeug mit Elektro-Messer trennen</li><li>Zweite Person nimmt die Solarzelle ab, dritte Person zieht den Kabelbaum durch das Dach</li></ol>
<div style="background:#fffbeb;border:1px solid #f6e05e;border-left:4px solid #ecc94b;border-radius:8px;padding:1rem;margin:1.5rem 0;"><strong style="color:#975a16;">Achtung</strong><p style="margin:0.25rem 0 0;font-size:0.9rem;color:#744210;">Die Solarzellen werden beim Ausbau zerstört und müssen immer durch neue ersetzt werden!</p></div>
<h2>Einbau</h2><ol><li>Klebstoffreste entfernen (VAS 6349)</li><li>Oberfläche reinigen</li><li>Schutzfolie mit zweiter Person abziehen</li><li>Panel positionieren (dritte Person führt Kabelbaum durch Dachlöcher)</li><li>Lufteinschlüsse vermeiden!</li><li>Im Abstand von ca. 1 cm abkleben</li><li>1K-Montageklebstoff umlaufend auftragen und glattstreichen</li><li>Kabelbaum an den Dachdurchführungen abdichten</li></ol>',
'meta'=>['gc_vw_code'=>'C20','gc_location'=>'Dach','gc_tools_needed'=>"Elektro-Messer V.A.G 1561A\nKlebestreifenentferner VAS 6349\nKartuschenpistole V.A.G 1628"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Solarzellen C20','Solarmodul','Kabelbaum']],

['title'=>'Stauschrank links vorn aus- und einbauen','excerpt'=>'Aus- und Einbau des linken vorderen Stauschranks. Beim Herausnehmen den Dachhimmel nach oben drücken.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher VAS 6494</li></ul>
<h2>Vorarbeit</h2><p>Bei langem Radstand: hintere linke obere Seitenverkleidung ausbauen.</p>
<h2>Ausbau</h2><ol><li>Schranktür öffnen</li><li>Innere Schrauben lösen</li><li>Untere Schrauben lösen</li><li>Schrank herausnehmen — dabei Dachhimmel nach oben drücken</li></ol>
<h2>Einbau</h2><p>Schrank mit angrenzenden Schränken und Nasszelle ausrichten.</p>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>1 Nm (handfest)</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Wohnbereich links vorn','gc_torque_specs'=>'Schrauben: 1 Nm'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Stauschrank','Dachhimmel']],

['title'=>'Temperaturfühler G18 aus- und einbauen','excerpt'=>'Aus- und Einbau des Temperaturfühlers G18 im Wohnbereich. Nur Demontagekeil benötigt.','content'=>'
<h2>Werkzeug</h2><ul><li>Demontagekeil 3409</li></ul>
<h2>Ausbau</h2><ol><li>Temperaturfühler G18 mit dem Demontagekeil 3409 heraushebeln</li><li>Sensor abnehmen und dabei den elektrischen Stecker trennen</li></ol>
<h2>Einbau</h2><p>In umgekehrter Reihenfolge.</p>',
'meta'=>['gc_vw_code'=>'G18','gc_location'=>'Mittelbereich links','gc_tools_needed'=>'Demontagekeil 3409'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Temperaturfühler G18']],

['title'=>'Temperaturfühler Innenraum G1090 aus- und einbauen','excerpt'=>'Aus- und Einbau des Innenraum-Temperaturfühlers G1090. Untere linke Seitenverkleidung muss vorher ausgebaut werden.','content'=>'
<h2>Werkzeug</h2><ul><li>Demontagekeil 3409</li></ul>
<h2>Ausbau</h2><ol><li>Sensor G1090 mit dem Demontagekeil 3409 heraushebeln</li><li>Untere linke Seitenverkleidung Mitte ausbauen</li><li>Sensor abnehmen und Stecker trennen</li></ol>
<h2>Einbau</h2><p>In umgekehrter Reihenfolge.</p>',
'meta'=>['gc_vw_code'=>'G1090','gc_location'=>'Mittelbereich links','gc_tools_needed'=>'Demontagekeil 3409'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Temperaturfühler G1090','Innenraumsensor']],

['title'=>'Toilette aus- und einbauen','excerpt'=>'Aus- und Einbau der Kassettentoilette. Fäkalientank und Serviceklappe müssen vorher raus.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li><li>Handkartuschenpistole V.A.G 1628</li></ul>
<h2>Vorarbeiten</h2><ul><li>Fäkalientank entfernen</li><li>Serviceklappe ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben lösen</li><li>Überwurfmutter abschrauben</li><li>Elektrische Stecker trennen</li><li>Klemmleiste öffnen, Kabelbaum herausnehmen</li><li>Kabelbaum freimachen</li><li>Umlaufende Dichtung lösen</li><li>Toilette herausnehmen</li></ol>
<h2>Einbau</h2><p>Passung im Bereich der Serviceklappe prüfen. Dichtmasse (D.476.MS1.A2) zwischen Wand und Toilette auftragen. Dichtprofil umlaufend mit Dichtmasse einsetzen.</p>
<h2>Drehmomente</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Toiletten-Schrauben</td><td><strong>1 Nm</strong></td></tr><tr><td>Schellen-Schrauben</td><td><strong>1 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Nasszelle','gc_torque_specs'=>'Alle Schrauben: 1 Nm'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Toilette','Kassettentoilette','Fäkalientank','Dichtprofil']],

['title'=>'Trittstufe aus- und einbauen','excerpt'=>'Aus- und Einbau der elektrischen Trittstufe an der Schiebetür. Zweite Person zum Halten benötigt.','content'=>'
<h2>Werkzeug</h2><ul><li>Entriegelungswerkzeugset VAS 1978/35</li><li>Leitungsstrang-Reparaturset VAS 1978 B</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben lösen, Abdeckungen entfernen</li><li>Weitere Schrauben lösen (pro Seite eine als Sicherung wenige Gewindegänge eingedreht lassen)</li><li>Elektrischen Stecker trennen</li><li>Bei Tausch: Kontakte aus dem Steckergehäuse auspinnen</li><li>Restliche Schrauben lösen</li><li><strong>Zweite Person</strong> hält die Trittstufe, vordere Schrauben lösen</li><li>Trittstufe abnehmen</li></ol>
<h2>Einbau</h2><p>Flachsteckergehäuse für den Wiedereinbau aufbewahren. Falls nicht vorhanden: Unterlegscheiben N 011 670 26 zwischen Mutter und Halterung einsetzen. Montagefläche zwischen Halterung und Karosserie muss frei von Unterbodenschutz sein.</p>',
'meta'=>['gc_location'=>'Schiebetür, Einstieg','gc_tools_needed'=>"Entriegelungswerkzeug VAS 1978/35\nReparaturset VAS 1978 B"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Trittstufe','Schiebetür','Steckergehäuse']],

['title'=>'Verbreiterung Querschläfer aus- und einbauen','excerpt'=>'Aus- und Einbau der ausklappbaren Bettverbreiterung (links und rechts). Verklebtes Bauteil mit präzisen Klebstoff-Maßen.','content'=>'
<h2>Werkzeug</h2><ul><li>Handkartuschenpistole V.A.G 1628</li><li>Elektro-Messer V.A.G 1561A</li></ul>
<h2>Ausbau</h2><ol><li>Obere Seitenverkleidung links bzw. rechts hinten ausbauen</li><li>Bettverbreiterung grob ausschneiden</li><li>Restliche Klebeverbindung mit Oszillierklinge vorsichtig trennen</li></ol>
<h2>Einbau</h2><ol><li>Lack prüfen, bei Bedarf ausbessern</li><li>Klebeflächen reinigen, Primer auftragen</li><li>1K-Scheibenklebstoff auftragen (Dreiecksraupe)</li><li>Bettverbreiterung einsetzen und mit gewebeverstärktem Klebeband fixieren</li><li>Überschüssigen Klebstoff glattstreichen</li><li>Klebeband nach Aushärtung entfernen</li></ol>
<h3>Klebstoff-Maße</h3>
<table><thead><tr><th>Seite</th><th>Höhe</th><th>Breite</th><th>Umlaufende Länge</th></tr></thead><tbody>
<tr><td>Rechts</td><td>10 mm</td><td>6,5 mm</td><td>4.095 mm</td></tr>
<tr><td>Links</td><td>10 mm / 13,5 mm</td><td>6,5 mm / 10 mm</td><td>4.095 mm + 1.550 mm (Zusatzraupe)</td></tr>
</tbody></table>',
'meta'=>['gc_location'=>'Seitenwand hinten','gc_tools_needed'=>"Kartuschenpistole V.A.G 1628\nElektro-Messer V.A.G 1561A"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Querschläfer','Bettverbreiterung','Scheibenklebstoff']],

['title'=>'Verkleidung B-Säule Mitte innen aus- und einbauen','excerpt'=>'Aus- und Einbau der inneren B-Säulen-Verkleidung. Gurtband darf beim Aus-/Einbau nicht verdreht werden.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmomentschlüssel V.A.G 1783</li><li>Keil T10383</li></ul>
<h2>Ausbau</h2><ol><li>Vordere Gurtendstückbefestigung ausbauen</li><li>Gurthöhenverstellschieber auf unterste Position</li><li>Abdeckkappe mit Keil T10383 von der B-Säulen-Verkleidung lösen</li><li>Schraube lösen</li><li>Verkleidung aus der Türdichtung lösen</li><li>Von der unteren B-Säulen-Verkleidung ausclipsen</li><li>Halteclips von unten nach oben mit T10383 lösen</li><li>Von der oberen B-Säulen-Verkleidung ausclipsen</li><li>Gurtband mit Schloss durch den Höhenverstellschieber fädeln</li><li>Verkleidung abnehmen</li></ol>
<h2>Einbau</h2><p>Clips aus Karosserie-Aufnahmepunkten entfernen und auf neue Verkleidung übertragen. Nach Einbau: Gurthöhenverstellung auf Funktion prüfen.</p>
<div style="background:#fffbeb;border:1px solid #f6e05e;border-left:4px solid #ecc94b;border-radius:8px;padding:1rem;margin:1.5rem 0;"><strong style="color:#975a16;">Wichtig</strong><p style="margin:0.25rem 0 0;font-size:0.9rem;color:#744210;">Gurtband darf nicht verdreht sein!</p></div>',
'meta'=>['gc_location'=>'B-Säule, Innenraum','gc_tools_needed'=>"Drehmomentschlüssel V.A.G 1783\nKeil T10383"],'categories'=>['Reparaturanleitungen','Aus- und Einbau','Karosserie'],'models'=>$gc,'years'=>$ay,'components'=>['B-Säulen-Verkleidung','Sicherheitsgurt','Gurthöhenverstellung']],

['title'=>'Verkleidung unter Dachstaukasten rechts aus- und einbauen','excerpt'=>'Aus- und Einbau der Verkleidung unterhalb des rechten Dachstaukastens. Nur Clips — kein Werkzeug nötig außer Demontagekeil.','content'=>'
<h2>Werkzeug</h2><ul><li>Demontagekeil 3409</li></ul>
<h2>Ausbau</h2><ol><li>Verkleidungsteile mit Demontagekeil 3409 lösen und abziehen</li><li>Elektrischen Stecker trennen</li><li>Verkleidung abnehmen</li></ol>
<h2>Einbau</h2><p>Befestigungsmaterial auf Beschädigungen prüfen, bei Bedarf erneuern.</p>',
'meta'=>['gc_location'=>'Dachstaukasten rechts','gc_tools_needed'=>'Demontagekeil 3409'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Verkleidung','Dachstaukasten']],

['title'=>'Verkleidungen für Dachstaukasten links aus- und einbauen','excerpt'=>'Aus- und Einbau der Verkleidungen am linken Dachstaukasten (hinten und unterhalb).','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li></ul>
<h2>Ausbau — Hintere linke Verkleidung</h2><ol><li>Blende des hinteren Dachstaukastens entfernen</li><li>Ambientebeleuchtung ausbauen</li><li>Staukastenklappen links und rechts öffnen</li><li>Schrauben beidseitig lösen</li><li>Verkleidungen abziehen</li></ol>
<h2>Ausbau — Untere linke Verkleidung</h2><ol><li>Verkleidung abziehen</li><li>Elektrischen Stecker trennen</li><li>Verkleidung abnehmen</li></ol>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Verkleidungsschrauben</td><td><strong>1 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Dachstaukasten links','gc_torque_specs'=>'Schrauben: 1 Nm'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Verkleidung','Dachstaukasten','Ambientebeleuchtung']],

['title'=>'Verstärkung Sitzbank aus- und einbauen','excerpt'=>'Aus- und Einbau der Sitzbank-Verstärkung (Strukturelement). Sitzfläche und Verkleidung müssen vorher runter.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmomentschlüssel V.A.G 1331</li><li>Drehmomentschlüssel V.A.G 1783</li></ul>
<h2>Ausbau</h2><ol><li>Sitzfläche abnehmen</li><li>Sitzbankverkleidung ausbauen</li><li>Schrauben lösen, dabei Gegenmuttern halten</li><li>Weitere Schrauben lösen</li><li>Verstärkung herausnehmen</li></ol>
<h2>Einbau</h2><p>In umgekehrter Reihenfolge. Drehmomente aus der Montageübersicht beachten.</p>',
'meta'=>['gc_location'=>'Sitzbank','gc_tools_needed'=>"Drehmomentschlüssel V.A.G 1331\nDrehmomentschlüssel V.A.G 1783"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Sitzbank-Verstärkung','Strukturelement']],

['title'=>'Waschbecken aus- und einbauen','excerpt'=>'Aus- und Einbau des Waschbeckens in der Nasszelle. Schrauben nur 1 Nm — umlaufend abdichten mit Dichtmasse.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li><li>Handkartuschenpistole V.A.G 1628</li></ul>
<h2>Ausbau</h2><ol><li>Waschbeckeneinsatz entfernen</li><li>Dichtmasse entfernen</li><li>Schranktüren öffnen</li><li>Schrauben lösen</li><li>Ablaufrohr abziehen</li><li>Schellen lösen, Schläuche abziehen</li><li>Waschbecken anheben und dabei Stecker trennen</li><li>Alle Klebstoffreste entfernen</li></ol>
<h2>Einbau</h2><p>Klebeflächen reinigen. Dichtmasse (D.476.MS1.A2) umlaufend auftragen und glattstreichen.</p>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>1 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Nasszelle','gc_torque_specs'=>'Schrauben: 1 Nm'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Waschbecken','Ablaufrohr','Nasszelle']],

['title'=>'Zusatzheizung (ZX2) aus- und einbauen','excerpt'=>'Aus- und Einbau der Zusatzheizung ZX2 (Truma). Achtung: Kraftstoffsystem steht unter Druck! Bei Gasheizung: Dichtigkeitsprüfung nach dem Einbau Pflicht.','content'=>'
<h2>Werkzeug</h2><ul><li>Schlauchklemmen bis 25 mm (3094)</li><li>Schlauchklemmenzange VAS 6340</li><li>Drehmoment-Schraubendreher VAS 6494</li><li>Abklemmzange VAS 531 007/2</li></ul>
<h2>Vorarbeit</h2><p>Sitzbank-Verstärkung ausbauen.</p>
<h2>Ausbau</h2><ol><li>Schellen lösen, Schläuche abziehen (ausstattungsabhängig)</li><li>Bei Gasheizung: Überwurfmutter lösen</li><li>Bei Dieselheizung: Schellen lösen, Kraftstoffschlauch abziehen</li><li>Alle Schläuche mit Klemmen 3094 abklemmen</li><li>Restliche Schläuche und Schellen lösen</li><li>Alle elektrischen Verbindungen trennen</li><li>Schrauben lösen</li><li>Zusatzheizung ZX2 herausnehmen, dabei Wasserablaufschlauch abziehen</li></ol>
<h2>Drehmomente</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Heizgerät-Schrauben</td><td><strong>10 Nm</strong></td></tr><tr><td>Mutter (Anker)</td><td><strong>18 Nm</strong></td></tr></tbody></table>
<div style="background:#fffbeb;border:1px solid #f6e05e;border-left:4px solid #ecc94b;border-radius:8px;padding:1rem;margin:1.5rem 0;"><strong style="color:#975a16;">Vorsicht — Kraftstoffsystem</strong><p style="margin:0.25rem 0 0;font-size:0.9rem;color:#744210;">Kraftstoff steht unter Druck! Verletzungsgefahr durch spritzendes Kraftstoff. Schutzbrille und Handschuhe tragen. Vor dem Öffnen Druck mit einem sauberen Tuch auffangen.</p></div>
<div style="background:#fff5f5;border:1px solid #fc8181;border-left:4px solid #e53e3e;border-radius:8px;padding:1rem;margin:1.5rem 0;"><strong style="color:#c53030;">Gefahr — Gasheizung</strong><p style="margin:0.25rem 0 0;font-size:0.9rem;color:#9b2c2c;">Bei gasangeschlossenen Heizungen MUSS vor der Wiederinbetriebnahme eine Dichtigkeitsprüfung durchgeführt werden!</p></div>',
'meta'=>['gc_vw_code'=>'ZX2','gc_location'=>'Unter Sitzbank','gc_torque_specs'=>"Heizgerät: 10 Nm\nMutter: 18 Nm",'gc_tools_needed'=>"Schlauchklemmen 3094\nKlemmenzange VAS 6340\nSchraubendreher VAS 6494"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Zusatzheizung ZX2','Truma','Gasheizung','Dieselheizung']],

['title'=>'Scharniere für Spiegel an der Rückwand aus- und einbauen','excerpt'=>'Aus- und Einbau der Spiegelscharniere an der Nasszellen-Rückwand. Schrauben mit 2 Nm anziehen.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li></ul>
<h2>Vorarbeit</h2><p>Spiegel nach separater Anleitung ausbauen.</p>
<h2>Ausbau</h2><ol><li>Schrauben lösen und dabei das Scharnier abnehmen</li></ol>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Scharnier-Schrauben</td><td><strong>2 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Nasszelle, Rückwand','gc_torque_specs'=>'Schrauben: 2 Nm'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Scharnier','Spiegel','Rückwand']],

        ];
    }
}

add_action('admin_init',function(){
    if(isset($_GET['gc_import_batch2c'])&&current_user_can('manage_options')){
        $r=GC_Batch2c_Import::run();
        add_action('admin_notices',function()use($r){echo '<div class="notice notice-success"><p>'.esc_html($r).'</p></div>';});
    }
});
