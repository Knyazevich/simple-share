function Webzp_Share() {
  this.init = function() {
    this.setPopups();
  }
};

Webzp_Share.prototype.setPopups = function() {
  const popupBtns = document.querySelectorAll(
    '.webzp-share-block__link[data-popup="true"]'
  );

  popupBtns.forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const href = e.target.href;
      window.open(href, 'Share', 'width=700, height=500');
    });
  });
  
};

document.addEventListener('DOMContentLoaded', function() {
  const s = new Webzp_Share;
  s.init();
});