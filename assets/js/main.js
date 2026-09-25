/* Dunamis Media — interactions */
(function () {
  "use strict";
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---- header state: transparent over the hero video, solid after ---- */
  var header = document.getElementById("siteHeader");
  var hero = document.getElementById("top");
  var onScroll = function () {
    var flipAt = hero ? Math.max(120, hero.offsetHeight - header.offsetHeight - 24) : 8;
    header.classList.toggle("is-stuck", window.scrollY > flipAt);
  };
  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("resize", onScroll, { passive: true });

  /* ---- theme toggle (light / dark, persisted) ---- */
  var themeBtn = document.getElementById("themeToggle");
  var root = document.documentElement;
  var themeMeta = document.querySelector('meta[name="theme-color"]');
  var syncTheme = function () {
    var dark = root.getAttribute("data-theme") === "dark";
    if (themeBtn) {
      themeBtn.setAttribute("aria-pressed", String(dark));
      var icon = themeBtn.querySelector(".theme-toggle-icon");
      if (icon) icon.textContent = dark ? "☀" : "☾";
    }
    if (themeMeta) themeMeta.setAttribute("content", dark ? "#0e0e10" : "#f2a900");
  };
  syncTheme();
  if (themeBtn) {
    themeBtn.addEventListener("click", function () {
      var next = root.getAttribute("data-theme") === "dark" ? "light" : "dark";
      if (next === "dark") { root.setAttribute("data-theme", "dark"); }
      else { root.removeAttribute("data-theme"); }
      try { localStorage.setItem("dunamis-theme", next); } catch (e) {}
      syncTheme();
    });
  }

  /* ---- hero background video: playlist loop + sound toggle ---- */
  var heroVideo = document.getElementById("heroVideo");
  var muteBtn = document.getElementById("heroMute");
  var HERO_PLAYBACK_RATE = 0.4; // footage plays a bit fast at native speed; slow it down
  if (heroVideo) {
    var playlist = [];
    try { playlist = JSON.parse(heroVideo.getAttribute("data-playlist") || "[]"); } catch (e) { playlist = []; }
    var clipIndex = 0;

    var applyRate = function () { heroVideo.playbackRate = HERO_PLAYBACK_RATE; };
    applyRate();
    // playbackRate can reset on browsers once metadata (re)loads, so reassert it
    heroVideo.addEventListener("loadedmetadata", applyRate);

    if (playlist.length > 1) {
      heroVideo.addEventListener("ended", function () {
        clipIndex = (clipIndex + 1) % playlist.length;
        var wasMuted = heroVideo.muted;
        heroVideo.src = playlist[clipIndex];
        heroVideo.muted = wasMuted; // changing src resets muted in some browsers
        applyRate();
        heroVideo.play().catch(function () {});
      });
    }

    if (muteBtn) {
      var mIcon = muteBtn.querySelector(".hero-mute-icon");
      var syncMute = function () {
        var off = heroVideo.muted || heroVideo.paused;
        muteBtn.setAttribute("aria-pressed", String(off));
        if (mIcon) mIcon.textContent = off ? "🔇" : "🔊";
      };
      var showMute = function () {
        if (heroVideo.readyState >= 2 && heroVideo.videoHeight > 0) muteBtn.hidden = false;
      };
      heroVideo.addEventListener("loadeddata", showMute);
      heroVideo.addEventListener("canplay", showMute);
      showMute();
      muteBtn.addEventListener("click", function () {
        heroVideo.muted = !heroVideo.muted;
        if (!heroVideo.muted && heroVideo.paused) { heroVideo.play().catch(function () {}); }
        syncMute();
      });
      syncMute();
    }
  }

  /* ---- hero title: typewriter effect ---- */
  var heroTitle = document.querySelector(".hero-title");
  if (heroTitle && !reduced) {
    var fullLabel = heroTitle.textContent.replace(/\s+/g, " ").trim();
    heroTitle.setAttribute("aria-label", fullLabel);

    var accentEl = heroTitle.querySelector(".accent-word");
    var beforeStr = "", accentStr = "", afterStr = "";
    if (accentEl) {
      var seenAccent = false;
      Array.prototype.forEach.call(heroTitle.childNodes, function (node) {
        if (node === accentEl) { seenAccent = true; accentStr = node.textContent; }
        else if (node.nodeType === 3) {
          if (seenAccent) afterStr += node.textContent; else beforeStr += node.textContent;
        }
      });
    } else {
      beforeStr = heroTitle.textContent;
    }

    var units = [];
    beforeStr.split("").forEach(function (ch) { units.push({ ch: ch, t: "before" }); });
    accentStr.split("").forEach(function (ch) { units.push({ ch: ch, t: "accent" }); });
    afterStr.split("").forEach(function (ch) { units.push({ ch: ch, t: "after" }); });

    var beforeNode = document.createTextNode("");
    var accentNode = document.createElement("span");
    if (accentEl) accentNode.className = accentEl.className;
    var afterNode = document.createTextNode("");
    var caret = document.createElement("span");
    caret.className = "typing-caret";
    caret.setAttribute("aria-hidden", "true");

    heroTitle.textContent = "";
    heroTitle.appendChild(beforeNode);
    if (accentEl) heroTitle.appendChild(accentNode);
    heroTitle.appendChild(afterNode);
    heroTitle.appendChild(caret);

    var appendAt = function (u) {
      if (u.t === "before") beforeNode.textContent += u.ch;
      else if (u.t === "accent") accentNode.textContent += u.ch;
      else afterNode.textContent += u.ch;
    };
    var removeAt = function (u) {
      if (u.t === "before") beforeNode.textContent = beforeNode.textContent.slice(0, -1);
      else if (u.t === "accent") accentNode.textContent = accentNode.textContent.slice(0, -1);
      else afterNode.textContent = afterNode.textContent.slice(0, -1);
    };

    var HOLD_FULL = 2200;  // pause once the full line is typed
    var HOLD_EMPTY = 500;  // pause once the line is fully erased, before retyping
    var idx = 0;

    var typeStep = function () {
      if (idx >= units.length) { setTimeout(deleteStep, HOLD_FULL); return; }
      appendAt(units[idx++]);
      var delay = units[idx - 1].ch === " " ? 28 : 34 + Math.random() * 40;
      setTimeout(typeStep, delay);
    };
    var deleteStep = function () {
      if (idx <= 0) { setTimeout(typeStep, HOLD_EMPTY); return; }
      removeAt(units[--idx]);
      setTimeout(deleteStep, 16 + Math.random() * 14);
    };

    // loop forever: type the line, hold, delete it, hold, retype…
    setTimeout(typeStep, 350);
  }

  /* ---- services: interactive gallery (list hover/click/keys drives preview) ---- */
  var galleryList = document.querySelector("[data-gallery-list]");
  var galleryPreview = document.querySelector("[data-gallery-preview]");
  if (galleryList && galleryPreview) {
    var items = Array.prototype.slice.call(galleryList.querySelectorAll(".service-item"));
    var slides = Array.prototype.slice.call(galleryPreview.querySelectorAll(".service-slide"));
    var canHover = window.matchMedia("(hover: hover) and (pointer: fine)").matches;

    var activate = function (index, focus) {
      items.forEach(function (item) {
        var on = Number(item.getAttribute("data-index")) === index;
        item.classList.toggle("is-active", on);
        item.setAttribute("aria-selected", String(on));
        item.setAttribute("tabindex", on ? "0" : "-1");
      });
      slides.forEach(function (slide) {
        slide.classList.toggle("is-active", Number(slide.getAttribute("data-index")) === index);
      });
      if (focus && items[index]) items[index].focus();
    };

    items.forEach(function (item, index) {
      item.addEventListener("click", function () { activate(index); });
      if (canHover) {
        item.addEventListener("mouseenter", function () { activate(index); });
      }
      item.addEventListener("keydown", function (e) {
        var last = items.length - 1;
        if (e.key === "ArrowDown" || e.key === "ArrowRight") {
          e.preventDefault(); activate(index === last ? 0 : index + 1, true);
        } else if (e.key === "ArrowUp" || e.key === "ArrowLeft") {
          e.preventDefault(); activate(index === 0 ? last : index - 1, true);
        } else if (e.key === "Home") {
          e.preventDefault(); activate(0, true);
        } else if (e.key === "End") {
          e.preventDefault(); activate(last, true);
        } else if (e.key === "Enter" || e.key === " ") {
          e.preventDefault(); activate(index);
        }
      });
    });
  }

  /* ---- mobile nav ---- */
  var toggle = document.getElementById("navToggle");
  var menu = document.getElementById("navMenu");
  var setNav = function (open) {
    toggle.setAttribute("aria-expanded", String(open));
    menu.classList.toggle("is-open", open);
    document.body.classList.toggle("nav-open", open);
  };
  toggle.addEventListener("click", function () {
    setNav(toggle.getAttribute("aria-expanded") !== "true");
  });
  menu.addEventListener("click", function (e) {
    if (e.target.closest("a")) setNav(false);
  });
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") setNav(false);
  });

  /* ---- reveal on scroll ---- */
  var reveals = document.querySelectorAll(".reveal");
  if (reduced || !("IntersectionObserver" in window)) {
    reveals.forEach(function (el) { el.classList.add("is-visible"); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -8% 0px" });
    reveals.forEach(function (el) { io.observe(el); });
  }

  /* ---- value bars ---- */
  var rows = document.querySelectorAll(".value-row");
  if (rows.length) {
    if (reduced || !("IntersectionObserver" in window)) {
      rows.forEach(function (r) { r.classList.add("is-visible"); });
    } else {
      var barIO = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            barIO.unobserve(entry.target);
          }
        });
      }, { threshold: 0.6 });
      rows.forEach(function (r) { barIO.observe(r); });
    }
  }

  /* ---- stat counters ---- */
  var stats = document.querySelectorAll(".stat-value[data-count]");
  var runCount = function (el) {
    var target = parseInt(el.getAttribute("data-count"), 10);
    if (isNaN(target) || reduced) { return; }
    var start = null, dur = 1400;
    var step = function (ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * eased).toString();
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };
  if (stats.length && "IntersectionObserver" in window) {
    var statIO = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { runCount(entry.target); statIO.unobserve(entry.target); }
      });
    }, { threshold: 0.8 });
    stats.forEach(function (s) { statIO.observe(s); });
  }

  /* ---- contact form (AJAX) ---- */
  var form = document.getElementById("contactForm");
  if (form) {
    var status = document.getElementById("cf-status");
    var btn = document.getElementById("cf-submit");
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      status.textContent = "";
      status.className = "form-status";
      form.classList.add("is-sending");
      btn.textContent = "Sending…";

      fetch(form.action, {
        method: "POST",
        headers: { "Accept": "application/json", "X-Requested-With": "fetch" },
        body: new FormData(form)
      })
        .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
        .then(function (res) {
          status.textContent = res.d.message || (res.ok ? "Sent." : "Something went wrong.");
          status.classList.add(res.d.ok ? "is-ok" : "is-err");
          if (res.d.ok) form.reset();
        })
        .catch(function () {
          status.textContent = "Network error — email us directly instead.";
          status.classList.add("is-err");
        })
        .finally(function () {
          form.classList.remove("is-sending");
          btn.textContent = "Send message";
        });
    });
  }

  /* ---- flash from classic (no-JS) submit ---- */
  var params = new URLSearchParams(location.search);
  if (params.has("sent") && form) {
    var s = document.getElementById("cf-status");
    s.textContent = params.get("msg") || "";
    s.classList.add(params.get("sent") === "1" ? "is-ok" : "is-err");
    history.replaceState(null, "", location.pathname + "#contact");
  }
})();
