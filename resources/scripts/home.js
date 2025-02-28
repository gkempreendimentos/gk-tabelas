import domReady from '@roots/sage/client/dom-ready';
import {cardEnterprise} from './modules/card/enterprise';

/**
 * Application entrypoint
 */
domReady(async () => {
  cardEnterprise();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
