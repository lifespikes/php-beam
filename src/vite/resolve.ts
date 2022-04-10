const resolve = (
  name: string,
  pages: Record<string, () => unknown | string>,
) => {
  for (const path in pages) {
    if (
      path.endsWith(`${name.replace('.', '/')}.tsx`) ||
      path.endsWith(`${name.replace('.', '/')}/index.tsx`)
    ) {
      return typeof pages[path] === 'function' ? pages[path]() : pages[path];
    }
  }

  throw new Error(`Page not found: ${name}`);
};

export default resolve;
