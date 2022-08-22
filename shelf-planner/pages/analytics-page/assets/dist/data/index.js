this["wc"] = this["wc"] || {}; this["wc"]["data"] =
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./packages/data/build-module/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _arrayWithoutHoles; });
function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) {
    for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) {
      arr2[i] = arr[i];
    }

    return arr2;
  }
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/defineProperty.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _defineProperty; });
function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArray.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _iterableToArray; });
function _iterableToArray(iter) {
  if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _nonIterableSpread; });
function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance");
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _toConsumableArray; });
/* harmony import */ var _arrayWithoutHoles__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithoutHoles */ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js");
/* harmony import */ var _iterableToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArray */ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js");
/* harmony import */ var _nonIterableSpread__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./nonIterableSpread */ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js");



function _toConsumableArray(arr) {
  return Object(_arrayWithoutHoles__WEBPACK_IMPORTED_MODULE_0__["default"])(arr) || Object(_iterableToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(arr) || Object(_nonIterableSpread__WEBPACK_IMPORTED_MODULE_2__["default"])();
}

/***/ }),

/***/ "./node_modules/@wordpress/data-controls/build-module/index.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/data-controls/build-module/index.js ***!
  \*********************************************************************/
/*! exports provided: apiFetch, select, dispatch, controls */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "apiFetch", function() { return apiFetch; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "select", function() { return select; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "dispatch", function() { return dispatch; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "controls", function() { return controls; });
/* harmony import */ var _babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/toConsumableArray */ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);


/**
 * WordPress dependencies
 */


/**
 * Dispatches a control action for triggering an api fetch call.
 *
 * @param {Object} request Arguments for the fetch request.
 *
 * @example
 * ```js
 * import { apiFetch } from '@wordpress/data-controls';
 *
 * // Action generator using apiFetch
 * export function* myAction() {
 * 	const path = '/v2/my-api/items';
 * 	const items = yield apiFetch( { path } );
 * 	// do something with the items.
 * }
 * ```
 *
 * @return {Object} The control descriptor.
 */

var apiFetch = function apiFetch(request) {
  return {
    type: 'API_FETCH',
    request: request
  };
};
/**
 * Dispatches a control action for triggering a registry select.
 *
 * Note: when this control action is handled, it automatically considers
 * selectors that may have a resolver. It will await and return the resolved
 * value when the selector has not been resolved yet.
 *
 * @param {string} storeKey      The key for the store the selector belongs to
 * @param {string} selectorName  The name of the selector
 * @param {Array}  args          Arguments for the select.
 *
 * @example
 * ```js
 * import { select } from '@wordpress/data-controls';
 *
 * // Action generator using select
 * export function* myAction() {
 * 	const isSidebarOpened = yield select( 'core/edit-post', 'isEditorSideBarOpened' );
 * 	// do stuff with the result from the select.
 * }
 * ```
 *
 * @return {Object} The control descriptor.
 */

function select(storeKey, selectorName) {
  for (var _len = arguments.length, args = new Array(_len > 2 ? _len - 2 : 0), _key = 2; _key < _len; _key++) {
    args[_key - 2] = arguments[_key];
  }

  return {
    type: 'SELECT',
    storeKey: storeKey,
    selectorName: selectorName,
    args: args
  };
}
/**
 * Dispatches a control action for triggering a registry dispatch.
 *
 * @param {string} storeKey    The key for the store the action belongs to
 * @param {string} actionName  The name of the action to dispatch
 * @param {Array}  args        Arguments for the dispatch action.
 *
 * @example
 * ```js
 * import { dispatch } from '@wordpress/data-controls';
 *
 * // Action generator using dispatch
 * export function* myAction() {
 * 	yield dispatch( 'core/edit-post', 'togglePublishSidebar' );
 * 	// do some other things.
 * }
 * ```
 *
 * @return {Object}  The control descriptor.
 */

function dispatch(storeKey, actionName) {
  for (var _len2 = arguments.length, args = new Array(_len2 > 2 ? _len2 - 2 : 0), _key2 = 2; _key2 < _len2; _key2++) {
    args[_key2 - 2] = arguments[_key2];
  }

  return {
    type: 'DISPATCH',
    storeKey: storeKey,
    actionName: actionName,
    args: args
  };
}
/**
 * The default export is what you use to register the controls with your custom
 * store.
 *
 * @example
 * ```js
 * // WordPress dependencies
 * import { controls } from '@wordpress/data-controls';
 * import { registerStore } from '@wordpress/data';
 *
 * // Internal dependencies
 * import reducer from './reducer';
 * import * as selectors from './selectors';
 * import * as actions from './actions';
 * import * as resolvers from './resolvers';
 *
 * registerStore( 'my-custom-store', {
 * 	reducer,
 * 	controls,
 * 	actions,
 * 	selectors,
 * 	resolvers,
 * } );
 * ```
 *
 * @return {Object} An object for registering the default controls with the
 *                  store.
 */

var controls = {
  API_FETCH: function API_FETCH(_ref) {
    var request = _ref.request;
    return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_1___default()(request);
  },
  SELECT: Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["createRegistryControl"])(function (registry) {
    return function (_ref2) {
      var _registry;

      var storeKey = _ref2.storeKey,
          selectorName = _ref2.selectorName,
          args = _ref2.args;
      return (_registry = registry[registry.select(storeKey)[selectorName].hasResolver ? '__experimentalResolveSelect' : 'select'](storeKey))[selectorName].apply(_registry, Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(args));
    };
  }),
  DISPATCH: Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["createRegistryControl"])(function (registry) {
    return function (_ref3) {
      var _registry$dispatch;

      var storeKey = _ref3.storeKey,
          actionName = _ref3.actionName,
          args = _ref3.args;
      return (_registry$dispatch = registry.dispatch(storeKey))[actionName].apply(_registry$dispatch, Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(args));
    };
  })
};
//# sourceMappingURL=index.js.map

