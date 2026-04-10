<?php
/**
 * Batch 1 Part 2: Weitere TPIs für GrandCali Technik-DB
 *
 * Aufruf: /wp-admin/?gc_import_batch1b=1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GC_Batch1b_Import {

    public static function run() {
        if ( get_option( 'gc_batch1b_imported' ) ) {
            return 'Batch 1b wurde bereits importiert.';
        }

        $articles = self::get_articles();
        $count = 0;

        foreach ( $articles as $article ) {
            $post_id = wp_insert_post( [
                'post_type'    => 'gc_article',
                'post_title'   => $article['title'],
                'post_content' => $article['content'],
                'post_excerpt' => $article['excerpt'],
                'post_status'  => 'publish',
            ] );

            if ( is_wp_error( $post_id ) ) { continue; }

            if ( ! empty( $article['meta'] ) ) {
                foreach ( $article['meta'] as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }
            }
            if ( ! empty( $article['categories'] ) ) {
                wp_set_object_terms( $post_id, $article['categories'], 'gc_category' );
            }
            if ( ! empty( $article['models'] ) ) {
                wp_set_object_terms( $post_id, $article['models'], 'gc_model' );
            }
            if ( ! empty( $article['years'] ) ) {
                wp_set_object_terms( $post_id, $article['years'], 'gc_model_year' );
            }
            if ( ! empty( $article['components'] ) ) {
                wp_set_object_terms( $post_id, $article['components'], 'gc_component' );
            }
            $count++;
        }

        update_option( 'gc_batch1b_imported', true );
        return sprintf( '%d Artikel (Batch 1b: weitere TPIs) wurden importiert.', $count );
    }

    private static function get_articles() {
        return [

            // =============================================================
            // TPI 2076980/1 — Türgriff Nasszelle fällt ab
            // =============================================================
            [
                'title'   => 'Türgriff der Nasszelle fällt ab oder sitzt lose (TPI 2076980)',
                'excerpt' => 'Der Türgriff am Bad des Grand California fällt ab oder lässt sich leicht herausziehen? Die Vierkant-Aufnahme muss nachbearbeitet werden. Ab MJ2025 ist ein optimiertes Bauteil verbaut.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Der Türgriff der Nasszelle (Bad) am Grand California kann sich lösen und abfallen. Betroffen sind Fahrzeuge aller Modelljahre vor dem Facelift.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Alle Modelljahre vor MJ2025</li>
<li>Ab MJ2025 ist ein optimierter Vierkant verbaut</li>
</ul>

<h2>Ursache</h2>
<p>Unzureichende Toleranzkompensation am Vierkant des Griffbolzens. Der Griff hat zu viel Spiel und arbeitet sich mit der Zeit heraus.</p>

<h2>Lösung</h2>
<ol>
<li>Vierkant-Bolzen an allen vier Außenseiten um mindestens 1 mm abfeilen</li>
<li>Korrosionsschutz auftragen (1K Wash Primer 4085, Teile-Nr. LVM.044.007.A2)</li>
<li>Madenschrauben beidseitig mit Schraubensicherung (Loctite 262, Teile-Nr. D.197.300.A2) sichern</li>
<li>Griff wieder montieren</li>
</ol>

<h2>Teilenummern</h2>
<table>
<thead><tr><th>Teil</th><th>Nummer</th></tr></thead>
<tbody>
<tr><td>Schraubensicherung Loctite 262</td><td>D.197.300.A2</td></tr>
<tr><td>1K Wash Primer 4085</td><td>LVM.044.007.A2</td></tr>
</tbody>
</table>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2076980/1', 'gc_location' => 'Nasszelle, Türgriff' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Türgriff Nasszelle', 'Vierkant-Bolzen', 'Badtür' ],
            ],

            // =============================================================
            // TPI 2077468/1 — Serviceklappe Toilette undicht
            // =============================================================
            [
                'title'   => 'Serviceklappe der Toilette undicht — Holzrahmen quillt auf (TPI 2077468)',
                'excerpt' => 'Die Serviceklappe am Heck für die Toilettenkassette ist undicht? Wasser dringt ein und der Holzrahmen quillt auf. Lösung: Neu verkleben mit Sikaflex 221.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Die Serviceklappe für die Toilettenkassette am Heck des Grand California kann undicht werden. Durch eindringendes Wasser quillt der Holzrahmen der Klappe auf und beginnt zu faulen.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Verschiedene Modelljahre</li>
<li>Serienfix ab KW 06/2025 (ab FIN WV1ZZZSY1S9042279) — Umstellung auf Sika-Klebstoff</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Feuchtigkeit an der Serviceklappe</li>
<li>Holzrahmen sichtbar aufgequollen oder weich</li>
<li>Wasser im Bereich des Toilettenfachs</li>
</ul>

<h2>Ursache</h2>
<p>Der original verwendete Klebstoff dichtet nicht ausreichend ab. Ab Werk wurde auf Sikaflex 221 umgestellt.</p>

<h2>Lösung</h2>
<ol>
<li>Serviceklappe ausbauen</li>
<li>Alte Klebereste vollständig entfernen</li>
<li>Primer 217 (Sika) auf die Klebeflächen auftragen</li>
<li>Sikaflex 221 (schwarz) als Kleberaupe auftragen: 5 mm Höhe, 5 mm Breite, ca. 1450 mm Länge</li>
<li>Klappe einsetzen und mit Klebeband (TESA 4657) fixieren bis der Klebstoff ausgehärtet ist</li>
<li>Falls der Holzrahmen beschädigt ist: Rahmen tauschen (7C4 068 155 A)</li>
<li>Falls die Klappe selbst beschädigt ist: Klappe tauschen (7C4 810 001 A)</li>
</ol>

<h2>Teilenummern</h2>
<table>
<thead><tr><th>Teil</th><th>Nummer</th></tr></thead>
<tbody>
<tr><td>Holzrahmen</td><td>7C4 068 155 A</td></tr>
<tr><td>Serviceklappe</td><td>7C4 810 001 A</td></tr>
<tr><td>Sikaflex 221 schwarz</td><td>Freier Handel</td></tr>
<tr><td>Primer 217 Sika</td><td>Freier Handel</td></tr>
<tr><td>Klebeband TESA 4657 (30–50 mm)</td><td>Freier Handel</td></tr>
</tbody>
</table>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2077468/1', 'gc_location' => 'Heck, Serviceklappe Toilette' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Serviceklappe', 'Toilettenfach', 'Holzrahmen', 'Sikaflex 221' ],
            ],

            // =============================================================
            // TPI 2075254/1 — Wassereintritt im Motorsteuergerät-Stecker
            // =============================================================
            [
                'title'   => 'Wassereintritt im Stecker des Motorsteuergeräts — Diverse Warnmeldungen (TPI 2075254)',
                'excerpt' => 'Verschiedene Warnmeldungen im Kombiinstrument ohne erkennbaren Grund? Feuchtigkeit kann über eine mangelhafte Kabelabdichtung in den Motorsteuergerät-Stecker eindringen.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Durch eine unzureichend abgedichtete Kabelverbindung (Knotenpunkt 179) kann Feuchtigkeit über die Masseleitungen in den Stecker des Motorsteuergeräts gelangen. Dies führt zu diversen Fehlermeldungen.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California und Crafter</li>
<li>Verschiedene Modelljahre</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Verschiedene Warnmeldungen im Kombiinstrument</li>
<li>Feuchtigkeit am Stecker T91a (Pin 1, 2 und 4)</li>
<li>Diverse Fehlerspeichereinträge</li>
</ul>

<h2>Ursache</h2>
<p>Die Abdichtung der Spleißverbindung (Knotenpunkt 179) im Kabelbaum nahe der linken A-Säule ist fehlerhaft. Feuchtigkeit kriecht über die Masseleitungen in den Steuergerätestecker.</p>

<h2>Lösung</h2>
<ol>
<li>Kabelbaum im Bereich der linken A-Säule öffnen</li>
<li>Knotenpunkt 179 freilegen</li>
<li>Alten Schrumpfschlauch entfernen</li>
<li>Butylringe auf jede einzelne Ader aufbringen (Butylschnur AKL 450 005 05)</li>
<li>Lücken zwischen den Adern mit Butyl abdichten</li>
<li>Neuen Schrumpfschlauch aufziehen (19 mm Durchmesser, 80–120 mm Länge, Teile-Nr. N 106 736 01)</li>
<li>Schrumpfschlauch erwärmen und Dichtigkeit prüfen</li>
<li>Bei Bedarf: Steuergerät und Steckergehäuse tauschen</li>
</ol>

<h2>Teilenummern</h2>
<table>
<thead><tr><th>Teil</th><th>Nummer</th></tr></thead>
<tbody>
<tr><td>Butylschnur</td><td>AKL 450 005 05</td></tr>
<tr><td>Schrumpfschlauch 19 mm</td><td>N 106 736 01</td></tr>
</tbody>
</table>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2075254/1', 'gc_location' => 'Linke A-Säule, Motorsteuergerät' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Elektrik & Elektronik' ],
                'models' => [ 'Grand California 600', 'Grand California 680', 'Crafter' ],
                'years' => [ '2022', '2023', '2024' ],
                'components' => [ 'Motorsteuergerät', 'Knotenpunkt 179', 'Kabelbaum', 'Stecker T91a' ],
            ],

            // =============================================================
            // TPI 2072536/2 — NTC-Sensor im Kühlschrank tauschen
            // =============================================================
            [
                'title'   => 'Kühlschrank kühlt zu stark — NTC-Temperatursensor tauschen (TPI 2072536)',
                'excerpt' => 'Lebensmittel frieren im Kühlschrank ein? Der NTC-Temperatursensor ist defekt und muss getauscht werden. Ausführliche Schritt-für-Schritt Anleitung.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Der Kühlschrank im Grand California kühlt zu stark und friert Lebensmittel ein, obwohl die Temperatur korrekt eingestellt ist. Ursache ist ein defekter NTC-Temperatursensor.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Alle Modelljahre</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Lebensmittel im Kühlschrank gefrieren</li>
<li>Kühlschrank kühlt deutlich zu stark</li>
<li>Temperaturanzeige weicht von der tatsächlichen Temperatur ab</li>
</ul>

<h2>Ursache</h2>
<p>Der NTC-Sensor (Temperatursensor) im Kühlschrank ist beschädigt und liefert falsche Temperaturwerte an die Steuerung.</p>

<h2>Lösung — Schritt für Schritt</h2>
<ol>
<li>Fahrzeug stromlos schalten</li>
<li>Kühlschrank (J699) nach Reparaturanleitung ausbauen</li>
<li>Schublade, Korb und Abtropfschale entfernen</li>
<li>Gefrierfach demontieren</li>
<li>Kabelbinder am alten Sensor lösen</li>
<li>Alten NTC-Sensor entfernen</li>
<li>Neuen NTC-Sensor einsetzen (Teile-Nr. <strong>7C4 919 378 A</strong>)</li>
<li>Kabeldurchführungen mit Originaldichtmasse abdichten</li>
<li>Alles in umgekehrter Reihenfolge wieder einbauen</li>
<li>Nach dem Einbau: NTC-Widerstandswerte gegen Temperaturkurve prüfen</li>
</ol>

<h2>Teilenummer</h2>
<p><strong>7C4 919 378 A</strong> — NTC-Temperatursensor (1 Stück benötigt)</p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2072536/2', 'gc_location' => 'Küche, Kühlschrank J699' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Kühlschrank J699', 'NTC-Sensor', 'Temperatursensor' ],
            ],

            // =============================================================
            // TPI 2078498/1 — Starterbatterie lädt nicht über Landstrom (MJ2025)
            // =============================================================
            [
                'title'   => 'Starterbatterie lädt nicht über Landstrom — Energiemanagement neu anlernen (TPI 2078498)',
                'excerpt' => 'Die Starterbatterie wird am Landstrom nicht geladen? Bei Grand California MJ2025 muss das Energiemanagement im Steuergerät für Sonderfunktionen neu angelernt werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Bei Grand California Fahrzeugen des Modelljahrs 2025 wird die Starterbatterie bei angeschlossenem Landstrom nicht geladen.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California MJ2025 (Facelift)</li>
<li>Serienfix ab FIN: WV1ZZZSYS9064432</li>
</ul>

<h2>Ursache</h2>
<p>Softwareabweichung im Steuergerät für Sonderfunktionen.</p>

<h2>Lösung</h2>
<ol>
<li>Am Diagnosetester die Messwerte für „Energiemanagement-Parallel Energienetztyp" aufrufen</li>
<li>Ist- und Soll-Werte vergleichen</li>
<li>Falls abweichend: SFD-Freigabe durchführen</li>
<li>Grundeinstellung „Speicherung Energiearchitektur" starten und abschließen</li>
<li>Werte erneut prüfen — müssen jetzt übereinstimmen</li>
<li>SFD wieder verriegeln</li>
</ol>

<h3>Hinweis</h3>
<p>Kein Teiletausch nötig — reine Software-Prozedur.</p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2078498/1', 'gc_location' => 'Steuergerät Sonderfunktionen' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Elektrik & Elektronik' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2025 (Facelift)' ],
                'components' => [ 'Starterbatterie', 'Landstrom', 'Energiemanagement', 'Steuergerät Sonderfunktionen' ],
            ],

            // =============================================================
            // TPI 2077522/1 — We Connect offline nach Werkstattbesuch
            // =============================================================
            [
                'title'   => 'Online-Dienste ohne Funktion nach Werkstattbesuch — We Connect Wiederherstellung (TPI 2077522)',
                'excerpt' => 'Alle Online-Dienste (We Connect) seit dem letzten Werkstattbesuch dauerhaft offline? Die App zeigt keine Verbindung und die SOS-LED leuchtet nicht grün? So wird das Problem gelöst.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Nach einem Werkstattbesuch, bei dem eine VKMS-Anpassung durchgeführt wurde (nach dem 02.03.2025), funktionieren alle Online-Dienste nicht mehr. Die Companion-App zeigt keine Verbindung zum Fahrzeug.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California (und andere Modelle mit Online-Diensten)</li>
<li>Letzter Werkstattbesuch nach dem 02.03.2025 mit VKMS-Anpassung</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Alle Online-Dienste dauerhaft offline</li>
<li>App zeigt keine Fahrzeugverbindung</li>
<li>SOS-LED im Dachhimmel leuchtet nicht grün</li>
<li>Fehlercode 33 in der VKMS-Anpassung</li>
</ul>

<h2>Ursache</h2>
<p>Softwareabweichung in der IT-Infrastruktur (serverseitig).</p>

<h2>Lösung</h2>
<ol>
<li>Erneute VKMS-Anpassung durchführen</li>
<li>Auf automatische VIN-Bereinigung in der IT-Infrastruktur warten (läuft zur vollen Stunde)</li>
<li>Nach der Bereinigung: Nochmals VKMS-Anpassung — alle Einheiten müssen „VKMS data OK" zeigen</li>
<li>Diagnose beenden, Fahrzeug verriegeln, 30 Minuten Bus-Ruhe abwarten</li>
<li>Falls noch nicht behoben: OCU-Reset mit Hard-Reset Option durchführen</li>
<li>SOS-LED prüfen — muss grün leuchten</li>
</ol>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2077522/1', 'gc_location' => 'Online-Dienste, Dachhimmel (SOS-LED)' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Elektrik & Elektronik' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'We Connect', 'Online-Dienste', 'VKMS', 'OCU', 'SOS-LED' ],
            ],

            // =============================================================
            // TPI 2074913/2 — Erneute Ölundichtigkeit Dichtflansch
            // =============================================================
            [
                'title'   => 'Erneute Ölundichtigkeit am vorderen Dichtflansch nach Teiletausch (TPI 2074913)',
                'excerpt' => 'Öl tritt erneut am Dichtflansch auf der Zahnriemenseite aus, obwohl das Teil bereits getauscht wurde? Die Montage muss exakt nach Vorgabe erfolgen.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Nach dem Tausch des vorderen Dichtflansches (Zahnriemenseite) tritt erneut Öl aus. Das Problem entsteht, wenn die Montage — insbesondere das Auftragen der Flüssigdichtung — nicht exakt nach Herstellervorgabe erfolgt ist.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California, Crafter</li>
<li>Modelljahre 2016 bis 2022</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Ölverlust am vorderen Dichtflansch (Zahnriemenseite)</li>
<li>Öl im Zahnriemenbereich</li>
<li>Dichtflansch an der Riemenscheibe undicht</li>
</ul>

<h2>Diagnose</h2>
<p>Zunächst prüfen ob das Öl tatsächlich vom Dichtflansch kommt. Mögliche andere Quellen:</p>
<ul>
<li>Nockenwellendichtring</li>
<li>Öleinfüllstutzen</li>
<li>Kurbelgehäuseentlüftung</li>
</ul>

<h2>Lösung</h2>
<p>Dichtflansch nach Reparaturanleitung tauschen. Dabei die Montagereihenfolge und den Umfang der Flüssigdichtung <strong>exakt einhalten</strong>.</p>
<p>Teilenummer Dichtflansch: <strong>04L 103 151 A</strong></p>

<h3>Wichtig für Gewährleistung</h3>
<p>Fotodokumentation des ausgebauten Dichtflansches ist Pflicht (Vorder-, Rück- und Unterseite).</p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2074913/2', 'gc_location' => 'Motor, Zahnriemenseite' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Motor & Antrieb' ],
                'models' => [ 'Grand California 600', 'Grand California 680', 'Crafter' ],
                'years' => [ '2019', '2020', '2021', '2022' ],
                'components' => [ 'Dichtflansch', 'Zahnriemen', 'Kurbelwelle', 'Flüssigdichtung' ],
            ],

            // =============================================================
            // TPI 2063139/4 — Anzeige "Zweitbatterie schwach"
            // =============================================================
            [
                'title'   => 'Falsche Anzeige „Zweitbatterie schwach" im Kombiinstrument (TPI 2063139)',
                'excerpt' => 'Die Meldung „Zweitbatterie schwach" erscheint obwohl die Batterie voll ist? Häufig wurde ein externes Ladegerät falsch angeschlossen. Softwareupdate und Neuanlernung lösen das Problem.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Im Kombiinstrument erscheint die Warnung „Zweitbatterie schwach" obwohl die Versorgungsbatterie ausreichend geladen ist. Alternativ zeigt das Camping-Bedienfeld eine unplausibel schnelle Entladung der Aufbaubatterie an.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Ab Modelljahr 2020</li>
<li>Fahrzeuge mit Ausstattung 8FE</li>
</ul>

<h2>Ursache</h2>
<p>Ein externes Ladegerät oder ein nachträglich installierter Wechselrichter wurde direkt an den Minus-Pol der Aufbaubatterie angeschlossen. Korrekt wäre der Anschluss an einen geeigneten Massepunkt. Durch den direkten Anschluss am Batterie-Minuspol wird das Batterie-Management-Modul umgangen und kann die Ladung nicht erkennen.</p>

<h2>Lösung</h2>
<ol>
<li><strong>Kunden informieren:</strong> Minus-Kabel externer Geräte immer an einen Massepunkt anschließen, nie direkt an den Batterie-Minuspol</li>
<li><strong>Softwareupdate:</strong> Steuergerät J608 auf Softwareversion 0503 aktualisieren (Maßnahmencode 3419)</li>
<li><strong>Batterien neu anlernen:</strong> Im Diagnosesystem einen falschen Ah-Wert eingeben, speichern, Zündung aus/ein, dann den korrekten Ah-Wert eingeben</li>
</ol>

<h3>Wichtiger Hinweis</h3>
<p>Externe Ladegeräte und Wechselrichter dürfen <strong>niemals</strong> direkt am Minuspol der Aufbaubatterie angeschlossen werden. Immer einen Fahrzeug-Massepunkt verwenden.</p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2063139/4', 'gc_location' => 'Technikschrank, Aufbaubatterie' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Elektrik & Elektronik' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Aufbaubatterie', 'Zweitbatterie', 'J608 Steuergerät', 'Batterie-Management', 'Wechselrichter' ],
            ],

            // =============================================================
            // TPI 2076232/1 — Sitzverkleidung Drehsitz lose
            // =============================================================
            [
                'title'   => 'Verkleidung am Drehsitz steht ab oder ist lose (TPI 2076232)',
                'excerpt' => 'Die Kunststoffverkleidung am Fahrer-Drehsitz steht ab und rutscht aus der Halterung? Die Sitzwanne muss gegen eine optimierte Version getauscht und mit einem Spreizniete gesichert werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Die Kunststoffverkleidung an der linken Außenseite des Fahrer-Drehsitzes sitzt lose und steht ab. In der oberen Sitzposition bildet sich ein Spalt zwischen Verkleidung und Sitzfläche.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Ab Modelljahr 2023</li>
</ul>

<h2>Ursache</h2>
<p>In der oberen Position der Sitzfläche entsteht ein Spalt, durch den die Verkleidung aus ihrer Aufnahme rutschen kann.</p>

<h2>Lösung</h2>
<ol>
<li>Vordersitz nach Reparaturanleitung ausbauen</li>
<li>Sitzwanne gegen optimierte Version tauschen</li>
<li>Neue Sitzverkleidung montieren</li>
<li>Mit Spreizniete in der vorgesehenen Bohrung sichern</li>
</ol>

<h2>Teilenummern</h2>
<table>
<thead><tr><th>Teil</th><th>Nummer</th></tr></thead>
<tbody>
<tr><td>Spreizniete</td><td>N 038 501 2</td></tr>
<tr><td>Sitzwanne links</td><td>2N0 881 105 DP</td></tr>
<tr><td>Sitzwanne rechts</td><td>2N0 881 106 DP</td></tr>
<tr><td>Sitzverkleidung links</td><td>7C0 881 317 K</td></tr>
<tr><td>Sitzverkleidung rechts</td><td>7C0 881 320 K</td></tr>
</tbody>
</table>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2076232/1', 'gc_location' => 'Fahrersitz, Drehsitz' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2023', '2024' ],
                'components' => [ 'Drehsitz', 'Sitzverkleidung', 'Sitzwanne', 'Fahrersitz' ],
            ],

            // =============================================================
            // TPI 2068430/2 — Dachverkleidung Mitte/hinten lose
            // =============================================================
            [
                'title'   => 'Dachverkleidung im Mittel-/Heckbereich löst sich (TPI 2068430)',
                'excerpt' => 'Die Dachverkleidung (Himmel) im Wohnbereich löst sich und hängt herunter? Die Verklebung muss mit Klettstreifen und Heißkleber erneuert werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Die Innen-Dachverkleidung (Dachhimmel) im mittleren und hinteren Bereich des Grand California löst sich und hängt herunter.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Diverse Modelljahre</li>
<li>Serienfix ab KW 33/2022 (neuer Heißkleber in der Produktion)</li>
</ul>

<h2>Ursache</h2>
<p>Die ursprüngliche Verklebung des Dachhimmels löst sich im Fahrbetrieb.</p>

<h2>Lösung</h2>
<p>Neuverklebung mit Klettstreifen und Heißkleber. Der Ablauf hängt davon ab, ob das Dach eine Holzverstärkung hat oder nicht:</p>

<h3>Dach ohne Holzverstärkung (optimiert)</h3>
<ol>
<li>Alte Klettreste mit Kunststoffkeilen entfernen</li>
<li>Haftvermittler (3M Adhesion Promoter 4298UV) auftragen</li>
<li>6 Minuten warten</li>
<li>Klettstreifen direkt auf die Dachprägung kleben</li>
</ol>

<h3>Dach mit Holzverstärkung (Original)</h3>
<ol>
<li>Heißkleber (Würth 202) auf den Klettstreifen auftragen</li>
<li>Klettstreifen auf die Holzverstärkung drücken</li>
<li>Gegenstück mit Heißkleber auf den Himmel kleben</li>
<li>Zusammendrücken</li>
</ol>

<h2>Benötigtes Material</h2>
<table>
<thead><tr><th>Material</th><th>Nummer</th></tr></thead>
<tbody>
<tr><td>Heißklebestick Würth 202</td><td>AKL438C00</td></tr>
<tr><td>Klettstreifen 25 mm (2x 40 cm)</td><td>7C4892007</td></tr>
<tr><td>3M Haftvermittler 4298UV</td><td>D 366 PR1 A1</td></tr>
<tr><td>Schaumstoffapplikator</td><td>D 009 500 25</td></tr>
<tr><td>Kunststoffkeil-Set</td><td>VAS 852 015</td></tr>
</tbody>
</table>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2068430/2', 'gc_location' => 'Innenraum, Dachhimmel Mitte/hinten' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022' ],
                'components' => [ 'Dachhimmel', 'Dachverkleidung', 'Klettstreifen', 'Heißkleber' ],
            ],

            // =============================================================
            // TPI 2071455/3 — AGR-Kühler Effizienzdiagnose
            // =============================================================
            [
                'title'   => 'AGR-Kühler: Trennblech löst sich — Effizienzdiagnose durchführen (TPI 2071455)',
                'excerpt' => 'Das Trennblech im Hochdruck-AGR-Kühler kann sich während der Fahrt lösen. Die Diagnose erfolgt über ein spezielles Testprogramm. In den meisten Fällen treten keine spürbaren Symptome auf.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Das Luft-/Trennblech im Hochdruck-AGR-Kühler (Abgasrückführung) kann sich während des Fahrbetriebs lösen. Das Problem verursacht in der Regel keine spürbaren Symptome oder Fehlercodes.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California und Crafter</li>
<li>Modelljahre 2021 bis 2024</li>
<li>Diverse Motorvarianten (DM*, DN*, DRE*)</li>
<li>Serienfix ab Q2/2024 (optimierte Materialstärke und Geometrie des Trennblechs)</li>
</ul>

<h2>Symptome</h2>
<p>Keine Kundensymptome oder Fehlercodes. Die geführte Fehlersuche leitet auf diese Prüfung.</p>

<h2>Diagnose</h2>
<p>Testprogramm „Kühler für Abgasrückführung prüfen" verwenden (verfügbar ab ODIS Release 2024.01.00).</p>

<h3>Ergebnis 1: OK</h3>
<p>Keine Maßnahme erforderlich.</p>

<h3>Ergebnis 2: Nicht OK (Trennblech fehlt)</h3>
<p>HD-AGR-Kühler nach Reparaturanleitung tauschen.</p>

<h3>Ergebnis 3: Nicht OK (Trennblech vorhanden)</h3>
<p>Weitere Prüfung gemäß Anhang. Falls defekt bestätigt: Tausch innerhalb der nächsten 2 Wochen bei einem geplanten Werkstattbesuch möglich.</p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2071455/3', 'gc_location' => 'Motor, Abgasrückführung' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Motor & Antrieb' ],
                'models' => [ 'Grand California 600', 'Grand California 680', 'Crafter' ],
                'years' => [ '2021', '2022', '2023', '2024' ],
                'components' => [ 'AGR-Kühler', 'Abgasrückführung', 'Trennblech', 'HD-AGR' ],
            ],

            // =============================================================
            // TPI 2071221/2 — Fliegengitter klappert
            // =============================================================
            [
                'title'   => 'Fliegengitter an der Schiebetür klappert während der Fahrt',
                'excerpt' => 'Das Fliegengitter an der Schiebetür vibriert und klappert im Fahrbetrieb? Das ist normal, wenn es nicht vollständig eingeschoben ist. Kein Reparaturbedarf.',
                'content' => '
<h2>Beobachtung</h2>
<p>Das Schiebetür-Fliegengitter am Grand California kann während der Fahrt klappern oder vibrieren. Das Geräusch tritt besonders auf Autobahnen und bei unebener Fahrbahn auf.</p>

<h2>Ursache</h2>
<p>Das Fliegengitter hat konstruktionsbedingt etwas Spiel in der Führungsschiene. Wenn es nicht vollständig bis zum Anschlag eingeschoben ist, kann es im Fahrbetrieb vibrieren.</p>

<h2>Lösung</h2>
<p><strong>Kein Reparaturbedarf.</strong> Vor jeder Fahrt sicherstellen, dass das Fliegengitter vollständig bis zum Anschlag eingeschoben ist. Korrekt positioniertes Fliegengitter klappert deutlich weniger.</p>

<h3>Hinweis</h3>
<p>Klappern durch ein nicht korrekt eingeschobenes Fliegengitter ist kein Garantiefall.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2071221)', 'gc_location' => 'Schiebetür' ],
                'categories' => [ 'Servicehinweise', 'Bekannte Probleme' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Fliegengitter', 'Schiebetür', 'Insektenschutz' ],
            ],

        ];
    }
}

// Admin-Trigger
add_action( 'admin_init', function () {
    if ( isset( $_GET['gc_import_batch1b'] ) && current_user_can( 'manage_options' ) ) {
        $result = GC_Batch1b_Import::run();
        add_action( 'admin_notices', function () use ( $result ) {
            echo '<div class="notice notice-success"><p>' . esc_html( $result ) . '</p></div>';
        } );
    }
} );
