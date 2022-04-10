import * as dotenv from 'dotenv';
import * as dotEnvExpand from 'dotenv-expand';

/* Parse dotenv */
const env = dotenv.config();
dotEnvExpand.expand(env);

const {
  VITE_URL,
  VITE_PORT,
  VITE_PUBLIC_URL,
  VITE_SSL,
  VITE_SSL_KEY,
  VITE_SSL_CERT,
} = process.env;

const origin = VITE_PUBLIC_URL ?? '';

const hostFromUrl = (url: string) => new URL(url).hostname;

const getSslParams = () => {
  const [enabled, key, cert] = [VITE_SSL, VITE_SSL_KEY, VITE_SSL_CERT];
  return enabled !== 'true' ? false : key && cert ? {key, cert} : true;
};

const getHmrParams = () => ({
  host: hostFromUrl(origin),
});

export const getConfig = () => {
  try {
    return {
      host: hostFromUrl(VITE_URL ?? ''),
      port: Number(VITE_PORT),
      https: getSslParams(),
      hmr: getHmrParams(),

      /* Mitigate HTTP2 errors */
      proxy: {[origin]: origin},
    };
  } catch (e) {
    console.log(process.env);
    throw e;
  }
};
