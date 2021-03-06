/*
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @fileoverview ResponseItem containing information about a specific response
 * from the server.
 */


/**
 * @class
 * Represents a response that was generated
 * by processing a data request item on the server.
 *
 * @name opensocial.ResponseItem
 */


/**
 * Represents a response that was generated by processing a data request item
 * on the server.
 *
 * @private
 * @constructor
 */
opensocial.ResponseItem = function() {};


/**
 * Returns true if there was an error in fetching this data from the server.
 *
 * @return {Boolean} True if there was an error; otherwise, false
 */
opensocial.ResponseItem.prototype.hadError = function() {};


/**
 * @static
 * @class
 *
 * Error codes that a response item can return.
 *
 * @name opensocial.ResponseItem.Error
 */
opensocial.ResponseItem.Error = {
  /**
   * This container does not support the request that was made.
   * This field may be used interchangeably with the string 'notImplemented'.
   * @member opensocial.ResponseItem.Error
   */
  NOT_IMPLEMENTED : 'notImplemented',

  /**
   * The gadget does not have access to the requested data.
   * To get access, use
   * <a href="opensocial.html#requestPermission">
   * opensocial.requestPermission()</a>.
   * This field may be used interchangeably with the string 'unauthorized'.
   * @member opensocial.ResponseItem.Error
   */
  UNAUTHORIZED : 'unauthorized',

  /**
   * The gadget can never have access to the requested data.
   * This field may be used interchangeably with the string 'forbidden'.
   * @member opensocial.ResponseItem.Error
   */
  FORBIDDEN : 'forbidden',

   /**
   * The request was invalid. Example: 'max' was -1.
   * This field may be used interchangeably with the string 'badRequest'.
   * @member opensocial.ResponseItem.Error
   */
  BAD_REQUEST : 'badRequest',

  /**
   * The request encountered an unexpected condition that
   * prevented it from fulfilling the request.
   * This field may be used interchangeably with the string 'internalError'.
   * @member opensocial.ResponseItem.Error
   */
  INTERNAL_ERROR : 'internalError',

  /**
   * The gadget exceeded a quota on the request. Example quotas include a
   * max number of calls per day, calls per user per day, calls within a
   * certain time period and so forth.
   * This field may be used interchangeably with the string 'limitExceeded'.
   * @member opensocial.ResponseItem.Error
   */
  LIMIT_EXCEEDED : 'limitExceeded'
};


/**
 * If the request had an error, returns the error code.
 * The error code can be container-specific
 * or one of the values defined by
 * <a href="opensocial.ResponseItem.Error.html"><code>Error</code></a>.
 *
 * @return {String} The error code, or null if no error occurred
 */
opensocial.ResponseItem.prototype.getErrorCode = function() {};


/**
 * If the request had an error, returns the error message.
 *
 * @return {String} A human-readable description of the error that occurred;
 *    can be null, even if an error occurred
 */
opensocial.ResponseItem.prototype.getErrorMessage = function() {};


/**
 * Returns the original data request item.
 *
 * @return {Object} The request item used to fetch this data
 *    response
 */
opensocial.ResponseItem.prototype.getOriginalDataRequest = function() {};


/**
 * Gets the response data.
 *
 * @return {Object} The requested value calculated by the server; the type of
 *    this value is defined by the type of request that was made
 */
opensocial.ResponseItem.prototype.getData = function() {};
