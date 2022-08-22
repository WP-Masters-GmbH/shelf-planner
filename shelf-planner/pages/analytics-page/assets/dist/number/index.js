this["wc"] = this["wc"] || {}; this["wc"]["number"] =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./packages/number/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _arrayWithHoles; });
function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _iterableToArrayLimit; });
function _iterableToArrayLimit(arr, i) {
  if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === "[object Arguments]")) {
    return;
  }

  var _arr = [];
  var _n = true;
  var _d = false;
  var _e = undefined;

  try {
    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);

      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }

  return _arr;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _nonIterableRest; });
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance");
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/slicedToArray.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _slicedToArray; });
/* harmony import */ var _arrayWithHoles__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithHoles */ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js");
/* harmony import */ var _iterableToArrayLimit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArrayLimit */ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js");
/* harmony import */ var _nonIterableRest__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./nonIterableRest */ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js");



function _slicedToArray(arr, i) {
  return Object(_arrayWithHoles__WEBPACK_IMPORTED_MODULE_0__["default"])(arr) || Object(_iterableToArrayLimit__WEBPACK_IMPORTED_MODULE_1__["default"])(arr, i) || Object(_nonIterableRest__WEBPACK_IMPORTED_MODULE_2__["default"])();
}

/***/ }),

/***/ "./node_modules/locutus/php/strings/number_format.js":
/*!***********************************************************!*\
  !*** ./node_modules/locutus/php/strings/number_format.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function number_format(number, decimals, decPoint, thousandsSep) {
  // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/number_format/
  // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: davook
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Theriault (https://github.com/Theriault)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: Michael White (http://getsprink.com)
  // bugfixed by: Benjamin Lupton
  // bugfixed by: Allan Jensen (http://www.winternet.no)
  // bugfixed by: Howard Yeend
  // bugfixed by: Diogo Resende
  // bugfixed by: Rival
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  //  revised by: Luke Smith (http://lucassmith.name)
  //    input by: Kheang Hok Chin (http://www.distantia.ca/)
  //    input by: Jay Klehr
  //    input by: Amir Habibi (http://www.residence-mixte.com/)
  //    input by: Amirouche
  //   example 1: number_format(1234.56)
  //   returns 1: '1,235'
  //   example 2: number_format(1234.56, 2, ',', ' ')
  //   returns 2: '1 234,56'
  //   example 3: number_format(1234.5678, 2, '.', '')
  //   returns 3: '1234.57'
  //   example 4: number_format(67, 2, ',', '.')
  //   returns 4: '67,00'
  //   example 5: number_format(1000)
  //   returns 5: '1,000'
  //   example 6: number_format(67.311, 2)
  //   returns 6: '67.31'
  //   example 7: number_format(1000.55, 1)
  //   returns 7: '1,000.6'
  //   example 8: number_format(67000, 5, ',', '.')
  //   returns 8: '67.000,00000'
  //   example 9: number_format(0.9, 0)
  //   returns 9: '1'
  //  example 10: number_format('1.20', 2)
  //  returns 10: '1.20'
  //  example 11: number_format('1.20', 4)
  //  returns 11: '1.2000'
  //  example 12: number_format('1.2000', 3)
  //  returns 12: '1.200'
  //  example 13: number_format('1 000,50', 2, '.', ' ')
  //  returns 13: '100 050.00'
  //  example 14: number_format(1e-8, 8, '.', '')
  //  returns 14: '0.00000001'

  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number;
  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
  var sep = typeof thousandsSep === 'undefined' ? ',' : thousandsSep;
  var dec = typeof decPoint === 'undefined' ? '.' : decPoint;
  var s = '';

  var toFixedFix = function toFixedFix(n, prec) {
    if (('' + n).indexOf('e') === -1) {
      return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
    } else {
      var arr = ('' + n).split('e');
      var sig = '';
      if (+arr[1] + prec > 0) {
        sig = '+';
      }
      return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
    }
  };

  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }

  return s.join(dec);
};
//# sourceMappingURL=number_format.js.map

/***/ }),

/***/ "./packages/number/build-module/index.js":
/*!***********************************************!*\
  !*** ./packages/number/build-module/index.js ***!
  \***********************************************/
/*! exports provided: numberFormat, formatValue, calculateDelta */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "numberFormat", function() { return numberFormat; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "formatValue", function() { return formatValue; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "calculateDelta", function() { return calculateDelta; });
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);

/** @format */

/**
 * External dependencies
 */



var number_format = __webpack_require__(/*! locutus/php/strings/number_format */ "./node_modules/locutus/php/strings/number_format.js");
/**
 * Formats a number using site's current locale
 *
 * @see http://locutus.io/php/strings/number_format/
 * @param {Number|String} number number to format
 * @param {int|null} [precision=null] optional decimal precision
 * @returns {?String} A formatted string.
 */


function numberFormat(number) {
  var precision = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

  if ('number' !== typeof number) {
    number = parseFloat(number);
  }

  if (isNaN(number)) {
    return '';
  }

  var decimalSeparator = Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(wcSettings, ['currency', 'decimal_separator'], '.');
  var thousandSeparator = Object(lodash__WEBPACK_IMPORTED_MODULE_1__["get"])(wcSettings, ['currency', 'thousand_separator'], ',');
  precision = parseInt(precision);

  if (isNaN(precision)) {
    var _number$toString$spli = number.toString().split('.'),
        _number$toString$spli2 = Object(_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_number$toString$spli, 2),
        decimals = _number$toString$spli2[1];

    precision = decimals ? decimals.length : 0;
  }

  return number_format(number, precision, decimalSeparator, thousandSeparator);
}
/**
 * Formats a number string based on type of `average` or `number`.
 *
 * @param {String} type of number to format, average or number
 * @param {int} value to format.
 * @returns {?String} A formatted string.
 */

function formatValue(type, value) {
  if (!Object(lodash__WEBPACK_IMPORTED_MODULE_1__["isFinite"])(value)) {
    return null;
  }

  switch (type) {
    case 'average':
      return Math.round(value);

    case 'number':
      return numberFormat(value);
  }
}
/**
 * Calculates the delta/percentage change between two numbers.
 *
 * @param {int} primaryValue the value to calculate change for.
 * @param {int} secondaryValue the baseline which to calculdate the change against.
 * @returns {?int} Percent change between the primaryValue from the secondaryValue.
 */

function calculateDelta(primaryValue, secondaryValue) {
  if (!Object(lodash__WEBPACK_IMPORTED_MODULE_1__["isFinite"])(primaryValue) || !Object(lodash__WEBPACK_IMPORTED_MODULE_1__["isFinite"])(secondaryValue)) {
    return null;
  }

  if (secondaryValue === 0) {
    return 0;
  }

  return Math.round((primaryValue - secondaryValue) / secondaryValue * 100);
}

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["lodash"]; }());

/***/ })

/******/ });
//# sourceMappingURL=index.js.map