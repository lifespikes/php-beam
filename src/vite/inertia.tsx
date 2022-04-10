import React, {ReactNode} from 'react'
import { render } from 'react-dom'
import { createInertiaApp } from '@inertiajs/inertia-react'
import resolvePageComponent from './resolve';

const createInertiaViteApp = <T extends Record<string, () => unknown>,>(pagesGlob: T) => {
  createInertiaApp({
    resolve: name =>
      resolvePageComponent(
        name,
        pagesGlob,
      ) as ReactNode,
    setup({ el, App, props }) {
      render(<App {...props} />, el)
    },
  });
};

export default createInertiaViteApp;
