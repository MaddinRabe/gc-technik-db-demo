<?php
/**
 * Sample Data Import für GrandCali Technik-DB
 *
 * Aufruf: WP-CLI oder als Admin über /wp-admin/?gc_import_sample=1
 *
 * WICHTIG: Inhalte sind umgeschrieben und nicht 1:1 aus VW-Dokumenten übernommen.
 * Technische Bezeichnungen (Bauteilcodes, Sicherungsnummern) bleiben original.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GC_Sample_Import {

    public static function run() {
        if ( get_option( 'gc_sample_data_imported' ) ) {
            return 'Sample-Daten wurden bereits importiert.';
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

            if ( is_wp_error( $post_id ) ) {
                continue;
            }

            // Meta
            if ( ! empty( $article['meta'] ) ) {
                foreach ( $article['meta'] as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }
            }

            // Taxonomien
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

        update_option( 'gc_sample_data_imported', true );

        return sprintf( '%d Technik-Artikel wurden erfolgreich importiert.', $count );
    }

    private static function get_articles() {
        return [

            // ============================================================
            // Artikel 1: Sicherungsbelegung Sicherungshalter A
            // ============================================================
            [
                'title'   => 'Sicherungshalter A — Übersicht und Belegung',
                'excerpt' => 'Übersicht aller Sicherungen im Sicherungshalter A des VW Grand California (Facelift ab 06/2024). Enthält Sicherungen SA2 bis SA6 mit Amperewerten und zugehörigen Verbrauchern.',
                'content' => '
<h2>Lage des Sicherungshalters A</h2>
<p>Der Sicherungshalter A befindet sich im Motorraum in direkter Nähe zur Fahrzeugbatterie. Er enthält die Hauptsicherungen für grundlegende Fahrzeugfunktionen.</p>

<h2>Sicherungsbelegung</h2>
<table>
<thead>
<tr><th>Sicherung</th><th>Ampere</th><th>Geschützter Verbraucher</th></tr>
</thead>
<tbody>
<tr><td>SA2</td><td>—</td><td>Nicht belegt</td></tr>
<tr><td>SA3</td><td>—</td><td>Nicht belegt</td></tr>
<tr><td>SA4</td><td>—</td><td>Nicht belegt</td></tr>
<tr><td>SA5</td><td>—</td><td>Nicht belegt</td></tr>
<tr><td>SA6</td><td>—</td><td>Nicht belegt</td></tr>
</tbody>
</table>

<h2>Zugehörige Bauteile</h2>
<ul>
<li><strong>A</strong> — Fahrzeugbatterie</li>
<li><strong>T2no</strong> — 2-fach Steckverbindung (schwarz)</li>
<li><strong>Z35</strong> — Heizelement der Luftzusatzheizung</li>
</ul>

<h2>Kabelfarben-Legende</h2>
<p>Die Kabelfarben im Sicherungshalter A folgen dem VW-Standard. Die wichtigsten Farben:</p>
<ul>
<li><code>rt</code> = rot (Dauerplus)</li>
<li><code>sw</code> = schwarz (Masse)</li>
<li><code>br</code> = braun (Masse)</li>
<li><code>bl</code> = blau</li>
<li><code>gn</code> = grün</li>
<li><code>ge</code> = gelb</li>
</ul>

<h3>Hinweis</h3>
<p>Die genaue Belegung kann je nach Ausstattungsvariante abweichen. Im Zweifel die fahrzeugspezifischen Daten über die FIN abfragen.</p>
',
                'meta' => [
                    'gc_vw_code'     => 'SA (Sicherungshalter A)',
                    'gc_fuse_rating' => 'Diverse (siehe Tabelle)',
                    'gc_location'    => 'Motorraum, an der Batterie',
                ],
                'categories' => [ 'Sicherungen' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2025 (Facelift)' ],
                'components' => [ 'Sicherungshalter A', 'SA2', 'SA3', 'SA4', 'SA5', 'SA6', 'Batterie', 'Z35 Heizelement' ],
            ],

            // ============================================================
            // Artikel 2: Sicherungshalter B
            // ============================================================
            [
                'title'   => 'Sicherungshalter B — Übersicht und Belegung',
                'excerpt' => 'Alle Sicherungen im Sicherungshalter B des Grand California. Enthält u.a. Absicherungen für Steuergeräte, Einparkhilfe, Telematik und beheizbare Frontscheibe.',
                'content' => '
<h2>Lage des Sicherungshalters B</h2>
<p>Der Sicherungshalter B sitzt im Fahrzeuginnenraum unter der Lenksäulenverkleidung. Er versorgt zahlreiche elektronische Steuergeräte und Komfortsysteme.</p>

<h2>Sicherungsbelegung (Auszug)</h2>
<table>
<thead>
<tr><th>Sicherung</th><th>Ampere</th><th>Geschützter Verbraucher / Steuergerät</th></tr>
</thead>
<tbody>
<tr><td>SB1–SB8</td><td>Diverse</td><td>Klimaanlage (J979), beheizbare Frontscheibe (J47), Steuergerät Heizung</td></tr>
<tr><td>SB30</td><td>—</td><td>Kupplungspositionsgeber (G476)</td></tr>
<tr><td>SB31</td><td>—</td><td>Starterrelais (J906, J907)</td></tr>
<tr><td>SB32</td><td>35A</td><td>Einparkhilfe (J446)</td></tr>
<tr><td>SB33</td><td>—</td><td>Rückfahrwarner (J1113)</td></tr>
<tr><td>SB34</td><td>—</td><td>Kältemittelkreislauf-Druckgeber (G805)</td></tr>
<tr><td>SB35</td><td>—</td><td>Sonderaufbauten Relais Kl. 15 (J821)</td></tr>
<tr><td>SB37</td><td>—</td><td>Telematik-Schnittstellensteuergerät (J1221)</td></tr>
<tr><td>SB38</td><td>—</td><td>Reduktionsmittelheizung (J891)</td></tr>
<tr><td>SB39</td><td>—</td><td>Beheizbare Frontscheibe Relais 2 (J611)</td></tr>
<tr><td>SB40</td><td>40A</td><td>Nicht belegt (Reserve)</td></tr>
<tr><td>SB41</td><td>—</td><td>Warmluftgebläse (J350)</td></tr>
<tr><td>SB42</td><td>—</td><td>Nicht belegt</td></tr>
</tbody>
</table>

<h2>Wichtige Steuergeräte am Sicherungshalter B</h2>
<ul>
<li><strong>J47</strong> — Relais für beheizbare Frontscheibe</li>
<li><strong>J350</strong> — Steuergerät für Warmluftgebläse</li>
<li><strong>J446</strong> — Steuergerät für Einparkhilfe</li>
<li><strong>J979</strong> — Steuergerät für Heizung und Klimaanlage</li>
<li><strong>J1113</strong> — Steuergerät für Rückfahrwarner</li>
<li><strong>J1221</strong> — Schnittstellensteuergerät für Telematik</li>
</ul>
',
                'meta' => [
                    'gc_vw_code'     => 'SB (Sicherungshalter B)',
                    'gc_fuse_rating' => 'Diverse (siehe Tabelle)',
                    'gc_location'    => 'Innenraum, unter der Lenksäulenverkleidung',
                ],
                'categories' => [ 'Sicherungen' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2025 (Facelift)' ],
                'components' => [ 'Sicherungshalter B', 'SB32', 'SB33', 'SB37', 'J446 Einparkhilfe', 'J979 Klimaanlage', 'J1221 Telematik', 'J350 Warmluftgebläse' ],
            ],

            // ============================================================
            // Artikel 3: Einbauorte Campingausrüstung
            // ============================================================
            [
                'title'   => 'Einbauorte der Camping-Komponenten — Übersicht',
                'excerpt' => 'Wo befinden sich welche Camping-Bauteile im Grand California? Übersicht aller Einbauorte von Schaltern, Steuergeräten, Steckverbindungen und Sensoren.',
                'content' => '
<h2>Technikschrank</h2>
<p>Der Technikschrank ist das zentrale Nervenzentrum der Campingausrüstung. Hier befinden sich:</p>
<ul>
<li><strong>SL</strong> — Sicherungshalter L (Camping-Sicherungen)</li>
<li><strong>E74</strong> — Batteriehauptschalter und Trennschalter</li>
<li><strong>J355</strong> — Steuergerät für Solarbetrieb</li>
<li><strong>S59</strong> — 220-V-Personenschutzautomat (FI-Schalter)</li>
<li><strong>J1124</strong> — Steuergerät für Ambientebeleuchtung</li>
<li><strong>A12</strong> — Spannungswandler 24V/12V</li>
<li><strong>TV1</strong> — Leitungsverteiler</li>
<li><strong>TV69</strong> — Leitungsverteiler 2</li>
</ul>

<h2>Küche</h2>
<ul>
<li><strong>E317</strong> — Schalter für Innenbeleuchtung</li>
<li><strong>E599</strong> — Schalter für Innenraumbeleuchtung</li>
<li><strong>E963</strong> — Schalter 2 für Wasserpumpe mit Frischwasser</li>
<li><strong>J699</strong> — Kühlschrank</li>
<li><strong>U11</strong> — Innensteckdose 230V</li>
<li><strong>U37</strong> — USB-Ladesteckdose 1</li>
<li><strong>C12</strong> — Kondensator für Hochspannungs-Kondensatorzündung (Gaskocher)</li>
</ul>

<h2>Nasszelle</h2>
<ul>
<li><strong>E181</strong> — Schalter für Toilettenspülung</li>
<li><strong>E962</strong> — Schalter 1 für Wasserpumpe mit Frischwasser</li>
</ul>

<h2>Mittelbereich links</h2>
<ul>
<li><strong>E153</strong> — Bedienungs- und Anzeigeeinheit für Campingausrüstung</li>
<li><strong>E407</strong> — Bedienungs- und Anzeigeeinheit für Zusatzheizung</li>
<li><strong>E91</strong> — Funktionswahlschalter</li>
<li><strong>E140</strong> — Schalter für Ausstellfenster links</li>
<li><strong>G18</strong> — Temperaturfühler</li>
<li><strong>U17</strong> — Innensteckdose 2 (230V)</li>
<li><strong>U38</strong> — USB-Ladesteckdose 2</li>
<li><strong>U123</strong> — USB-Ladesteckdose 3</li>
<li><strong>J1146</strong> — Ladegerät 1 für mobile Endgeräte</li>
<li><strong>E981</strong> — Taster für Duschzellenbeleuchtung</li>
</ul>

<h2>Mittelbereich rechts</h2>
<ul>
<li><strong>E6</strong> — Schalter für Innenleuchte hinten</li>
<li><strong>E311</strong> — Taster für Innenverriegelung hinten rechts</li>
<li><strong>G120</strong> — Wasserstandsgeber</li>
<li><strong>U32</strong> — Innensteckdose 4 (230V)</li>
<li><strong>TV70</strong> — Leitungsverteiler 3</li>
</ul>

<h2>Steuergeräte unter dem Beifahrersitz</h2>
<ul>
<li><strong>R12</strong> — Verstärker</li>
<li><strong>J666</strong> — Steuergerät für Internetzugang</li>
<li><strong>F598</strong> — Digitaler Satelliten-TV-Verstärker</li>
<li><strong>J1273</strong> — Zentralsteuergerät für Datenbuskommunikation</li>
<li><strong>R403</strong> — Steuereinheit für Satellitenantennenausrichtung</li>
</ul>

<h2>Fahrzeug Außen</h2>
<ul>
<li><strong>C20</strong> — Solarzellen (Dach)</li>
<li><strong>U8</strong> — Außensteckdose 230V</li>
<li><strong>R170</strong> — Satellitenantenne</li>
<li><strong>RX5</strong> — Dachantenne</li>
<li><strong>F597</strong> — Stellelement für Serviceklappe im Heck</li>
</ul>

<h2>Fahrzeug Unterseite</h2>
<ul>
<li><strong>G126</strong> — Abwasserstandsgeber</li>
<li><strong>G249</strong> — Temperaturfühler 2 für Außentemperatur</li>
</ul>
',
                'meta' => [
                    'gc_location' => 'Diverse — siehe Artikelinhalt',
                ],
                'categories' => [ 'Camping' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2025 (Facelift)' ],
                'components' => [ 'E153 Bedieneinheit', 'J355 Solar', 'J699 Kühlschrank', 'E74 Batteriehauptschalter', 'SL Sicherungshalter L', 'S59 FI-Schalter', 'A12 Spannungswandler', 'J666 Internet', 'G120 Wasserstandsgeber', 'G126 Abwasserstandsgeber' ],
            ],

            // ============================================================
            // Artikel 4: Stromlaufplan Campingbeleuchtung
            // ============================================================
            [
                'title'   => 'Stromlaufplan — Campingbeleuchtung im Innenraum',
                'excerpt' => 'Verdrahtung und Anschlüsse der Innenraumbeleuchtung im Campingaufbau. Kabelfarben, Steckverbindungen und Signalzuordnung aller Leuchten.',
                'content' => '
<h2>Übersicht Innenraumleuchten</h2>
<p>Die Campingbeleuchtung wird über das Steuergerät für Ambientebeleuchtung (J1124) und die Bedieneinheit E153 gesteuert. Alle Leuchten hängen am LIN-Bus oder werden direkt über Signalleitungen geschaltet.</p>

<h2>Leuchten und ihre Anschlüsse</h2>
<table>
<thead>
<tr><th>Bauteil</th><th>Bezeichnung</th><th>Stecker</th><th>Signal-Kabel</th><th>Masse</th><th>Plus (KL30a)</th></tr>
</thead>
<tbody>
<tr><td>W11</td><td>Leseleuchte hinten links</td><td>T4tj</td><td>Pin 3: gn/rt (SIG)</td><td>Pin 1: br (GND)</td><td>Pin 2: bl/rt</td></tr>
<tr><td>W12</td><td>Leseleuchte hinten rechts</td><td>T4hv</td><td>Pin 3: gn/ws, Pin 4: gr/sw</td><td>Pin 1: br</td><td>Pin 2: bl/rt</td></tr>
<tr><td>W24</td><td>Leseleuchte Ausziehtisch</td><td>T4ig</td><td>Pin 3: rt/sw, Pin 4: ge/gn</td><td>Pin 1: br</td><td>Pin 2: bl/rt</td></tr>
<tr><td>W44</td><td>Leseleuchte hinten Mitte</td><td>T4lt</td><td>Pin 3: ge/vi, Pin 4: gr</td><td>Pin 1: br</td><td>Pin 2: bl/rt</td></tr>
<tr><td>W57</td><td>Leuchte 1 Küche</td><td>T4js</td><td>Pin 3: ws/sw, Pin 4: sw/ws</td><td>Pin 1: br</td><td>Pin 2: bl/rt</td></tr>
<tr><td>W65</td><td>Leseleuchte vorn links</td><td>T4kg</td><td>Pin 3: gn/vi, Pin 4: bl/sw</td><td>Pin 1: br</td><td>Pin 2: bl/rt</td></tr>
<tr><td>W135</td><td>Innenleuchte Stauraum</td><td>T3pf</td><td>Pin 2: sw/ge (SIG)</td><td>Pin 3: br</td><td>—</td></tr>
<tr><td>W140</td><td>Duschzellenbeleuchtung</td><td>T2vl</td><td>Pin 2: br/sw (SIG)</td><td>Pin 1: br/ws</td><td>—</td></tr>
<tr><td>W143</td><td>Duschzellenbeleuchtung 2</td><td>T2tt</td><td>Pin 2: br/sw (SIG)</td><td>Pin 1: br/ws</td><td>—</td></tr>
<tr><td>W16</td><td>Innenleuchte links</td><td>T2yq</td><td>Pin 2: ws (SIG)</td><td>Pin 1: br</td><td>—</td></tr>
<tr><td>W7</td><td>Innenleuchte Mitte</td><td>T2yp</td><td>Pin 2: rt/gn (SIG)</td><td>Pin 1: br</td><td>—</td></tr>
<tr><td>W17</td><td>Innenleuchte rechts</td><td>T2yr</td><td>Pin 2: rt/gn (SIG)</td><td>Pin 1: br</td><td>—</td></tr>
<tr><td>W43</td><td>Innenleuchte hinten</td><td>T2ys</td><td>Pin 2: gn (SIG)</td><td>Pin 1: br/ws</td><td>—</td></tr>
<tr><td>W48</td><td>Innenleuchte hinten rechts</td><td>T2yt</td><td>Pin 2: gn (SIG)</td><td>Pin 1: br</td><td>—</td></tr>
</tbody>
</table>

<h2>Versorgungsleitungen</h2>
<ul>
<li><strong>B113</strong> — Plusverbindung (KL30a) im Leitungsstrang Innenraum links</li>
<li><strong>B944</strong> — Verbindung 1 (Minus, Innenraumbeleuchtung) im Leitungsstrang Camper</li>
<li><strong>B710</strong> — Verbindung 5 (LIN-Bus) im Hauptleitungsstrang</li>
<li><strong>B711</strong> — Verbindung 6 (LIN-Bus) im Hauptleitungsstrang</li>
</ul>

<h2>Kabelfarben-Referenz</h2>
<p>Die Kabelbezeichnungen bestehen aus Grundfarbe/Kontrastfarbe:</p>
<ul>
<li><code>bl/rt</code> = blau mit rotem Streifen (Dauerplus KL30a)</li>
<li><code>br</code> = braun (Masse/GND)</li>
<li><code>gn/rt</code> = grün mit rotem Streifen (Signal)</li>
<li><code>sw/ge</code> = schwarz mit gelbem Streifen (Signal)</li>
</ul>

<h3>Radstandabhängige Unterschiede</h3>
<p>Einige Innenleuchten (W7, W17) werden je nach Radstand über unterschiedliche Kabelvarianten angeschlossen. Beim kurzen Radstand ist die Signalleitung <code>rt/gn</code>, beim langen Radstand <code>ws</code>.</p>
',
                'meta' => [
                    'gc_vw_code'     => 'W7, W11, W12, W16, W17, W24, W43, W44, W48, W57, W65, W135, W140, W143',
                    'gc_wire_colors' => "bl/rt = Plus KL30a\nbr = Masse\ngn/rt = Signal\nsw/ge = Signal\nrt/gn = Signal\ngn/ws = Signal",
                    'gc_location'    => 'Diverse Innenraumpositionen',
                ],
                'categories' => [ 'Stromlaufpläne', 'Beleuchtung' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2025 (Facelift)' ],
                'components' => [ 'W11 Leseleuchte', 'W12 Leseleuchte', 'W24 Leseleuchte', 'W57 Küchenleuchte', 'W135 Stauraumleuchte', 'W140 Duschleuchte', 'J1124 Ambientebeleuchtung', 'B944 Minus Beleuchtung', 'B113 Plus KL30a' ],
            ],

            // ============================================================
            // Artikel 5: Make-up-Spiegel W49 Aus-/Einbau
            // ============================================================
            [
                'title'   => 'Beleuchteter Make-up-Spiegel (W49) — Aus- und Einbau',
                'excerpt' => 'Anleitung zum Aus- und Einbau des beleuchteten Make-up-Spiegels im Fahrgastraum links (W49). Inkl. Werkzeug, Klebstoffhinweise und Anzugsdrehmomente.',
                'content' => '
<h2>Benötigtes Werkzeug</h2>
<ul>
<li>Demontagekeil (VW Spezialwerkzeug 3409)</li>
<li>1K-Montage-Klebstoff (siehe ETKA)</li>
<li>Reinigungslösung</li>
</ul>

<h2>Ausbau des Spiegels</h2>
<ol>
<li>Die Verklebung rund um den Make-up-Spiegel W49 vorsichtig mit dem Demontagekeil lösen.</li>
<li>Den Spiegel W49 aus der Blende herausclipsen.</li>
<li>Die elektrische Steckverbindung trennen und den Spiegel abnehmen.</li>
</ol>

<h3>Halterung demontieren (falls nötig)</h3>
<ol>
<li>Die Befestigungsschrauben herausdrehen.</li>
<li>Die Halterung abnehmen.</li>
</ol>

<h2>Einbau</h2>
<p>Der Einbau erfolgt in umgekehrter Reihenfolge. Folgende Punkte sind dabei wichtig:</p>

<h3>Vorbereitung der Klebeflächen</h3>
<ul>
<li>Alle Klebeflächen gründlich mit Reinigungslösung säubern.</li>
<li>Die Ablüftzeit der Reinigungslösung abwarten.</li>
<li>Die Flächen müssen frei von Staub, Fett und sonstigen Rückständen sein.</li>
<li>Die Klebefläche muss vollständig trocken sein.</li>
</ul>

<h3>Klebstoff auftragen</h3>
<ul>
<li>1K-Montage-Klebstoff umlaufend auftragen.</li>
<li>Anschließend sauber verstreichen.</li>
<li>Verarbeitungshinweise des Klebstoffherstellers beachten.</li>
<li>Kleberaupe: Dreiecksform, ca. 5 mm Höhe, ca. 5 mm Breite.</li>
</ul>

<h2>Anzugsdrehmomente</h2>
<table>
<thead>
<tr><th>Bauteil</th><th>Anzugsdrehmoment</th></tr>
</thead>
<tbody>
<tr><td>Befestigungsschrauben der Halterung</td><td>1 Nm</td></tr>
</tbody>
</table>
',
                'meta' => [
                    'gc_vw_code'      => 'W49',
                    'gc_location'     => 'Fahrgastraum links',
                    'gc_tools_needed' => "Demontagekeil (VW 3409)\n1K-Montage-Klebstoff\nReinigungslösung",
                    'gc_torque_specs' => 'Halterungsschrauben: 1 Nm',
                ],
                'categories' => [ 'Aus- und Einbau', 'Innenausstattung' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2022', '2023', '2024', '2025 (Facelift)' ],
                'components' => [ 'W49 Make-up-Spiegel' ],
            ],

            // ============================================================
            // Artikel 6: Wasserversorgung — Schalter und Sensoren
            // ============================================================
            [
                'title'   => 'Wasserversorgung — Schalter, Pumpen und Sensoren',
                'excerpt' => 'Verdrahtung der Wasserversorgung im Grand California: Frischwasserpumpe, Dusche, Wasserstandsgeber und Abwassersensor mit Kabelfarben und Steckerbelegung.',
                'content' => '
<h2>Übersicht Wasserversorgung</h2>
<p>Das Wassersystem im Grand California wird über mehrere Schalter und Sensoren gesteuert, die alle an der Bedieneinheit E153 und dem Camping-Leitungsstrang hängen.</p>

<h2>Schalter und Sensoren</h2>
<table>
<thead>
<tr><th>Bauteil</th><th>Bezeichnung</th><th>Stecker</th><th>Einbauort</th></tr>
</thead>
<tbody>
<tr><td>E134</td><td>Schalter Wasserpumpe/Dusche</td><td>—</td><td>Ansicht von hinten, Pos. 4</td></tr>
<tr><td>E962</td><td>Schalter 1 Frischwasserpumpe</td><td>T4a</td><td>Nasszelle, Pos. 2</td></tr>
<tr><td>E963</td><td>Schalter 2 Frischwasserpumpe</td><td>—</td><td>Küche, Pos. 1</td></tr>
<tr><td>G120</td><td>Wasserstandsgeber (Frisch)</td><td>T3ph</td><td>Mittelbereich rechts, Pos. 5</td></tr>
<tr><td>G126</td><td>Abwasserstandsgeber</td><td>T3pi</td><td>Fahrzeugunterseite</td></tr>
</tbody>
</table>

<h2>Verdrahtung Wasserstandsgeber G120</h2>
<ul>
<li><strong>T3ph Pin 1</strong>: br (GND/Masse)</li>
<li><strong>T3ph Pin 2</strong>: gn/bl (Signal)</li>
<li><strong>T3ph Pin 3</strong>: ws/bl (Signal)</li>
</ul>

<h2>Verdrahtung Abwasserstandsgeber G126</h2>
<ul>
<li><strong>T3pi Pin 1</strong>: br (GND/Masse)</li>
<li><strong>T3pi Pin 2</strong>: gn/gr (Signal)</li>
<li><strong>T3pi Pin 3</strong>: ws/bl (Signal)</li>
</ul>

<h2>Versorgung</h2>
<ul>
<li><strong>B910</strong> — Verbindung 1 (KL30g, Schalter/Steuergerät) im Leitungsstrang Camper</li>
<li><strong>B914</strong> — Verbindung 5 (KL30g, Schalter/Steuergerät) im Leitungsstrang Camper</li>
<li><strong>B1020</strong> — Verbindung 20 (KL30a) im Leitungsstrang Camper</li>
<li><strong>449</strong> — Masseverbindung 2 im Leitungsstrang Camper</li>
</ul>
',
                'meta' => [
                    'gc_vw_code'     => 'E134, E962, E963, G120, G126',
                    'gc_wire_colors' => "br = Masse\ngn/bl = Signal G120\ngn/gr = Signal G126\nws/bl = Signal",
                    'gc_location'    => 'Nasszelle, Küche, Fahrzeugunterseite',
                ],
                'categories' => [ 'Wasser & Sanitär', 'Stromlaufpläne' ],
                'models'     => [ 'Grand California 600', 'Grand California 680' ],
                'years'      => [ '2025 (Facelift)' ],
                'components' => [ 'E134 Wasserpumpe', 'E962 Frischwasserpumpe', 'E963 Frischwasserpumpe', 'G120 Wasserstandsgeber', 'G126 Abwasserstandsgeber' ],
            ],

        ];
    }
}

// Admin-Trigger
add_action( 'admin_init', function () {
    if ( isset( $_GET['gc_import_sample'] ) && current_user_can( 'manage_options' ) ) {
        $result = GC_Sample_Import::run();
        add_action( 'admin_notices', function () use ( $result ) {
            echo '<div class="notice notice-success"><p>' . esc_html( $result ) . '</p></div>';
        } );
    }
} );
