<?php
/**
 * Batch 1 Part 3: Servicehinweise + Truma + weitere TPIs
 *
 * Aufruf: /wp-admin/?gc_import_batch1c=1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GC_Batch1c_Import {

    public static function run() {
        if ( get_option( 'gc_batch1c_imported' ) ) {
            return 'Batch 1c wurde bereits importiert.';
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
            if ( ! empty( $article['meta'] ) ) { foreach ( $article['meta'] as $k => $v ) { update_post_meta( $post_id, $k, $v ); } }
            if ( ! empty( $article['categories'] ) ) { wp_set_object_terms( $post_id, $article['categories'], 'gc_category' ); }
            if ( ! empty( $article['models'] ) ) { wp_set_object_terms( $post_id, $article['models'], 'gc_model' ); }
            if ( ! empty( $article['years'] ) ) { wp_set_object_terms( $post_id, $article['years'], 'gc_model_year' ); }
            if ( ! empty( $article['components'] ) ) { wp_set_object_terms( $post_id, $article['components'], 'gc_component' ); }
            $count++;
        }

        update_option( 'gc_batch1c_imported', true );
        return sprintf( '%d Artikel (Batch 1c) wurden importiert.', $count );
    }

    private static function get_articles() {
        return [

            // === TRUMA HEIZUNG (3 Fehlercodes + Abschaltung) ===

            [
                'title'   => 'Truma Heizung startet nicht — Fehlercode E515H: Verbrennungsluftmotor defekt',
                'excerpt' => 'Die Truma Heizung lässt sich nicht einschalten und zeigt den Fehlercode E515H? Der Verbrennungsluftmotor läuft außerhalb der Toleranz und muss getauscht werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Die Truma Combi-Heizung im Grand California startet nicht. Im Ereignisspeicher der Heizung findet sich der Eintrag <strong>E515H</strong>.</p>

<h2>Ursache</h2>
<p>Der Verbrennungsluftmotor im Dieselkamin dreht außerhalb der zulässigen Drehzahl-Toleranz. Das Bauteil muss getauscht werden.</p>

<h2>Lösung — Schritt für Schritt</h2>
<ol>
<li>Sitzauflage und Abdeckung der Sitzbank abnehmen</li>
<li>Schutzabdeckung am Dieselkamin entfernen</li>
<li>Verbrennungsluftmotor ausbauen (Verriegelungslasche beachten)</li>
<li>Anlageflächen gründlich reinigen</li>
<li>Neuen Motor einbauen — Dichtung prüfen</li>
</ol>

<h3>Anzugsdrehmoment</h3>
<p>Befestigungsschrauben: <strong>3 Nm</strong></p>

<h2>Teilenummer</h2>
<p><strong>7C4.820.059</strong> — Verbrennungsluftmotor</p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2074850/2 (E515H)', 'gc_location' => 'Unter Sitzbank, Dieselkamin', 'gc_torque_specs' => 'Schrauben: 3 Nm' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Heizung & Klima' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Truma Heizung', 'Verbrennungsluftmotor', 'Dieselkamin', 'E515H' ],
            ],

            [
                'title'   => 'Truma Heizung startet nicht — Fehlercode E133H: Kurzschluss im Verbrennungsluftmotor',
                'excerpt' => 'Truma Heizung ohne Funktion mit Fehlercode E133H? Kurzschluss im Verbrennungsluftmotor oder Kabelstrang. Motor tauschen.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Die Truma Heizung funktioniert nicht, Fehlercode <strong>E133H</strong> im Ereignisspeicher.</p>

<h2>Ursache</h2>
<p>Kurzschluss im Verbrennungsluftmotor im Dieselkamin oder im zugehörigen Kabelstrang.</p>

<h2>Lösung</h2>
<ol>
<li>Schutzdeckel am Dieselkamin abschrauben (4 Schrauben)</li>
<li>Verbrennungsluftmotor ausbauen (Verriegelungslasche beachten)</li>
<li>Anlageflächen reinigen, Dichtung prüfen</li>
<li>Neuen Motor einbauen — <strong>3 Nm</strong></li>
</ol>

<h2>Teilenummer</h2>
<p><strong>7C4.820.059</strong> — Verbrennungsluftmotor</p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2072685/3 (E133H)', 'gc_location' => 'Unter Sitzbank, Dieselkamin', 'gc_torque_specs' => 'Schrauben: 3 Nm' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Heizung & Klima' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Truma Heizung', 'Verbrennungsluftmotor', 'E133H', 'Kurzschluss' ],
            ],

            [
                'title'   => 'Truma Heizung startet nicht — Fehlercode E525H: Strom zu hoch',
                'excerpt' => 'Fehlercode E525H bei der Truma Heizung? Der Verbrennungsluftmotor zieht zu viel Strom und muss getauscht werden.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Die Truma Heizung startet nicht. Im Ereignisspeicher steht <strong>E525H</strong> — der Verbrennungsluftmotor zieht zu viel Strom.</p>

<h2>Lösung</h2>
<p>Identischer Ablauf wie bei E515H und E133H: Verbrennungsluftmotor ausbauen und ersetzen.</p>
<p>Teilenummer: <strong>7C4.820.059</strong> — Anzugsdrehmoment: <strong>3 Nm</strong></p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2075692/1 (E525H)', 'gc_location' => 'Unter Sitzbank, Dieselkamin' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Heizung & Klima' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Truma Heizung', 'Verbrennungsluftmotor', 'E525H' ],
            ],

            [
                'title'   => 'Truma Heizung schaltet ohne Grund ab — Softwareupdate auf SW0110',
                'excerpt' => 'Die Truma Heizung schaltet sich ohne erkennbare Ursache ab? Ein Softwareupdate der Dometic ZBE auf Version 0110 behebt das Problem.',
                'content' => '
<h2>Problembeschreibung</h2>
<p>Die Truma Heizung schaltet sich ohne eindeutige Ursache oder Fehlermeldung ab. Das Problem liegt nicht an der Heizung selbst, sondern an der Software der Dometic Zentralbedieneinheit.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California 600 und 680</li>
<li>Serienfix ab FIN: WV1 ZZZ SY 5 R 9 074 601</li>
</ul>

<h2>Lösung</h2>
<p><strong>Wichtig: Die ZBE darf NICHT getauscht werden!</strong> Softwareupdate auf <strong>SW0110</strong> per SD-Karte.</p>
<ol>
<li>SD Creator Tool verwenden (Teile-Nr. 3G8 919 360 BN)</li>
<li>Software auf Micro-SD-Karte flashen (2–32 GB)</li>
<li>Batteriehauptschalter ausschalten</li>
<li>ZBE-Abdeckungen vorsichtig abhebeln</li>
<li>SD-Karte einsetzen, Hauptschalter ein, Update bestätigen</li>
<li>Nach dem Update: SD-Karte entfernen, zusammenbauen</li>
<li>Reset durchführen: Blauen 6-poligen Stecker T6cb am J608 für 2 Minuten abziehen</li>
</ol>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2074574/1', 'gc_location' => 'Bedieneinheit, Dometic ZBE' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Heizung & Klima' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2022', '2023', '2024' ],
                'components' => [ 'Truma Heizung', 'Dometic ZBE', 'J608 Steuergerät', 'SD-Karte' ],
            ],

            // === SERVICEHINWEISE ===

            [
                'title'   => 'Campingmodus lässt sich nicht ausschalten — So reaktivierst du das Steuergerät',
                'excerpt' => 'Der Campingmodus-Taster reagiert nicht nach längerer Standzeit? Das Steuergerät ist im Schlafmodus. Ein einfacher Trick weckt es auf.',
                'content' => '
<h2>Problem</h2>
<p>Nach längerer Standzeit lässt sich der Campingmodus nicht über die Taste an der Bedieneinheit ausschalten. Der Taster reagiert nicht.</p>

<h2>Ursache</h2>
<p>Nach längerer Ruhezeit geht das Steuergerät für Sonderfunktionen in den Schlafmodus. In diesem Zustand reagiert es nicht auf die Campingmodus-Taste.</p>

<h2>Lösung</h2>
<p>Das Steuergerät muss zuerst aufgeweckt werden. Dafür reicht es, einen beliebigen Stromverbraucher im Campingbereich einzuschalten:</p>
<ul>
<li>Trittstufe betätigen</li>
<li>Wasserhahn öffnen</li>
<li>Innenbeleuchtung einschalten</li>
<li>Kühlschranktür öffnen (Licht geht an)</li>
</ul>
<p>Danach kann der Campingmodus normal über die Taste ausgeschaltet werden. Alternativ: Zündschlüssel umdrehen oder Motor starten.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2073309)', 'gc_location' => 'Bedieneinheit Campingausrüstung' ],
                'categories' => [ 'Servicehinweise', 'Lösungen & Workarounds' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Campingmodus', 'Steuergerät Sonderfunktionen', 'Bedieneinheit' ],
            ],

            [
                'title'   => 'Abwasseranzeige zeigt bei leerem Tank nicht 0% an',
                'excerpt' => 'Die Abwasseranzeige zeigt 2–7% obwohl der Tank leer ist? Das ist der Serienzustand und kein Defekt.',
                'content' => '
<h2>Beobachtung</h2>
<p>Die Abwasseranzeige am Camping-Display zeigt auch bei vollständig entleertem Tank einen Wert von 2–7% an.</p>

<h2>Erklärung</h2>
<p>Dies ist der <strong>normale Serienzustand</strong> der Bedieneinheit. Der Sensor kann konstruktionsbedingt keine exakte 0%-Anzeige liefern. Ein Defekt liegt nicht vor.</p>

<h2>Was du tun kannst</h2>
<p>Nichts — der leicht erhöhte Wert bei leerem Tank ist normal und hat keinen Einfluss auf die Funktion. Die Anzeige ist als Orientierungshilfe gedacht, nicht als exaktes Messgerät.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2068862)', 'gc_location' => 'Abwassertank, Bedieneinheit' ],
                'categories' => [ 'Servicehinweise', 'Bekannte Probleme' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Abwasseranzeige', 'Abwassertank', 'Abwasserstandsgeber G126' ],
            ],

            [
                'title'   => 'Abgasklappe pfeift nach Kaltstart — Kein Defekt',
                'excerpt' => 'Ein lautes Pfeifen beim Beschleunigen 1–2 Minuten nach dem Kaltstart? Die Abgasklappe ist die Ursache — das ist technisch bedingt und kein Mangel.',
                'content' => '
<h2>Beobachtung</h2>
<p>Etwa 1–2 Minuten nach einem Kaltstart tritt beim Beschleunigen ein deutliches Pfeifgeräusch auf. Das Geräusch verschwindet nach kurzer Fahrt.</p>

<h2>Ursache</h2>
<p>Nach dem Kaltstart wird die Abgasklappe weit geschlossen, um die Abgasnachbehandlung schnell auf Betriebstemperatur zu bringen. Der hohe Abgasstrom durch den verengten Querschnitt erzeugt das Pfeifgeräusch.</p>

<h2>Bewertung</h2>
<p><strong>Kein Defekt, kein Austausch nötig.</strong> Das Verhalten ist technisch bedingt und tritt bei allen Fahrzeugen mit diesem Motortyp auf.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2065515)', 'gc_location' => 'Abgasanlage' ],
                'categories' => [ 'Servicehinweise', 'Bekannte Probleme' ],
                'models' => [ 'Grand California 600', 'Grand California 680', 'Crafter' ],
                'years' => [ '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Abgasklappe', 'Motor', 'Kaltstart' ],
            ],

            [
                'title'   => 'Furnier der Sitzbankverkleidung quillt auf oder löst sich',
                'excerpt' => 'Das Holzfurnier an der Sitzbank wirft Blasen, quillt auf oder löst sich? Feuchtigkeit ist die Ursache. Das Furnier ist nicht wasserbeständig.',
                'content' => '
<h2>Problem</h2>
<p>Die Furnierverkleidung an der Sitzbank im Wohnbereich quillt auf, wirft Blasen oder die Beschichtung löst sich.</p>

<h2>Ursache</h2>
<p>Das Holzfurnier ist <strong>nicht wasserbeständig</strong>. Kontakt mit Wasser — durch Spritzwasser aus der Nasszelle, Kondenswasser der Truma-Heizung oder verschüttete Flüssigkeiten — führt zu Quellschäden.</p>

<h2>Vorbeugung</h2>
<ul>
<li>Beim Duschen die Badtür geschlossen halten</li>
<li>Verschüttete Flüssigkeiten sofort aufwischen</li>
<li>Boden im Bereich der Sitzbank trocken halten</li>
<li>Bei sichtbaren Undichtigkeiten (z.B. an der Heizung) sofort die Werkstatt informieren</li>
</ul>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2074450)', 'gc_location' => 'Sitzbank, Wohnbereich' ],
                'categories' => [ 'Servicehinweise', 'Bekannte Probleme' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Sitzbankverkleidung', 'Furnier', 'Holzverkleidung' ],
            ],

            [
                'title'   => 'Befestigungshaken der Innenraumleiter abgebrochen — Richtige Verwendung',
                'excerpt' => 'Ein Haken an der Leiter zum Dachbett ist abgebrochen? Die Leiter darf nur an die hellgrauen Haken gehängt werden, nicht an die schwarzen.',
                'content' => '
<h2>Problem</h2>
<p>Ein oder mehrere Befestigungshaken an der Leiter für das Enkelbett (Dachbett) sind abgebrochen.</p>

<h2>Ursache</h2>
<p>Die Leiter wurde an den <strong>schwarzen Haken</strong> eingehängt statt an den dafür vorgesehenen <strong>hellgrauen Haken</strong>. Zusätzlich können zwischen Bett und Leiter eingeklemmte Gegenstände (Decken, Kissen) die schwarzen Haken überlasten.</p>

<h2>Richtige Verwendung</h2>
<ul>
<li>Leiter <strong>immer an den hellgrauen Haken</strong> einhängen</li>
<li>Niemals an den schwarzen Haken</li>
<li>Keine Gegenstände zwischen Enkelbett und Leiter legen</li>
</ul>

<h3>Hinweis</h3>
<p>Bruch durch falsche Verwendung ist kein Garantiefall.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2070802)', 'gc_location' => 'Enkelbett, Dachbett' ],
                'categories' => [ 'Servicehinweise', 'Bekannte Probleme' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Innenraumleiter', 'Enkelbett', 'Dachbett', 'Befestigungshaken' ],
            ],

            [
                'title'   => 'DAB+ Empfang gestört — Störquellen im Fahrzeug identifizieren',
                'excerpt' => 'DAB+ Radio hat schlechten Empfang? Elektromagnetische Störquellen im Fahrzeug sind die häufigste Ursache.',
                'content' => '
<h2>Problem</h2>
<p>Der DAB+ Empfang am Radio ist gestört oder bricht häufig ab.</p>

<h2>Häufige Störquellen</h2>
<p>Folgende Geräte im Fahrzeug können den DAB+ Empfang stören:</p>
<ul>
<li>USB-KFZ-Ladegeräte (12V-Adapter)</li>
<li>Smartphones in der Nähe der Antenne</li>
<li>DECT-Telefone</li>
<li>Laptops und Tablets</li>
<li>Babyphones</li>
<li>Wetterstationen</li>
<li>Mobile Navigationsgeräte</li>
<li>Handyhalterungen mit 12V-Anschluss</li>
<li>LED-Beleuchtung am 12V-Stecker</li>
</ul>

<h2>Lösung</h2>
<p>Störquellen nacheinander entfernen und DAB+ Empfang prüfen. Meist reicht es, das verursachende Gerät auszustecken oder an eine andere Position zu verlegen.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2045236)', 'gc_location' => 'Radio, Infotainment' ],
                'categories' => [ 'Servicehinweise', 'Lösungen & Workarounds' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'DAB+ Radio', 'Antenne', 'Infotainment' ],
            ],

            [
                'title'   => 'Quietschgeräusche bei vollem Lenkeinschlag oder beim Rangieren',
                'excerpt' => 'Quietschen bei vollem Lenkeinschlag, besonders bei Nässe? Das kommt von den Antriebswellen-Faltenbalgen und ist kein Defekt.',
                'content' => '
<h2>Beobachtung</h2>
<p>Beim vollen Lenkeinschlag oder beim Rangieren treten Quietsch- oder Knarzgeräusche auf. Besonders auffällig bei feuchter Witterung.</p>

<h2>Ursache</h2>
<p>Die Rippen der äußeren Faltenbälge an den Antriebswellen berühren sich bei vollem Lenkeinschlag. Bei Nässe entsteht ein Haftgleiteffekt (Ruckgleiten), der das Quietschen verursacht.</p>

<h2>Bewertung</h2>
<p><strong>Serienzustand — kein Defekt.</strong> Das Geräusch hat keinen Einfluss auf Funktion oder Lebensdauer der Antriebswellen. Ein Teiletausch ist nicht sinnvoll und wird nicht empfohlen.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2026214)', 'gc_location' => 'Vorderachse, Antriebswellen' ],
                'categories' => [ 'Servicehinweise', 'Bekannte Probleme' ],
                'models' => [ 'Grand California 600', 'Grand California 680', 'Crafter' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Antriebswelle', 'Faltenbalg', 'Lenkung', 'Fahrwerk' ],
            ],

            [
                'title'   => 'Schimmel auf der Markise vermeiden — Richtig einrollen',
                'excerpt' => 'Schimmel oder starke Falten auf dem Markisentuch? Nass eingerollte Markise ist die häufigste Ursache.',
                'content' => '
<h2>Problem</h2>
<p>Auf dem Markisenstoff bildet sich Schimmel und/oder starke Falten.</p>

<h2>Ursache</h2>
<p>Die Markise wurde im nassen oder feuchten Zustand eingerollt. Feuchtigkeit im aufgerollten Stoff führt schnell zu Schimmelbildung.</p>

<h2>Vorbeugung</h2>
<p><strong>Markise immer vollständig trocknen lassen bevor sie eingefahren wird.</strong> Falls das nicht möglich ist: bei der nächsten Gelegenheit die Markise ausfahren und trocknen lassen.</p>

<h3>Hinweis</h3>
<p>Schimmel durch falsches Einrollen ist kein Garantiefall.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2075483)', 'gc_location' => 'Außen, Markise' ],
                'categories' => [ 'Servicehinweise', 'Bekannte Probleme' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Markise', 'Markisentuch' ],
            ],

            [
                'title'   => 'Insekten im Scheinwerfer oder in der Schlussleuchte — So entfernst du sie',
                'excerpt' => 'Insekten oder Fremdkörper im Inneren der Scheinwerfer? Die Leuchten haben Belüftungsöffnungen, durch die kleine Insekten eindringen können.',
                'content' => '
<h2>Problem</h2>
<p>Im Inneren der Scheinwerfer oder Schlussleuchten sind Insekten oder andere Fremdkörper sichtbar.</p>

<h2>Ursache</h2>
<p>Scheinwerfer und Leuchten sind als offenes System konstruiert — sie haben Belüftungsöffnungen für den Temperaturausgleich. Durch diese Öffnungen können kleine Insekten eindringen.</p>

<h2>Selbst entfernen</h2>
<h3>Scheinwerfer</h3>
<p>Serviceklappe öffnen und mit einem Staubsauger oder ölfreier Druckluft vorsichtig die Insekten entfernen.</p>

<h3>Schlussleuchten</h3>
<p>Leuchte ausbauen, Staubschutzkappen entfernen, mit Staubsauger oder Druckluft reinigen.</p>

<h3>Hinweis</h3>
<p>Insekten in den Leuchten sind kein Garantiegrund für einen Leuchtentausch.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2075741)', 'gc_location' => 'Scheinwerfer, Schlussleuchten' ],
                'categories' => [ 'Servicehinweise', 'Lösungen & Workarounds' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Scheinwerfer', 'Schlussleuchte', 'Beleuchtung' ],
            ],

            [
                'title'   => 'Wechselrichter 230V ohne Funktion — Stecker unter dem Beifahrersitz prüfen',
                'excerpt' => 'Die 230V Steckdosen haben keinen Strom? Der Stecker zum Wechselrichter unter dem Vordersitz kann sich durch die Kabelverlegung lösen.',
                'content' => '
<h2>Problem</h2>
<p>Die 230V Steckdosen im Grand California liefern keinen Strom und/oder die Zweitbatterie wird über 230V nicht geladen.</p>

<h2>Ursache</h2>
<p>Durch die Kabelverlegung kann sich der Stecker der 230V-Verbindung zum Wechselrichter lösen. Der Stecker befindet sich unter dem Vordersitz links.</p>

<h2>Lösung</h2>
<ol>
<li>Unter dem Fahrersitz nachschauen</li>
<li>Steckerverbindung zum Wechselrichter prüfen</li>
<li>Stecker wieder aufstecken und in Richtung Wechselrichter drücken bis er einrastet</li>
</ol>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2060374)', 'gc_location' => 'Unter Fahrersitz, Wechselrichter' ],
                'categories' => [ 'Servicehinweise', 'Lösungen & Workarounds' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Wechselrichter', '230V Steckdose', 'Stecker' ],
            ],

            [
                'title'   => 'Gummileiste am Bettrahmen löst sich — Neu verkleben mit Spezialkleber',
                'excerpt' => 'Die Gummileisten am Lattenrost lösen sich oder verrutschen? Der originale Klebestreifen hält hohen Temperaturen und Feuchtigkeit nicht stand. Anleitung zum Neuverkleben.',
                'content' => '
<h2>Problem</h2>
<p>Die Gummileisten/Gummipuffer am Bettrahmen bzw. Lattenrost lösen sich und verrutschen.</p>

<h2>Ursache</h2>
<p>Das original verwendete doppelseitige Klebeband (3M 950) ist nicht beständig gegen Temperaturen über 80°C und hohe Luftfeuchtigkeit (über 90%). Beides tritt im Grand California regelmäßig auf, besonders bei laufender Heizung.</p>

<h2>Lösung — Neu verkleben</h2>
<ol>
<li>Bettrahmen demontieren</li>
<li>Alte Klebereste mit Kunststoffreiniger (LVM 001 001 A2) entfernen</li>
<li>Oberflächen mit Entfetter (D 009 401 04) reinigen</li>
<li>Mit 2K-Polyurethan-Klebstoff TEROSON PU 6700 (D 180 KD2 A1) neu verkleben</li>
<li>Mindestens <strong>6 Stunden</strong> aushärten lassen</li>
</ol>

<h2>Benötigtes Material</h2>
<table>
<thead><tr><th>Material</th><th>Nummer</th></tr></thead>
<tbody>
<tr><td>Kunststoffreiniger</td><td>LVM 001 001 A2</td></tr>
<tr><td>Entfetter</td><td>D 009 401 04</td></tr>
<tr><td>2K-PU-Klebstoff TEROSON PU 6700</td><td>D 180 KD2 A1</td></tr>
</tbody>
</table>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2070857/1', 'gc_location' => 'Bettrahmen, Lattenrost' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Aufbau & Ausstattung' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023' ],
                'components' => [ 'Bettrahmen', 'Lattenrost', 'Gummileiste', 'Gummipuffer' ],
            ],

            [
                'title'   => 'Riss in der Bad-Seitenwand — Entkopplungsplatte als Lösung',
                'excerpt' => 'Riss in der Seitenwand des Bades im Bereich der Truma-Heizung? Eine Entkopplungsplatte verteilt den Druck gleichmäßig und verhindert weitere Risse.',
                'content' => '
<h2>Problem</h2>
<p>An der Seitenwand des Badezimmers bildet sich ein Riss, typischerweise im Bereich des Truma-Heizungsanschlusses (T-Stück).</p>

<h2>Ursache</h2>
<p>Der Rand des Truma T-Stücks liegt nicht plan an der nur 13 mm dicken Seitenwand an. Eine Aufwölbung erzeugt punktuelle Belastung, die sich im Heizbetrieb durch thermische Ausdehnung verstärkt. Zusätzlich können ab Werk ausgefranzte Bohrränder und nicht zentriert eingeklebte Endstücke das Problem begünstigen.</p>

<h2>Lösung: Entkopplungsplatte montieren</h2>
<p>Die Entkopplungsplatte neutralisiert den Druckpunkt und verteilt die Belastung gleichmäßig über eine größere Fläche.</p>

<h3>Montageablauf</h3>
<ol>
<li>Sitzfläche der Bank abheben</li>
<li>Abdeckplatte lösen</li>
<li>Lüftungskanäle abziehen</li>
<li>T-Stück abschrauben</li>
<li>Entkopplungsplatte montieren</li>
<li>Alles in umgekehrter Reihenfolge zusammenbauen</li>
</ol>

<h3>Zusätzliche Empfehlungen</h3>
<ul>
<li>Bohrung mit Klarlack versiegeln</li>
<li>Endstück zentriert verkleben</li>
<li>Verschlussklappen gegen Truma Lamelleneinsatz LA tauschen</li>
<li>Lüftungskanäle fixieren</li>
</ul>

<h2>Bezugsquelle</h2>
<p>Die Entkopplungsplatte ist für ca. 35 EUR bei spezialisierten Anbietern erhältlich.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (Entkopplungsplatte)', 'gc_location' => 'Nasszelle, Bad-Seitenwand' ],
                'categories' => [ 'Servicehinweise', 'Lösungen & Workarounds' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'Truma Heizung', 'Bad-Seitenwand', 'Entkopplungsplatte', 'T-Stück' ],
            ],

            [
                'title'   => 'Anzeige „Fehler: Fahrergurt" leuchtet ohne Grund — Gurtzyklus durchführen',
                'excerpt' => 'Die Meldung „Fehler: Fahrergurt" erscheint obwohl kein Defekt vorliegt? Der Zähler für Fahrten ohne Gurt muss zurückgesetzt werden.',
                'content' => '
<h2>Problem</h2>
<p>Im Display erscheint die Meldung „Fehler: Fahrergurt", obwohl kein Fehlerspeichereintrag im Airbag-Steuergerät vorhanden ist.</p>

<h2>Ursache</h2>
<p>Das Fahrzeug wurde zu oft ohne angelegten Sicherheitsgurt bewegt (z.B. beim Rangieren auf dem Campingplatz). Ein interner Zähler hat den Grenzwert überschritten.</p>

<h2>Lösung — Gurtzyklus durchführen</h2>
<p>Folgenden Ablauf <strong>1x korrekt</strong> durchführen — danach verschwindet die Meldung:</p>
<ol>
<li>Fahrzeug entriegeln</li>
<li>Einsteigen</li>
<li>Sicherheitsgurt anlegen</li>
<li>Motor starten und losfahren (über 15 km/h)</li>
<li>Anhalten</li>
<li>Gurt lösen</li>
<li>Zündung ausschalten</li>
<li>Aussteigen</li>
<li>Fahrzeug verriegeln</li>
</ol>
<p>Nach einem korrekten Zyklus verschwindet die Meldung. Nach 5 Zyklen ist der Zähler vollständig zurückgesetzt.</p>

<h3>Wichtig</h3>
<p>Kein Bauteiletausch nötig. Das Problem lässt sich rein durch korrektes Angurten lösen.</p>
',
                'meta' => [ 'gc_vw_code' => 'Servicehinweis (TPI 2075389)', 'gc_location' => 'Kombiinstrument, Airbag-System' ],
                'categories' => [ 'Servicehinweise', 'Lösungen & Workarounds' ],
                'models' => [ 'Grand California 600', 'Grand California 680' ],
                'years' => [ '2024', '2025 (Facelift)' ],
                'components' => [ 'Sicherheitsgurt', 'Kombiinstrument', 'Gurtwarnung' ],
            ],

            [
                'title'   => 'Ölverbrauch am MAR-Motor zu hoch — Ventilschaftdichtungen prüfen',
                'excerpt' => 'Überhöhter Ölverbrauch (über 0,5L/1.000 km) am Grand California? Die Ventilschaftdichtungen können von den Ventilführungen abgerutscht sein.',
                'content' => '
<h2>Problem</h2>
<p>Der Motorölverbrauch am Grand California mit MAR-Motor liegt über 0,5 Liter pro 1.000 Kilometer, bestätigt durch eine standardisierte Ölverbrauchsmessung.</p>

<h2>Betroffene Fahrzeuge</h2>
<ul>
<li>Grand California und Crafter mit MAR-Motor (DM*, DN*, DR*)</li>
<li>Modelljahre 2019 bis 2024</li>
<li>Optimierte Ventilschaftdichtungen ab Q4/2023 im Serieneinsatz</li>
</ul>

<h2>Ursache</h2>
<p>Die Ventilschaftdichtungen können von den Ventilführungen abrutschen, was zu erhöhtem Ölverbrauch führt.</p>

<h2>Lösung</h2>
<ol>
<li>Ventilschaftdichtungen visuell kontrollieren</li>
<li>Falls hochgerutscht: alle 16 Ventilschaftdichtungen und die Zylinderkopfhaube tauschen</li>
<li>Fotodokumentation einer ausgebauten Dichtung erstellen</li>
</ol>

<h3>Hinweis zur Ölverbrauchsmessung</h3>
<p>Ein gewisser Ölverbrauch ist normal — besonders in den ersten 5.000 km. Eine standardisierte Messung muss durchgeführt werden bevor eine Reparatur eingeleitet wird.</p>
',
                'meta' => [ 'gc_vw_code' => 'TPI 2075488/1', 'gc_location' => 'Motor, Zylinderkopf' ],
                'categories' => [ 'Technische Produktinformationen (TPI)', 'Motor & Antrieb' ],
                'models' => [ 'Grand California 600', 'Grand California 680', 'Crafter' ],
                'years' => [ '2019', '2020', '2021', '2022', '2023', '2024' ],
                'components' => [ 'Ventilschaftdichtung', 'MAR-Motor', 'Zylinderkopfhaube', 'Ölverbrauch' ],
            ],

        ];
    }
}

add_action( 'admin_init', function () {
    if ( isset( $_GET['gc_import_batch1c'] ) && current_user_can( 'manage_options' ) ) {
        $result = GC_Batch1c_Import::run();
        add_action( 'admin_notices', function () use ( $result ) {
            echo '<div class="notice notice-success"><p>' . esc_html( $result ) . '</p></div>';
        } );
    }
} );
