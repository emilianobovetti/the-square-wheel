---
date: 2019-07-14T12:46:00+02:00
title: CPU intensive tasks in Elm
lang: en

---
A little premise first: the benchmarks in this article depend closely on how things work today. The code is designed to work efficiently here and now (Elm 0.18-0.19), the evolution of the environment may change drastically the numbers, so don't rely too much on them!

Last year I was working on an [Elm](https://elm-lang.org) project and I had to implement a fuzzy search system. There wasn't a huge amount of data to process or special needs, so the most straightforward approach seemed to calculate [edit distance](https://en.wikipedia.org/wiki/Edit_distance) directly in Elm.

The good news is that functional languages often allow to write nice implementations from mathematical definitions.
In fact [Levenshtein distance](https://en.wikipedia.org/wiki/Levenshtein_distance#Definition) can be calculated as follows:

```elm
min3 n = min n >> min

lev a b =
  case (String.uncons a, String.uncons b) of
    (Nothing, _) ->
      String.length b

    (_, Nothing) ->
      String.length a

    (Just (aHd, aTl), Just (bHd, bTl)) ->
      if aHd == bHd then
        lev aTl bTl
      else
        min3
          (lev aTl b + 1)
          (lev a bTl + 1)
          (lev aTl bTl + 1)
```

Bad news is this code is going be *real* slow. Let's say we have two strings of length `n` with no characters in common, in that case the number of `lev`'s recursive calls grows exponentially when `n` grows. It's like a fork bomb with an exit condition: two strings of just 10 characters each will produce up to 12ॱ146ॱ178 function calls.

There are some algorithms and techniques to solve this problem efficiently, if you are interested in the topic, then [A Comparison of Approximate String Matching Algorithms](https://www.cs.hut.fi/~tarhio/papers/jtu.pdf) is probably something you want to read.

Writing an efficient Elm implementation, anyway, isn't trivial task because we have to represent a matrix column and update its components. This is one of those cases in which we **really** want side effects and achieve single thread raw speed, but to write nice and portable Elm code, that benefits from all the guarantees of the language, we have to deal with immutability. It can be challenging to guess which solution is going to give best performance, the game consists in run some benchmark and try again with another approach.

At a certain point, I had a [working implementation](https://package.elm-lang.org/packages/emilianobovetti/edit-distance/latest), now the questions that arise are: 1. how much data can we process and 2. how much faster would the task be in pure js.

So I chose a [npm package](https://www.npmjs.com/package/leven) to compute edit distance and wrote a [benchmark](https://github.com/emilianobovetti/edit-distance-benchmark/blob/master/main.js). To draw an upper bound I assumed the process shouldn't take more than one second to complete, otherwise we probably are blocking the main thread for too long.

As you can see the test uses [two random strings](https://github.com/emilianobovetti/edit-distance-benchmark/blob/2c75a072831967c0d19b94976976a218bfdd33b2/main.js#L25-L26): `text` and `pattern`, the distance between them - naturally - doesn't depend on their order (`lev text pattern == lev pattern text`), but the assumption here is that `pattern` is shorter than `text`, because [`patternLoop`](https://github.com/emilianobovetti/edit-distance/blob/35ad136177b31a87e473e6739328d710ca8b3f1c/src/EditDistance.elm#L46) isn't tail recursive.

Since time complexity is `O(length text * length pattern)` the time that it takes to process a 100-chars text and 10-chars pattern should be *about* the same as with 10-chars text and 100-chars pattern.

Anyway, the following numbers came from this environment:

- `awk -F ':' '/model name/{print $2}' < /proc/cpuinfo` <br>
    Intel(R) Core(TM) i3-6006U CPU @ 2.00GHz
- `uname -nrv` <br>
    hp-250-g6 4.9.0-9-amd64 #1 SMP Debian 4.9.168-1+deb9u3 (2019-06-16)
- `node -v` <br>
    v10.16.0

To process a `10ॱ000` characters text and `1ॱ000` characters pattern takes about `.7s` with my code and about `.046s` with [leven](https://www.npmjs.com/package/leven). <br>
Because of `patternLoop`, Elm implementation will probably cause a stack overflow when pattern exceed `~1ॱ500` characters, now
if we say that text and pattern have about the same length:

1. the fastest Elm approach I was able to find runs about 10-20 times slower than the fastest javascript library I found
2. you may consider Elm approach with inputs up to about `1ॱ000` characters
3. you may want to switch to javascript with inputs up to `10ॱ000` characters
4. you should consider another approach if you need to process more data: use a different algorithm, run it in a web worker or just move the job outside the browser

## Where Elm really shines

The very first problem I had to face in this project is the lack of a `String.charAt` function. So the options are [String.uncons](https://package.elm-lang.org/packages/elm/core/latest/String#uncons) or [String.toList](https://package.elm-lang.org/packages/elm/core/latest/String#toList). Now, the former solution would call [String.prototype.slice](https://github.com/elm/core/blob/f88d6a6de98802f5cc2d99dc1a0c2734cc5bdd7b/src/Elm/Kernel/String.js#L21-L22) for every character in the string, not awesome for performance. The latter, on the other hand, would lead to a nice [interface](https://package.elm-lang.org/packages/emilianobovetti/edit-distance/latest/EditDistance#levenshtein) that can be used with every pair of `List comparable`, not just with strings.

Anyway the reason why Elm doesn't provide a `charAt` function is that it's doing some work for us behind the scenes: it handles [surrogate pairs](https://en.wikipedia.org/wiki/UTF-16#U.2B010000_to_U.2B10FFFF), so our code works out of the box where a pure js implementation may yield unintuitive results:

```shell
git clone git@github.com:emilianobovetti/edit-distance-benchmark.git
cd edit-distance-benchmark
make
node
```

```js
const elmApp = require('./elm-app.js').Elm.Main.init();
const leven = require('leven');

leven('x', '🚀'); // 2

elmApp.ports.sendDistance.subscribe(console.log);
elmApp.ports.calcDistance.send({ text: 'x', pattern: '🚀' }); // 1
```

In conclusion Elm seems a valid alternative to javascript even for some CPU intensive tasks. Interestingly, the time overhead is comparable to that of [Array.prototype.reduce](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/reduce) vs. for loops, a price that, in many cases, I'll happily pay.