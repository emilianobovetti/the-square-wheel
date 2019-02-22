const camelToDashes = str =>
  str.replace(/([A-Z])/g, '-$1');

const serialize = val => (
  typeof val === 'string'
    ? val
    : Object.keys(val)
      .map(key => `${camelToDashes(key)}: ${serialize(val[key])};`)
      .join('\n')
);

export default serialize;
