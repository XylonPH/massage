import './article-editor';
import { createBodyPainMapper } from './components/body-pain-mapper';

window.initThreeBodyMapper = function (container, onSelectRegion) {
    return createBodyPainMapper(container, onSelectRegion);
};

// Cookie consent banner: writes a real first-party cookie recording the
// user's choice. Only a session-identity cookie is currently set by the
// app (login), so "Reject Non-Essential" has nothing to disable yet; the
// analytics toggle is wired up in advance of any analytics being added.
const COOKIE_CONSENT_NAME = 'massage_nexus_consent';
const COOKIE_CONSENT_DAYS = 180;

function readCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/[.$?*|{}()[\]\\/+^]/g, '\\$&') + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
}

function writeConsentCookie(consent) {
    const expires = new Date(Date.now() + COOKIE_CONSENT_DAYS * 24 * 60 * 60 * 1000).toUTCString();
    const payload = encodeURIComponent(JSON.stringify({ ...consent, decided_at: new Date().toISOString() }));
    document.cookie = `${COOKIE_CONSENT_NAME}=${payload}; expires=${expires}; path=/; SameSite=Lax`;
}

const cookieBanner = document.getElementById('cookie-banner');
if (cookieBanner) {
    if (!readCookie(COOKIE_CONSENT_NAME)) {
        cookieBanner.hidden = false;
    }

    const hideBanner = () => { cookieBanner.hidden = true; };

    cookieBanner.querySelector('[data-cookie-accept]')?.addEventListener('click', () => {
        writeConsentCookie({ necessary: true, analytics: true });
        hideBanner();
    });

    cookieBanner.querySelector('[data-cookie-reject]')?.addEventListener('click', () => {
        writeConsentCookie({ necessary: true, analytics: false });
        hideBanner();
    });

    cookieBanner.querySelector('[data-cookie-manage]')?.addEventListener('click', () => {
        cookieBanner.querySelector('[data-cookie-preferences]')?.removeAttribute('hidden');
    });

    cookieBanner.querySelector('[data-cookie-save]')?.addEventListener('click', () => {
        const analytics = cookieBanner.querySelector('[data-cookie-analytics-toggle]')?.checked ?? false;
        writeConsentCookie({ necessary: true, analytics });
        hideBanner();
    });
}

// Mobile navigation toggle
document.querySelectorAll('[data-menu-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const menu = document.getElementById(button.getAttribute('aria-controls'));
        if (!menu) return;
        const open = menu.hasAttribute('hidden');
        menu.toggleAttribute('hidden', !open);
        button.setAttribute('aria-expanded', String(open));
    });
});

// Homepage search tabs: switch the search target and placeholder
const searchTabs = document.querySelectorAll('[data-search-tab]');
if (searchTabs.length > 0) {
    const typeInput = document.querySelector('[data-search-type]');
    const queryInput = document.querySelector('[data-search-query]');
    searchTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            searchTabs.forEach((other) => {
                const active = other === tab;
                other.setAttribute('aria-selected', String(active));
                other.classList.toggle('bg-white', active);
                other.classList.toggle('text-ink-950', active);
                other.classList.toggle('bg-white/10', !active);
                other.classList.toggle('text-white', !active);
                other.classList.toggle('hover:bg-white/20', !active);
            });
            if (typeInput) typeInput.value = tab.dataset.type;
            if (queryInput) {
                queryInput.placeholder = tab.dataset.placeholder;
                queryInput.focus();
            }
        });
    });
}

