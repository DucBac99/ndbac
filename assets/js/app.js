function loadConfig() {
    localStorage.getItem("layout-mode") &&
      document.body.setAttribute(
        "data-layout-mode",
        localStorage.getItem("layout-mode")
      );
    localStorage.getItem("data-layout-size") &&
      document.body.setAttribute(
        "data-layout-size",
        localStorage.getItem("data-layout-size")
      );
    localStorage.getItem("data-topbar") &&
      document.body.setAttribute(
        "data-topbar",
        localStorage.getItem("data-topbar")
      );
  
    localStorage.getItem("data-sidebar") &&
      document.body.setAttribute(
        "data-sidebar",
        localStorage.getItem("data-sidebar")
      );
  }
  
  function setConfig(name, value) {
    localStorage.setItem(name, value);
    loadConfig();
  }
  
  loadConfig();
  
  !(function (n) {
    "use strict";
    var e, t, a;
    function i() {
      var t = document.querySelectorAll(".counter-value");
      t.forEach(function (o) {
        !(function t() {
          var e = +o.getAttribute("data-target"),
            a = +o.innerText,
            n = e / 250;
          n < 1 && (n = 1),
            a < e
              ? ((o.innerText = (a + n).toFixed(0)), setTimeout(t, 1))
              : (o.innerText = e);
        })();
      });
    }
    function s() {
      for (
        var t = document
            .getElementById("topnav-menu-content")
            .getElementsByTagName("a"),
          e = 0,
          a = t.length;
        e < a;
        e++
      )
        t[e] &&
          t[e].parentElement &&
          "nav-item dropdown active" ===
            t[e].parentElement.getAttribute("class") &&
          (t[e].parentElement.classList.remove("active"),
          t[e].nextElementSibling &&
            t[e].nextElementSibling.classList.remove("show"));
    }
    function l(t) {
      null !== t && (document.getElementById(t).checked = !0);
    }
    function u() {
      document.webkitIsFullScreen ||
        document.mozFullScreen ||
        document.msFullscreenElement ||
        n("body").removeClass("fullscreen-enable");
    }
    n("#side-menu").metisMenu(),
      i(),
      (e = document.body.getAttribute("data-sidebar-size")),
      n(window).on("load", function () {
        n(".switch").on("switch-change", function () {
          toggleWeather();
        });
        var t,
          e = document.querySelector("body");
        for (t of e.getAttributeNames()) {
          var a = e.getAttribute(t);
          localStorage.setItem(t, a),
            document.body.hasAttribute("data-sidebar")
              ? localStorage.removeItem("data-topbar")
              : document.body.hasAttribute("data-topbar") &&
                localStorage.removeItem("data-sidebar");
        }
        document.body.hasAttribute(!1) && l("topbar-color-light"),
          1024 <= window.innerWidth &&
            window.innerWidth <= 1366 &&
            (document.body.setAttribute("data-sidebar-size", "sm"),
            l("sidebar-size-small"));
      }),
      n("#vertical-menu-btn").on("click", function (t) {
        t.preventDefault(),
          n("body").toggleClass("sidebar-enable"),
          992 <= n(window).width() &&
            (null == e
              ? null == document.body.getAttribute("data-sidebar-size") ||
                "lg" == document.body.getAttribute("data-sidebar-size")
                ? document.body.setAttribute("data-sidebar-size", "sm")
                : document.body.setAttribute("data-sidebar-size", "lg")
              : "md" == e
              ? "md" == document.body.getAttribute("data-sidebar-size")
                ? document.body.setAttribute("data-sidebar-size", "sm")
                : document.body.setAttribute("data-sidebar-size", "md")
              : "sm" == document.body.getAttribute("data-sidebar-size")
              ? document.body.setAttribute("data-sidebar-size", "lg")
              : document.body.setAttribute("data-sidebar-size", "sm"));
      }),
      n("#sidebar-menu a").each(function () {
        var t = window.location.href.split(/[?#]/)[0];
        var dump = t.split("/");
        if (
          dump[dump.length - 1] == "new" ||
          !isNaN(parseInt(dump[dump.length - 1]))
        ) {
          dump.pop();
          t = dump.join("/");
        }
        this.href == t &&
          (n(this).addClass("active"),
          n(this).parent().addClass("mm-active"),
          n(this).parent().parent().addClass("mm-show"),
          n(this).parent().parent().prev().addClass("mm-active"),
          n(this).parent().parent().parent().addClass("mm-active"),
          n(this).parent().parent().parent().parent().addClass("mm-show"),
          n(this)
            .parent()
            .parent()
            .parent()
            .parent()
            .parent()
            .addClass("mm-active"));
      }),
      n(document).ready(function () {
        var t;
        0 < n("#sidebar-menu").length &&
          0 < n("#sidebar-menu .mm-active .active").length &&
          300 < (t = n("#sidebar-menu .mm-active .active").offset().top) &&
          ((t -= 300),
          n(".vertical-menu .simplebar-content-wrapper").animate(
            { scrollTop: t },
            "slow"
          ));
      }),
      n(".navbar-nav a").each(function () {
        var t = window.location.href.split(/[?#]/)[0];
        this.href == t &&
          (n(this).addClass("active"),
          n(this).parent().addClass("active"),
          n(this).parent().parent().addClass("active"),
          n(this).parent().parent().parent().addClass("active"),
          n(this).parent().parent().parent().parent().addClass("active"),
          n(this).parent().parent().parent().parent().parent().addClass("active"),
          n(this)
            .parent()
            .parent()
            .parent()
            .parent()
            .parent()
            .parent()
            .addClass("active"));
      }),
      n('[data-toggle="fullscreen"]').on("click", function (t) {
        t.preventDefault(),
          n("body").toggleClass("fullscreen-enable"),
          document.fullscreenElement ||
          document.mozFullScreenElement ||
          document.webkitFullscreenElement
            ? document.cancelFullScreen
              ? document.cancelFullScreen()
              : document.mozCancelFullScreen
              ? document.mozCancelFullScreen()
              : document.webkitCancelFullScreen &&
                document.webkitCancelFullScreen()
            : document.documentElement.requestFullscreen
            ? document.documentElement.requestFullscreen()
            : document.documentElement.mozRequestFullScreen
            ? document.documentElement.mozRequestFullScreen()
            : document.documentElement.webkitRequestFullscreen &&
              document.documentElement.webkitRequestFullscreen(
                Element.ALLOW_KEYBOARD_INPUT
              );
      }),
      document.addEventListener("fullscreenchange", u),
      document.addEventListener("webkitfullscreenchange", u),
      document.addEventListener("mozfullscreenchange", u),
      (function () {
        if (document.getElementById("topnav-menu-content")) {
          for (
            var t = document
                .getElementById("topnav-menu-content")
                .getElementsByTagName("a"),
              e = 0,
              a = t.length;
            e < a;
            e++
          )
            t[e].onclick = function (t) {
              t &&
                t.target &&
                "#" === t.target.getAttribute("href") &&
                (t.target.parentElement.classList.toggle("active"),
                t.target.nextElementSibling &&
                  t.target.nextElementSibling.classList.toggle("show"));
            };
          window.addEventListener("resize", s);
        }
      })(),
      [].slice
        .call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        .map(function (t) {
          return new bootstrap.Tooltip(t);
        }),
      [].slice
        .call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        .map(function (t) {
          return new bootstrap.Popover(t);
        }),
      [].slice.call(document.querySelectorAll(".toast")).map(function (t) {
        return new bootstrap.Toast(t);
      }),
      window.sessionStorage &&
        ((t = sessionStorage.getItem("is_visited"))
          ? n("#" + t).prop("checked", !0)
          : sessionStorage.setItem("is_visited", "layout-ltr")),
      n("#password-addon").on("click", function () {
        0 < n(this).siblings("input").length &&
          ("password" == n(this).siblings("input").attr("type")
            ? n(this).siblings("input").attr("type", "input")
            : n(this).siblings("input").attr("type", "password"));
      }),
      feather.replace(),
      n(window).on("load", function () {
        n("#status").fadeOut(), n("#preloader").delay(350).fadeOut("slow");
      }),
      (a = document.getElementsByTagName("body")[0]),
      n(".right-bar-toggle").on("click", function (t) {
        n("body").toggleClass("right-bar-enabled");
      }),
      n("#mode-setting-btn").on("click", function (t) {
        localStorage.setItem(
          "layout-mode",
          localStorage.getItem("layout-mode") == "light" ? "dark" : "light"
        );
        a.hasAttribute("data-layout-mode") &&
        "dark" == a.getAttribute("data-layout-mode")
          ? ("dark" == localStorage.getItem("data-sidebar") &&
              "horizontal" !== a.getAttribute("data-layout") &&
              document.body.setAttribute("data-sidebar", "dark"),
            "dark" == localStorage.getItem("data-topbar") &&
              "horizontal" !== a.getAttribute("data-layout") &&
              (document.body.setAttribute("data-sidebar", "light"),
              document.body.setAttribute("data-topbar", "dark")),
            document.body.setAttribute("data-layout-mode", "light"),
            "dark" !== localStorage.getItem("data-topbar") &&
              (document.body.setAttribute("data-topbar", "light"),
              l("topbar-color-light")),
            "brand" == localStorage.getItem("data-sidebar") &&
              document.body.setAttribute("data-sidebar", "brand"),
            localStorage.getItem("data-sidebar") ||
              localStorage.getItem("data-topbar") ||
              document.body.removeAttribute("data-sidebar"))
          : ("dark" == localStorage.getItem("data-sidebar") &&
              document.body.setAttribute("data-sidebar", "dark"),
            document.body.setAttribute("data-layout-mode", "dark"),
            document.body.setAttribute("data-topbar", "dark"),
            document.body.setAttribute("data-sidebar", "dark"));
      }),
      n(document).on("click", "body", function (t) {
        0 < n(t.target).closest(".right-bar-toggle, .right-bar").length ||
          n("body").removeClass("right-bar-enabled");
      }),
      a.hasAttribute("data-layout-mode") &&
      "dark" == a.getAttribute("data-layout-mode")
        ? l("layout-mode-dark")
        : l("layout-mode-light"),
      a.hasAttribute("data-layout-size") &&
      "boxed" == a.getAttribute("data-layout-size")
        ? l("layout-width-boxed")
        : l("layout-width-fuild"),
      "light" != a.getAttribute("data-topbar") &&
      "dark" == a.getAttribute("data-topbar")
        ? l("topbar-color-dark")
        : l("topbar-color-light"),
      a.hasAttribute("data-sidebar") && "dark" == a.getAttribute("data-sidebar")
        ? l("sidebar-color-dark")
        : l("sidebar-color-light"),
      n("input[name='layout-mode']").on("change", function () {
        "light" == n(this).val()
          ? (document.body.setAttribute("data-layout-mode", "light"),
            document.body.setAttribute("data-topbar", "light"),
            document.body.setAttribute("data-sidebar", "light"),
            (a.hasAttribute("data-layout") &&
              "horizontal" == a.getAttribute("data-layout")) ||
              document.body.setAttribute("data-sidebar", "light"),
            l("topbar-color-light"),
            l("sidebar-color-light"))
          : (document.body.setAttribute("data-layout-mode", "dark"),
            document.body.setAttribute("data-topbar", "dark"),
            document.body.setAttribute("data-sidebar", "dark"),
            (a.hasAttribute("data-layout") &&
              "horizontal" == a.getAttribute("data-layout")) ||
              document.body.setAttribute("data-sidebar", "dark"),
            l("sidebar-color-dark"));
      }),
      Waves.init(),
      n("#checkAll").on("change", function () {
        n(".table-check .form-check-input").prop(
          "checked",
          n(this).prop("checked")
        );
      }),
      n(".table-check .form-check-input").change(function () {
        n(".table-check .form-check-input:checked").length ==
        n(".table-check .form-check-input").length
          ? n("#checkAll").prop("checked", !0)
          : n("#checkAll").prop("checked", !1);
      });
  })(jQuery);
