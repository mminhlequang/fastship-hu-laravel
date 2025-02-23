require("./bootstrap");

$(function () {
  $(".select2").select2();
  $("input.flat-blue").iCheck({
    checkboxClass: "icheckbox_square-blue",
    radioClass: "iradio_square-blue",
    increaseArea: "20%",
  });
  $('[data-toggle="tooltip"]').tooltip();

  // Opera 8.0+
  var isOpera =
    (!!window.opr && !!opr.addons) ||
    !!window.opera ||
    navigator.userAgent.indexOf(" OPR/") >= 0;

  // Firefox 1.0+
  var isFirefox = typeof InstallTrigger !== "undefined";

  // Safari 3.0+ "[object HTMLElementConstructor]"
  var isSafari =
    /constructor/i.test(window.HTMLElement) ||
    (function (p) {
      return p.toString() === "[object SafariRemoteNotification]";
    })(!window["safari"] || safari.pushNotification);

  // Internet Explorer 6-11
  var isIE = /*@cc_on!@*/ false || !!document.documentMode;

  // Edge 20+
  var isEdge = !isIE && !!window.StyleMedia;

  // Chrome 1+
  var isChrome = !!window.chrome && !!window.chrome.webstore;

  // Blink engine detection
  var isBlink = (isChrome || isOpera) && !!window.CSS;

  if (isChrome) {
    //chrome
  } else {
    //not chrome
    var warning =
      '<div style="padding: 1px;font-size: 11px;" class="alert alert-warning alert-dismissible">\
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
                  <i class="icon fa fa-warning"></i>\
                  Ứng dụng được hỗ trợ tốt nhất với trình duyệt <a href="https://www.google.com/intl/vi/chrome/browser/desktop/index.html" target="_blank">Chrome</a>\
              </div>';
  }
});