/***/ }),

/***/ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!***********************************************************************************************************!*\
  !*** ./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \***********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _arrayLikeToArray; });
function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

/***/ }),

/***/ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js":
/*!************************************************************************************************************!*\
  !*** ./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _arrayWithoutHoles; });
/* harmony import */ var _arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray */ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return Object(_arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(arr);
}

/***/ }),

/***/ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/iterableToArray.js":
/*!**********************************************************************************************************!*\
  !*** ./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/iterableToArray.js ***!
  \**********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _iterableToArray; });
function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter);
}

/***/ }),

/***/ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js":
/*!************************************************************************************************************!*\
  !*** ./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _nonIterableSpread; });
function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

/***/ }),

/***/ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/toConsumableArray.js":
/*!************************************************************************************************************!*\
  !*** ./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/toConsumableArray.js ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _toConsumableArray; });
/* harmony import */ var _arrayWithoutHoles__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithoutHoles */ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js");
/* harmony import */ var _iterableToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArray */ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/iterableToArray.js");
/* harmony import */ var _unsupportedIterableToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray */ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableSpread__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableSpread */ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js");




function _toConsumableArray(arr) {
  return Object(_arrayWithoutHoles__WEBPACK_IMPORTED_MODULE_0__["default"])(arr) || Object(_iterableToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(arr) || Object(_unsupportedIterableToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(arr) || Object(_nonIterableSpread__WEBPACK_IMPORTED_MODULE_3__["default"])();
}

/***/ }),

/***/ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*********************************************************************************************************************!*\
  !*** ./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*********************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _unsupportedIterableToArray; });
/* harmony import */ var _arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray */ "./node_modules/@wordpress/data-controls/node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return Object(_arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(n);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return Object(_arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(o, minLen);
}

/***/ }),

/***/ "./packages/data/build-module/constants.js":
/*!*************************************************!*\
  !*** ./packages/data/build-module/constants.js ***!
  \*************************************************/
