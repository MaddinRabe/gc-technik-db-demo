<?php
/**
 * Batch 2 Part 2: Aus-/Einbau Anleitungen (18 Artikel)
 * Aufruf: /wp-admin/?gc_import_batch2b=1
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class GC_Batch2b_Import {
    public static function run() {
        if ( get_option( 'gc_batch2b_imported' ) ) { return 'Batch 2b bereits importiert.'; }
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
        update_option('gc_batch2b_imported',true);
        return sprintf('%d Aus-/Einbau Artikel (Batch 2b) importiert.',$count);
    }

    private static function get_articles() {
        $gc = ['Grand California 600','Grand California 680'];
        $ay = ['2019','2020','2021','2022','2023','2024','2025 (Facelift)'];
        $cats = ['Reparaturanleitungen','Aus- und Einbau','Campingausstattung Grand California'];
        return [

['title'=>'Halter für Markisenkurbel aus- und einbauen','excerpt'=>'Aus- und Einbau des Halters für die Markisenkurbel. Schrauben mit 8 Nm anziehen.','content'=>'
<h2>Werkzeug</h2><ul><li>Stehleiter VAS 6292/4</li><li>Drehmoment-Schraubendreher V.A.G 1624</li></ul>
<h2>Ausbau</h2><ol><li>Kurbel aus dem Halter lösen</li><li>Schrauben herausdrehen und Halter abnehmen</li></ol>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>8 Nm</strong></td></tr></tbody></table>
<p>Ausstattungsabhängig kann das Handstück der Kurbel auch auf der linken Seite sitzen.</p>',
'meta'=>['gc_location'=>'Dach, Markise','gc_torque_specs'=>'Schrauben: 8 Nm','gc_tools_needed'=>"Stehleiter VAS 6292/4\nSchraubendreher V.A.G 1624"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Markisenkurbel','Halter']],

['title'=>'Halterahmen der Serviceklappe Toilette aus- und einbauen','excerpt'=>'Aus- und Einbau des Halterahmens der Toiletten-Serviceklappe. Befestigung mit Butyl-Dichtschnur (3 mm).','content'=>'
<h2>Werkzeug</h2><ul><li>Spachtelset VAS 6845</li><li>Klebestreifenentferner VAS 6349</li></ul>
<h2>Vorarbeit</h2><p>Rückwand der Nasszelle ausbauen (separate Anleitung).</p>
<h2>Ausbau</h2><ol><li>Rahmen lösen und vom Seitenteil abnehmen</li><li>Klebstoffreste mit Klebestreifenentferner VAS 6349 entfernen</li></ol>
<h2>Einbau</h2><ol><li>Klebefläche mit Reinigungslösung reinigen (Ablüftzeit beachten)</li><li>Butyl-Klebe-Dichtschnur einlegen (Durchmesser 3 mm)</li><li>Rahmen am Fahrzeug ausrichten und fixieren</li></ol>
<p>Verarbeitungstemperatur für Butylschnur: +18°C bis +35°C</p>',
'meta'=>['gc_location'=>'Heck, Serviceklappe Toilette','gc_tools_needed'=>"Spachtelset VAS 6845\nKlebestreifenentferner VAS 6349"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Halterahmen','Serviceklappe','Toilette','Butylschnur']],

['title'=>'Handtuchhalter aus- und einbauen','excerpt'=>'Aus- und Einbau des Handtuchhalters in der Nasszelle. Schrauben mit 1 Nm anziehen.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li><li>Demontagekeil 3409</li></ul>
<h2>Ausbau</h2><ol><li>Abdeckkappen abnehmen</li><li>Hülsen entnehmen (falls vorhanden)</li><li>Schrauben herausdrehen</li><li>Handtuchhalter abnehmen</li></ol>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>1 Nm (handfest)</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Nasszelle','gc_torque_specs'=>'Schrauben: 1 Nm','gc_tools_needed'=>"Schraubendreher V.A.G 1624\nDemontagekeil 3409"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Handtuchhalter','Nasszelle']],

['title'=>'Hochgesetzte Bremsleuchte aus- und einbauen','excerpt'=>'Aus- und Einbau der dritten Bremsleuchte am Dach. Beim Grand California muss der hintere Dachstaukasten vorher ausgebaut werden.','content'=>'
<h2>Werkzeug</h2><ul><li>Arbeitsplattform</li><li>Drehmoment-Schraubendreher VAS 6494</li></ul>
<h2>Vorarbeit</h2><ol><li>Zündung und alle Verbraucher ausschalten, Schlüssel abziehen</li><li>Lichtschalter auf Null</li><li>Beim Grand California: hinteren Dachstaukasten ausbauen</li></ol>
<h2>Ausbau</h2><ol><li>Schrauben lösen</li><li>Elektrische Stecker trennen</li><li>Bremsleuchte an den Metallklammern ausclipsen</li><li>Bremsleuchte abnehmen</li></ol>
<h2>Einbau</h2><p>Einbau in umgekehrter Reihenfolge. Funktion nach dem Einbau prüfen.</p>',
'meta'=>['gc_location'=>'Dach hinten','gc_tools_needed'=>"Arbeitsplattform\nSchraubendreher VAS 6494"],'categories'=>['Reparaturanleitungen','Aus- und Einbau','Elektrik'],'models'=>$gc,'years'=>$ay,'components'=>['Bremsleuchte','Dachstaukasten']],

['title'=>'Hochraumdach (Kunststoff) abdichten — Kurzer Radstand','excerpt'=>'Anleitung zum Abdichten des GFK-Hochraumdachs am Grand California 600 (kurzer Radstand).','content'=>'
<h2>Werkzeug</h2><ul><li>Druckluftpistole V.A.G 1761/1</li></ul>
<h2>Ablauf</h2><ol><li>Klebeflächen mit Reinigungslösung reinigen (Ablüftzeit beachten)</li><li>Abdichtbereich mit handelsüblichem Klebeband abkleben</li><li>1K-Montageklebstoff auftragen (Klebstoffbreite: 12 mm)</li><li>Trocken- und Verarbeitungszeit des Klebstoffs beachten</li><li>Überschüssigen Klebstoff entfernen</li></ol>
<p>Die Abdichtung erfolgt in drei Abschnitten (A-A, B-B, C-C) mit jeweils 12 mm Klebstoffbreite.</p>',
'meta'=>['gc_location'=>'Dach','gc_tools_needed'=>'Druckluftpistole V.A.G 1761/1'],'categories'=>['Reparaturanleitungen','Wartung'],'models'=>['Grand California 600'],'years'=>$ay,'components'=>['Hochraumdach','GFK-Dach','Abdichtung']],

['title'=>'Hochraumdach (Kunststoff) abdichten — Langer Radstand','excerpt'=>'Anleitung zum Abdichten des GFK-Hochraumdachs am Grand California 680 (langer Radstand). Abweichende Maße gegenüber dem kurzen Radstand.','content'=>'
<h2>Werkzeug</h2><ul><li>Druckluftpistole V.A.G 1761/1</li></ul>
<h2>Ablauf</h2><ol><li>Klebeflächen mit Reinigungslösung reinigen (Ablüftzeit beachten)</li><li>Abdichtbereich mit handelsüblichem Klebeband abkleben</li><li>1K-Montageklebstoff auftragen</li><li>Trocken- und Verarbeitungszeit beachten</li><li>Überschüssigen Klebstoff entfernen</li></ol>
<h2>Maße (abweichend vom kurzen Radstand)</h2><table><thead><tr><th>Abschnitt</th><th>Breite a</th><th>Höhe b</th></tr></thead><tbody><tr><td>A-A / B-B</td><td>6 mm</td><td>8 mm</td></tr><tr><td>C-C</td><td>8 mm</td><td>—</td></tr></tbody></table>',
'meta'=>['gc_location'=>'Dach','gc_tools_needed'=>'Druckluftpistole V.A.G 1761/1'],'categories'=>['Reparaturanleitungen','Wartung'],'models'=>['Grand California 680'],'years'=>$ay,'components'=>['Hochraumdach','GFK-Dach','Abdichtung']],

['title'=>'Hochraumdach (Kunststoff) komplett ersetzen','excerpt'=>'Komplettersatz des GFK-Hochraumdachs. Aufwendige Arbeit die mindestens 2 Personen, 6 Saugheber und eine Karosseriesäge erfordert.','content'=>'
<h2>Werkzeug (Auswahl)</h2><ul><li>Arbeitsplattform</li><li>Keil T10357</li><li>6x Saugheber V.A.G 1344</li><li>Druckluftpistole V.A.G 1761/1</li><li>Absauganlage VAS 6572/2</li><li>Hebegurte</li><li>Karosseriesäge</li></ul>
<h2>Ausbau (Kurzfassung)</h2><ol><li>Batterien abklemmen, Batteriehauptschalter E74 auf Off</li><li>Alle Dachanbauteile ausbauen (Klimaanlage, Markise, Dachstaukästen, Solarzellen, Antennen, Dachhauben, Bremsleuchte etc.)</li><li>Kabelbefestigungen dokumentieren (Fotos!)</li><li>Kabelstränge vom Hochraumdach lösen</li><li>Schrauben umlaufend herausdrehen</li><li>6 Saugheber aufsetzen (Gewicht gleichmäßig verteilen)</li><li>Trennschnitt mit Karosseriesäge, danach Klebverbindung mit Keil T10357 lösen</li><li><strong>Mit mindestens 2 Personen</strong> das Dach abnehmen</li></ol>
<h2>Einbau (Kurzfassung)</h2><ol><li>Falls Klebeflansch länger als 6 Stunden offen lag: Aktivator auftragen</li><li>Neues Dach reinigen, 6 Saugheber aufsetzen</li><li>Dach mit Hebegurten aufsetzen, Position markieren</li><li>Primer auftragen, Kleberaupe auftragen (Dreiecksform, 15 mm Höhe, 10 mm Breite)</li><li>Schrauben 1–10 gleichmäßig von vorn nach hinten anziehen</li></ol>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Dachschrauben</td><td><strong>20 Nm</strong></td></tr></tbody></table>
<div style="background:#fffbeb;border:1px solid #f6e05e;border-left:4px solid #ecc94b;border-radius:8px;padding:1rem;margin:1.5rem 0;"><strong style="color:#975a16;">Sicherheit</strong><p style="margin:0.25rem 0 0;font-size:0.9rem;color:#744210;">Schutzmaske, Schutzhandschuhe und Schutzbrille tragen! Staubpartikel beim Schneiden können Augen und Atemwege verletzen.</p></div>',
'meta'=>['gc_location'=>'Dach (komplett)','gc_torque_specs'=>'Dachschrauben: 20 Nm','gc_tools_needed'=>"6x Saugheber V.A.G 1344\nKarosseriesäge\nKeil T10357\nHebegurte\nAbsauganlage VAS 6572/2"],'categories'=>['Reparaturanleitungen','Aus- und Einbau','Karosserie'],'models'=>$gc,'years'=>$ay,'components'=>['Hochraumdach','GFK-Dach','Karosseriesäge','Saugheber']],

['title'=>'Jalousette (Fensterrollo) aus- und einbauen','excerpt'=>'Aus- und Einbau der Jalousette am Campingfenster. Holzleisten nach dem Ausbau auf Schäden prüfen.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li></ul>
<h2>Vorarbeiten</h2><ul><li>Betreffende Seitenverkleidung ausbauen</li><li>Ggf. Lattenrost hinten ausbauen</li><li>Bei langem Radstand: Stauschrank vor dem Fenstermodul ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben lösen</li><li>Jalousette abnehmen</li></ol>
<h2>Einbau</h2><p>In umgekehrter Reihenfolge. Holzleisten auf Beschädigungen prüfen — bei Bedarf ersetzen.</p>',
'meta'=>['gc_location'=>'Seitenwand, Campingfenster','gc_tools_needed'=>'Schraubendreher V.A.G 1624'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Jalousette','Fensterrollo','Campingfenster']],

['title'=>'Kappe (Markisenabdeckung) aus- und einbauen','excerpt'=>'Aus- und Einbau der Abdeckkappe an der Markise.','content'=>'
<h2>Werkzeug</h2><ul><li>Stehleiter VAS 6292/4</li><li>Drehmoment-Schraubendreher V.A.G 1624</li></ul>
<h2>Ausbau</h2><ol><li>Markise öffnen</li><li>Schraube herausdrehen</li><li>Kappe abnehmen</li></ol>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schraube</td><td><strong>1 Nm</strong> (bis Schraubenkopf bündig)</td></tr></tbody></table>',
'meta'=>['gc_location'=>'Markise','gc_torque_specs'=>'Schraube: 1 Nm','gc_tools_needed'=>"Stehleiter VAS 6292/4\nSchraubendreher V.A.G 1624"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Kappe','Markise']],

['title'=>'Kühlschrank (J699) aus- und einbauen','excerpt'=>'Aus- und Einbau des Kühlschranks J699. Aufwendige Arbeit — Klimakompressor muss mit ausgebaut werden. Zweite Person benötigt.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmomentschlüssel V.A.G 1783</li><li>Drehmoment-Schraubendreher VAS 6494</li></ul>
<h2>Ausbau</h2><ol><li>Blende lösen und entnehmen</li><li>Elektrische Stecker trennen, Leitungen auspinnen</li><li>Befestigungsschraube lösen, Scheibe abnehmen</li><li>Schublade unter der Spüle entnehmen</li><li>Kühlschranktür/Schublade entnehmen</li><li>Abdeckkappen entfernen, Schrauben von innen lösen</li><li>Kühlschrank entriegeln und aus dem Schrank ziehen</li><li>Abdeckung des Klimakompressors lösen</li><li>Klimakompressor mit Anbauteilen nach oben herausnehmen</li><li><strong>Mit zweiter Person</strong> Kühlschrank aus dem Fahrzeug nehmen</li></ol>
<h2>Drehmomente</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben Kühlschrank</td><td><strong>1 Nm</strong></td></tr><tr><td>Schrauben Kompressor-Aufnahme</td><td><strong>1 Nm</strong></td></tr><tr><td>Ankerschraube</td><td><strong>25 Nm</strong></td></tr><tr><td>Schubladenschienen</td><td><strong>1 Nm</strong></td></tr></tbody></table>
<div style="background:#fffbeb;border:1px solid #f6e05e;border-left:4px solid #ecc94b;border-radius:8px;padding:1rem;margin:1.5rem 0;"><strong style="color:#975a16;">Wichtig</strong><p style="margin:0.25rem 0 0;font-size:0.9rem;color:#744210;">Klimakompressor niemals an den Kältemittelleitungen anheben!</p></div>',
'meta'=>['gc_vw_code'=>'J699','gc_location'=>'Küche','gc_torque_specs'=>"Kühlschrank: 1 Nm\nKompressor: 1 Nm\nAnker: 25 Nm",'gc_tools_needed'=>"Drehmomentschlüssel V.A.G 1783\nSchraubendreher VAS 6494"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Kühlschrank J699','Klimakompressor','Schublade']],

['title'=>'Markise aus- und einbauen','excerpt'=>'Aus- und Einbau der Markise. Zweite Person erforderlich. Beim langen Radstand gibt es einen dritten Befestigungspunkt.','content'=>'
<h2>Werkzeug</h2><ul><li>Stehleiter VAS 6292/4</li><li>Drehmomentschlüssel V.A.G 1783</li></ul>
<h2>Ausbau</h2><ol><li>Markise öffnen</li><li>Abdeckung im Bereich der Schrauben abnehmen</li><li>Schrauben auf beiden Seiten herausdrehen (inkl. Unterlegscheibe und Federring)</li><li>Verdeck der Markise schließen</li><li><strong>Mit zweiter Person</strong> die Markise von den Konsolen abnehmen</li></ol>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Markisen-Schrauben</td><td><strong>7,5 Nm</strong></td></tr></tbody></table>
<p><strong>Hinweis:</strong> Beim Grand California 680 (langer Radstand) gibt es einen dritten Befestigungspunkt in der Mitte.</p>',
'meta'=>['gc_location'=>'Dach, Außen','gc_torque_specs'=>'Schrauben: 7,5 Nm','gc_tools_needed'=>"Stehleiter VAS 6292/4\nDrehmomentschlüssel V.A.G 1783"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Markise','Konsolen']],

['title'=>'Rahmen für Dachhaube Nasszelle aus- und einbauen','excerpt'=>'Aus- und Einbau des Rahmens der Nasszellen-Dachhaube. Nur für Fahrzeuge mit langem Radstand.','content'=>'
<h2>Werkzeug</h2><ul><li>Handkartuschenpistole V.A.G 1628</li><li>Elektro-Messer V.A.G 1561A</li></ul>
<h2>Ausbau</h2><ol><li>Dachhaube der Nasszelle ausbauen</li><li>Rahmen durch Trennen der Kleberaupe vom Dach lösen</li><li>Klebstoffreste zurückschneiden</li></ol>
<h2>Einbau</h2><ol><li>Kleberaupe auf der Dachinnenseite auftragen (3 mm hoch, 3 mm breit)</li><li>Rahmen auf die Kleberaupe aufsetzen</li><li>Rahmen mit 1,5 mm Abstand zur Innenkontur des Dachausschnitts positionieren</li><li>Fixieren (z.B. mit Klebeband) bis Klebstoff ausgehärtet ist</li></ol>
<p><strong>Hinweis:</strong> Nur für Fahrzeuge mit langem Radstand (Grand California 680).</p>',
'meta'=>['gc_location'=>'Dach, Nasszelle','gc_tools_needed'=>"Kartuschenpistole V.A.G 1628\nElektro-Messer V.A.G 1561A"],'categories'=>$cats,'models'=>['Grand California 680'],'years'=>$ay,'components'=>['Dachhaube-Rahmen','Nasszelle']],

['title'=>'Regenrinne mit LED-Beleuchtung aus- und einbauen','excerpt'=>'Aus- und Einbau der Regenrinne mit integrierter LED-Beleuchtung.','content'=>'
<h2>Werkzeug</h2><ul><li>Handkartuschenpistole V.A.G 1628</li><li>Demontagekeil 3409</li><li>Drehmoment-Schraubendreher VAS 6494</li></ul>
<h2>Ausbau</h2><ol><li>Kabelhalter öffnen</li><li>Gummitülle aus der Öffnung lösen</li><li>Schrauben herausdrehen</li><li>Regenrinne mit Demontagekeil 3409 lösen</li><li>Regenrinne abnehmen, dabei elektrischen Stecker trennen</li></ol>
<h2>Einbau</h2><ol><li>Klebefläche reinigen (Ablüftzeit beachten)</li><li>Kleberaupe (4 mm Breite) zwischen Regenrinne und Karosserie auftragen</li></ol>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben</td><td><strong>1,7 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Dach, Außen','gc_torque_specs'=>'Schrauben: 1,7 Nm','gc_tools_needed'=>"Kartuschenpistole V.A.G 1628\nDemontagekeil 3409\nSchraubendreher VAS 6494"],'categories'=>['Reparaturanleitungen','Aus- und Einbau','Karosserie'],'models'=>$gc,'years'=>$ay,'components'=>['Regenrinne','LED-Beleuchtung']],

['title'=>'Rückwand (Nasszelle) aus- und einbauen','excerpt'=>'Aus- und Einbau der Rückwand in der Nasszelle. Umfangreiche Vorarbeiten nötig — Spiegel, Schrank, Duschwanne und Handtuchhalter müssen vorher raus.','content'=>'
<h2>Werkzeug</h2><ul><li>Handkartuschenpistole V.A.G 1628</li></ul>
<h2>Vorarbeiten</h2><ul><li>Scharnier für Spiegel ausbauen</li><li>Schrank ausbauen</li><li>Dachrahmen ausbauen</li><li>Duschwanne ausbauen</li><li>Make-up-Spiegel W49 mit Halterung ausbauen</li><li>Handtuchhalter ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Umlaufende Abdichtung lösen und entfernen</li><li>Klebeverbindung trennen, Rückwand herausnehmen</li><li>Klebstoffreste vollständig entfernen</li></ol>
<h2>Einbau</h2><ol><li>Klebeflächen reinigen, Primer auftragen</li><li>1K-Scheibenklebstoff auftragen (Dreiecksraupe, 5 mm Höhe, 5 mm Breite)</li><li>Rückwand einsetzen und bis zur Trocknung fixieren</li><li>Von innen umlaufend mit Dichtmasse (D.476.MS1.A2) abdichten und glattstreichen</li></ol>',
'meta'=>['gc_location'=>'Nasszelle','gc_tools_needed'=>'Kartuschenpistole V.A.G 1628'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Rückwand','Nasszelle','Dichtmasse']],

['title'=>'Schrank (Nasszelle) aus- und einbauen','excerpt'=>'Aus- und Einbau des Einbauschranks in der Nasszelle. Toilette und Waschbecken müssen vorher ausgebaut werden.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher V.A.G 1624</li><li>Demontagekeil 3409</li><li>Handkartuschenpistole V.A.G 1628</li></ul>
<h2>Vorarbeiten</h2><ul><li>Toilette ausbauen</li><li>Waschbecken ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Schrauben lösen, Platte abnehmen</li><li>Weitere Schrauben lösen</li><li>Dichtmittel entfernen</li><li>Schrank herausnehmen</li></ol>
<h2>Einbau</h2><p>Dichtmasse (D.476.MS1.A2) auftragen und sauber glattstreichen.</p>
<h2>Drehmoment</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben Platte</td><td><strong>1 Nm</strong></td></tr><tr><td>Schrauben Schrank</td><td><strong>1 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Nasszelle','gc_torque_specs'=>"Platte: 1 Nm\nSchrank: 1 Nm",'gc_tools_needed'=>"Schraubendreher V.A.G 1624\nDemontagekeil 3409\nKartuschenpistole V.A.G 1628"],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Schrank','Nasszelle','Toilette','Waschbecken']],

['title'=>'Schutzgitter der Dachhaube aus- und einbauen','excerpt'=>'Aus- und Einbau des Schutzgitters an der Dachhaube. Befestigt mit 4 Spreiznieten.','content'=>'
<h2>Werkzeug</h2><ul><li>Spachtelset VAS 6845</li></ul>
<h2>Ausbau</h2><ol><li>Dachhaube öffnen</li><li>4 Spreizniete entriegeln</li><li>Spreizniete herausziehen und Schutzgitter entnehmen</li></ol>
<h2>Einbau</h2><p>In umgekehrter Reihenfolge. Spreizniete auf festen Sitz prüfen.</p>',
'meta'=>['gc_location'=>'Dachhaube','gc_tools_needed'=>'Spachtelset VAS 6845'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Schutzgitter','Dachhaube','Spreizniete']],

['title'=>'Seitenverkleidung Mitte links oben aus- und einbauen','excerpt'=>'Aus- und Einbau der oberen linken Seitenverkleidung im Mittelbereich. Keine Schrauben — nur Clips und Klett.','content'=>'
<h2>Werkzeug</h2><ul><li>Demontagekeil 3409</li></ul>
<h2>Vorarbeit</h2><ul><li>Ggf. Tisch entnehmen</li><li>Verkleidung B-Säule Mitte innen ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Verkleidung lösen</li><li>Verkleidung aus der oberen Verkleidung nach unten herausziehen</li></ol>
<h2>Einbau</h2><p>In umgekehrter Reihenfolge. Bei Bedarf Klettband erneuern.</p>',
'meta'=>['gc_location'=>'Mittelbereich links','gc_tools_needed'=>'Demontagekeil 3409'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Seitenverkleidung','Mittelbereich']],

['title'=>'Seitenverkleidung Mitte links unten aus- und einbauen','excerpt'=>'Aus- und Einbau der unteren linken Seitenverkleidung. Zahlreiche elektrische Komponenten müssen vorher getrennt werden.','content'=>'
<h2>Werkzeug</h2><ul><li>Drehmoment-Schraubendreher VAS 6494</li></ul>
<h2>Vorarbeiten</h2><ul><li>Sitzbank ausbauen</li><li>Obere Seitenverkleidung Mitte links ausbauen</li></ul>
<h2>Ausbau</h2><ol><li>Schellen lösen und Schläuche abziehen</li><li>12V-Steckdose mit Antennenanschluss UX6 ausbauen</li><li>USB-Ladesteckdose 3 (U123) ausbauen</li><li>Ladegerät für mobile Endgeräte (J1146) ausbauen</li><li>Stecker vom Schalter Ausstellfenster links (E140) trennen</li><li>Stecker vom Temperaturfühler (G18) trennen</li><li>Alle Leitungen durch die Öffnung der Verkleidung führen</li><li>Schrauben lösen</li><li>Verkleidung herausnehmen</li></ol>
<h2>Drehmomente</h2><table><thead><tr><th>Bauteil</th><th>Drehmoment</th></tr></thead><tbody><tr><td>Schrauben oben</td><td><strong>1 Nm</strong></td></tr><tr><td>Schrauben unten</td><td><strong>1 Nm</strong></td></tr><tr><td>Haltewinkel</td><td><strong>1 Nm</strong></td></tr></tbody></table>',
'meta'=>['gc_location'=>'Mittelbereich links unten','gc_torque_specs'=>"Alle Schrauben: 1 Nm",'gc_tools_needed'=>'Schraubendreher VAS 6494'],'categories'=>$cats,'models'=>$gc,'years'=>$ay,'components'=>['Seitenverkleidung','U123 USB','E140 Ausstellfenster','G18 Temperaturfühler','J1146 Ladegerät']],

        ];
    }
}

add_action('admin_init',function(){
    if(isset($_GET['gc_import_batch2b'])&&current_user_can('manage_options')){
        $r=GC_Batch2b_Import::run();
        add_action('admin_notices',function()use($r){echo '<div class="notice notice-success"><p>'.esc_html($r).'</p></div>';});
    }
});
