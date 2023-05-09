<script setup lang="ts">
    import AuthLayout from '../Layouts/AuthLayout.vue'
    import { Head } from '@inertiajs/inertia-vue3'

    import { Settings } from '../Interfaces/Settings'
    import { PropType } from 'vue'

    let props = defineProps({
        settings: {
            type: Object as PropType<Settings>,
            required: true,
        }
    })
</script>

<template>
    <div v-cloak>
        <Head>
            <title>Barrierefreiheit</title>
        </Head>

        <AuthLayout>
            <template #main>
                <main>
                    <h1 class="text-headline-xl">Erklärung zur Barrierefreiheit</h1>

                    <h2 class="text-headline">Barrierefreiheit dieser Internetseite</h2>
                    <p>
                        Diese Erklärung zur Barrierefreiheit gilt für die unter der {{ settings.gdpr.domain_url }}
                        veröffentlichten Website der {{ settings.gdpr.domain_owner }}.
                    </p>

                    <p>
                        Als öffentliche Stelle im Sinne der Richtlinie (EU) 2016/2102 sind wir bemüht, unsere Websites
                        und mobilen Anwendungen im Einklang mit den Bestimmungen des Behindertengleichstellungsgesetzes
                        (BGG NRW) sowie der Barrierefreien-Informationstechnik-Verordnung NRW (BITV NRW) zur Umsetzung
                        der Richtlinie (EU) 2016/2102 barrierefrei zugänglich zu machen.
                    </p>

                    <h2 class="text-headline">Stand der Vereinbarkeit mit den Anforderungen</h2>
                    <p>
                        Die Anforderungen der Barrierefreiheit ergeben sich aus Paragraphen 3 Absätze 1 bis 4 und
                        Paragraph 4 der Barrierefreien-Informationstechnik-Verordnung NRW (BITV NRW), die auf der
                        Grundlage von § 10e Behindertengleichstellungsgesetz (BGG NRW) erlassen wurde.
                    </p>

                    <p>Die Überprüfung der Einhaltung der Anforderungen beruht auf:</p>

                    <ol>
                        <li>einer am / im Zeitraum von durchgeführten Selbstbewertung</li>
                        <li>einer von [Name der Prüfinstitution ergänzen] am / im Zeitraum von vorgenommenen Bewertung durch [genauere Beschreibung des Bewertungsverfahrens und Link zum Bewertungsbericht ergänzen].</li>
                        <li>Aufgrund der Überprüfung ist die Website/ mobile Anwendung mit den zuvor genannten Anforderungen [Unzutreffendes streichen]</li>
                        <li>vollständig [nur zutreffend, wenn alle Anforderungen der Normen oder technischen Spezifikationen vollständig und ausnahmslos erfüllt sind.]</li>
                        <li>wegen der folgenden [Unvereinbarkeiten] [und/oder] [Ausnahmen] teilweise [nur zutreffend, wenn die meisten Anforderungen der Normen oder technischen Spezifikationen mit einigen wenigen Ausnahmen erfüllt sind. In diesem Fall empfiehlt es sich zu beschreiben, wann und wie die noch bestehenden Barrieren beseitigt werden sollen.]</li>
                        <li>nicht [nur zutreffend, wenn die meisten Anforderungen der Normen oder technischen Spezifikationen nicht erfüllt sind] vereinbar.</li>
                    </ol>

                    <p><strong>Die nachstehend aufgeführten Inhalte sind aus folgenden Gründen nicht barrierefrei:</strong></p>

                    <ol>
                        <li>
                            <p>
                                Der [Führen Sie die nicht barrierefreien Abschnitte/Inhalte/Funktionen auf] ist mit dem
                                Erfolgskriterium [Nennen Sie das Erfolgskriterium aus der zu Grunde liegenden europäisch
                                harmonisierten Norm EN 301549 mit Bezug auf die referenzierte WCAG 2.1 und beschreiben
                                Sie den Mangel in nicht allzu technischer Form; z. B.: ‚Das Log-in-Formular der
                                Anwendung für den Dokumentenaustausch ist per Tastatur nicht vollständig nutzbar im
                                Sinne der Anforderung XX nach XX] nicht vereinbar.
                            </p>
                            <p>
                                Folgende Maßnahmen zur Erreichung der Barrierefreiheit sind
                                [Geben Sie Abhilfemaßnahmen] bis [mit Zeitrahmen] geplant.
                            </p>
                            <p>
                                Folgende barrierefreie Alternativen, um die Inhalte zu erreichen,
                                halten wir für Sie bereit.
                            </p>
                            <p>[Geben Sie jeweils etwaige barrierefreie Alternativen an]</p>
                        </li>
                        <li>
                            <p>
                                Der [Führen Sie die nicht barrierefreien Abschnitte/Inhalte/Funktionen auf] ist aufgrund
                                einer unverhältnismäßigen Belastung nach § 10 Absatz 4 BGG NRW vorübergehend nicht
                                barrierefrei zugänglich gestaltet. [Diese Ausnahmen sind im Sinne der Richtlinie sowie
                                des BGG NRW sehr eng auszulegen; weiterhin sind die sich anschließenden Vorschriften im
                                Sinne des Artikel 5 Richtlinie (EU) 2016/2102 zu beachten]
                            </p>
                            <p>
                                Folgende Maßnahmen zur Erreichung der Barrierefreiheit sind [Geben Sie Abhilfemaßnahmen]
                                bis [mit Zeitrahmen] geplant.
                            </p>
                            <p>
                                Folgende barrierefreie Alternativen, um die Inhalte zu erreichen,
                                halten wir für Sie bereit.
                            </p>
                            <p>[Geben Sie jeweils etwaige barrierefreie Alternativen an]</p>
                        </li>
                        <li>
                            <p>
                                3. Der [Führen Sie die nicht barrierefreien Abschnitte/Inhalte/Funktionen auf, die nicht
                                in den Anwendungsbereich der anwendbaren Rechtsvorschriften fallen] ist nicht barrierefrei
                                zugänglich gestaltet, da diese Inhalte nach § 2 Absätze 2 und 3 BITV NRW nicht in den
                                Anwendungsbereich der anwendbaren Rechtsvorschriften fallen.
                            </p>
                            <p>Fakultativ barrierefreie Inhalte</p>
                            <p>
                                [nur ausfüllen, falls diese Inhalte vorhanden sind, dann in einer einfachen Liste;
                                ansonsten streichen samt Überschrift]
                            </p>
                            <p>
                                Folgende Inhalte sind aufgrund der Absicht, ein höheres Maß an digitaler
                                Barrierefreiheit als gesetzlich gefordert umzusetzen, realisiert:
                            </p>
                        </li>
                    </ol>

                    <h2 class="text-headline">Datum der Erstellung bzw. der letzten Aktualisierung der Erklärung</h2>
                    <p>
                        Diese Erklärung wurde am {{ settings.gdpr.creation_date }} erstellt
                        und zuletzt am {{ settings.gdpr.last_update }} aktualisiert.
                    </p>

                    <h2 class="text-headline">Barrieren melden: Kontakt zu den Feedback Ansprechpartnern</h2>
                    <p>
                        Sie möchten uns bestehende Barrieren mitteilen oder Informationen zur Umsetzung der
                        Barrierefreiheit erfragen? Für Ihr Feedback sowie alle weiteren Informationen sprechen Sie
                        unsere verantwortlichen Kontaktpersonen unter {{ settings.gdpr.report_contact_person }} an.
                    </p>

                    <p v-if="settings.gdpr.feedback_form_url">
                        <a
                            :href="settings.gdpr.feedback_form_url"
                            title="Barrieren melden [Externe Ressource]"
                            target="_blank"
                            rel="noreferrer noopener nofollow"
                        >
                            Barrieren melden
                        </a>
                    </p>

                    <p v-else>
                        [verlinkte URL mit Name des Feedback-Mechanismus, z. B. „Barrieren melden“ angeben. Dort –
                        und nicht hier in der Erklärung zur Barrierefreiheit – sind die Kontaktdaten sowie weitere
                        Erläuterungen zur Nutzung des Feedback-Mechanismus zu geben. Insbesondere ist auf die Leitlinie
                        zur Umsetzung und den Anforderungen zum Feedback-Mechanismus zu achten]
                    </p>

                    <h2 class="text-headline">Schlichtungsverfahren</h2>
                    <p>
                        Wenn auch nach Ihrem Feedback an den oben genannten Kontakt keine zufriedenstellende Lösung
                        gefunden wurde, können Sie sich an die Schlichtungsstelle nach § 10d Behindertengleichstellungsgesetz
                        NRW (BGG NRW) wenden. Die Schlichtungsstelle BGG NRW hat die Aufgabe, bei Konflikten zum Thema
                        Barrierefreiheit zwischen Menschen mit Behinderungen und öffentlichen Stellen des Bundes eine
                        außergerichtliche Streitbeilegung zu unterstützen. Das Schlichtungsverfahren ist kostenlos.
                        Es muss kein Rechtsbeistand eingeschaltet werden. Weitere Informationen zum Schlichtungsverfahren
                        und den Möglichkeiten der Antragstellung erhalten Sie unter:

                        <a
                            href="https://www.mags.nrw/ombudsstelle-barrierefreie-informationstechnik"
                            target="_blank"
                            rel="nofollow noopener noreferrer"
                            title="Ombudsstelle barrierefreie Informationstechnik [Externe Ressource]"
                        >
                            https://www.mags.nrw/ombudsstelle-barrierefreie-informationstechnik
                        </a>
                    </p>
                    <p>
                        Direkt kontaktieren können Sie die Schlichtungsstelle BGG NRW unter
                        <a
                            href="mailto:ombudsstelle-barrierefreie-it@mags.nrw.de"
                            target="_blank"
                            rel="nofollow noopener noreferrer"
                            title="Nachricht schreiben an ombudsstelle-barrierefreie-it@mags.nrw.de"
                        >
                            ombudsstelle-barrierefreie-it@mags.nrw.de
                        </a>
                    </p>
                    <p>Das oben beschriebene Muster erhebt keinen Anspruch auf Vollständigkeit.</p>
                    <p>Link zum Durchführungsbeschluss der EU: Durchführungsbeschluss (EU) 2018/1523</p>
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
        @apply ui-list-decimal ui-list-outside ui-ml-6 ui-text-lg ui-space-y-3
    }

    ol li {
        @apply ui-pl-2 ui-space-y-3
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
