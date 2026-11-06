/*!
 * VT14 Popup — Content Block JavaScript
 *
 * Frequenzsteuerung via Web Storage (kein Cookie):
 *   always  → immer anzeigen
 *   once    → einmalig, dauerhaft gespeichert (localStorage)
 *   session → einmal pro Browser-Sitzung (sessionStorage)
 *   daily   → einmal täglich (localStorage + Zeitstempel)
 *   weekly  → einmal wöchentlich (localStorage + Zeitstempel)
 *   monthly → einmal monatlich (localStorage + Zeitstempel)
 */
(function () {
    'use strict';

    /** Zeitintervalle in Millisekunden für zeitbasierte Frequenzmodi. */
    const INTERVALS = {
        daily:   24 * 60 * 60 * 1000,
        weekly:  7  * 24 * 60 * 60 * 1000,
        monthly: 30 * 24 * 60 * 60 * 1000,
    };

    /** Eindeutiger Schlüssel für einen Popup anhand seiner Content-Element-UID. */
    function storageKey(uid) {
        return 'vt14popup_' + uid;
    }

    /**
     * Prüft anhand der konfigurierten Häufigkeit und des Web-Storage-Eintrags,
     * ob das Pop-up dem aktuellen Besucher angezeigt werden soll.
     */
    function shouldShow(frequency, uid) {
        if (frequency === 'always') {
            return true;
        }

        const key = storageKey(uid);

        if (frequency === 'session') {
            return sessionStorage.getItem(key) === null;
        }

        const stored = localStorage.getItem(key);

        if (frequency === 'once') {
            return stored === null;
        }

        if (stored === null) {
            return true;
        }

        const elapsed = Date.now() - parseInt(stored, 10);
        return elapsed >= (INTERVALS[frequency] ?? Infinity);
    }

    /**
     * Speichert nach der Anzeige, dass das Pop-up gezeigt wurde,
     * damit die Frequenzlogik beim nächsten Besuch greifen kann.
     */
    function recordShown(frequency, uid) {
        if (frequency === 'always') {
            return;
        }

        const key = storageKey(uid);

        if (frequency === 'session') {
            sessionStorage.setItem(key, '1');
        } else {
            localStorage.setItem(key, String(Date.now()));
        }
    }

    /** Öffnet das Pop-up mit der Einblend-Animation. */
    function openPopup(popup) {
        popup.setAttribute('aria-hidden', 'false');
        popup.classList.add('vt14-popup--open');
        document.body.classList.add('vt14-popup-lock');
    }

    /**
     * Schließt das Pop-up mit der Ausblend-Animation und räumt danach
     * alle CSS-Klassen sowie den Scroll-Lock auf.
     */
    function closePopup(popup) {
        const dialog = popup.querySelector('.vt14-popup__dialog');

        popup.classList.add('vt14-popup--closing');

        // Warte auf das Ende der CSS-Animation; falle sofort zurück,
        // wenn keine Animation aktiv ist (z.B. prefers-reduced-motion).
        const animations = dialog.getAnimations ? dialog.getAnimations() : [];

        function finalize() {
            popup.classList.remove('vt14-popup--open', 'vt14-popup--closing');
            popup.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('vt14-popup-lock');
        }

        if (animations.length > 0) {
            dialog.addEventListener('animationend', finalize, { once: true });
        } else {
            finalize();
        }
    }

    /** Initialisiert ein einzelnes Popup-Element und bindet alle Event-Handler. */
    function initPopup(popup) {
        const uid       = popup.dataset.popupUid;
        const delay     = parseInt(popup.dataset.popupDelay, 10) * 1000;
        const frequency = popup.dataset.popupFrequency;

        if (!shouldShow(frequency, uid)) {
            return;
        }

        // Nach der konfigurierten Verzögerung automatisch öffnen.
        setTimeout(function () {
            openPopup(popup);
            recordShown(frequency, uid);
        }, delay);

        // Schließen über den ×-Button.
        const closeBtn = popup.querySelector('[data-popup-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closePopup(popup);
            });
        }

        // Schließen durch Klick auf den Overlay-Hintergrund.
        const overlay = popup.querySelector('.vt14-popup__overlay');
        if (overlay) {
            overlay.addEventListener('click', function (event) {
                if (event.target === overlay) {
                    closePopup(popup);
                }
            });
        }

        // Schließen über die Escape-Taste (Barrierefreiheit).
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && popup.classList.contains('vt14-popup--open')) {
                closePopup(popup);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-popup-uid]').forEach(initPopup);
    });
}());
