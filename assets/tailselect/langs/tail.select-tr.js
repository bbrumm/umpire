/*
 |  tail.select - Another solution to make select fields beautiful again!
 |  @file       ./langs/tail.select-tr.js
 |  @author     SamBrishes <sam@pytes.net>
 |  @version    0.5.12 - Beta
 |
 |  @website    https://github.com/pytesNET/tail.select
 |  @license    X11 / MIT License
 |  @copyright  Copyright © 2014 - 2019 SamBrishes, pytesNET <info@pytes.net>
 */
/*
 |  Translator:     Noxludio - https://github.com/noxludio
 |  GitHub:         https://github.com/pytesNET/tail.select/pull/35
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
    select.strings.register("tr", {
        all: "Tümü",
        none: "Hiçbiri",
        actionAll: "Tümünü Seç",
        actionNone: "Hiçbirini Seçme",
        empty: "Seçenek yok",
        emptySearch: "Seçenek bulunamadı",
        limit: "Daha fazla Seçenek seçemezsiniz",
        placeholder: "Bir Seçenek seçin...",
        placeholderMulti: "En fazla :limit Seçenek seçin...",
        search: "Aramak için yazın...",
        disabled: "Bu Alan kullanılamaz"
    });
    return select;
}));