/*! exports provided: JETPACK_NAMESPACE, NAMESPACE, WC_ADMIN_NAMESPACE, WCS_NAMESPACE, MAX_PER_PAGE, SECOND, MINUTE, HOUR, DEFAULT_REQUIREMENT, DEFAULT_ACTIONABLE_STATUSES, QUERY_DEFAULTS */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "JETPACK_NAMESPACE", function() { return JETPACK_NAMESPACE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "NAMESPACE", function() { return NAMESPACE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "WC_ADMIN_NAMESPACE", function() { return WC_ADMIN_NAMESPACE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "WCS_NAMESPACE", function() { return WCS_NAMESPACE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "MAX_PER_PAGE", function() { return MAX_PER_PAGE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SECOND", function() { return SECOND; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "MINUTE", function() { return MINUTE; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "HOUR", function() { return HOUR; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DEFAULT_REQUIREMENT", function() { return DEFAULT_REQUIREMENT; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "DEFAULT_ACTIONABLE_STATUSES", function() { return DEFAULT_ACTIONABLE_STATUSES; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "QUERY_DEFAULTS", function() { return QUERY_DEFAULTS; });
var JETPACK_NAMESPACE = '/jetpack/v4';
var NAMESPACE = '/wc-analytics';
var WC_ADMIN_NAMESPACE = '/wc-admin';
var WCS_NAMESPACE = '/wc/v1'; // WCS endpoints like Stripe are not avaiable on later /wc versions
// WordPress & WooCommerce both set a hard limit of 100 for the per_page parameter

var MAX_PER_PAGE = 100;
var SECOND = 1000;
var MINUTE = 60 * SECOND;
var HOUR = 60 * MINUTE;
var DEFAULT_REQUIREMENT = {
  timeout: 1 * MINUTE,
  freshness: 30 * MINUTE
};
var DEFAULT_ACTIONABLE_STATUSES = ['processing', 'on-hold'];
var QUERY_DEFAULTS = {
  pageSize: 25,
  period: 'month',
  compare: 'previous_year'
};

/***/ }),

/***/ "./packages/data/build-module/index.js":
/*!*********************************************!*\
  !*** ./packages/data/build-module/index.js ***!
  \*********************************************/
/*! exports provided: SETTINGS_STORE_NAME, withSettingsHydration, useSettings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./settings */ "./packages/data/build-module/settings/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "SETTINGS_STORE_NAME", function() { return _settings__WEBPACK_IMPORTED_MODULE_0__["SETTINGS_STORE_NAME"]; });

/* harmony import */ var _settings_with_settings_hydration__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./settings/with-settings-hydration */ "./packages/data/build-module/settings/with-settings-hydration.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "withSettingsHydration", function() { return _settings_with_settings_hydration__WEBPACK_IMPORTED_MODULE_1__["withSettingsHydration"]; });

/* harmony import */ var _settings_use_settings__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./settings/use-settings */ "./packages/data/build-module/settings/use-settings.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "useSettings", function() { return _settings_use_settings__WEBPACK_IMPORTED_MODULE_2__["useSettings"]; });





/***/ }),

/***/ "./packages/data/build-module/settings/action-types.js":
/*!*************************************************************!*\
  !*** ./packages/data/build-module/settings/action-types.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var TYPES = {
  UPDATE_SETTINGS_FOR_GROUP: 'UPDATE_SETTINGS_FOR_GROUP',
  UPDATE_ERROR_FOR_GROUP: 'UPDATE_ERROR_FOR_GROUP',
  CLEAR_SETTINGS: 'CLEAR_SETTINGS',
  SET_IS_REQUESTING: 'SET_IS_REQUESTING',
  CLEAR_IS_DIRTY: 'CLEAR_IS_DIRTY'
};
/* harmony default export */ __webpack_exports__["default"] = (TYPES);

/***/ }),

/***/ "./packages/data/build-module/settings/actions.js":
/*!********************************************************!*\
  !*** ./packages/data/build-module/settings/actions.js ***!
  \********************************************************/
/*! exports provided: updateSettingsForGroup, updateErrorForGroup, setIsRequesting, clearIsDirty, updateAndPersistSettingsForGroup, persistSettingsForGroup, clearSettings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "updateSettingsForGroup", function() { return updateSettingsForGroup; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "updateErrorForGroup", function() { return updateErrorForGroup; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "setIsRequesting", function() { return setIsRequesting; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "clearIsDirty", function() { return clearIsDirty; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "updateAndPersistSettingsForGroup", function() { return updateAndPersistSettingsForGroup; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "persistSettingsForGroup", function() { return persistSettingsForGroup; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "clearSettings", function() { return clearSettings; });
/* harmony import */ var _wordpress_data_controls__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data-controls */ "./node_modules/@wordpress/data-controls/build-module/index.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../constants */ "./packages/data/build-module/constants.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./constants */ "./packages/data/build-module/settings/constants.js");
/* harmony import */ var _action_types__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./action-types */ "./packages/data/build-module/settings/action-types.js");
var _marked = /*#__PURE__*/regeneratorRuntime.mark(updateAndPersistSettingsForGroup),
    _marked2 = /*#__PURE__*/regeneratorRuntime.mark(persistSettingsForGroup);
/**
 * External Dependencies
 */




/**
 * Internal Dependencies
 */




function updateSettingsForGroup(group, data) {
  var time = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : new Date();
  return {
    type: _action_types__WEBPACK_IMPORTED_MODULE_4__["default"].UPDATE_SETTINGS_FOR_GROUP,
    group: group,
    data: data,
    time: time
  };
}
function updateErrorForGroup(group, data, error) {
  var time = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : new Date();
  return {
    type: _action_types__WEBPACK_IMPORTED_MODULE_4__["default"].UPDATE_ERROR_FOR_GROUP,
    group: group,
    data: data,
    error: error,
    time: time
  };
}
function setIsRequesting(group, isRequesting) {
  return {
    type: _action_types__WEBPACK_IMPORTED_MODULE_4__["default"].SET_IS_REQUESTING,
    group: group,
    isRequesting: isRequesting
  };
}
function clearIsDirty(group) {
  return {
    type: _action_types__WEBPACK_IMPORTED_MODULE_4__["default"].CLEAR_IS_DIRTY,
    group: group
  };
} // allows updating and persisting immediately in one action.

