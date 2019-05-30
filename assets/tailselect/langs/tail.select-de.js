/*
 |  tail.select - Another solution to make select fields beautiful again!
 |  @file       ./langs/tail.select-de.js
 |  @author     SamBrishes <sam@pytes.net>
 |  @version    0.5.12 - Beta
 |
 |  @website    https://github.com/pytesNET/tail.select
 |  @license    X11 / MIT License
 |  @copyright  Copyright © 2014 - 2019 SamBrishes, pytesNET <info@pytes.net>
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
    select.strings.register("de", {
        all: "Alle",
        none: "Keine",
        actionAll: "Alle auswählen",
        actionNone: "Alle abwählen",
        empty: "Keine Optionen verfügbar",
        emptySearch: "Keine Optionen gefunden",
        limit: "Keine weiteren Optionen wählbar",
        placeholder: "Wähle eine Option...",
        placeholderMulti: "Wähle bis zu :limit Optionen...",
        search: "Tippen zum suchen",
        disabled: "Dieses Feld ist deaktiviert"
    });
    return select;
}));
