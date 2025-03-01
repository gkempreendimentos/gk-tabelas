import domReady from '@roots/sage/client/dom-ready';
import {toggleModal} from './utils/toggle';

/**
 * Application entrypoint
 */
domReady(async () => {
  toggleModal();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