function updateAndPersistSettingsForGroup(group, data) {
  return regeneratorRuntime.wrap(function updateAndPersistSettingsForGroup$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          _context.next = 2;
          return updateSettingsForGroup(group, data);

        case 2:
          return _context.delegateYield(persistSettingsForGroup(group), "t0", 3);

        case 3:
        case "end":
          return _context.stop();
      }
    }
  }, _marked);
} // this would replace setSettingsForGroup

function persistSettingsForGroup(group) {
  var dirtyKeys, dirtyData, url, update, results;
  return regeneratorRuntime.wrap(function persistSettingsForGroup$(_context2) {
    while (1) {
      switch (_context2.prev = _context2.next) {
        case 0:
          _context2.next = 2;
          return setIsRequesting(group, true);

        case 2:
          _context2.next = 4;
          return Object(_wordpress_data_controls__WEBPACK_IMPORTED_MODULE_0__["select"])(_constants__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"], 'getDirtyKeys', group);

        case 4:
          dirtyKeys = _context2.sent;

          if (!(dirtyKeys.length === 0)) {
            _context2.next = 9;
            break;
          }

          _context2.next = 8;
          return setIsRequesting(group, false);

        case 8:
          return _context2.abrupt("return");

        case 9:
          _context2.next = 11;
          return Object(_wordpress_data_controls__WEBPACK_IMPORTED_MODULE_0__["select"])(_constants__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"], 'getSettingsForGroup', group, dirtyKeys);

        case 11:
          dirtyData = _context2.sent;
          url = "".concat(_constants__WEBPACK_IMPORTED_MODULE_2__["NAMESPACE"], "/settings/").concat(group, "/batch");
          update = dirtyKeys.reduce(function (updates, key) {
            var u = Object.keys(dirtyData[key]).map(function (k) {
              return {
                id: k,
                value: dirtyData[key][k]
              };
            });
            return Object(lodash__WEBPACK_IMPORTED_MODULE_1__["concat"])(updates, u);
          }, []);
          _context2.prev = 14;
          _context2.next = 17;
          return Object(_wordpress_data_controls__WEBPACK_IMPORTED_MODULE_0__["apiFetch"])({
            path: url,
            method: 'POST',
            data: {
              update: update
            }
          });

        case 17:
          results = _context2.sent;

          if (results) {
            _context2.next = 20;
            break;
          }

          throw new Error('settings did not update');

        case 20:
          _context2.next = 22;
          return clearIsDirty(group);

        case 22:
          _context2.next = 28;
          break;

        case 24:
          _context2.prev = 24;
          _context2.t0 = _context2["catch"](14);
          _context2.next = 28;
          return updateErrorForGroup(group, null, _context2.t0);

        case 28:
          _context2.next = 30;
          return setIsRequesting(group, false);

        case 30:
        case "end":
          return _context2.stop();
      }
    }
  }, _marked2, null, [[14, 24]]);
}
function clearSettings() {
  return {
    type: _action_types__WEBPACK_IMPORTED_MODULE_4__["default"].CLEAR_SETTINGS
  };
}

/***/ }),

/***/ "./packages/data/build-module/settings/constants.js":
/*!**********************************************************!*\
  !*** ./packages/data/build-module/settings/constants.js ***!
  \**********************************************************/
/*! exports provided: STORE_NAME */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "STORE_NAME", function() { return STORE_NAME; });
var STORE_NAME = 'wc/admin/settings';

/***/ }),

/***/ "./packages/data/build-module/settings/index.js":
/*!******************************************************!*\
  !*** ./packages/data/build-module/settings/index.js ***!
  \******************************************************/
/*! exports provided: SETTINGS_STORE_NAME */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SETTINGS_STORE_NAME", function() { return SETTINGS_STORE_NAME; });
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data_controls__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data-controls */ "./node_modules/@wordpress/data-controls/build-module/index.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./constants */ "./packages/data/build-module/settings/constants.js");
/* harmony import */ var _selectors__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./selectors */ "./packages/data/build-module/settings/selectors.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./actions */ "./packages/data/build-module/settings/actions.js");
/* harmony import */ var _resolvers__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./resolvers */ "./packages/data/build-module/settings/resolvers.js");
/* harmony import */ var _reducer__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./reducer */ "./packages/data/build-module/settings/reducer.js");
/**
 * External dependencies
 */