// Spa profile tabs: reveal one profile section in place without moving the
// page. The sections remain readable when JavaScript is unavailable.
const spaTabList = document.querySelector('[data-spa-tabs] [role="tablist"]');
if (spaTabList) {
    const spaTabs = [...spaTabList.querySelectorAll('[data-spa-tab]')];
    const spaTabPanels = [...document.querySelectorAll('[data-spa-tab-panel]')];
    const activeClasses = ['bg-ember-50', 'text-ember-700', 'dark:bg-ember-950', 'dark:text-ember-300'];
    const inactiveClasses = [
        'text-ink-600',
        'hover:bg-ink-50',
        'hover:text-ember-600',
        'dark:text-ink-300',
        'dark:hover:bg-ink-800',
        'dark:hover:text-ember-400',
    ];
    const getHashTarget = (hash) => {
        if (!hash || hash === '#') return null;
        try {
            return document.getElementById(decodeURIComponent(hash.slice(1)));
        } catch {
            return null;
        }
    };

    const activateSpaTab = (key, focusTab = false) => {
        const nextTab = spaTabs.find((tab) => tab.dataset.spaTab === key);
        const nextPanel = spaTabPanels.find((panel) => panel.dataset.spaTabPanel === key);
        if (!nextTab || !nextPanel) return;

        spaTabs.forEach((tab) => {
            const active = tab === nextTab;
            tab.setAttribute('aria-selected', String(active));
            tab.setAttribute('tabindex', active ? '0' : '-1');
            activeClasses.forEach((className) => tab.classList.toggle(className, active));
            inactiveClasses.forEach((className) => tab.classList.toggle(className, !active));
        });
        spaTabPanels.forEach((panel) => panel.toggleAttribute('hidden', panel !== nextPanel));

        if (focusTab) nextTab.focus();
    };

    spaTabs.forEach((tab, index) => {
        tab.addEventListener('click', () => activateSpaTab(tab.dataset.spaTab));
        tab.addEventListener('keydown', (event) => {
            let nextIndex;
            if (event.key === 'ArrowRight') nextIndex = (index + 1) % spaTabs.length;
            if (event.key === 'ArrowLeft') nextIndex = (index - 1 + spaTabs.length) % spaTabs.length;
            if (event.key === 'Home') nextIndex = 0;
            if (event.key === 'End') nextIndex = spaTabs.length - 1;
            if (nextIndex === undefined) return;

            event.preventDefault();
            activateSpaTab(spaTabs[nextIndex].dataset.spaTab, true);
        });
    });

    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', () => {
            const target = getHashTarget(link.hash);
            const panel = target?.closest('[data-spa-tab-panel]');
            if (panel) activateSpaTab(panel.dataset.spaTabPanel);
        });
    });

    const initialTarget = getHashTarget(window.location.hash);
    const initialPanel = initialTarget?.closest('[data-spa-tab-panel]');
    activateSpaTab(initialPanel?.dataset.spaTabPanel ?? 'overview');
}

// Password visibility toggle
document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordToggle);
        if (!input) return;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(show));
        button.querySelectorAll('[data-eye]').forEach((icon) => {
            icon.toggleAttribute('hidden', (icon.dataset.eye === 'open') === show);
        });
    });
});

// Advisory password strength indicator (server-side validation remains authoritative)
const strengthInput = document.querySelector('[data-strength-input]');
const strengthMeter = document.querySelector('[data-strength-meter]');
const strengthText = document.querySelector('[data-strength-text]');
if (strengthInput && strengthMeter && strengthText) {
    const levels = [
        { min: 0, width: '15%', color: 'bg-ember-600', key: 'tooShort' },
        { min: 15, width: '45%', color: 'bg-ember-400', key: 'fair' },
        { min: 20, width: '70%', color: 'bg-leaf-400', key: 'good' },
        { min: 28, width: '100%', color: 'bg-leaf-600', key: 'strong' },
    ];
    strengthInput.addEventListener('input', () => {
        const length = strengthInput.value.length;
        const level = [...levels].reverse().find((candidate) => length >= candidate.min) ?? levels[0];
        strengthMeter.className = `h-1.5 rounded-full transition-all duration-300 ${level.color}`;
        strengthMeter.style.width = length === 0 ? '0' : level.width;
        strengthText.textContent = length === 0 ? '' : strengthText.dataset[level.key];
    });
}

