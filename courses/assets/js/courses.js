document.addEventListener("DOMContentLoaded",function(){
  const button=document.querySelector(".nsdm-menu-toggle");
  const menu=document.querySelector(".nsdm-menu");
  if(button&&menu){
    button.addEventListener("click",function(){
      const open=menu.classList.toggle("is-open");
      button.setAttribute("aria-expanded",String(open));
    });
  }

  document.querySelectorAll("[data-current-year]").forEach(function(item){
    item.textContent=new Date().getFullYear();
  });

  const search=document.querySelector("#course-search");
  const clear=document.querySelector("#clear-search");
  const rows=[...document.querySelectorAll("[data-course-row]")];
  const sections=[...document.querySelectorAll("[data-course-section]")];
  const empty=document.querySelector("#no-results");

  function applySearch(){
    if(!rows.length) return;
    const q=(search&&search.value ? search.value : "").trim().toLowerCase();
    let visible=0;

    rows.forEach(function(row){
      const text=(row.dataset.search||"").toLowerCase();
      const show=!q || text.includes(q);
      row.hidden=!show;
      if(show) visible++;
    });

    sections.forEach(function(section){
      const visibleRows=[...section.querySelectorAll("[data-course-row]")].some(function(row){
        return !row.hidden;
      });
      section.hidden=!visibleRows;
    });

    if(empty) empty.hidden=visible!==0;
  }

  if(search && rows.length){
    search.addEventListener("input",applySearch);
    applySearch();
  }

  if(clear && search){
    clear.addEventListener("click",function(){
      search.value="";
      applySearch();
      search.focus();
    });
  }

  document.querySelectorAll('a[href^="#"]').forEach(function(anchor){
    anchor.addEventListener("click",function(){
      if(menu && window.matchMedia("(max-width:720px)").matches){
        menu.classList.remove("is-open");
        if(button) button.setAttribute("aria-expanded","false");
      }
    });
  });
});