/**
 * Internal dependencies
 */






Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__["registerStore"])(_constants__WEBPACK_IMPORTED_MODULE_2__["STORE_NAME"], {
  reducer: _reducer__WEBPACK_IMPORTED_MODULE_6__["default"],
  actions: _actions__WEBPACK_IMPORTED_MODULE_4__,
  controls: _wordpress_data_controls__WEBPACK_IMPORTED_MODULE_1__["controls"],
  selectors: _selectors__WEBPACK_IMPORTED_MODULE_3__,
  resolvers: _resolvers__WEBPACK_IMPORTED_MODULE_5__
});
var SETTINGS_STORE_NAME = _constants__WEBPACK_IMPORTED_MODULE_2__["STORE_NAME"];

/***/ }),

/***/ "./packages/data/build-module/settings/reducer.js":
/*!********************************************************!*\
  !*** ./packages/data/build-module/settings/reducer.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/toConsumableArray */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _action_types__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./action-types */ "./packages/data/build-module/settings/action-types.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils */ "./packages/data/build-module/utils.js");



function ownKeys(object, enumerableOnly) {
  var keys = Object.keys(object);

  if (Object.getOwnPropertySymbols) {
    var symbols = Object.getOwnPropertySymbols(object);
    if (enumerableOnly) symbols = symbols.filter(function (sym) {
      return Object.getOwnPropertyDescriptor(object, sym).enumerable;
    });
    keys.push.apply(keys, symbols);
  }

  return keys;
}

function _objectSpread(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};

    if (i % 2) {
      ownKeys(Object(source), true).forEach(function (key) {
        Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(target, key, source[key]);
      });
    } else if (Object.getOwnPropertyDescriptors) {
      Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
    } else {
      ownKeys(Object(source)).forEach(function (key) {
        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
      });
    }
  }

  return target;
}
/**
 * External dependencies
 */



/**
 * Internal dependencies
 */




var updateGroupDataInNewState = function updateGroupDataInNewState(newState, _ref) {
  var group = _ref.group,
      groupIds = _ref.groupIds,
      data = _ref.data,
      time = _ref.time,
      error = _ref.error;
  groupIds.forEach(function (id) {
    newState[Object(_utils__WEBPACK_IMPORTED_MODULE_4__["getResourceName"])(group, id)] = {
      data: data[id],
      lastReceived: time,
      error: error
    };
  });
  return newState;
};

var receiveSettings = function receiveSettings() {
  var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

  var _ref2 = arguments.length > 1 ? arguments[1] : undefined,
      type = _ref2.type,
      group = _ref2.group,
      data = _ref2.data,
      error = _ref2.error,
      time = _ref2.time,
      isRequesting = _ref2.isRequesting;

  var newState = {};

  switch (type) {
    case _action_types__WEBPACK_IMPORTED_MODULE_3__["default"].SET_IS_REQUESTING:
      state = _objectSpread({}, state, Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])({}, group, _objectSpread({}, state[group], {
        isRequesting: isRequesting
      })));
      break;

    case _action_types__WEBPACK_IMPORTED_MODULE_3__["default"].CLEAR_IS_DIRTY:
      state = _objectSpread({}, state, Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])({}, group, _objectSpread({}, state[group], {
        dirty: []
      })));
      break;

    case _action_types__WEBPACK_IMPORTED_MODULE_3__["default"].UPDATE_SETTINGS_FOR_GROUP:
    case _action_types__WEBPACK_IMPORTED_MODULE_3__["default"].UPDATE_ERROR_FOR_GROUP:
      var groupIds = data ? Object.keys(data) : [];

      if (data === null) {
        state = _objectSpread({}, state, Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])({}, group, {
          data: state[group] ? state[group].data : [],
          error: error,
          lastReceived: time
        }));
      } else {
        state = _objectSpread({}, state, Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])({}, group, {
          data: state[group] && state[group].data ? [].concat(Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(state[group].data), Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(groupIds)) : groupIds,
          error: error,
          lastReceived: time,
          isRequesting: false,
          dirty: state[group] && state[group].dirty ? Object(lodash__WEBPACK_IMPORTED_MODULE_2__["union"])(state[group].dirty, groupIds) : groupIds
        }), updateGroupDataInNewState(newState, {
          group: group,
          groupIds: groupIds,
          data: data,
          time: time,
          error: error
        }));
      }

      break;

    case _action_types__WEBPACK_IMPORTED_MODULE_3__["default"].CLEAR_SETTINGS:
      state = {};
  }

  return state;
};

