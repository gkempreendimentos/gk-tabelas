export const map = () => {
  const openDot = (dot) => {
    const $dots = document.querySelectorAll('.map [data-toggle]');
    const $button = document.querySelector(`.map [data-toggle="${dot}"]`);

    $dots.forEach((btn) => {
      if (btn.dataset.toggle !== $button.dataset.toggle) {
        btn.closest('li').classList.remove('-opened');
      }
    });

    $button.closest('li').classList.toggle('-opened');
  };

  const toggleDots = () => {
    const $buttons = document.querySelectorAll('.map [data-toggle]');

    $buttons.forEach((button) => {
      button.addEventListener('click', function (e) {
        const $el = e.target;
        const id = $el.dataset.toggle;

        openDot(id);
      });
    });
  };

  (() => {
    toggleDots();
  })();
};
