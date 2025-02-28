export const cardEnterprise = () => {
  const toggleMaterial = () => {
    const $button = document.querySelectorAll('[data-id="materials"]');

    $button.forEach((button) => {
      button.addEventListener('click', function (e) {
        const $el = e.target;
        const $card = $el.closest('.card');

        $card.classList.toggle('-opened');
      });
    });
  };

  (() => {
    toggleMaterial();
  })();
};
