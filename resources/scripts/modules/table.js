export const table = () => {
  const toggleRow = () => {
    const $rows = document.querySelectorAll('.column-primary');

    $rows.forEach((row) => {
      row.addEventListener('click', function () {
        row.closest('tr').classList.toggle('-expanded');
      });
    });
  };

  (() => {
    toggleRow();
  })();
};