/* harmony default export */ __webpack_exports__["default"] = (receiveSettings);

/***/ }),

/***/ "./packages/data/build-module/settings/resolvers.js":
/*!**********************************************************!*\
  !*** ./packages/data/build-module/settings/resolvers.js ***!
  \**********************************************************/
/*! exports provided: getSettings, getSettingsForGroup */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getSettings", function() { return getSettings; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getSettingsForGroup", function() { return getSettingsForGroup; });
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _wordpress_data_controls__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data-controls */ "./node_modules/@wordpress/data-controls/build-module/index.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../constants */ "./packages/data/build-module/constants.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./constants */ "./packages/data/build-module/settings/constants.js");
/* harmony import */ var _actions__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./actions */ "./packages/data/build-module/settings/actions.js");


var _marked = /*#__PURE__*/regeneratorRuntime.mark(getSettings),
    _marked2 = /*#__PURE__*/regeneratorRuntime.mark(getSettingsForGroup);
/**
 * External Dependencies
 */



/**
 * Internal dependencies
 */





function settingsToSettingsResource(settings) {
  return settings.reduce(function (resource, setting) {
    resource[setting.id] = setting.value;
    return resource;
  }, {});
}

function getSettings(group) {
  var url, results, resource;
  return regeneratorRuntime.wrap(function getSettings$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          _context.next = 2;
          return Object(_wordpress_data_controls__WEBPACK_IMPORTED_MODULE_1__["dispatch"])(_constants__WEBPACK_IMPORTED_MODULE_3__["STORE_NAME"], 'setIsRequesting', group, true);

        case 2:
          _context.prev = 2;
          url = _constants__WEBPACK_IMPORTED_MODULE_2__["NAMESPACE"] + '/settings/' + group;
          _context.next = 6;
          return Object(_wordpress_data_controls__WEBPACK_IMPORTED_MODULE_1__["apiFetch"])({
            path: url,
            method: 'GET'
          });

        case 6:
          results = _context.sent;
          resource = settingsToSettingsResource(results);
          return _context.abrupt("return", Object(_actions__WEBPACK_IMPORTED_MODULE_4__["updateSettingsForGroup"])(group, Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, group, resource)));

        case 11:
          _context.prev = 11;
          _context.t0 = _context["catch"](2);
          return _context.abrupt("return", Object(_actions__WEBPACK_IMPORTED_MODULE_4__["updateErrorForGroup"])(group, null, _context.t0.message));

        case 14:
        case "end":
          return _context.stop();
      }
    }
  }, _marked, null, [[2, 11]]);
}
function getSettingsForGroup(group) {
  return regeneratorRuntime.wrap(function getSettingsForGroup$(_context2) {
    while (1) {
      switch (_context2.prev = _context2.next) {
        case 0:
          return _context2.abrupt("return", getSettings(group));

        case 1:
        case "end":
          return _context2.stop();
      }
    }
  }, _marked2);
}

/***/ }),

/***/ "./packages/data/build-module/settings/selectors.js":
/*!**********************************************************!*\
  !*** ./packages/data/build-module/settings/selectors.js ***!
  \**********************************************************/