/* Fix 5: generate one-shot course screenshot and share */
(function () {
  function loadHtml2Canvas() {
    return new Promise(function (resolve, reject) {
      if (window.html2canvas) {
        resolve();
        return;
      }

      var script = document.createElement("script");
      script.src = "https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js";
      script.onload = resolve;
      script.onerror = reject;
      document.head.appendChild(script);
    });
  }

  function textFrom(selector, root) {
    var node = (root || document).querySelector(selector);
    return node ? node.textContent.trim() : "";
  }

  function buildShareCard() {
    var courseCard = document.querySelector(".nsdm-course-card");
    var subjectTable = document.querySelector(".nsdm-subject-table");

    if (!courseCard || !subjectTable) {
      return null;
    }

    var code = textFrom(".nsdm-course-code", courseCard);
    var title = textFrom(".nsdm-course-title", courseCard);

    var facts = [];
    courseCard.querySelectorAll(".nsdm-course-facts div").forEach(function (item) {
      var label = textFrom("dt", item);
      var value = textFrom("dd", item);
      if (label && value) {
        facts.push({ label: label, value: value });
      }
    });

    var render = document.createElement("div");
    render.style.width = "760px";
    render.style.padding = "26px";
    render.style.background = "#f6f9ff";
    render.style.border = "4px solid #173f93";
    render.style.borderRadius = "22px";
    render.style.fontFamily = "Segoe UI, Arial, Helvetica, sans-serif";
    render.style.color = "#18233a";

    var html = "";
    html += '<div style="background:#fff;border:2px solid #c6d7f5;border-radius:18px;padding:20px;box-shadow:0 10px 26px rgba(16,47,112,.10);">';
    html += '<div style="display:inline-block;background:#e9bf22;color:#102f70;font-weight:900;border-radius:999px;padding:8px 16px;font-size:18px;">' + code + '</div>';
    html += '<h1 style="margin:12px 0 10px;color:#102f70;font-size:34px;line-height:1.12;">' + title + '</h1>';
    html += '<p style="margin:0 0 16px;color:#607087;font-size:17px;">Nehru Skill Development Mission - Course Details</p>';

    html += '<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:18px;">';
    facts.forEach(function (fact, index) {
      var colors = [
        ["#eef5ff", "#2459b7"],
        ["#effaf3", "#2f9b62"],
        ["#f4f0ff", "#6b5bd6"],
        ["#fff5df", "#bd7a12"]
      ];
      var c = colors[index % colors.length];
      html += '<div style="background:' + c[0] + ';border:1px solid #d8e1ee;border-left:6px solid ' + c[1] + ';border-radius:12px;padding:10px 12px;">';
      html += '<div style="font-size:12px;text-transform:uppercase;letter-spacing:.05em;color:#607087;font-weight:800;">' + fact.label + '</div>';
      html += '<div style="font-size:18px;color:#102f70;font-weight:900;margin-top:3px;">' + fact.value + '</div>';
      html += '</div>';
    });
    html += '</div>';

    html += '<h2 style="margin:0 0 10px;color:#102f70;font-size:28px;">Course Subjects</h2>';
    html += '<table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #d8e1ee;font-size:15px;">';
    html += '<thead><tr>';
    html += '<th style="background:#1f4599;color:#fff;padding:9px;border:1px solid #1f4599;text-align:left;width:58px;">S.No</th>';
    html += '<th style="background:#1f4599;color:#fff;padding:9px;border:1px solid #1f4599;text-align:left;width:130px;">Code</th>';
    html += '<th style="background:#1f4599;color:#fff;padding:9px;border:1px solid #1f4599;text-align:left;">Subject</th>';
    html += '</tr></thead><tbody>';

    subjectTable.querySelectorAll("tbody tr").forEach(function (row) {
      var cells = row.querySelectorAll("td");
      if (cells.length >= 3) {
        html += '<tr>';
        html += '<td style="padding:8px;border:1px solid #d8e1ee;text-align:center;">' + cells[0].textContent.trim() + '</td>';
        html += '<td style="padding:8px;border:1px solid #d8e1ee;font-weight:900;color:#102f70;">' + cells[1].textContent.trim() + '</td>';
        html += '<td style="padding:8px;border:1px solid #d8e1ee;">' + cells[2].textContent.trim() + '</td>';
        html += '</tr>';
      }
    });

    html += '</tbody></table>';
    html += '<div style="margin-top:14px;text-align:center;color:#607087;font-size:13px;font-weight:700;">nehruskilldevelopmentmission.com</div>';
    html += '</div>';

    render.innerHTML = html;
    render.style.position = "fixed";
    render.style.left = "-10000px";
    render.style.top = "0";
    render.style.zIndex = "-1";

    document.body.appendChild(render);
    return render;
  }

  async function shareCourseDetails() {
    var button = document.querySelector(".nsdm-course-share-btn");
    if (button) {
      button.disabled = true;
      button.textContent = "Preparing image...";
    }

    try {
      await loadHtml2Canvas();

      var card = buildShareCard();
      if (!card) {
        alert("Course details not found.");
        return;
      }

      var canvas = await window.html2canvas(card, {
        backgroundColor: "#f6f9ff",
        scale: 2,
        useCORS: true
      });

      card.remove();

      canvas.toBlob(async function (blob) {
        var file = new File([blob], "nsdm-course-details.png", { type: "image/png" });

        if (navigator.canShare && navigator.canShare({ files: [file] })) {
          await navigator.share({
            title: "NSDM Course Details",
            text: "NSDM course details",
            files: [file]
          });
        } else {
          var link = document.createElement("a");
          link.download = "nsdm-course-details.png";
          link.href = canvas.toDataURL("image/png");
          link.click();
          alert("Image downloaded. You can share it on WhatsApp.");
        }
      }, "image/png");
    } catch (error) {
      console.error(error);
      alert("Unable to prepare course image. Please try again.");
    } finally {
      if (button) {
        button.disabled = false;
        button.textContent = "Share Course on WhatsApp";
      }
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    var courseCard = document.querySelector(".nsdm-course-card");
    var subjectPanel = document.querySelector(".nsdm-subject-table")?.closest(".nsdm-panel");

    if (!courseCard || !subjectPanel) {
      return;
    }

    document.body.classList.add("nsdm-course-detail-page");

    if (!document.querySelector(".nsdm-course-share-action")) {
      var wrap = document.createElement("div");
      wrap.className = "nsdm-course-share-action";
      wrap.innerHTML = '<button class="nsdm-course-share-btn" type="button">Share Course on WhatsApp</button>';
      subjectPanel.insertAdjacentElement("afterend", wrap);

      wrap.querySelector("button").addEventListener("click", shareCourseDetails);
    }
  });
})();
