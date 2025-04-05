import {Fancybox} from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';

export const lightbox = () => {
  const start = () => {
    Fancybox.bind('[data-fancybox]', {});
  };

  const download = (url, nome = 'imagem.jpg') => {
    fetch(url)
      .then((res) => res.blob())
      .then((blob) => {
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = nome;
        document.body.appendChild(a);
        a.click();
        a.remove();
      });
  };

  (() => {
    start();
    window.downloadImage = download;
  })();
};
