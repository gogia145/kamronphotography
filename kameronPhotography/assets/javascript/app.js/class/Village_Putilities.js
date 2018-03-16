var Village_Utilities;

Village_Utilities = (function() {
  function Village_Utilities() {}

  Village_Utilities.prototype.delay = function(ms, func) {
    return setTimeout(func, ms);
  };

  Village_Utilities.prototype.to_$ = function(el) {
    if (_.isString(el)) {
      return $(el);
    } else {
      return el;
    }
  };

  Village_Utilities.prototype.random_time = function() {
    return _.random(500, 1000) / 1000;
  };

  return Village_Utilities;

})();