// Advisory username availability check (server-side uniqueness check in
// RegisterController::store() remains authoritative on submission)
const usernameInput = document.querySelector('[data-username-check]');
const usernameAvailability = document.querySelector('[data-username-availability]');
if (usernameInput && usernameAvailability) {
    const checkUrl = usernameInput.dataset.usernameCheck;
    const pattern = /^[a-z][a-z0-9]{3,29}$/;
    let debounceTimer;
    let controller;

    const getServerError = () => usernameInput.parentElement.querySelector('[data-server-validation-error]');

    const setStatus = (text, tone) => {
        usernameAvailability.textContent = text;
        usernameAvailability.className = `mt-1.5 text-xs font-semibold ${tone}`;
    };

    const checkAvailability = async (value) => {
        controller?.abort();
        controller = new AbortController();
        setStatus(usernameAvailability.dataset.checking, 'text-ink-400');
        try {
            const response = await fetch(`${checkUrl}?username=${encodeURIComponent(value)}`, {
                headers: { Accept: 'application/json' },
                cache: 'no-cache',
                signal: controller.signal,
            });
            const data = await response.json();
            setStatus(data.message ?? '', data.available ? 'text-leaf-600' : 'text-ember-600');
        } catch (error) {
            if (error.name !== 'AbortError') setStatus('', 'text-ink-400');
        }
    };

    usernameInput.addEventListener('input', () => {
        // Clear server-side error immediately on new input
        const serverError = getServerError();
        if (serverError) {
            serverError.remove();
            usernameInput.classList.remove('border-ember-400');
            usernameInput.classList.add('border-ink-200');
        }

        clearTimeout(debounceTimer);
        controller?.abort();
        setStatus('', 'text-ink-400');
        const value = usernameInput.value.toLowerCase();
        if (!pattern.test(value)) {
            return;
        }
        debounceTimer = setTimeout(() => checkAvailability(value), 400);
    });

    usernameInput.addEventListener('blur', () => {
        clearTimeout(debounceTimer);
        if (getServerError()) {
            setStatus('', 'text-ink-400');
            return;
        }
        const value = usernameInput.value.toLowerCase();
        if (pattern.test(value)) checkAvailability(value);
    });
}

// Community Pulse poll (visual preview — submissions are not stored yet)
const poll = document.querySelector('[data-poll]');
if (poll) {
    poll.addEventListener('submit', (event) => {
        event.preventDefault();
        poll.querySelector('[data-poll-form]').setAttribute('hidden', '');
        poll.querySelector('[data-poll-result]').removeAttribute('hidden');
    });
}

// Campus Quiz (visual preview — progress is not stored yet)
const quiz = document.querySelector('[data-quiz]');
if (quiz) {
    quiz.addEventListener('submit', (event) => {
        event.preventDefault();
        const choice = quiz.querySelector('input[name="quiz_answer"]:checked');
        if (!choice) return;
        const correct = choice.value === quiz.dataset.quizAnswer;
        quiz.querySelector('[data-quiz-correct]').toggleAttribute('hidden', !correct);
        quiz.querySelector('[data-quiz-incorrect]').toggleAttribute('hidden', correct);
    });
}

// Theme switch: light → dark → system. The inline head script in
// partials/theme-init.blade.php already applied the stored theme pre-paint;
// this block keeps the <html> class, button icons, and labels in sync.
const THEME_KEY = 'mn-theme';
const themeMedia = window.matchMedia('(prefers-color-scheme: dark)');

function currentThemeMode() {
    const stored = localStorage.getItem(THEME_KEY);
    return stored === 'light' || stored === 'dark' ? stored : 'system';
}

function applyTheme() {
    const mode = currentThemeMode();
    const dark = mode === 'dark' || (mode === 'system' && themeMedia.matches);
    document.documentElement.classList.toggle('dark', dark);
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.dataset.themeState = mode;
        const label = button.dataset[`label${mode.charAt(0).toUpperCase()}${mode.slice(1)}`];
        if (label) button.setAttribute('aria-label', label);
        button.querySelectorAll('[data-theme-icon]').forEach((icon) => {
            icon.toggleAttribute('hidden', icon.dataset.themeIcon !== mode);
        });
    });
}

document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const order = ['light', 'dark', 'system'];
        const next = order[(order.indexOf(currentThemeMode()) + 1) % order.length];
        if (next === 'system') {
            localStorage.removeItem(THEME_KEY);
        } else {
            localStorage.setItem(THEME_KEY, next);
        }
        applyTheme();
    });
});

themeMedia.addEventListener('change', () => {
    if (currentThemeMode() === 'system') applyTheme();
});

applyTheme();
