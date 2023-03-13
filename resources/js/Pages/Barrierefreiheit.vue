<script setup lang="ts">
    import AuthLayout from '../Layouts/AuthLayout.vue'
    import { Head } from '@inertiajs/inertia-vue3'
</script>

<template>
    <div v-cloak>
        <Head>
            <title>Barrierefreiheit</title>
        </Head>

        <AuthLayout>
            <template #main>
                <main>
                    <h1 class="headline-1">Erklärung zur Barrierefreiheit</h1>

                    <h2 class="headline-2">Barrierefreiheit dieser Internetseite</h2>
                    <p>Das Ministerium für Schule und Bildung des Landes Nordrhein-Westfalen ist bemüht, seinen Webauftritt barrierefrei zugänglich zu machen. Internetseiten sollen so gestaltet sein, dass sie im Einklang mit den nationalen Rechtsvorschriften zur Umsetzung der Richtlinie (EU) 2016/2102 des Europäischen Parlaments stehen. Die folgende Erklärung zur Barrierefreiheit gilt für den Internetauftritt <a href="https://www.schulministerium.nrw.de/suche" target="_blank">www.schulministerium.nrw</a> und wurde am 25. September 2020 erstellt.</p>

                    <h2 class="headline-2">Stand der Vereinbarkeit mit den Anforderungen zur barrierefreien Informationstechnik</h2>
                    <p>Wir haben den Webauftritt am 12. Oktober 2020 auf seine Barrierefreiheit getestet. Die Unvereinbarkeiten sind nachstehend aufgeführt. Die Anforderungen der Barrierefreiheit ergeben sich aus Paragraphen 3 Absätze 1 bis 4 und Paragraph 4 der Barrierefreien-Informationstechnik-Verordnung NRW (BITV NRW), die auf der Grundlage von § 10e Behindertengleichstellungsgesetz (BGG NRW) erlassen wurde. Die Überprüfung der Einhaltung der Anforderungen beruht auf einer von Kompetenzzentrum Barrierefreie IT (KBIT) bei IT.NRW im Zeitraum vom 5. Bis 12. Oktober 2020 vorgenommenen Bewertung auf Basis der Prüfschritte des WCAG Testverfahrens. Aufgrund der Überprüfung ist die Website mit den zuvor genannten Anforderungen wegen der folgenden Unvereinbarkeiten teilweise vereinbar. Die nachstehend aufgeführten Inhalte sind nicht barrierefrei:</p>
                    <ol>
                        <li>Auf allen getesteten Seiten ist der Prüfschritt 1.1.1b Alternativtexte für Grafiken und Objekte, nicht erfüllt. Die Onlineredaktion hat die Texte auf den geprüften Seiten bereits angepasst. Die Adaption sämtlicher Alternativtexte für Grafiken und Objekte wird angestrebt, sie wird jedoch aufgrund des großen Umfangs einige Zeit in Anspruch nehmen.</li>
                        <li>Auf allen getesteten Seiten ist der Prüfschritt 2.4.7a Aktuelle Position des Fokus deutlich, nicht erfüllt. Grund: Sobald ein Element im unteren Bereich der Seite angesteuert wird, ist der Fokus außerhalb des sichtbaren Bereichs im Browserfenster.</li>
                        <li>Auf allen getesteten Seiten ist der Prüfschritt 4.1.1a Korrekte Syntax, nicht erfüllt. Grund: Auf den angegebenen Seiten sind Probleme in der HTML Struktur vorhanden, die zu Fehlern in verschiedenen Programmen führen können. Der nachstehend aufgeführte Inhalt ist teilweise barrierefrei:</li>
                        <li>Auf der Seite Suche <a href="https://www.schulministerium.nrw.de/suche" target="_blank">www.schulministerium.nrw</a> ist der Prüfschritt 1.4.11a Kontraste von Grafiken und grafischen Bedienelementen ausreichend, teilweise erfüllt. Grund: Die Schaltflächen Suchen und Filter haben keinen ausreichenden Kontrast. Zu den Punkten 2 bis 4: Wir arbeiten derzeit daran, die Zugänglichkeit der Seite zu verbessern. Dafür bitten wir jedoch um etwas Geduld, da für diese Optimierungen im Rahmen des CMS Drupal teilweise in die Programmierung der Seite eingegriffen werden muss.</li>
                    </ol>

                    <h2 class="headline-2">Erstellung dieser Erklärung</h2>
                    <p>Diese Erklärung wurde am 30. November 2020 zu Düsseldorf erstellt.</p>

                    <h2 class="headline-2">Feedback und Kontakt</h2>
                    <p>Sind Ihnen Mängel beim barrierefreien Zugang zu Inhalten auf dieser Seite aufgefallen? Oder haben Sie Fragen zum Thema Barrierefreiheit? Dann können Sie sich gerne bei uns melden. Bitte benutzen Sie dafür das vorgesehene Kontaktformular auf dieser Website: <a href="www.land.nrw/de/servicecenter/kontakt" target="_blank">www.land.nrw/de/servicecenter/kontakt</a>Bitte beachten Sie, dass mit dem Absenden des Formulars die von Ihnen angegebenen personenbezogenen Daten sowie die besonderen Daten (§ 3 Abs. 9 BDSG, z.B. politische Meinungen) an die Landesregierung Nordrhein-Westfalen übermittelt und zur Beantwortung der Anfrage verwendet werden. Beachten Sie hierzu bitte auch unsere <a href="https://www.schulministerium.nrw/impressum-haftungsausschluss-datenschutzbestimmungen" target="_blank">Datenschutzhinweise</a>. Sie können uns auch per Post oder telefonisch kontaktieren:</p>
                    <ul>
                        <li>Ministerium für Schule und Bildung des Landes Nordrhein-Westfalen</li>
                        <li>Völklinger Straße 49</li>
                        <li>40221 Düsseldorf</li>
                        <li>Telefon: (0211) 58 67 - 40</li>
                    </ul>

                    <h2 class="headline-2">Überwachungsstelle für barrierefreie Informationstechnik des Landes Nordrhein-Westfalen</h2>
                    <p>Die Überwachungsstelle für barrierefreie Informationstechnik des Landes Nordrhein-Westfalen prüft regelmäßig, ob und inwiefern Internetseiten und mobile Anwendungen öffentlicher Stellen des Landes den Anforderungen an die Barrierefreiheit genügen. Ziel der Arbeit der Überwachungsstelle ist es, die Einhaltung der Anforderungen an die barrierefreie Informationstechnik sicherzustellen und für eine flächendeckende Umsetzung der gesetzlichen Regelungen zu sorgen. Eine E-Mail an die Überwachungsstelle können Sie an die E-Mail-Adresse <a href="mailto:ombudsstelle-barrierefreie-it@mags.nrw.de" target="_blank">ombudsstelle-barrierefreie-it@mags.nrw.de</a> senden. Weitere Informationen zur Überwachungsstelle finden Sie hier: <a href="https://www.mags.nrw/ueberwachungsstelle-barrierefreie-informationstechnik" target="_blank">www.mags.nrw/ueberwachungsstelle-barrierefreie-informationstechnik</a></p>
                    <h2 class="headline-2">Schlichtungsverfahren/ Durchsetzungsverfahren</h2>
                    <p>Sollten Sie auf Mitteilungen oder Anfragen zur barrierefreien Informationstechnik dieser Internetseite keine zufriedenstellende Antwort erhalten, können Sie die Ombudsstelle für barrierefreie Informationstechnik des Landes Nordrhein-Westfalen einschalten. Die Ombudsstelle ist der Beauftragten für die Belange der Menschen mit Behinderung zugeordnet und in §§ 10d, 10e BGG NRW und §§ 9 ff der BITV NRW gesetzlich verankert. Unter Einbeziehung aller Beteiligten versucht die Ombudsstelle, die Umstände der fehlenden Barrierefreiheit zu ermitteln, damit der Träger diese beheben kann. Dabei geht es nicht darum, Gewinner oder Verlierer zu finden. Vielmehr ist es das Ziel, mit Hilfe der Schlichtungsstelle gemeinsam und außergerichtlich eine Lösung für ein Problem zu finden. Das Schlichtungsverfahren ist kostenlos. Sie brauchen auch keinen Rechtsbeistand. Telefonisch ist die Ombudsstelle für barrierefreie Informationstechnik NRW unter folgender Rufnummer zu erreichen: 0211 / 855-3451. Weitere Informationen zur Ombudsstelle für barrierefreie Informationstechnik NRW finden Sie hier: <a href="https://www.mags.nrw/ombudsstelle-barrierefreie-informationstechnik" target="_blank">www.mags.nrw/ombudsstelle-barrierefreie-informationstechnik</a></p>
                </main>
            </template>
        </AuthLayout>
    </div>
</template>

<style scoped>
    main {
        @apply ui-bg-white ui-rounded-lg ui-shadow-lg ui-p-8 ui-flex ui-flex-col ui-gap-6 ui-w-full ui-max-w-6xl ui-mx-auto ui-my-8 ui-overflow-y-auto
    }

    h1 {
        @apply ui-uppercase
    }

    h1, h2 {
        @apply ui-border-b ui-border-gray-300 ui-pb-2 ui-leading-relaxed
    }

    p {
        @apply ui-text-lg ui-leading-7
    }

    ul {
        @apply ui-list-disc ui-list-outside ui-ml-6 ui-text-lg
    }

    ol {
        @apply ui-list-decimal ui-list-outside ui-ml-6 ui-text-lg ui-flex ui-flex-col ui-gap-3
    }

    ol li {
        @apply ui-pl-2
    }

    a {
        @apply
        ui-decoration-black/50 hover:ui-decoration-black
        focus-visible:ui-outline focus-visible:ui-text-black/75 ui-outline-offset-2 ui-outline-2 ui-outline-black
        ui-underline
        ui-rounded-sm
        ui-break-all
    }
</style>
