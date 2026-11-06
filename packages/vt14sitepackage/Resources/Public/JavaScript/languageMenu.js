/**
 * languageMenu.js
 * ---------------
 * Dieses Skript steuert das Sprachumschaltmenü in der Navigation.
 *
 * Was es tut:
 *  1. Beim Laden der Seite liest es die aktuelle Sprache aus dem <html>-Tag
 *     (z. B. lang="de") und zeigt das zugehörige Kürzel (z. B. "DE") neben
 *     dem Globus-Icon an.
 *  2. Im Sprachmenü wird der Link der aktiven Sprache optisch hervorgehoben
 *     und erhält ein aria-current-Attribut für Screenreader.
 *  3. Ein Klick auf den Sprachbutton öffnet oder schließt das Menü.
 *     Klickt man irgendwo außerhalb, schließt sich das Menü automatisch.
 *  4. Das Menü ist vollständig per Tastatur bedienbar:
 *     - Pfeiltasten hoch/runter navigieren zwischen den Einträgen.
 *     - Escape schließt das Menü und setzt den Fokus zurück auf den Button.
 *
 * Voraussetzungen im HTML:
 *  - Ein Button mit id="language-button" und darin ein <span id="current-language">
 *  - Eine Liste/Container mit id="language-menu", deren Links ein
 *    data-lang-Attribut tragen (z. B. data-lang="de").
 */
document.addEventListener('DOMContentLoaded', function () {
    const languageButton = document.getElementById('language-button');
    const languageMenu = document.getElementById('language-menu');
    const currentLanguageSpan = document.getElementById('current-language');

    // Aktive Sprache der Website ermitteln
    const currentLanguageCode = document.documentElement.lang.substr(0, 2); // 'de', 'en', etc.

    const ariaLabels = {
        'de': 'Sprache wechseln',
        'en': 'Change language',
        'fr': 'Changer de langue',
        'it': 'Cambiare la lingua',
        'es': 'Cambiar idioma',
        'pt': 'Mudar idioma',
        'nl': 'Taal wijzigen',
        'pl': 'Zmień język',
        'sv': 'Byt språk',
        'da': 'Skift sprog',
        'no': 'Bytt språk',
        'fi': 'Vaihda kieltä',
        'cs': 'Změnit jazyk',
        'hu': 'Nyelvet váltani',
        'ro': 'Schimbați limba',
        'el': 'Αλλαγή γλώσσας',
        'ru': 'Изменить язык',
        'uk': 'Змінити мову',
        'tr': 'Dil değiştir',
        'ar': 'تغيير اللغة',
        'zh': '切换语言',
        'ja': '言語を変更',
        'ko': '언어 변경'
    };
    //console.log(document.documentElement.lang);

    languageButton.setAttribute('aria-label', ariaLabels[currentLanguageCode] || 'Change language');

    // Aktive Sprache neben dem Icon anzeigen
    currentLanguageSpan.textContent = currentLanguageCode.toUpperCase();

    // Aktive Sprache im Menü hervorheben
    const menuItems = languageMenu.querySelectorAll('a');
    menuItems.forEach(function (item) {
        if (item.getAttribute('data-lang') === currentLanguageCode) {
            item.classList.add('active-language');
            item.setAttribute('aria-current', 'page');
        } else {
            item.classList.remove('active-language');
            item.removeAttribute('aria-current');
        }
    });

    // Menüanzeige umschalten
    languageButton.addEventListener('click', function (event) {
        event.preventDefault();
        const expanded = languageButton.getAttribute('aria-expanded') === 'true';
        languageButton.setAttribute('aria-expanded', !expanded);
        languageMenu.setAttribute('aria-hidden', expanded);
    });

    // Menü schließen, wenn außerhalb geklickt wird
    document.addEventListener('click', function (event) {
        if (!languageButton.contains(event.target) && !languageMenu.contains(event.target)) {
            languageButton.setAttribute('aria-expanded', 'false');
            languageMenu.setAttribute('aria-hidden', 'true');
        }
    });

    // Tastaturnavigation
    languageButton.addEventListener('keydown', function (event) {
        if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            languageButton.click();
            languageMenu.querySelector('a').focus();
        }
    });

    menuItems.forEach(function (item) {
        item.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                if (item.parentElement.nextElementSibling) {
                    item.parentElement.nextElementSibling.querySelector('a').focus();
                } else {
                    menuItems[0].focus();
                }
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                if (item.parentElement.previousElementSibling) {
                    item.parentElement.previousElementSibling.querySelector('a').focus();
                } else {
                    menuItems[menuItems.length - 1].focus();
                }
            } else if (event.key === 'Escape') {
                languageButton.focus();
                languageButton.setAttribute('aria-expanded', 'false');
                languageMenu.setAttribute('aria-hidden', 'true');
            }
        });
    });
});
