---
date: 2019-10-07T12:46:00+02:00
lang: en
title: 'Javascript & currying: why not'
---

Over the years programmers that came to javascript brought and implemented many foreign patterns and techniques in the attempt to "fix" this language<sup>[<a href="https://xkcd.com/285/">citation needed</a>]</sup>.

This practice, sometimes referred to as [greenspunning](https://en.wikipedia.org/wiki/Greenspun%27s_tenth_rule), seems to be quite popular in javascript. Maybe because some people feel like the language is broken, maybe due to the flexibility it offers. I guiltily admit I've tried too.

However I'd like to talk about function [currying](https://en.wikipedia.org/wiki/Currying). Some people think it could bring huge benefit to javascript, many others just hate it, but how does it work? Is it a good idea to use it extensively?

<h2 id="first-part">What is currying?</h2>

<small>Wait, I know you functional people have rock-solid grasp on the topic, just [skip](#second-part) the first part, see you later!</small>

Some languages like [Haskell](https://en.wikipedia.org/wiki/Haskell_%28programming_language%29) and those in [ML family](https://en.wikipedia.org/wiki/ML_%28programming_language%29) adopt an odd philosophy: every function takes exactly one argument, no more, no less. So a multiple argument function - in those languages - will be represented with a single argument function that returns another function and so on. At the end of this chain, the last function will return some non-function value.

For example in javascript we write `Math.min(10, 5)`, while in OCaml the expression would be `min 10 5`. Difference is subtle: `Math.min(10)` will be evaluated to `10`, but `min 10` will produce a function that, when applied to a second integer, will give the minimum between `10` and that number.

This difference can be clearer if we try to implement OCaml's `min` in javascript. The code may look like this:

```javascript
const min = x => y => x > y ? y : x;
min(10)(5); // 5
```

In the same way zero-argument functions typically are represented with one-argument functions that take a particular value called _unit_.

```ocaml
(* OCaml's print_newline takes a unit and returns a unit *)
let () = print_newline ()

(* this `()` is treated exactly as any other value! *)
let the_unit_value = ()
let () = print_newline the_unit_value
```

## Why bother?

Computer scientists just love simple models, those functional languages have a neat definition of what a function is thanks to currying. Of course, besides theoretical aspect, it gives expressive power too! For example we may define generic functions and get more specialized ones by applying one argument at time:

```javascript
const slice = begin => end => array => array.slice(begin, end);

const take = slice(0);
const copy = take(Infinity);

slice(1)(3)([1, 2, 3, 4]); // [ 2, 3 ]
take(3)([1, 2, 3, 4]); // [ 1, 2, 3 ]
copy([1, 2]); // [ 1, 2 ]
```

But it's useful to make anonymous functions super concise too:

```javascript
const get = key => obj => obj[key];

const people = [{ name: 'Ellis' }, { name: 'Clay' }, { name: 'Toby' }];

const names = people.map(get('name'));
// [ 'Ellis', 'Clay', 'Toby' ]
```

## Looks ugly?

Well the syntax we are using for declaring and calling those functions isn't great, you're right. But we can do better!

```javascript
const curry = (fn, numArgs = fn.length, args = []) =>
  args.length < numArgs
    ? (...innerArgs) => curry(fn, numArgs, args.concat(innerArgs))
    : fn(...args);

const slice = curry((begin, end, array) => array.slice(begin, end));

const take = slice(0);
const copy = take(Infinity);

slice(1, 3, [1, 2, 3, 4]); // [ 2, 3 ]
take(3, [1, 2, 3, 4]); // [ 1, 2, 3 ]
copy([1, 2]); // [ 1, 2 ]
```

Okay, I lied. I said that curried functions take exactly one argument, and now my `curry` produces functions that sometimes take one argument, but work with two or three too.
This technique is called [partial application](https://en.wikipedia.org/wiki/Partial_application) actually, and means that we may provide more than one argument and get a function back when the arguments aren't enough to produce the final value. <br>
Basically `curried_slice(1)(3) ≈ papply_slice(1, 3)`.

Javascript syntax makes partial application way more ergonomic than actual currying, so from now on we'll just ignore the difference, may Haskell Brooks Curry forgive our souls.

## Let's `curry` everything!

Slow down, cowboy, now I'd like to tell why you may not want to do that.

The first obvious reason is the arguments order: you may have noticed our `slice` function takes its array as last argument. This pattern is widespread in functional programming and currying makes it very tempting!

If we used the argument order a javascript developer probably would have used it's unlikely that partial application would have been useful: who needs a function that takes two indices and gives a slice of a particular array?

```javascript
const slice = curry((array, begin, end) => array.slice(begin, end));

const getSliceOfNames = slice(['Ellis', 'Clay', 'Toby']);
getSliceOfNames(1, 3); // [ 'Clay', 'Toby' ]
getSliceOfNames(0, 2); // [ 'Ellis', 'Clay' ]
```

So libraries with "curry everything" mindset have to redefine everything with an unusual argument order and tell people to stick with their choice because the normal order wouldn't be functional enough.

<h2 id="second-part">Against the flow</h2>

One does not simply tell what is and what isn't idiomatic in javascript, there aren't community accepted standards, we can't even tell if it's better to use semicolons! So one can't just say "currying isn't idiomatic in javascript", if people start to adopt, it will become idiomatic eventually.

However some language design choices don't play well with currying, in particular _variadic functions_. A language can have functions with variable number of arguments _or_ curried by default functions, not both. Since _every_ javascript function can be called with an arbitrary number of arguments, it will never be part of the language, whoever wants it has to work with user space solutions.

But [rest parameters](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Functions/rest_parameters) and [default parameters](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Functions/Default_parameters) syntaxes _are_ part of the language and using these with currying isn't ideal. For example:

```javascript
// Note that Function.length only includes parameters
// before the first one with a default value
const slice = curry(
  (begin = 0, end = Infinity, array) => array.slice(begin, end),
  3
); // ← ...so we have to tell explicitly
//    this function takes 3 arguments

// Here we want the default value... ugh!
//          ↓
slice(1, undefined, [1, 2, 3, 4]); // [ 2, 3, 4 ]
```

## With great power...

While doing research for this article, I noticed I couldn't find any dynamically typed language with auto-currying. Clojure has [partial](https://clojuredocs.org/clojure.core/partial), Common Lisp has [curry](https://common-lisp.net/project/bese/docs/arnesi/html/api/function_005FIT.BESE.ARNESI_003A_003ACURRY.html), but these are opt-in, not default behaviour. Moreover [Erlang](https://en.wikipedia.org/wiki/Erlang_%28programming_language%29) and [Elixir](https://en.wikipedia.org/wiki/Elixir_%28programming_language%29) don't seem to have those utilities at all and yet they are functional languages (according to wikipedia, at least).

This technique is tightly coupled with fully type-inferred languages, which are very strict environments where the type system can correctly determine the type of every expression often without any type annotation.

The fact is currying makes everything implicit, and languages which adopt it have a type system expressly designed to handle that. On the other hand in javascript if we forget an argument, for example, we get a partially applied function passed around our application and refactoring might become not-so-awesome™.

Of course partial application is handy and it's a breeze to implement in modern javascript, but use it extensively doesn't sound good to me. If there isn't a language designer willing to bet on currying with dynamic typing why should we trust people that say "nah, it's going to work just fine"?

I think this idea to curry everything it's tied to a _write-js-like-haskell_ attitude and I'm afraid that is giving to many people the wrong idea about functional programming being about gibberish math terms and unreadable code written with some exotic library.

## Why can't we have nice things?

If applying a particular paradigm directly in javascript isn't the best idea it doesn't mean it's a bad idea in general! There are many great tools out there for functional programming lovers like [Elm](https://elm-lang.org/), [BuckleScript](https://bucklescript.github.io/) and [PureScript](http://www.purescript.org/). I don't know if you'll love or hate them, but if you really want to use currying, without any doubt these are the right tool for the job.
