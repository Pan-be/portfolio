// Cookie banner: stores the visitor's choice and loads Google Analytics only after "Accept".
// The cookie value carries a version (v1), so bumping it asks everyone again after a policy change.
(function () {
    const COOKIE = 'panbe_consent';
    const VERSION = 'v1';
    const MAX_AGE = 60 * 60 * 24 * 182; // about 6 months, then ask again

    const banner = document.querySelector('#consent');
    if (!banner) return;

    function getChoice() {
        const match = document.cookie.match(new RegExp('(?:^|;\\s*)' + COOKIE + '=' + VERSION + '\\.(granted|denied)(?:;|$)'));
        return match ? match[1] : null;
    }

    function saveChoice(choice) {
        const secure = location.protocol === 'https:' ? '; Secure' : '';
        document.cookie = COOKIE + '=' + VERSION + '.' + choice + '; Max-Age=' + MAX_AGE + '; Path=/; SameSite=Lax' + secure;
    }

    // GA4 sets _ga and _ga_<id> on the registrable domain; clear them on every level of the hostname.
    function clearAnalyticsCookies() {
        const names = document.cookie.split(';').map(c => c.trim().split('=')[0]).filter(n => n === '_ga' || n.startsWith('_ga_'));
        const parts = location.hostname.split('.');
        names.forEach(name => {
            for (let i = 0; i < parts.length - 1; i++) {
                const domain = parts.slice(i).join('.');
                document.cookie = name + '=; Max-Age=0; Path=/; Domain=.' + domain;
            }
            document.cookie = name + '=; Max-Age=0; Path=/';
        });
    }

    function show() {
        banner.hidden = false;
    }

    function hide() {
        banner.hidden = true;
    }

    banner.addEventListener('click', function (e) {
        const button = e.target.closest('[data-consent]');
        if (!button) return;
        const choice = button.dataset.consent;
        const previous = getChoice();
        saveChoice(choice);
        hide();
        if (choice === 'granted') {
            window.panbeLoadAnalytics && window.panbeLoadAnalytics();
        } else if (previous === 'granted' || window.panbeAnalyticsLoaded) {
            // GA is already running on this page; drop its cookies and reload so it stops.
            clearAnalyticsCookies();
            location.reload();
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.consent-settings')) {
            e.preventDefault();
            show();
            banner.querySelector('[data-consent]').focus();
        }
    });

    if (!getChoice()) show();
})();
