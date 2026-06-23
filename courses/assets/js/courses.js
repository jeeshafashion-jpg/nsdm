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
