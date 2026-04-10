<?php
/**
 * Batch 3: Wartung, Servicemaßnahmen, Rückrufe, Sonstiges
 * Aufruf: /wp-admin/?gc_import_batch3=1
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

class GC_Batch3_Import {
    public static function run() {
        if ( get_option( 'gc_batch3_imported' ) ) { return 'Batch 3 bereits importiert.'; }
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
        update_option('gc_batch3_imported',true);
        return sprintf('%d Artikel (Batch 3: Wartung, Servicemaßnahmen, Rest) importiert.',$count);
    }

    private static function get_articles() {
        $gc = ['Grand California 600','Grand California 680'];
        $ay = ['2019','2020','2021','2022','2023','2024','2025 (Facelift)'];

        return [

            // === WARTUNG ===

            ['title'=>'Wassersystem entleeren — Schwerkraftmethode','excerpt'=>'Anleitung zum Entleeren des Frisch- und Abwassersystems sowie der Heizungsanlage per Schwerkraft. Wichtig vor der Winterlagerung.',
            'content'=>'
<h2>Wann entleeren?</h2>
<p>Vor der Winterlagerung oder bei Frostgefahr muss das gesamte Wassersystem entleert werden. Restwasser in den Leitungen kann bei Minusgraden gefrieren und die Leitungen beschädigen.</p>
<h2>Ablauf — Schwerkraftmethode</h2>
<ol>
<li>Frischwassertank über den Ablasshahn vollständig entleeren</li>
<li>Alle Wasserhähne im Fahrzeug öffnen (Küche + Nasszelle, warm + kalt)</li>
<li>Dusche öffnen</li>
<li>Toilettenspülung betätigen</li>
<li>Heizungsanlage über den vorgesehenen Ablasshahn entleeren</li>
<li>Abwassertank über das Ablassventil entleeren</li>
<li>Alle Hähne geöffnet lassen bis kein Wasser mehr nachläuft</li>
</ol>
<h3>Hinweis</h3>
<p>Die Schwerkraftmethode entfernt den Großteil des Wassers, kann aber Restwasser in Leitungsbögen hinterlassen. Für eine vollständige Entleerung zusätzlich die Druckluftmethode anwenden.</p>',
            'meta'=>['gc_location'=>'Wassersystem komplett'],'categories'=>['Reparaturanleitungen','Wartung','Campingausstattung Grand California'],'models'=>$gc,'years'=>$ay,'components'=>['Frischwassertank','Abwassertank','Heizungsanlage','Wasserleitungen']],

            ['title'=>'Wasserleitungen entleeren — Druckluftmethode','excerpt'=>'Entleeren der Wasserleitungen mit Druckluft. Ergänzung zur Schwerkraftmethode für eine vollständige Winterentleerung.',
            'content'=>'
<h2>Wann anwenden?</h2>
<p>Ergänzend zur Schwerkraftmethode — besonders empfohlen wenn das Fahrzeug bei Minustemperaturen steht. Mit Druckluft werden auch Restwasser aus Leitungsbögen und Ventilen gedrückt.</p>
<h2>Benötigtes Werkzeug</h2>
<ul><li>Druckluftkompressor mit Druckminderer</li><li>Adapter für den Frischwasseranschluss</li></ul>
<h2>Ablauf</h2>
<ol>
<li>Frischwassertank entleeren (Schwerkraftmethode vorher durchführen)</li>
<li>Alle Wasserhähne öffnen</li>
<li>Druckluft mit reduziertem Druck (max. 1–2 bar) über den Frischwasseranschluss einspeisen</li>
<li>Warten bis nur noch Luft aus allen Hähnen kommt</li>
<li>Heizungskreislauf separat mit Druckluft ausblasen</li>
</ol>
<h3>Hinweis</h3>
<p>Wer keinen eigenen Kompressor hat, kann die Entleerung in einer Werkstatt durchführen lassen.</p>',
            'meta'=>['gc_location'=>'Wassersystem komplett','gc_tools_needed'=>'Druckluftkompressor mit Druckminderer'],'categories'=>['Reparaturanleitungen','Wartung','Campingausstattung Grand California'],'models'=>$gc,'years'=>$ay,'components'=>['Wasserleitungen','Frischwassertank','Druckluft']],

            ['title'=>'Dachklimaanlage — Staub- und Pollenfilter reinigen','excerpt'=>'So reinigst du den Filter der Dachklimaanlage im Grand California. Regelmäßige Reinigung verbessert die Luftqualität und Kühlleistung.',
            'content'=>'
<h2>Benötigtes Material</h2><ul><li>Handelsübliches Spülmittel</li><li>Klares Wasser</li></ul>
<h2>Ablauf</h2><ol><li>Filter aus der Dachklimaanlage entnehmen</li><li>Filter mit lauwarmem Wasser und mildem Spülmittel auswaschen</li><li>Gründlich mit klarem Wasser nachspülen</li><li>Filter vollständig trocknen lassen</li><li>Trockenen Filter wieder einsetzen</li></ol>
<h3>Empfehlung</h3><p>Den Filter mindestens einmal pro Saison reinigen — bei häufiger Nutzung öfter.</p>',
            'meta'=>['gc_location'=>'Dachklimaanlage'],'categories'=>['Reparaturanleitungen','Wartung','Campingausstattung Grand California'],'models'=>$gc,'years'=>$ay,'components'=>['Dachklimaanlage','Pollenfilter','Staubfilter']],

            ['title'=>'Dachklimaanlage — Dichtigkeitsprüfung zum Innenraum','excerpt'=>'Sichtprüfung der Dichtigkeit der Dachklimaanlage zum Innenraum. Teil der regulären Wartung.',
            'content'=>'
<h2>Prüfung</h2><p>Die Dichtigkeit der Dachklimaanlage zum Fahrzeuginnenraum muss regelmäßig visuell geprüft werden.</p>
<h3>Worauf achten</h3><ul><li>Feuchtigkeitsspuren an der Einfassung der Klimaanlage</li><li>Wasserflecken an der Deckenverkleidung im Bereich der Klimaanlage</li><li>Beschädigungen oder Verformungen der Dichtungen</li></ul>
<p>Bei Auffälligkeiten: Dichtungen erneuern oder Werkstatt aufsuchen.</p>',
            'meta'=>['gc_location'=>'Dachklimaanlage'],'categories'=>['Reparaturanleitungen','Wartung','Campingausstattung Grand California'],'models'=>$gc,'years'=>$ay,'components'=>['Dachklimaanlage','Dichtung']],

            ['title'=>'Dachhauben — Außenreinigung','excerpt'=>'Richtige Reinigung der Dachhauben (Dachlucken) von außen. Säurefreies Reinigungsmittel und Mikrofasertuch verwenden.',
            'content'=>'
<h2>Benötigtes Material</h2><ul><li>Arbeitsplattform oder Leiter</li><li>Mikrofasertuch</li><li>Säurefreies Reinigungsmittel</li></ul>
<h2>Ablauf</h2><ol><li>Dachhaube von außen mit reichlich Wasser abspülen</li><li>Säurefreies Reinigungsmittel auftragen</li><li>Mit Mikrofasertuch vorsichtig reinigen</li><li>Mit klarem Wasser nachspülen</li></ol>
<h3>Wichtig</h3><p>Keine aggressiven Reiniger, Glasreiniger oder Scheuermittel verwenden — das Acrylglas kann reißen (siehe separaten Artikel zur Acrylglas-Pflege).</p>',
            'meta'=>['gc_location'=>'Dach'],'categories'=>['Reparaturanleitungen','Wartung','Campingausstattung Grand California'],'models'=>$gc,'years'=>$ay,'components'=>['Dachhaube','Dachluke','Acrylglas']],

            ['title'=>'Dachhauben — Funktionsprüfung der Aufsteller','excerpt'=>'Regelmäßige Funktionsprüfung der Dachluken-Aufsteller. Auch für Fahrzeuge mit Dachklimaanlage relevant.',
            'content'=>'
<h2>Prüfung</h2><ol><li>Dachhaube vollständig öffnen</li><li>Aufsteller-Mechanismus auf Leichtgängigkeit prüfen</li><li>Dachhaube in verschiedenen Positionen arretieren — hält sie?</li><li>Schließmechanismus prüfen — schließt die Luke dicht?</li></ol>
<p>Bei Schwergängigkeit oder fehlendem Einrasten: Aufsteller reinigen oder tauschen.</p>',
            'meta'=>['gc_location'=>'Dach'],'categories'=>['Reparaturanleitungen','Wartung','Campingausstattung Grand California'],'models'=>$gc,'years'=>$ay,'components'=>['Dachhaube','Aufsteller','Dachluke']],

            ['title'=>'Dachhauben — Sichtprüfung der Profildichtung','excerpt'=>'Regelmäßige Kontrolle der Gummidichtungen an den Dachhauben. Bei Rissen oder Verhärtung muss die Dichtung erneuert werden.',
            'content'=>'
<h2>Benötigtes Material</h2><ul><li>Gummipflegemittel (Talkum oder weiße Vaseline)</li></ul>
<h2>Prüfung</h2><ol><li>Dachhaube öffnen</li><li>Profildichtung umlaufend auf Risse, Verhärtung und Verformung prüfen</li><li>Dichtungssitz kontrollieren — sitzt die Dichtung noch korrekt in der Nut?</li></ol>
<h2>Pflege</h2><p>Dichtungen regelmäßig mit Talkumpuder oder weißer Vaseline pflegen. <strong>Niemals</strong> Silikonspray oder chemische Gummipfleger verwenden — diese können das Acrylglas angreifen.</p>
<p>Bei sichtbaren Schäden: Dichtung erneuern.</p>',
            'meta'=>['gc_location'=>'Dach'],'categories'=>['Reparaturanleitungen','Wartung','Campingausstattung Grand California'],'models'=>$gc,'years'=>$ay,'components'=>['Profildichtung','Dachhaube','Gummipflege']],

            ['title'=>'Besondere Schutzmaßnahmen für den Innenraum bei Werkstattarbeiten','excerpt'=>'Der Innenraum des Grand California ist ein Wohnraum und muss bei Werkstattarbeiten besonders geschützt werden.',
            'content'=>'
<h2>Grundregel</h2>
<p>Der Innenraum des Grand California ist kein normaler Laderaum, sondern ein <strong>Wohnraum</strong>. Bei allen Werkstattarbeiten muss der Innenraum entsprechend geschützt werden.</p>
<h2>Schutzmaßnahmen</h2>
<ul>
<li>Saubere Schutzbezüge auf alle Sitzflächen und die Sitzbank legen</li>
<li>Boden im Wohnbereich mit Abdeckfolie schützen</li>
<li>Holzfurniere und Möbeloberflächen vor Kratzern und Flüssigkeiten schützen</li>
<li>Bei Schweißarbeiten oder Lackierarbeiten: Wohnbereich vollständig abdecken</li>
<li>Keine Werkzeuge oder Teile auf den Möbeln ablegen</li>
<li>Nach Abschluss der Arbeiten: Innenraum reinigen</li>
</ul>',
            'meta'=>['gc_location'=>'Innenraum komplett'],'categories'=>['Reparaturanleitungen','Wartung'],'models'=>$gc,'years'=>$ay,'components'=>['Innenraum','Wohnbereich','Werkstatt']],

            ['title'=>'Wartungs-Zusatzarbeiten am Grand California — Übersicht','excerpt'=>'Übersicht aller zusätzlichen Wartungsarbeiten die spezifisch für den Grand California anfallen — über die normalen Crafter-Inspektionen hinaus.',
            'content'=>'
<h2>Grand California spezifische Wartung</h2>
<p>Neben den regulären Service-Intervallen des Basisfahrzeugs gibt es beim Grand California zusätzliche Wartungsarbeiten für den Campingaufbau.</p>
<h2>Regelmäßige Prüfpunkte</h2>
<table>
<thead><tr><th>Bereich</th><th>Prüfung</th><th>Intervall</th></tr></thead>
<tbody>
<tr><td>Dachklimaanlage</td><td>Pollenfilter reinigen</td><td>Jährlich / bei Bedarf</td></tr>
<tr><td>Dachklimaanlage</td><td>Dichtigkeit zum Innenraum prüfen</td><td>Jährlich</td></tr>
<tr><td>Dachhauben</td><td>Außenreinigung</td><td>Jährlich</td></tr>
<tr><td>Dachhauben</td><td>Aufsteller Funktionsprüfung</td><td>Jährlich</td></tr>
<tr><td>Dachhauben</td><td>Profildichtung Sichtprüfung</td><td>Jährlich</td></tr>
<tr><td>Wassersystem</td><td>Entleeren vor dem Winter</td><td>Vor Frostperiode</td></tr>
<tr><td>Gasanlage</td><td>Dichtigkeitsprüfung (bei Gasheizung)</td><td>Nach jedem Eingriff</td></tr>
<tr><td>Truma Heizung</td><td>Funktion und Fehlerspeicher prüfen</td><td>Jährlich</td></tr>
<tr><td>Markise</td><td>Stoff auf Schäden/Schimmel prüfen</td><td>Jährlich</td></tr>
<tr><td>Aufbaubatterie</td><td>Ladezustand und Anschlüsse prüfen</td><td>Jährlich</td></tr>
</tbody>
</table>',
            'meta'=>['gc_location'=>'Campingaufbau komplett'],'categories'=>['Reparaturanleitungen','Wartung'],'models'=>$gc,'years'=>$ay,'components'=>['Dachklimaanlage','Dachhauben','Wassersystem','Truma Heizung','Markise','Aufbaubatterie']],

            // === SERVICEMASSNAHMEN & RÜCKRUFE ===

            ['title'=>'Servicemaßnahme 23DT — Informationen für Grand California Besitzer','excerpt'=>'Informationen zur Servicemaßnahme 23DT für den Grand California. Werkstattbesuch erforderlich.',
            'content'=>'
<h2>Was ist die Servicemaßnahme 23DT?</h2>
<p>Die Servicemaßnahme 23DT betrifft bestimmte Grand California und Crafter Fahrzeuge. Betroffene Fahrzeughalter werden direkt vom Hersteller informiert.</p>
<h2>Was muss ich tun?</h2>
<p>Wenn du eine Benachrichtigung erhalten hast: Termin bei einer autorisierten Werkstatt vereinbaren. Die Maßnahme wird kostenlos durchgeführt.</p>
<h2>Bin ich betroffen?</h2>
<p>Ob dein Fahrzeug betroffen ist, kannst du über deine FIN bei der Werkstatt oder über den Kundenservice prüfen lassen.</p>',
            'meta'=>['gc_vw_code'=>'Servicemaßnahme 23DT'],'categories'=>['Servicemaßnahmen & Rückrufe'],'models'=>$gc,'years'=>['2022','2023'],'components'=>['Servicemaßnahme']],

            ['title'=>'Servicemaßnahme 94R4 — Informationen für Grand California Besitzer','excerpt'=>'Informationen zur Servicemaßnahme 94R4. Werkstattbesuch erforderlich.',
            'content'=>'
<h2>Was ist die Servicemaßnahme 94R4?</h2>
<p>Die Servicemaßnahme 94R4 betrifft bestimmte Grand California Fahrzeuge. Betroffene Halter werden direkt informiert.</p>
<h2>Was muss ich tun?</h2>
<p>Bei Erhalt einer Benachrichtigung: Termin bei einer autorisierten Werkstatt vereinbaren. Die Durchführung ist kostenlos.</p>',
            'meta'=>['gc_vw_code'=>'Servicemaßnahme 94R4'],'categories'=>['Servicemaßnahmen & Rückrufe'],'models'=>$gc,'years'=>['2022','2023','2024'],'components'=>['Servicemaßnahme']],

            ['title'=>'Servicemaßnahme 23OM mit Kundenbenachrichtigung','excerpt'=>'Servicemaßnahme 23OM mit aktiver Kundenansprache. Betroffene Fahrzeuge werden direkt benachrichtigt.',
            'content'=>'
<h2>Was ist 23OM?</h2>
<p>Die Servicemaßnahme 23OM betrifft einen begrenzten Fertigungszeitraum. Betroffene Fahrzeughalter erhalten eine direkte Benachrichtigung.</p>
<h2>Was muss ich tun?</h2>
<p>Termin bei einer autorisierten Werkstatt vereinbaren. Kostenlose Durchführung.</p>',
            'meta'=>['gc_vw_code'=>'Servicemaßnahme 23OM'],'categories'=>['Servicemaßnahmen & Rückrufe'],'models'=>$gc,'years'=>['2023'],'components'=>['Servicemaßnahme']],

            ['title'=>'Servicemaßnahme 23BB — Motorsteuergerät Softwareupdate (Bi-Turbo)','excerpt'=>'Servicemaßnahme 23BB: Softwareupdate für das Motorsteuergerät bei Fahrzeugen mit Bi-Turbo Motor.',
            'content'=>'
<h2>Was wird gemacht?</h2>
<p>Im Rahmen der Servicemaßnahme 23BB wird ein Softwareupdate für das Motorsteuergerät durchgeführt. Betroffen sind Fahrzeuge mit Bi-Turbo Motor.</p>
<h2>Betroffene Fahrzeuge</h2>
<ul><li>Grand California und Crafter mit Bi-Turbo Dieselmotor</li><li>Begrenzter Fertigungszeitraum</li></ul>
<h2>Was muss ich tun?</h2>
<p>Bei Benachrichtigung: Werkstatttermin vereinbaren. Das Update dauert in der Regel 30–60 Minuten. Kostenlos.</p>',
            'meta'=>['gc_vw_code'=>'Servicemaßnahme 23BB'],'categories'=>['Servicemaßnahmen & Rückrufe'],'models'=>$gc,'years'=>['2024','2025 (Facelift)'],'components'=>['Motorsteuergerät','Bi-Turbo','Softwareupdate']],

            ['title'=>'Rückrufaktion 75A3 — Informationen für Betroffene','excerpt'=>'Informationen zur Rückrufaktion 75A3. Sicherheitsrelevanter Rückruf — bitte zeitnah die Werkstatt aufsuchen.',
            'content'=>'
<h2>Rückrufaktion 75A3</h2>
<p>Die Rückrufaktion 75A3 betrifft bestimmte Grand California Fahrzeuge. Es handelt sich um einen sicherheitsrelevanten Rückruf.</p>
<h2>Was muss ich tun?</h2>
<p><strong>Bitte zeitnah einen Termin bei einer autorisierten Werkstatt vereinbaren.</strong> Die Durchführung ist kostenlos. Du wirst per Post oder über die App benachrichtigt.</p>
<h2>Bin ich betroffen?</h2>
<p>Die Werkstatt kann anhand deiner FIN prüfen, ob dein Fahrzeug betroffen ist.</p>',
            'meta'=>['gc_vw_code'=>'Rückruf 75A3'],'categories'=>['Servicemaßnahmen & Rückrufe'],'models'=>$gc,'years'=>['2022'],'components'=>['Rückruf']],

            ['title'=>'Rückrufaktion 76AF — Informationen für Betroffene','excerpt'=>'Informationen zur Rückrufaktion 76AF. Betrifft Grand California Modelljahr 2024.',
            'content'=>'
<h2>Rückrufaktion 76AF</h2>
<p>Die Rückrufaktion 76AF betrifft Grand California Fahrzeuge aus dem Modelljahr 2024.</p>
<h2>Was muss ich tun?</h2>
<p>Bei Erhalt der Benachrichtigung: <strong>zeitnah</strong> Werkstatttermin vereinbaren. Die Maßnahme ist kostenlos.</p>',
            'meta'=>['gc_vw_code'=>'Rückruf 76AF'],'categories'=>['Servicemaßnahmen & Rückrufe'],'models'=>$gc,'years'=>['2024'],'components'=>['Rückruf']],

            // === SONSTIGES ===

            ['title'=>'Over-The-Air Update (OUC2) — Was du wissen musst','excerpt'=>'Das Grand California kann Software-Updates drahtlos empfangen. Hier erfährst du alles zum OTA-Update OUC2.',
            'content'=>'
<h2>Was ist ein OTA-Update?</h2>
<p>Over-The-Air (OTA) Updates ermöglichen es, bestimmte Steuergeräte-Software drahtlos zu aktualisieren — ohne Werkstattbesuch. Das Update OUC2 wurde für bestimmte Grand California Fahrzeuge bereitgestellt.</p>
<h2>Wie funktioniert es?</h2>
<ol>
<li>Du erhältst eine Benachrichtigung im Infotainment-System oder in der App</li>
<li>Update herunterladen (am besten bei WLAN-Verbindung oder gutem Mobilfunkempfang)</li>
<li>Installation bestätigen</li>
<li>Das Fahrzeug muss während der Installation stehen und die Zündung eingeschaltet sein</li>
</ol>
<h3>Hinweis</h3>
<p>OTA-Updates betreffen nur bestimmte Steuergeräte. Für größere Updates (z.B. Motorsteuergerät) ist weiterhin ein Werkstattbesuch nötig.</p>',
            'meta'=>['gc_vw_code'=>'OUC2'],'categories'=>['Servicehinweise','Lösungen & Workarounds'],'models'=>$gc,'years'=>['2022','2023','2024'],'components'=>['OTA-Update','Infotainment','Steuergerät']],

            ['title'=>'Windgeräusche an der vorderen Dachluke — Abhilfe (TPI 2067465)','excerpt'=>'Störende Windgeräusche an der vorderen Dachluke? Dichtung und Schließmechanismus können die Ursache sein.',
            'content'=>'
<h2>Problem</h2>
<p>Bei höheren Geschwindigkeiten treten Windgeräusche an der vorderen Dachhaube (Dachluke) auf.</p>
<h2>Mögliche Ursachen</h2>
<ul>
<li>Dichtung der Dachluke ist verhärtet oder verformt</li>
<li>Schließmechanismus rastet nicht vollständig ein</li>
<li>Dachluke sitzt nicht plan auf dem Rahmen</li>
</ul>
<h2>Lösung</h2>
<ol>
<li>Dichtung auf Beschädigungen und Verhärtung prüfen — bei Bedarf tauschen</li>
<li>Schließmechanismus auf festen Sitz und vollständiges Einrasten prüfen</li>
<li>Dachluke schließen und von außen prüfen, ob sie plan aufliegt</li>
</ol>',
            'meta'=>['gc_vw_code'=>'TPI 2067465/1','gc_location'=>'Dach, vordere Dachluke'],'categories'=>['Technische Produktinformationen (TPI)','Aufbau & Ausstattung'],'models'=>$gc,'years'=>['2022','2023','2024'],'components'=>['Dachluke','Dachhaube','Windgeräusche','Dichtung']],

            ['title'=>'Einbauorte der Innenleuchten — Übersicht','excerpt'=>'Wo sitzen welche Innenleuchten im Grand California? Übersicht aller Leuchtenpositionen im Wohnbereich.',
            'content'=>'
<h2>Innenleuchten im Wohnbereich</h2>
<p>Der Grand California verfügt über zahlreiche Innenleuchten im Campingaufbau. Alle werden über das Steuergerät für Ambientebeleuchtung (J1124) und die Bedieneinheit E153 gesteuert.</p>
<h2>Leuchtenpositionen</h2>
<table>
<thead><tr><th>Bauteil</th><th>Bezeichnung</th><th>Position</th></tr></thead>
<tbody>
<tr><td>W7</td><td>Innenleuchte Mitte</td><td>Decke, Mitte</td></tr>
<tr><td>W11</td><td>Leseleuchte hinten links</td><td>Seitenwand links</td></tr>
<tr><td>W12</td><td>Leseleuchte hinten rechts</td><td>Seitenwand rechts</td></tr>
<tr><td>W16</td><td>Innenleuchte links</td><td>Decke, links</td></tr>
<tr><td>W17</td><td>Innenleuchte rechts</td><td>Decke, rechts</td></tr>
<tr><td>W24</td><td>Leseleuchte Ausziehtisch</td><td>Über dem Tisch</td></tr>
<tr><td>W34</td><td>Einstiegsleuchte hinten</td><td>Schiebetür</td></tr>
<tr><td>W43</td><td>Innenleuchte hinten</td><td>Decke, hinten</td></tr>
<tr><td>W44</td><td>Leseleuchte hinten Mitte</td><td>Über dem Bett</td></tr>
<tr><td>W48</td><td>Innenleuchte hinten rechts</td><td>Decke, hinten rechts</td></tr>
<tr><td>W57</td><td>Küchenleuchte</td><td>Über der Küchenzeile</td></tr>
<tr><td>W65</td><td>Leseleuchte vorn links</td><td>Seitenwand vorn</td></tr>
<tr><td>W135</td><td>Stauraumleuchte</td><td>Im Stauraum</td></tr>
<tr><td>W140</td><td>Duschbeleuchtung</td><td>Nasszelle</td></tr>
<tr><td>W143</td><td>Duschbeleuchtung 2</td><td>Nasszelle</td></tr>
</tbody>
</table>',
            'meta'=>['gc_location'=>'Innenraum komplett'],'categories'=>['Elektrik','Beleuchtung','Campingausstattung Grand California'],'models'=>$gc,'years'=>$ay,'components'=>['Innenleuchten','W7','W11','W12','W16','W17','W24','W57','W135','W140','J1124 Ambientebeleuchtung']],

            ['title'=>'Einbauorte der Folierung — Übersicht','excerpt'=>'Übersicht aller Folierbereiche am Grand California (C-Säule, D-Säule, weitere Abdeckungen).','content'=>'
<h2>Folierung am Grand California</h2>
<p>Der Grand California hat an verschiedenen Stellen dekorative Folierungen (Abdeckfolien). Diese Übersicht zeigt, welche Bereiche foliert sind.</p>
<h2>Folierpositionen</h2>
<ul>
<li><strong>B-Säule:</strong> Abdeckfolie links und rechts</li>
<li><strong>B–C-Säule:</strong> Abdeckfolie im Übergangsbereich</li>
<li><strong>C–D-Säule:</strong> Abdeckfolie links und rechts</li>
<li><strong>Hecktür:</strong> Abdeckfolie links und rechts</li>
</ul>
<p>Bei Beschädigungen können die Folien einzeln getauscht werden. Beim Zebra-Design gelten besondere Regeln (siehe TPI 2066033).</p>',
            'meta'=>['gc_location'=>'Karosserie außen'],'categories'=>['Karosserie','Folierung'],'models'=>$gc,'years'=>$ay,'components'=>['Folierung','B-Säule','C-Säule','D-Säule','Hecktür']],

        ];
    }
}

add_action('admin_init',function(){
    if(isset($_GET['gc_import_batch3'])&&current_user_can('manage_options')){
        $r=GC_Batch3_Import::run();
        add_action('admin_notices',function()use($r){echo '<div class="notice notice-success"><p>'.esc_html($r).'</p></div>';});
    }
});
