export const tabpanel = () => {
  const toggleTabs = () => {
    const $buttons = document.querySelectorAll('.tabpanel [data-toggle]');

    $buttons.forEach((button) => {
      button.addEventListener('click', function (e) {
        const $el = e.target;
        const id = $el.dataset.toggle;
        const $tabs = document.querySelectorAll('.tabpanel [data-modal]');
        const $tab = document.querySelector(`.tabpanel [data-modal="${id}"]`);

        $buttons.forEach((btn) => {
          btn.classList.remove('-selected');
        });
        $tabs.forEach((tab) => {
          tab.classList.remove('-opened');
        });

        $el.classList.add('-selected');
        $tab.classList.add('-opened');
      });
    });
  };

  (() => {
    toggleTabs();
  })();
};
