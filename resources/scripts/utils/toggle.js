export const toggleModal = (id) => {
  const $modal = document.querySelector(`.modal[data-modal="${id}"]`);

  console.log($modal);

  if ($modal?.classList.contains('-opened')) {
    $modal.classList.add('-closing');
    $modal.classList.remove('-opened');

    $modal.addEventListener(
      'transitionend',
      () => {
        $modal.classList.remove('-closing');
      },
      {once: true},
    );

    return false;
  } else {
    $modal?.classList.add('-opened');
    return true;
  }
};
