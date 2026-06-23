/*! ========= INFORMATION ============================
    - document:  Button Generator PRO
    - brand:     Wow-Company
    - brand-url: https://wow-company.com/
    - store-url: https://wow-estore.com/
    - author:    Dmytro Lobov
    - url:       https://wow-estore.com/item/button-generator-pro/
==================================================== */

'use strict';

class ButtonGenerator {

  constructor(buttonElement) {
    this.button = buttonElement;
    this.atts = {
      action: this.button.dataset.action,
      link: this.button.dataset.url,
      target: this.button.dataset.target,
      track: this.button.dataset.track,
      id: this.button.dataset.btnid,
      badge: this.button.querySelector('.btg-counter'),
    };
    this.run = this.run.bind(this);
    buttonElement.addEventListener('click', this.run);
  }

  run() {
    this.handleAction();
    this.counter();
  }

  handleAction() {
    const actionMap = {
      link: () => this.links(),
    };

    const actionFunction = actionMap[this.atts.action];
    if (actionFunction) {
      actionFunction();
    }
  }

  links() {
    if (this.atts.target !== undefined) {
      window.open(this.atts.link, this.atts.target);
    } else {
      window.open(this.atts.link, '_self');
    }
  }

  counter() {
    let data = new FormData();
    data.append('action', 'button_action');
    data.append('security', btg_button.security);
    data.append('id', this.atts.id);

    fetch(btg_button.url, {
      method: 'POST',
      body: data
    })
    .then(response => response.text())
    .then(data => {
      let response = JSON.parse(data);
      if(response.msg === 'OK' && this.atts.badge !== null) {
        this.atts.badge.innerText = response.count;
      }

    })
    .catch(error => console.error('Error:', error));
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const buttons = document.querySelectorAll('.btg-button');
  const buttonHandlers = Array.from(buttons).map(button => new ButtonGenerator(button));
});