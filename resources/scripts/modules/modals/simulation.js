import {mask} from '../../utils/mask';
import {toggleModal} from '../../utils/toggle';

export const simulation = () => {
  const $form = document.querySelector('.modal.-simulation form');

  const getLocalSimulation = () => {
    const storage = localStorage.getItem('simulation');

    return JSON.parse(storage);
  };

  const setLocalSimulation = (obj) => {
    const storage = getLocalSimulation();
    const simulation = Object.assign(
      {
        entry: 0,
        boosterQuantity: 0,
        boosterTotal: 0,
        installments: 0,
      },
      storage,
      obj,
    );

    localStorage.setItem('simulation', JSON.stringify(simulation));
  };

  const toggleSimulation = () => {
    const $buttons = document.querySelectorAll('[data-toggle="simulation"]');

    $buttons.forEach((button) => {
      button.addEventListener('click', function () {
        const price = button.dataset.price;

        toggleModal('simulation');

        if (price) {
          setLocalSimulation({
            price: Number(price),
          });

          $form.price.value = mask.currency(price);
        }

        calculator();
      });
    });
  };

  const calculator = () => {
    const storage = getLocalSimulation();
    const entry = (storage.entry * storage.price) / 100;
    const booster = (storage.boosterQuantity * storage.boosterTotal) / 100;
    const installments =
      (storage.price - entry - booster) / storage.installments;
    const $buttonWpp = document.querySelector('[data-calc="whatsapp"]');
    const message = `
    *Valor Total:* ${mask.currency(storage.price)}
    *Entrada (65%):* ${mask.currency(entry)}
    *13 reforços:* ${mask.currency(booster)} cada
    *100 parcelas:* ${mask.currency(installments)}/mês
    `;
    $buttonWpp.href = `https://api.whatsapp.com/send?text=${message}`;

    const $entry = document.querySelector('[data-calc="entry"]');
    const $installments = document.querySelector('[data-calc="installments"]');
    const $range = document.querySelector('[data-calc="range"]');

    $range.innerHTML = `(${storage.entry}%)`;
    $entry.innerHTML = mask.currency(entry);
    $entry.value = entry;
    $installments.innerHTML = mask.currency(
      installments > 0 ? installments : 0,
    );
  };

  const renderWhatsapp = () => {};

  const onChange = () => {
    $form.addEventListener('input', function (e) {
      const $input = e.target;
      const name = $input.name;
      const value = $input.value;

      if (value && $input.type === 'text') {
        $input.value = mask.currency(value);
      }

      setLocalSimulation({
        [name]: Number(value.replace(/\D/g, '')),
      });

      calculator();
      renderWhatsapp();
    });
  };

  (() => {
    onChange();
    toggleSimulation();
  })();
};
