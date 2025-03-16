import {Fancybox} from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';

export const lightbox = () => {
  const start = () => {
    Fancybox.bind('[data-fancybox]', {});
  };

  (() => {
    start();
  })();
};
