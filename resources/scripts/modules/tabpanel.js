export const tabpanel = () => {
  const goToTab = (tab) => {
    const $buttons = document.querySelectorAll('.tabpanel-tabs [data-toggle]');
    const $tabs = document.querySelectorAll('.tabpanel [data-tab]');
    const $tab = document.querySelector(`.tabpanel [data-tab="${tab}"]`);
    const $button = document.querySelector(
      `.tabpanel-tabs [data-toggle=${tab}]`,
    );

    $buttons.forEach((btn) => {
      btn.classList.remove('-selected');
    });
    $tabs.forEach((tab) => {
      tab.classList.remove('-opened');
    });

    $tab.classList.add('-opened');
    $button.classList.add('-selected');
    location.hash = tab;
  };

  const toggleTabs = () => {
    const $buttons = document.querySelectorAll('.tabpanel-tabs [data-toggle]');

    $buttons.forEach((button) => {
      button.addEventListener('click', function (e) {
        const $el = e.target;
        const id = $el.dataset.toggle;

        goToTab(id);
      });
    });

    if (location.hash) {
      goToTab(location.hash.split('?')[0].replace('#', ''));
    }
  };

  (() => {
    toggleTabs();
  })();
};
