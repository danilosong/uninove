/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

window.App = {
    modules: {}
};

var settings = {
    
};

/**
 * 
 * @type Function|undefined
 */
App.modules.chargeOptions = (function(){
    
    var options = {
        pagination: true
    };
    
    settings = $.extend({}, options, settings);
})();

/**
 * Modulo de paginacao
 * 
 * @type Function|undefined
 */
App.modules.Pagination = (function () {
    
    if(settings.pagination == true){
        alert("Ol")
    };
    
})();

App.modules.Messenger = (function(){

})();



