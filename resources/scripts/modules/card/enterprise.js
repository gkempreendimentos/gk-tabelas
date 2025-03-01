import {toggleModal} from '../../utils/toggle';

export const cardEnterprise = () => {
  const toggleMaterials = () => {
    const $button = document.querySelectorAll(
      '.card.-enterprise [data-toggle]',
    );

    $button.forEach((button) => {
      button.addEventListener('click', function (e) {
        const $el = e.target.closest('.button');
        const $label = $el.querySelector('span');

        $label.textContent = toggleModal($el.dataset.toggle)
          ? 'Ocultar materiais'
          : 'Ver materiais';
      });
    });
  };

  (() => {
    toggleMaterials();
  })();
};
