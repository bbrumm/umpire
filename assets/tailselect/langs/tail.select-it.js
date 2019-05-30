/*
 |  tail.select - Another solution to make select fields beautiful again!
 |  @file       ./langs/tail.select-it.js
 |  @author     SamBrishes <sam@pytes.net>
 |  @version    0.5.12 - Beta
 |
 |  @website    https://github.com/pytesNET/tail.select
 |  @license    X11 / MIT License
 |  @copyright  Copyright © 2014 - 2019 SamBrishes, pytesNET <info@pytes.net>
 */
/*
 |  Translator:     Alberto Vincenzi - (https://github.com/albertovincenzi)
 |  GitHub:         https://github.com/pytesNET/tail.select/issues/43
 */
;(function(factory){
   if(typeof(define) == "function" && define.amd){
       define(function(){
           return function(select){ factory(select); };
       });
   } else {
       if(typeof(window.tail) != "undefined" && window.tail.select){
           factory(window.tail.select);
       }
   }
}(function(select){
    select.strings.register("it", {
        all: "Tutti",
        none: "Nessuno",
        actionAll: "Seleziona Tutto",
        actionNone: "Deseleziona Tutto",
        empty: "Nessuna voce disponibile",
        emptySearch: "Nessuna voce trovata",
        limit: "Non puoi selezionare più Voci",
        placeholder: "Seleziona una Voce",
        placeholderMulti: "Selezione limitata a :limit Voci...",
        search: "Digita per cercare...",
        disabled: "Questo Campo è disabilitato"
    });
    return select;
}));
