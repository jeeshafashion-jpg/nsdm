/*! ========= INFORMATION ============================
    - document:  Button Generator PRO
    - brand:     Wow-Company
    - brand-url: https://wow-company.com/
    - store-url: https://wow-estore.com/
    - author:    Dmytro Lobov
    - url:       https://wow-estore.com/item/button-generator-pro/
==================================================== */
"use strict";class ButtonGenerator{constructor(t){this.button=t,this.atts={action:this.button.dataset.action,link:this.button.dataset.url,target:this.button.dataset.target,track:this.button.dataset.track,id:this.button.dataset.btnid,badge:this.button.querySelector(".btg-counter")},this.run=this.run.bind(this),t.addEventListener("click",this.run)}run(){this.handleAction(),this.counter()}handleAction(){const t={link:()=>this.links()}[this.atts.action];t&&t()}links(){void 0!==this.atts.target?window.open(this.atts.link,this.atts.target):window.open(this.atts.link,"_self")}counter(){let t=new FormData;t.append("action","button_action"),t.append("security",btg_button.security),t.append("id",this.atts.id),fetch(btg_button.url,{method:"POST",body:t}).then((t=>t.text())).then((t=>{let n=JSON.parse(t);"OK"===n.msg&&null!==this.atts.badge&&(this.atts.badge.innerText=n.count)})).catch((t=>console.error("Error:",t)))}}document.addEventListener("DOMContentLoaded",(function(){const t=document.querySelectorAll(".btg-button");Array.from(t).map((t=>new ButtonGenerator(t)))}));