/*! exports provided: getSettingsGroupNames, getSettings, getDirtyKeys, getIsDirty, getSettingsForGroup, isGetSettingsRequesting, getSetting, getLastSettingsErrorForGroup, getSettingsError */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getSettingsGroupNames", function() { return getSettingsGroupNames; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getSettings", function() { return getSettings; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getDirtyKeys", function() { return getDirtyKeys; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getIsDirty", function() { return getIsDirty; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getSettingsForGroup", function() { return getSettingsForGroup; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isGetSettingsRequesting", function() { return isGetSettingsRequesting; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getSetting", function() { return getSetting; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getLastSettingsErrorForGroup", function() { return getLastSettingsErrorForGroup; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getSettingsError", function() { return getSettingsError; });
/* harmony import */ var _babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/toConsumableArray */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils */ "./packages/data/build-module/utils.js");

/**
 * Internal dependencies
 */


var getSettingsGroupNames = function getSettingsGroupNames(state) {
  var groupNames = new Set(Object.keys(state).map(function (resourceName) {
    return Object(_utils__WEBPACK_IMPORTED_MODULE_1__["getResourcePrefix"])(resourceName);
  }));
  return Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(groupNames);
};
var getSettings = function getSettings(state, group) {
  var settings = {};
  var settingIds = state[group] && state[group].data || [];

  if (settingIds.length === 0) {
    return settings;
  }

  settingIds.forEach(function (id) {
    settings[id] = state[Object(_utils__WEBPACK_IMPORTED_MODULE_1__["getResourceName"])(group, id)].data;
  });
  return settings;
};
var getDirtyKeys = function getDirtyKeys(state, group) {
  return state[group].dirty || [];
};
var getIsDirty = function getIsDirty(state, group) {
  var keys = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];
  var dirtyMap = getDirtyKeys(state, group); // if empty array bail

  if (dirtyMap.length === 0) {
    return false;
  } // if at least one of the keys is in the dirty map then the state is dirty
  // meaning it hasn't been persisted.


  return keys.some(function (key) {
    return dirtyMap.includes(key);
  });
};
var getSettingsForGroup = function getSettingsForGroup(state, group, keys) {
  var allSettings = getSettings(state, group);
  return keys.reduce(function (accumulator, key) {
    accumulator[key] = allSettings[key] || {};
    return accumulator;
  }, {});
};
var isGetSettingsRequesting = function isGetSettingsRequesting(state, group) {
  return state[group] && Boolean(state[group].isRequesting);
};
/**
 * Retrieves a setting value from the setting store.
 *
 * @export
 * @param {Object}   state                        State param added by wp.data.
 * @param {string}   group                        The settings group.
 * @param {string}   name                         The identifier for the setting.
 * @param {*}    [fallback=false]             The value to use as a fallback
 *                                                if the setting is not in the
 *                                                state.
 * @param {Function} [filter=( val ) => val]  	  A callback for filtering the
 *                                                value before it's returned.
 *                                                Receives both the found value
 *                                                (if it exists for the key) and
 *                                                the provided fallback arg.
 *
 * @return {*}  The value present in the settings state for the given
 *                   name.
 */

function getSetting(state, group, name) {
  var fallback = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
  var filter = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : function (val) {
    return val;
  };
  var resourceName = Object(_utils__WEBPACK_IMPORTED_MODULE_1__["getResourceName"])(group, name);
  var value = state[resourceName] && state[resourceName].data || fallback;
  return filter(value, fallback);
}
var getLastSettingsErrorForGroup = function getLastSettingsErrorForGroup(state, group) {
  var settingsIds = state[group].data;

  if (settingsIds.length === 0) {
    return state[group].error;
  }

  return Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__["default"])(settingsIds).pop().error;
};
var getSettingsError = function getSettingsError(state, group, id) {
  if (!id) {
    return state[group] && state[group].error || false;
  }

  return state[Object(_utils__WEBPACK_IMPORTED_MODULE_1__["getResourceName"])(group, id)].error || false;
};

/***/ }),

/***/ "./packages/data/build-module/settings/use-settings.js":
/*!*************************************************************!*\
  !*** ./packages/data/build-module/settings/use-settings.js ***!
  \*************************************************************/
/*! exports provided: useSettings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useSettings", function() { return useSettings; });
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./constants */ "./packages/data/build-module/settings/constants.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);


function ownKeys(object, enumerableOnly) {
  var keys = Object.keys(object);

  if (Object.getOwnPropertySymbols) {
    var symbols = Object.getOwnPropertySymbols(object);
    if (enumerableOnly) symbols = symbols.filter(function (sym) {
      return Object.getOwnPropertyDescriptor(object, sym).enumerable;
    });
    keys.push.apply(keys, symbols);
  }

  return keys;
}

function _objectSpread(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};

    if (i % 2) {
      ownKeys(Object(source), true).forEach(function (key) {
        Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(target, key, source[key]);
      });
    } else if (Object.getOwnPropertyDescriptors) {
      Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
    } else {
      ownKeys(Object(source)).forEach(function (key) {
        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
      });
    }
  }

  return target;
}
/**
 * External dependencies
 */





