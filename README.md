# Laravel-Inertia-Vite-React (LIVR)

_Component of `lifespikes/lifespikes`_

The title gives it away, doesn't it?

A Laravel package that provides a simple way to deploy Inertia applications
powered by Vite and React.

## Getting Started

### Installation

Install the package using Composer:

```bash
composer require lifespikes/php-beam
```

You'll need to install the applicable Inertia, React, and Vite packages
as well. We've included a `package.json` that will install those for you if
you use a package manager bridge like Foxy.

While we try to keep everything as agnostic as possible, you might not be
able to use our configuration helpers if you use an older version of these
tools.

### Quick Start

LIVR is a package with an opinionated stack, but everything else is up to
you. There are two steps to configure LIVR:

**Laravel Config**

Our service provider wraps around `innocenzi/laravel-vite` and
`inertiajs/inertia-laravel`. We provide a standard configuration for both
libraries that should support most installations. You can configure PhpBeam
using by overriding any of the config options found in `config/php-beam.php`.

**JS Configuration**

LIVR comes with a set of TypeScript files you can use to quickly get your
environment going.

Simply begin by creating a `vite.config.ts` file in your project root, and
import in our config helper:

```typescript
/* Running vite:tsconfig will provide you with a @php-beam/config alias */
import generateViteConfig from '@php-beam/config';

export default generateViteConfig({
  ...customViteConfig
})
```

Our helper will take care of generating the configuration for you. If there's
any custom configuration you want to add, you can do so by passing it in as
a first argument to the helper.

**Root view, pages, and boot**

The last step is providing the primary entrypoint and root view for your
Inertia app. By default, our config will look for an entry point in
`resources/js/App.tsx` and use its built-in root view.

You can customize any of these by specifying them in your `php-beam` config:

```php
return [
    'entry_point' => 'resources/js/App.tsx',
    'root_view' => 'php-beam::app',
    
    // There are other options you can customize,
    // be sure to check the config/php-beam.php file
    // for more information.
];
```

## Usage

### Inertia Entrypoints

We've tried to make setting up the entrypoint of your Inertia app as simple
as possible. Once you specify your entrypoint _(Or use the default one)_, you
can use the `createInertiaViteApp` helper to set up your Inertia app.

```ts
import createInertiaViteApp from '@php-beam/inertia';

createInertiaViteApp(
  import.meta.glob('./your-pages-dir/**/*.tsx')
);

```

> If you want a more custom setup, like setting custom resolving logic, you can
> use the standard `createInertiaApp` helper. You can still use our `resolvePageComponent`
> helper for module resolution though!

### Environment Configuration

The best way of customizing configuration for LIVR will be using environment
variables. Modifying config values directly may cause issues with how LIVR
synchronizes settings with its vite helpers.

Here is a list of the env vars you can use to customize LIVR:

| Name                | Description                                    |
|---------------------|------------------------------------------------|
| `VITE_PUBLIC_DIR`   | The public directory for your Vite app.        |
| `VITE_BUILD_DIR`    | The build directory for your Vite app.         |
| `VITE_ENTRY_POINT`  | Path to your entrypoint.                       |
| `VITE_PORT`         | The port for your Vite dev server.             |
| `VITE_URL`          | The URL your dev server is bound to.           |
| `VITE_PUBLIC_URL`   | The URL your dev server is accessible from.    |
| `VITE_SSL`          | Whether or not to use SSL for your dev server. |
| `VITE_SSL_KEY`      | The SSL key for your dev server.               |
| `VITE_SSL_CERT`     | The SSL cert for your dev server.              |
| `VITE_INERTIA_VIEW` | The root view for your Inertia app.            |
