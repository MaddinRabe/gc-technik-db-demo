<?php
/**
 * Batch 1 Import: TPIs + Servicehinweise für GrandCali Technik-DB
 *
 * Aufruf: /wp-admin/?gc_import_batch1=1
 *
 * WICHTIG: Alle Inhalte sind eigenständig formuliert. Keine 1:1 Übernahme.
 * Technische Bezeichnungen (Bauteilcodes, Teilenummern) bleiben korrekt.
 * Kein Verweis auf ETKA, ERWIN oder andere geschützte Systeme.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GC_Batch1_Import {

    public static function run() {
        if ( get_option( 'gc_batch1_imported' ) ) {
            return 'Batch 1 wurde bereits importiert.';
        }

        $articles = self::get_tpi_articles();
        $count = 0;

        foreach ( $articles as $article ) {
            $post_id = wp_insert_post( [
                'post_type'    => 'gc_article',
                'post_title'   => $article['title'],
                'post_content' => $article['content'],
                'post_excerpt' => $article['excerpt'],
                'post_status'  => 'publish',
            ] );

            if ( is_wp_error( $post_id ) ) {
                continue;
            }

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

        update_option( 'gc_batch1_imported', true );
        return sprintf( '%d Artikel (Batch 1: TPIs + Servicehinweise) wurden importiert.', $count );
    }

    private static function get_tpi_articles() {
        return [

            // =============================================================
            // TPI 2073180/4 — Kühlmittelverlust durch undichtes T-Stück
            // =============================================================
            [
                'title'   => 'Kühlmittelverlust durch undichtes T-Stück im Kühlmittelschlauch (TPI 2073180)',
                'excerpt' => 'Kühlmittelverlust am Grand California Facelift (MJ2025): Das T-Stück im Kühlmittelschlauch zum Ausgleichsbehälter kann undicht werden. Ursache ist ein klemmendes Rückschlagventil in der Kühlmittelleitung.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Bei einigen Grand California Modellen ab Modelljahr 2025 (Facelift) mit DMZD-Motor kann es zu Kühlmittelverlust kommen. Betroffen ist das T-Stück im Kühlmittelschlauch, das die Verbindung zum Ausgleichsbehälter herstellt.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680 (Facelift)</li>
<li>Modelljahr 2025</li>
<li>Motor: DMZD</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Sichtbarer Kühlmittelverlust</li>
<li>Kühlmittelstand im Ausgleichsbehälter sinkt</li>
<li>Ggf. Warnmeldung im Kombiinstrument</li>
</ul>

<h2>Ursache</h2>
<p>Das Rückschlagventil in der Kühlmittelleitung (Teile-Nr. 7C0 121 400 A oder 7C0 121 481 A) klemmt dauerhaft. Dadurch baut sich ein erhöhter Druck auf, der zur Undichtigkeit am T-Stück führt.</p>

<h2>Lösung</h2>
<p>Folgende Teile müssen getauscht werden:</p>
<ol>
<li>Kühlmittelschlauch mit T-Stück erneuern</li>
<li>Kühlmittelleitung ersetzen</li>
<li>Entlüftungsschlauch tauschen</li>
<li>AGR-Kühler mit Hochdruck-Steuerventil erneuern</li>
<li>Kühlsystem nach Reparatur entlüften</li>
</ol>

<h2>Relevante Teilenummern</h2>
<table>
<thead>
<tr><th>Teil</th><th>Nummer</th><th>Hinweis</th></tr>
</thead>
<tbody>
<tr><td>Kühlmittelschlauch mit T-Stück</td><td>7C1 121 157 A</td><td>Linkslenker</td></tr>
<tr><td>Kühlmittelschlauch mit T-Stück</td><td>7C2 121 157 A</td><td>Rechtslenker</td></tr>
<tr><td>Entlüftungsschlauch</td><td>7C0 121 177 / 178</td><td></td></tr>
<tr><td>Kühlmittelleitung</td><td>7C0 121 400 C / 481 B</td><td>Ersetzt die klemmenden Varianten</td></tr>
<tr><td>AGR-Kühler mit Steuerventil</td><td>04L 131 512 CM</td><td></td></tr>
</tbody>
</table>
',
                'meta' => [
                    'gc_vw_code' => 'TPI 2073180/4',
                    'gc_location' => 'Motorraum, Kühlmittelkreislauf',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Motor & Antrieb' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2025 (Facelift)' ],
                'components' => [ 'Kühlmittelschlauch', 'T-Stück', 'AGR-Kühler', 'Rückschlagventil' ],
            ],

            // =============================================================
            // TPI 2080537/1 — Klimaanlage ohne Funktion
            // =============================================================
            [
                'title'   => 'Klimaanlage ohne Funktion? Überlastschutz am Klimakompressor hat ausgelöst (TPI 2080537)',
                'excerpt' => 'Die Klimaanlage am Grand California MJ2025 kühlt nicht mehr? Möglicherweise hat der Überlastschutz (Drehmomentbegrenzer) am Kompressor ausgelöst. Das Kältemittelsystem muss dafür nicht geöffnet werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Bei Grand California Fahrzeugen ab Modelljahr 2025 mit DMZD-Motor kann die Klimaanlage plötzlich ausfallen. Die Ursache liegt nicht am Kältemittel oder am Kompressor selbst, sondern am Drehmomentbegrenzer (Überlastschutz), der fälschlicherweise auslöst.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680 (Facelift)</li>
<li>Modelljahr 2025 mit Ausstattung KH6</li>
<li>Motor: DMZD</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Klimaanlage kühlt nicht mehr</li>
<li>Kompressorwelle dreht frei (kein Widerstand spürbar)</li>
<li>Kein Kältemittelverlust</li>
</ul>

<h2>Ursache</h2>
<p>Hohe Klimaleistung erzeugt Drehmomentspitzen im Riementrieb. Diese lösen den Überlastschutz am Kompressor aus, obwohl der Kompressor selbst nicht blockiert ist. Es handelt sich also nicht um einen Defekt des Kompressors.</p>

<h2>Lösung</h2>
<p>Der Drehmomentbegrenzer muss getauscht werden. <strong>Das Kältemittelsystem muss dafür nicht evakuiert werden</strong> — die Reparatur erfolgt rein mechanisch.</p>

<h3>Reparaturablauf</h3>
<ol>
<li>Reparaturset für Überlastschutz bestellen (7E0.898.810)</li>
<li>Riemen abnehmen</li>
<li>Drehmomentbegrenzer am Kompressor tauschen</li>
<li>Riemen wieder aufziehen</li>
</ol>

<h3>Anzugsdrehmomente</h3>
<table>
<thead>
<tr><th>Bauteil</th><th>Drehmoment</th></tr>
</thead>
<tbody>
<tr><td>Mutter</td><td>8 Nm + 30° nachziehen</td></tr>
<tr><td>Schrauben</td><td>13,5 Nm</td></tr>
</tbody>
</table>

<h2>Teilenummer</h2>
<p><strong>7E0.898.810</strong> — Reparaturset für Überlastschutz (Drehmomentbegrenzer)</p>
',
                'meta' => [
                    'gc_vw_code'      => 'TPI 2080537/1',
                    'gc_location'     => 'Motorraum, Klimakompressor',
                    'gc_torque_specs' => "Mutter: 8 Nm + 30°\nSchrauben: 13,5 Nm",
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Heizung & Klima' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2025 (Facelift)' ],
                'components' => [ 'Klimakompressor', 'Drehmomentbegrenzer', 'Überlastschutz', 'Riementrieb' ],
            ],

            // =============================================================
            // TPI 2080644/1 — Fenstermodul links undicht
            // =============================================================
            [
                'title'   => 'Fenstermodul links undicht — Wassereintritt im Innenraum (TPI 2080644)',
                'excerpt' => 'Wassereintritt an der linken Seitenscheibe bzw. am Campingfenster? Das Fenstermodul links kann undicht sein und muss komplett getauscht werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Bei einigen Grand California Fahrzeugen tritt Wasser an der linken Seite ein — entweder über die Seitenscheibe oder das Campingfenster. Ursache ist ein undichtes Fenstermodul auf der Fahrerseite.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Verschiedene Modelljahre (dokumentiert ab MJ2022)</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Feuchtigkeit im Innenraum, besonders hinten links</li>
<li>Nasse Rücksitze oder Polster</li>
<li>Wasserflecken an der Seitenverkleidung</li>
<li>Campingfenster links hinten undicht</li>
</ul>

<h2>Lösung</h2>
<p>Das betroffene Fenstermodul links muss komplett getauscht werden. Eine Nachbesserung der Dichtung allein reicht in der Regel nicht aus.</p>

<h2>Teilenummern</h2>
<p>Je nach Produktionszeitraum:</p>
<ul>
<li><strong>7C4 845 211</strong></li>
<li><strong>7C4 845 211 A</strong></li>
<li><strong>7C4 845 211 B</strong></li>
</ul>
',
                'meta' => [
                    'gc_vw_code'  => 'TPI 2080644/1',
                    'gc_location' => 'Fahrerseite, Fenstermodul links',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2022', '2023', '2024' ],
                'components' => [ 'Fenstermodul links', 'Campingfenster', 'Seitenscheibe' ],
            ],

            // =============================================================
            // TPI 2065884/7 — MIB3 Infotainment Fehlfunktionen
            // =============================================================
            [
                'title'   => 'MIB3 Infotainment mit Fehlfunktionen — Softwareupdate auf Version 0889 (TPI 2065884)',
                'excerpt' => 'Apple CarPlay bricht ab, Rückfahrkamera zeigt schwarzes Bild, Navigation friert ein? Das MIB3 Infotainment-System im Grand California hat diverse Softwareprobleme. Ein Update auf Version 0889 behebt die meisten Fehler.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Das MIB3 Radio-Navigationssystem (Ausstattungscode 8AR) zeigt in verschiedenen Grand California Modellen ab MJ2022 eine Vielzahl von Fehlfunktionen. Der Fehlerspeicher enthält typischerweise den Eintrag B125C13.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Ab Modelljahr 2022</li>
<li>Nur Fahrzeuge mit Ausstattung 8AR (Radio-Navigationssystem)</li>
<li>Software-Versionen 07xx und 08xx</li>
</ul>

<h2>Mögliche Symptome (Auswahl)</h2>
<p>Die Symptome sind extrem vielfältig. Hier die häufigsten:</p>
<ul>
<li>Apple CarPlay trennt sich regelmäßig</li>
<li>Rückfahrkamera zeigt schwarzes Bild</li>
<li>Display friert ein oder flackert</li>
<li>Sprachsteuerung funktioniert nicht</li>
<li>Bluetooth-Verbindung bricht ab</li>
<li>Hotspot nicht verfügbar</li>
<li>Navigation verliert GPS-Signal</li>
<li>Lautstärke springt auf Maximum</li>
<li>System startet spontan neu</li>
<li>Spotify-Wiedergabe stoppt</li>
<li>Telefonkontakte werden gelöscht</li>
<li>Geschwindigkeitswarnung setzt sich zurück</li>
</ul>

<h2>Ursache</h2>
<p>Softwareabweichung im Infotainment-Steuergerät.</p>

<h2>Lösung</h2>
<h3>Schritt 1: Softwareupdate via USB-Stick</h3>
<ol>
<li>Vor dem Update: Werksreset des Infotainment-Systems durchführen</li>
<li>Update auf Software-Version <strong>0889</strong> per USB-Stick einspielen</li>
<li>Das Update über einen Diagnosetester konfigurieren</li>
</ol>

<h3>Wichtige Hinweise</h3>
<ul>
<li>SD Creator Tool wird benötigt (5H0.919.360.C)</li>
<li>Maßnahmencode 3700 für die Dokumentation verwenden</li>
<li>Ziel-Softwareversion: <strong>0889</strong></li>
</ul>
',
                'meta' => [
                    'gc_vw_code'  => 'TPI 2065884/7',
                    'gc_location' => 'Armaturenbrett, Infotainment-System',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Elektrik & Elektronik' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'MIB3 Infotainment', 'Radio-Navigation', 'Apple CarPlay', 'Rückfahrkamera', 'Bluetooth' ],
            ],

            // =============================================================
            // TPI 2080660/1 — Energiemanagement und Sicherungshalter
            // =============================================================
            [
                'title'   => 'Starterbatterie lädt nicht über Landstrom — Energiemanagement anpassen (Servicemaßnahme 91UD)',
                'excerpt' => 'Die Starterbatterie wird am Landstrom nicht geladen, oder die Versorgungsbatterien laden während der Fahrt nicht? Bei MJ2025 Fahrzeugen muss das Energiemanagement angepasst und ggf. der Sicherungshalter getauscht werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Bei bestimmten Grand California Fahrzeugen des Modelljahrs 2025 aus einem begrenzten Fertigungszeitraum kann es zu Ladeproblemen kommen. Die Starterbatterie wird am Landstrom nicht geladen und/oder die Versorgungsbatterien laden während der Fahrt nicht korrekt.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California Facelift (MJ 2025)</li>
<li>Motor: DMZD</li>
<li>Begrenzter Fertigungszeitraum (Servicemaßnahme mit Kundenansprache)</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Starterbatterie lädt nicht bei angeschlossenem Landstrom</li>
<li>Versorgungsbatterien laden nicht während der Fahrt</li>
<li>Anzeige „Zweitbatterie schwach" im Display</li>
</ul>

<h2>Ursache und Lösung</h2>
<p>Es gibt zwei mögliche Ursachen, die getrennt geprüft werden:</p>

<h3>Kriterium 1: Energiemanagement-Software</h3>
<p>Das Steuergerät für Sonderfunktionen muss angepasst werden:</p>
<ol>
<li>Ist- und Soll-Werte der Energiearchitektur vergleichen</li>
<li>SFD-Freigabe durchführen</li>
<li>Grundeinstellung „Speicherung Energiearchitektur" ausführen</li>
</ol>

<h3>Kriterium 2: Sicherungshalter (Potentialverteiler SH)</h3>
<p>Bei bestimmten Fahrzeugen muss der Sicherungshalter (Potentialverteiler SH) getauscht werden.</p>
<p>Teilenummer: <strong>7C0 937 517 AK</strong></p>
',
                'meta' => [
                    'gc_vw_code'  => 'Servicemaßnahme 91UD / TPI 2080660',
                    'gc_location' => 'Steuergeräte, Sicherungshalter',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Elektrik & Elektronik', 'Servicemaßnahmen & Rückrufe' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2025 (Facelift)' ],
                'components' => [ 'Energiemanagement', 'Sicherungshalter SH', 'Starterbatterie', 'Versorgungsbatterie', 'Landstrom' ],
            ],

            // =============================================================
            // TPI 2023230/33 — Starterbatterie entladen
            // =============================================================
            [
                'title'   => 'Starterbatterie entladen — Motor startet nicht (TPI 2023230)',
                'excerpt' => 'Motor startet nicht oder die Batterie ist leer? Umfassender Diagnose-Leitfaden für Batterieprobleme am Grand California. Von der Sichtprüfung bis zum Austausch.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Bei längerer Standzeit oder technischen Fehlern kann die Starterbatterie so weit entladen sein, dass der Motor nicht mehr startet. Dieser Leitfaden beschreibt die systematische Diagnose und Behebung.</p>

<h2>Betroffene Fahrzeuge</h2>
<p>Alle Grand California Modelle (600 und 680), alle Modelljahre.</p>

<h2>Mögliche Symptome</h2>
<ul>
<li>Motor startet nicht oder startet schlecht</li>
<li>Ruckeln während der Fahrt</li>
<li>Diverse Fehlermeldungen im Kombiinstrument</li>
<li>Batterie komplett entladen</li>
</ul>

<h2>Diagnoseschritte</h2>

<h3>1. Sichtprüfung der Batterie</h3>
<ul>
<li>Säurestandanzeige (ALI) prüfen: <strong>Wenn gelb → Batterie sofort tauschen, keinesfalls Starthilfe geben!</strong></li>
<li>Polklemmen auf festen Sitz und Korrosion prüfen</li>
<li>Batteriegehäuse auf Beschädigungen prüfen</li>
</ul>

<h3>2. Batterietest</h3>
<p>Mit geeignetem Testgerät oder Diagnosetester den Batteriezustand messen. Ladestrom maximal 55A.</p>

<h3>3. Ruhestrom- und Generatorprüfung</h3>
<p>Falls die Batterie nicht defekt ist: Ruhestromtest durchführen um parasitäre Verbraucher zu identifizieren. Zusätzlich die Ladeanlage (Generator) prüfen.</p>

<h2>Lösung</h2>
<ul>
<li><strong>Batterie defekt:</strong> Batterie nach Herstellervorgaben tauschen</li>
<li><strong>Batterie nicht defekt:</strong> Ruhestrom- und Generatorprüfung, Fehlerquelle beheben</li>
<li>Nach dem Tausch: Alle Fehlerspeicher löschen und neu auslesen</li>
<li>Bei Fahrzeugen mit Batterie-Diagnose-Manager (J367): Neue Batterie über das Diagnosesystem anlernen</li>
</ul>

<h3>Wichtig bei stehendem Fahrzeug</h3>
<p>Fahrzeuge die länger als 3 Wochen stehen, sollten an ein Erhaltungsladegerät angeschlossen werden. Besonders der Grand California mit seinen vielen Steuergeräten hat einen erhöhten Ruhestromverbrauch.</p>
',
                'meta' => [
                    'gc_vw_code'  => 'TPI 2023230/33',
                    'gc_location' => 'Motorraum, Batterie',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Elektrik & Elektronik' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Starterbatterie', 'Generator', 'Ruhestrom', 'Batterie-Diagnose-Manager J367' ],
            ],

            // =============================================================
            // TPI 2066033/2 — Zebra-Folierung löst sich
            // =============================================================
            [
                'title'   => 'Außenfolierung „Zebra-Design" — Blasenbildung und Ablösung (TPI 2066033)',
                'excerpt' => 'Die Zebra-Design Folierung am Grand California zeigt Blasen, löst sich oder wirft Falten? Die betroffenen Seitenfolien müssen komplett getauscht werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Bei Grand California Fahrzeugen mit der optionalen Zebra-Design Folierung kann es zu Blasenbildung, Ablösung oder Faltenbildung der Außenfolien kommen.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Ab Modelljahr 2021</li>
<li>Nur Fahrzeuge mit Zebra-Design Folierung</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Blasen unter der Folierung</li>
<li>Folierung löst sich von der Karosserie</li>
<li>Falten oder Wellen in der Folie</li>
</ul>

<h2>Lösung</h2>
<p>Alle Seitenfolien der betroffenen Seite müssen komplett erneuert werden. Eine Teilreparatur einzelner Blasen ist nicht dauerhaft und wird nicht empfohlen.</p>

<h3>Reparaturablauf</h3>
<ol>
<li>Innenverkleidung und Fenster im betroffenen Bereich demontieren</li>
<li>Fensterrahmen erwärmen (Heißluftgebläse)</li>
<li>Alte Folierung und Butylreste entfernen</li>
<li>Neue Folierung professionell aufbringen (idealerweise durch Folierer)</li>
<li>Fenster mit neuer Butylschnur (6mm, schwarz) und neuen Gummipuffern wieder einsetzen</li>
</ol>

<h3>Hinweis</h3>
<p>Sind nur die Hecktür-Folien betroffen, können diese einzeln getauscht werden.</p>

<h2>Relevante Teilenummern</h2>
<table>
<thead>
<tr><th>Teil</th><th>Nummer</th></tr>
</thead>
<tbody>
<tr><td>Abdeckfolien (diverse Positionen)</td><td>7C4.853.309.B bis 7C4.853.316.B</td></tr>
<tr><td>Gummipuffer (8 Stk. pro Fenster)</td><td>7H0.845.235</td></tr>
<tr><td>Butylschnur 6mm schwarz (10m)</td><td>Y7C0015334</td></tr>
</tbody>
</table>
',
                'meta' => [
                    'gc_vw_code'  => 'TPI 2066033/2',
                    'gc_location' => 'Außen, Seitenverkleidung',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2021', '2022', '2023', '2024' ],
                'components' => [ 'Zebra-Folierung', 'Außenfolie', 'Butylschnur', 'Fenstermodul' ],
            ],

            // =============================================================
            // TPI 2068161/2 — Klickgeräusch Gelenkwelle
            // =============================================================
            [
                'title'   => 'Klickgeräusch an der vorderen Gelenkwelle beim Anfahren mit Lenkeinschlag (TPI 2068161)',
                'excerpt' => 'Klicken aus dem Vorderwagen beim Anfahren mit eingeschlagener Lenkung? Die Antriebswelle kann durch hohe Drehmomentspitzen beim Lenken beschädigt werden. Softwareupdate + Gelenktausch lösen das Problem.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Beim Anfahren mit eingeschlagener Lenkung (z.B. beim Rangieren oder Ausparken) sind mehrfache Klickgeräusche aus dem Vorderwagen zu hören. Betroffen ist das radseitige Gleichlaufgelenk der Antriebswelle.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California (und Crafter) ab Modelljahr 2022</li>
<li>Fahrzeuge mit Allradantrieb (Ausstattung 4BH)</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Mehrfaches Klicken/Knacken beim Anfahren mit Lenkeinschlag</li>
<li>Geräusch kommt von vorn links oder rechts</li>
<li>Besonders auffällig beim Rangieren</li>
</ul>

<h2>Ursache</h2>
<p>Häufige Hochdrehmoment-Situationen bei gleichzeitigem Lenkeinschlag — z.B. starkes Beschleunigen über Bordsteinkanten — können das radseitige Gleichlaufgelenk der Antriebswelle beschädigen.</p>

<h2>Lösung</h2>
<ol>
<li><strong>Softwareupdate:</strong> Neue Motorsteuergeräte-Software aufspielen. Diese enthält einen Komponentenschutz, der das maximale Drehmoment abhängig vom Lenkwinkel beim Anfahren reduziert.</li>
<li><strong>Gelenktausch:</strong> Das betroffene äußere Gleichlaufgelenk der Antriebswelle muss nach Reparaturanleitung getauscht werden.</li>
</ol>

<h3>Hinweis</h3>
<p>Eine Audioaufnahme des Geräuschs sollte zur Dokumentation erstellt werden.</p>
',
                'meta' => [
                    'gc_vw_code'  => 'TPI 2068161/2',
                    'gc_location' => 'Vorderachse, Antriebswelle',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Fahrwerk & Lenkung' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2022', '2023', '2024' ],
                'components' => [ 'Antriebswelle', 'Gleichlaufgelenk', 'Gelenkwelle', 'Motorsteuergerät' ],
            ],

            // =============================================================
            // TPI 2072102/2 — Dometic Bedieneinheit Tasten ohne Funktion
            // =============================================================
            [
                'title'   => 'Dometic Zentralbedieneinheit: Drei Tasten ohne Funktion (TPI 2072102)',
                'excerpt' => 'Die rechten drei Tasten an der Dometic Camping-Bedieneinheit reagieren nicht? Ein Softwareupdate auf Version 0109 per SD-Karte behebt das Problem. Das Steuergerät muss nicht getauscht werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>An der Dometic Zentralbedieneinheit (ZBE) für die Campingausstattung sind die drei rechten Tasten ohne Funktion. Die Tasten reagieren nicht auf Berührung.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California ab Modelljahr 2022 (MJ2, Dometic ZBE)</li>
<li>Fahrzeuge vor FIN WV1 ZZZ SY 5 R 9 016 049</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Drei rechte Tasten am Camping-Bedienfeld reagieren nicht</li>
<li>Restliche Tasten funktionieren normal</li>
</ul>

<h2>Ursache</h2>
<p>Softwareabweichung in der Zentralbedieneinheit.</p>

<h2>Lösung</h2>
<p><strong>Wichtig: Das Dometic Steuergerät darf NICHT getauscht werden!</strong> Das Problem wird per Softwareupdate behoben.</p>

<h3>Update-Ablauf</h3>
<ol>
<li>SD Creator Tool verwenden (Teile-Nr. 3G8 919 360 N)</li>
<li>Software 0109 auf eine Micro-SD-Karte flashen (max. 2 GB)</li>
<li>Zentralbedieneinheit ausbauen</li>
<li>SD-Karte einsetzen und Gerät einschalten</li>
<li>Update bestätigen und abwarten</li>
<li>Nach dem Update: Batteriehauptschalter ausschalten</li>
<li>6-poligen blauen Stecker (T6cb) am Steuergerät J608 für 2 Minuten abziehen → Reset</li>
<li>Stecker wieder aufstecken, System prüfen</li>
</ol>
',
                'meta' => [
                    'gc_vw_code'  => 'TPI 2072102/2',
                    'gc_location' => 'Mittelbereich links, Bedieneinheit',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Elektrik & Elektronik' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2022', '2023', '2024' ],
                'components' => [ 'Dometic ZBE', 'Zentralbedieneinheit', 'Camping-Bedienfeld', 'J608 Steuergerät' ],
            ],

            // =============================================================
            // TPI 2068544/2 — Risse in Acrylglas-Fenstern
            // =============================================================
            [
                'title'   => 'Rissbildung in Campingfenstern und Dachhauben aus Acrylglas — Reinigungshinweis',
                'excerpt' => 'Risse in den Camping- oder Dachfenstern des Grand California? Aggressive Reinigungsmittel sind die häufigste Ursache. So reinigt man Acrylglas-Fenster richtig.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>An den Campingfenstern und Dachhauben aus Acrylglas (PMMA) können Risse entstehen, insbesondere im Bereich der Gummidichtung. Die Ursache ist fast immer die Verwendung ungeeigneter Reinigungsmittel.</p>

<h2>Betroffene Bauteile</h2>
<ul>
<li>Alle Kipp-/Campingfenster aus Acrylglas</li>
<li>Dachhauben (Dachlucken)</li>
<li>Alle Grand California Modelljahre</li>
</ul>

<h2>Ursache</h2>
<p>Aggressive oder ätzende Chemikalien in Reinigungsmitteln und Gummipflegeprodukten verursachen eine Versprödung des Acrylglases. Die Risse treten typischerweise im Kontaktbereich der Gummidichtung auf, da sich dort die Chemikalien ansammeln.</p>

<h2>Richtiges Reinigen von Acrylglas-Fenstern</h2>

<h3>Erlaubt</h3>
<ul>
<li>Baumwolltuch oder Mikrofasertuch</li>
<li>Reichlich klares Wasser</li>
<li>Für Gummipflege: ausschließlich Talkumpuder oder weiße Vaseline</li>
</ul>

<h3>Verboten — Niemals verwenden!</h3>
<ul>
<li>Allzweckreiniger</li>
<li>Glasreiniger</li>
<li>Brennspiritus</li>
<li>Terpentinersatz</li>
<li>Metallschwämme oder Scheuerpad</li>
<li>Alle chemisch aggressiven Reiniger</li>
</ul>

<h3>Wichtige Regeln</h3>
<ul>
<li>Immer mit viel Wasser reinigen, niemals trocken wischen</li>
<li>Gummidichtungen nur mit Talkum oder weißer Vaseline pflegen</li>
<li>Wenn Risse im Dichtungsbereich auftreten und das Fenster getauscht wird: <strong>Die Gummidichtung muss ebenfalls erneuert werden</strong></li>
</ul>

<h3>Hinweis</h3>
<p>Rissbildung durch falsche Reinigung ist kein Garantiefall. Dieser Artikel ist ein vorbeugender Hinweis zur richtigen Pflege.</p>
',
                'meta' => [
                    'gc_vw_code'  => 'Servicehinweis (TPI 2068544)',
                    'gc_location' => 'Campingfenster, Dachhauben',
                ],
                'categories' => [ 'Servicehinweise', 'Bekannte Probleme', 'Aufbau & Ausstattung' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Campingfenster', 'Dachhaube', 'Acrylglas', 'PMMA', 'Gummidichtung' ],
            ],

            // =============================================================
            // TPI 2069832/1 — Trittstufe schwergängig oder ohne Funktion
            // =============================================================
            [
                'title'   => 'Elektrische Trittstufe schwergängig oder ohne Funktion (TPI 2069832)',
                'excerpt' => 'Die elektrische Trittstufe am Grand California fährt nicht mehr aus oder bewegt sich nur schwer? Reinigung der Führungsschienen und ein Softwareupdate lösen das Problem in den meisten Fällen.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Die elektrische Trittstufe am Grand California kann im Laufe der Zeit schwergängig werden oder komplett ausfallen. Verschmutzung der Führungsschienen ist die häufigste Ursache.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Modelljahre 2019 bis 2023</li>
</ul>

<h2>Symptome</h2>
<ul>
<li>Trittstufe fährt nur noch langsam oder ruckelnd aus/ein</li>
<li>Trittstufe funktioniert gar nicht mehr</li>
<li>Mögliche Fehlerspeichereinträge im Steuergerät J608: B17D211, B17D212, B17D311, B17D312</li>
</ul>

<h2>Ursache</h2>
<p>Verschmutzung und Ablagerungen in den Führungsschienen erhöhen den Widerstand. Die Stromaufnahme beim Losbrechen steigt dadurch über den zulässigen Wert.</p>

<h2>Lösung</h2>
<ol>
<li><strong>Reinigung:</strong> Führungsschienen und Gehäuse der Trittstufe innen und außen gründlich reinigen</li>
<li><strong>Softwareupdate:</strong> Steuergerät J608 (Sonderfunktionen) auf Softwareversion 0503 aktualisieren (Maßnahmencode 3419)</li>
<li>Falls Software bereits auf 0503: Nur Softwarekonfiguration durchführen</li>
</ol>

<h3>Hinweis</h3>
<p>Ab Kalenderwoche 17/2023 wird Software 0503 ab Werk verbaut.</p>
',
                'meta' => [
                    'gc_vw_code'  => 'TPI 2069832/1',
                    'gc_location' => 'Einstieg, Schiebetür',
                ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2019', '2020', '2021', '2022', '2023' ],
                'components' => [ 'Trittstufe', 'J608 Steuergerät', 'Führungsschienen' ],
            ],

        ];
    }
}

// Admin-Trigger
add_action( 'admin_init', function () {
    if ( isset( $_GET['gc_import_batch1'] ) && current_user_can( 'manage_options' ) ) {
        $result = GC_Batch1_Import::run();
        add_action( 'admin_notices', function () use ( $result ) {
            echo '<div class="notice notice-success"><p>' . esc_html( $result ) . '</p></div>';
        } );
    }
} );