var useSettings = function useSettings(group) {
  var settingsKeys = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];

  var _useSelect = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select) {
    var _select = select(_constants__WEBPACK_IMPORTED_MODULE_2__["STORE_NAME"]),
        getLastSettingsErrorForGroup = _select.getLastSettingsErrorForGroup,
        getSettingsForGroup = _select.getSettingsForGroup,
        getIsDirty = _select.getIsDirty,
        isGetSettingsRequesting = _select.isGetSettingsRequesting;

    return {
      requestedSettings: getSettingsForGroup(group, settingsKeys),
      settingsError: Boolean(getLastSettingsErrorForGroup(group)),
      isRequesting: isGetSettingsRequesting(group),
      isDirty: getIsDirty(group, settingsKeys)
    };
  }, [group, settingsKeys]),
      requestedSettings = _useSelect.requestedSettings,
      settingsError = _useSelect.settingsError,
      isRequesting = _useSelect.isRequesting,
      isDirty = _useSelect.isDirty;

  var _useDispatch = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useDispatch"])(_constants__WEBPACK_IMPORTED_MODULE_2__["STORE_NAME"]),
      persistSettingsForGroup = _useDispatch.persistSettingsForGroup,
      updateAndPersistSettingsForGroup = _useDispatch.updateAndPersistSettingsForGroup,
      updateSettingsForGroup = _useDispatch.updateSettingsForGroup;

  var updateSettings = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useCallback"])(function (name, data) {
    updateSettingsForGroup(group, Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, name, data));
  }, [group]);
  var persistSettings = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useCallback"])(function () {
    // this action would simply persist all settings marked as dirty in the
    // store state and then remove the dirty record in the isDirtyMap
    persistSettingsForGroup(group);
  }, [group]);
  var updateAndPersistSettings = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__["useCallback"])(function (name, data) {
    updateAndPersistSettingsForGroup(group, Object(_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, name, data));
  }, [group]);
  return _objectSpread({
    settingsError: settingsError,
    isRequesting: isRequesting,
    isDirty: isDirty
  }, requestedSettings, {
    persistSettings: persistSettings,
    updateAndPersistSettings: updateAndPersistSettings,
    updateSettings: updateSettings
  });
};

/***/ }),

/***/ "./packages/data/build-module/settings/with-settings-hydration.js":
/*!************************************************************************!*\
  !*** ./packages/data/build-module/settings/with-settings-hydration.js ***!
  \************************************************************************/
/*! exports provided: withSettingsHydration */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "withSettingsHydration", function() { return withSettingsHydration; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./constants */ "./packages/data/build-module/settings/constants.js");

/**
 * External dependencies
 */



/**
 * Internal dependencies
 */


var withSettingsHydration = function withSettingsHydration(group, settings) {
  return function (OriginalComponent) {
    return function (props) {
      var settingsRef = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useRef"])(settings);
      Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["useSelect"])(function (select, registry) {
        if (!settingsRef.current) {
          return;
        }

        var _select = select(_constants__WEBPACK_IMPORTED_MODULE_2__["STORE_NAME"]),
            isResolving = _select.isResolving,
            hasFinishedResolution = _select.hasFinishedResolution;

        var _registry$dispatch = registry.dispatch(_constants__WEBPACK_IMPORTED_MODULE_2__["STORE_NAME"]),
            startResolution = _registry$dispatch.startResolution,
            finishResolution = _registry$dispatch.finishResolution,
            updateSettingsForGroup = _registry$dispatch.updateSettingsForGroup,
            clearIsDirty = _registry$dispatch.clearIsDirty;

        if (!isResolving('getSettings', [group]) && !hasFinishedResolution('getSettings', [group])) {
          startResolution('getSettings', [group]);
          updateSettingsForGroup(group, settingsRef.current);
          clearIsDirty(group);
          finishResolution('getSettings', [group]);
        }
      }, []);
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(OriginalComponent, props);
    };
  };
};

/***/ }),

/***/ "./packages/data/build-module/utils.js":
/*!*********************************************!*\
  !*** ./packages/data/build-module/utils.js ***!
  \*********************************************/
/*! exports provided: getResourceName, getResourcePrefix, isResourcePrefix, getResourceIdentifier */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getResourceName", function() { return getResourceName; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getResourcePrefix", function() { return getResourcePrefix; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isResourcePrefix", function() { return isResourcePrefix; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getResourceIdentifier", function() { return getResourceIdentifier; });
function getResourceName(prefix, identifier) {
  var identifierString = JSON.stringify(identifier, Object.keys(identifier).sort());
  return "".concat(prefix, ":").concat(identifierString);
}
function getResourcePrefix(resourceName) {
  var hasPrefixIndex = resourceName.indexOf(':');
  return hasPrefixIndex < 0 ? resourceName : resourceName.substring(0, hasPrefixIndex);
}
function isResourcePrefix(resourceName, prefix) {
  var resourcePrefix = getResourcePrefix(resourceName);
  return resourcePrefix === prefix;
}
function getResourceIdentifier(resourceName) {
  var identifierString = resourceName.substring(resourceName.indexOf(':') + 1);
  return JSON.parse(identifierString);
}

/***/ }),

/***/ "@wordpress/api-fetch":
/*!*******************************************!*\
  !*** external {"this":["wp","apiFetch"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["apiFetch"]; }());

/***/ }),

/***/ "@wordpress/data":
/*!***************************************!*\
  !*** external {"this":["wp","data"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["data"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["element"]; }());

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