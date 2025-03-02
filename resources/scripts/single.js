import domReady from '@roots/sage/client/dom-ready';
import {tabpanel} from './modules/tabpanel';

/**
 * Application entrypoint
 */
domReady(async () => {
  tabpanel();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
