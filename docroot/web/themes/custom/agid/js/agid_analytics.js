(function ($, D) {
  D.behaviors.agidAnalytics = {
    attach: function (context, settings) {
      /**
       * Attach Google Analytics Script
       */
      // (function (i, s, o, g, r, a, m) {
      //   i["GoogleAnalyticsObject"] = r;
      //   i[r] = i[r] || function () {
      //     (i[r].q = i[r].q || []).push(arguments)
      //   }, i[r].l = 1 * new Date();
      //   a = s.createElement(o), m = s.getElementsByTagName(o)[0];
      //   a.async = 1;
      //   a.src = g;
      //   m.parentNode.insertBefore(a, m)
      // })(window, document, "script", "//www.google-analytics.com/analytics.js", "ga");
      // ga("create", "UA-3351165-19", {"cookieDomain": "auto"});
      // ga("set", "anonymizeIp", true);
      // if (1 && (!Drupal || !Drupal.eu_cookie_compliance || !Drupal.eu_cookie_compliance.hasAgreed())) {
        // window['ga-disable-UA-3351165-19'] = true;
      // };
      // ga("send", "pageview");


      /**
       * Attach HotJar
       */
      (function (h, o, t, j, a, r) {
        h.hj = h.hj || function () {
          (h.hj.q = h.hj.q || []).push(arguments)
        };
        h._hjSettings = {hjid: 338855, hjsv: 5};
        a = o.getElementsByTagName('head')[0];
        r = o.createElement('script');
        r.async = 1;
        r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
        a.appendChild(r);
      })(window, document, '//static.hotjar.com/c/hotjar-', '.js?sv=');

      /**
       * Attach siteimprove
       */
      (function() {
        var sz = document.createElement('script'); sz.type = 'text/javascript'; sz.async = true;
        sz.src = '//siteimproveanalytics.com/js/siteanalyze_6086464.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sz, s);
      })();
    }
  }
})(jQuery, Drupal);

/* Web Analytics Italia */
var _paq = window._paq || [];
/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
_paq.push(['trackPageView']);
_paq.push(['enableLinkTracking']);
(function() {
  var u="https://ingestion.webanalytics.italia.it/";
  _paq.push(['setTrackerUrl', u+'matomo.php']);
  _paq.push(['setSiteId', 'RjLp6G7qWQ']);
  var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
  g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
})();
