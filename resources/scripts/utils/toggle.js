export const toggleModal = (id) => {
  const $modal = document.querySelector(`[data-modal="${id}"]`);

  if ($modal?.classList.contains('-opened')) {
    $modal.classList.remove('-opened');
    return false;
  } else {
    $modal?.classList.add('-opened');
    return true;
  }
};
