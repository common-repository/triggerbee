
var mtr_custom = mtr_custom || {};

(function () {
     var s = document.createElement('script'); s.async = true; s.src = '//t.myvisitors.se/js?site_id=' + mtr_site_id;
     (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(s);
     var sc = document.createElement('script'); sc.async = true; sc.src = '//t.myvisitors.se/js/' + mtr_site_id + (window.location.href.indexOf('tb-nocache') > -1 ? '?v=' + Math.floor(Math.random()*999) : '');
     (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(sc);
})();