const compose =
  (fst, ...functions) =>
  (...args) =>
    functions.reduce((acc, fn) => fn(acc), fst(...args));

export default compose;
