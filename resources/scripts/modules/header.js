import {toggleModal} from '../utils/toggle';

export const header = () => {
  const toggleGlobalnav = () => {
    const $buttons = document.querySelectorAll('[data-toggle="globalnav"]');

    $buttons.forEach((button) => {
      button.addEventListener('click', function () {
        toggleModal('globalnav');
      });
    });
  };

  (() => {
    toggleGlobalnav();
  })();
};
