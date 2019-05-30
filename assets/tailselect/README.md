tail.select - Beautify Select Fields
====================================
[![npm Version](https://s.pytes.me/47a6bf48)](https://s.pytes.me/2a8c886a)
[![npm Downloads](https://s.pytes.me/f678004c)](https://s.pytes.me/2a8c886a)
[![Support Me](https://s.pytes.me/4a1717aa)](https://buymeacoffee.com/pytesNET)
[![plainJS](https://s.pytes.me/cb2d2d94)](https://s.pytes.me/21d65dff)
[![License](https://s.pytes.me/8257ac72)](LICENSE.md)

The **tail.select** script is back and ready to beautify your (multiple) select fields again, now
also with an own search method and many features to increase the usability and handling on many as
well as on just a few options! It contains all options and features from the original deprecated
jQuery Version, back from 2014 (+ a MooTools implementation)!

[Wanna see **tail.select** in action?](https://github.pytes.net/tail.select)

[Wanna translate **tail.select** in your language?](https://github.com/pytesNET/tail.select/wiki/Help-Translating)

Support
-------
<p align="center" atyle="text-align:center">
You really like my <b>tail.select</b> script and want to support me and all of my projects?<br/>
Then I would be extremely grateful for a coffee! (<b>Thanks to all Supporters</b>)<br/><br/>
<a href="https://www.buymeacoffee.com/pytesNET"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" title="Buy Me A Coffee" /></a>
</p>

Features
--------
-   Beautiful Single / Multiple Select replacement.
-   Search function to find desired options quickly.
-   Deselect-able, Limit-able and optional option descriptions.
-   Manipulate and Move (selected) options during the runtime.
-   Completely Translatable and already available in multiple languages.
-   Many Settings to adapt and configure the design and behaviour.
-   Supports AMD (requireJS) and available as browserify and **ES6** module.
-   Compatible with all modern browsers **(including IE 10+)**
-   No Dependencies, just embed and use
-   Free/To/Use - MIT Licensed

Install & Embed
---------------
The master branch will always contain the latest Release, which you can download directly here
as [.tar](https://github.com/pytesNET/tail.select/tarball/master) or as [.zip](https://github.com/pytesNET/tail.select/zipball/master)
archive, or just visit the [Releases](https://github.com/pytesNET/tail.select/releases) Page
on GitHub directly. You can also be cool and using npm, Yarn or bower:

```markup
npm install tail.select --save
```

```markup
yarn add tail.select --save
```

```markup
bower install tail.select --save
```

### Using a CDN
You can also use the awesome CDN services from jsDelivr or UNPKG.

```markup
https://cdn.jsdelivr.net/npm/tail.select@latest/
```

```markup
https://unpkg.com/tail.select/
```

Thanks To
---------
-   [Octicons](https://octicons.github.com/) for the cute Icons
-   [jsCompress](https://jscompress.com/) for the Compressor
-   [prismJS](https://prismjs.com) for the Syntax highlighting library
-   [MenuSpy](https://github.com/lcdsantos/menuspy) for the Menu Navigation

### Translations
-   [Anthony Rabine](https://github.com/arabine) / [French Translation](https://github.com/pytesNET/tail.select/issues/11)
-   [Igor](https://github.com/igorcm) / [Brazilian Portuguese Translation](https://github.com/pytesNET/tail.select/pull/34)
-   [Noxludio](https://github.com/noxludio) / [Finnish Translation](https://github.com/pytesNET/tail.select/pull/35)
-   [Roman Yepanchenko](https://github.com/tizis) / [Russian Translation](https://github.com/pytesNET/tail.select/issues/38)
-   [elPesecillo](https://github.com/elPesecillo) / [Spanish Translation](https://github.com/pytesNET/tail.select/issues/41)
-   [Alberto Vincenzi](https://github.com/albertovincenzi) / [Italian Translation](https://github.com/pytesNET/tail.select/issues/43)
-   [WoxVold](https://github.com/woxwold) / [Norwegish Translation](https://github.com/pytesNET/tail.select/issues/45)
-   [Spritus](https://github.com/spritus) / [Turkish Translation](https://github.com/pytesNET/tail.select/issues/48)

Documentation
-------------
The Documentation has been moved to [GitHubs Wiki Pages](https://github.com/pytesNET/tail.select/wiki),
but I will keep a table of contents list here and some basic instructions.

-   [Install & Embed](https://www.github.com/pytesNET/tail.select/wiki/instructions)
-   [Default Usage](https://www.github.com/pytesNET/tail.select/wiki/default-usage)
-   [Public Options](https://www.github.com/pytesNET/tail.select/wiki/public-options)
-   [Public Methods](https://www.github.com/pytesNET/tail.select/wiki/public-methods)
-   [Events & Callbacks](https://www.github.com/pytesNET/tail.select/wiki/events-callbacks)
-   [Internal Variables & Methods](https://www.github.com/pytesNET/tail.select/wiki/internal)
-   [HowTos, Tips & Tricks](https://www.github.com/pytesNET/tail.select/wiki/How-Tos)

### Files
The `tail.select` package contains different JavaScript files:

-   `js/tail.select(.min).js` The main JavaScript with `en` tranlation strings only.
-   `js/tail.select-full(.min).js` The main JavaScript with ALL available translations.
-   `js/tail.select-es6(.min).js` An **experimental** ECMAScript 2015 / ES6 Module version (includes all translations).
-   `langs/tail.select-all(.min).js` Just ALL translation strings itself.
-   `langs/tail.select-{locale}.js` Just the {locale} translation strings.

### Basic Instructions
You can pass up to 2 arguments to the **tail.select** constructor, the first parameter is required
and need to be an `Element`, a `NodeList`, a `HTMLCollection`, an Array with `Element` objects or
just a single selector as `string`, which calls the `querySelectorAll()` method on its own. The
second parameter is optional and, if set, MUST be an object with your *tail.select* options.

```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <link type="text/css" rel="stylesheet" href="css/tail.select-default.css" />
    </head>
    <body>
        <script type="text/javascript" src="js/tail.select.min.js"></script>
        <!-- <script type="text/javascript" src="langs/tail.select-{lang}.js"></script> -->

        <select>
            <option>My Option</option>
        </select>

        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function(){
                tail.select("select", { /* Your Options */ });
            });
        </script>
    </body>
</html>
```

### Default options
Please check out [GitHubs Wiki Pages](https://github.com/pytesNET/tail.select/wiki) to read more
about each single option!

```javascript
tail.select("select", {
    animate: true,
    classNames: null,
    csvOutput: false,
    csvSeparator: ",",
    descriptions: false,
    deselect: false,
    disabled: false,                // NEW IN 0.5.0
    height: 350,
    hideDisabled: false,
    hideSelected: false,
    items: {},
    locale: "en",                   // NEW IN 0.5.0
    lingusticRules: {},             // EXPERIMENTAL SINCE 0.5.9
    multiple: false,
    multiLimit: Infinity,           // UPDATE IN 0.5.0
    multiPinSelected: false,        // NEW IN 0.5.0
    multiContainer: false,          // UPDATE IN 0.5.0
    multiShowCount: true,
    multiShowLimit: false,          // NEW IN 0.5.0
    multiSelectAll: false,
    multiSelectGroup: true,
    openAbove: null,
    placeholder: null,
    search: false,
    searchFocus: true,
    searchMarked: true,
    searchDisabled: true,           // NEW IN 0.5.5
    sortItems: false,
    sortGroups: false,
    sourceBind: false,              // NEW IN 0.5.0
    sourceHide: true,               // NEW IN 0.5.0
    startOpen: false,
    stayOpen: false,                // UPDATED IN 0.5.0
    width: null,
    cbComplete: undefined,          // NEW IN 0.5.0
    cbEmpty: undefined,             // NEW IN 0.5.0
    cbLoopItem: undefined,
    cbLoopGroup: undefined
});
```

Copyright & License
-------------------
Published under the MIT-License; Copyright &copy; 2014 - 2019 SamBrishes, pytesNET
