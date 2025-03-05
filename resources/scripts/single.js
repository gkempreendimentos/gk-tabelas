import domReady from '@roots/sage/client/dom-ready';
import {tabpanel} from './modules/tabpanel';
import {table} from './modules/table';
import {map} from './modules/map';

/**
 * Application entrypoint
 */
domReady(async () => {
  tabpanel();
  table();
  map();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
