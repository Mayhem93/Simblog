/*
* Kendo UI Web v2012.1.322 (http://kendoui.com)
* Copyright 2012 Telerik AD. All rights reserved.
*
* Kendo UI Web commercial licenses may be obtained at http://kendoui.com/web-license
* If you do not own a commercial license, this file shall be governed by the
* GNU General Public License (GPL) version 3.
* For GPL requirements, please review: http://www.gnu.org/copyleft/gpl.html
*/
;(function($, undefined) {
    var kendo = window.kendo,
        Widget = kendo.ui.Widget,
        INVALIDMSG = "k-invalid-msg",
        INVALIDINPUT = "k-invalid",
        emailRegExp = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i,
        urlRegExp = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
        INPUTSELECTOR = ":input:not(:button,[type=submit],[type=reset])",
        NUMBERINPUTSELECTOR = "[type=number],[type=range]",
        BLUR = "blur",
        NAME = "name",
        FORM = "form",
        NOVALIDATE = "novalidate",
        proxy = $.proxy,
        patternMatcher = function(value, pattern) {
            if (typeof pattern === "string") {
                pattern = new RegExp('^(?:' + pattern + ')$');
            }
            return pattern.test(value);
        },
        matcher = function(input, selector, pattern) {
            var value = input.val();

            if (input.filter(selector).length && value !== "") {
                return patternMatcher(value, pattern);
            }
            return true;
        },
        hasAttribute = function(input, name) {
            if (input.length)  {
                return input[0].attributes[name] !== undefined;
            }
            return false;
        },
        nameSpecialCharRegExp = /(\[|\]|\$|\.|\:|\+)/g;

    /**
     *  @name kendo.ui.Validator.Description
     *
     *  @section
     *  <p>
     *     Validator offers an easy way to do client-side form validation.
     *     Built around the HTML5 form validation attributes it supports variety of built-in validation rules, but also provides a convenient way for setting custom rules handling.
     *  </p>
     *  @exampleTitle <b>Validator</b> initialization to validate input elements inside a container
     *  @example
     *  <div id="myform">
     *   <input type="text" name="firstName" required />
     *   <input type="text" name="lastName" required />
     *   <button id="save" type="button">Save</button>
     *  </div>
     *
     *  <script>
     *   $(document).ready(function(){
     *       var validatable = $("#myform").kendoValidator().data("kendoValidator");
     *       $("#save").click(function() {
     *          if (validatable.validate()) {
     *              save();
     *          }
     *       });
     *   });
     *   </script>
     *  @section <h4>Validation Rules</h4>
     *
     *  @exampleTitle <strong>required</strong>- element should have a value
     *  @example
     *  <input type="text" name="firstName" required />
     *
     *  @exampleTitle <strong>pattern</strong>- constrains the value to match a specific regular expression
     *  @example
     *  <input type="text" name="twitter" pattern="https?://(?:www\.)?twitter\.com/.+i" />
     *
     *  @exampleTitle <strong>max/min</strong>- constrain the minimum and/or maximum numeric values that can be entered
     *  @example
     *  <input type="number" name="age" min="1" max="42" />
     *
     *  @exampleTitle <strong>step</strong>- when used in combination with the min and max attributes, constrains the granularity of values that can be entered
     *  @example
     *  <input type="number" name="age" min="1" max="100" step="2" />
     *
     *  @exampleTitle <strong>url</strong>- constrain the value to being a valid URL
     *  @example
     *  <input type="url" name="url" />
     *
     *  @exampleTitle <strong>email</strong>- constrain the value to being a valid email
     *  @example
     *  <input type="email" name="email" />
     *
     *  @section
     *  <p>Beside the built-in validation rules, KendoUI Validator also provides a convenient way for setting custom rules through its rules configuration option. </p>
     *
     *  @exampleTitle
     *  @example
     *  $("#myform").kendoValidator({
     *      rules: {
     *        custom: function(input) {
     *          // Only Tom will be a valid value for FirstName input
     *          return input.is("[name=firstname]") && input.val() === "Tom";
     *        }
     *      }
     * });
     *
     *  @section <h4>Validation Messages</h4>
     *  <p>There are several ways to control the messages which appears if validation fails:</p>
     *
     *  @exampleTitle Set the validation messages for all input elements, through configuration options
     *  @example
     *   $("#myform").kendoValidator({
     *      rules: {
     *          custom: function(input) {
     *                  //...
     *          }
     *      },
     *      messages: {
     *        // defines message for the 'custom' validation rule
     *        custom: "Please enter valid value for my custom rule",
     *        // overrides the built-in message for required rule
     *        required: "My custom required message",
     *        // overrides the built-in email rule message with a custom function which return the actual message
     *        email: function(input) {
     *          return getMessage(input);
     *        }
     *     }
     *  });
     *  @exampleTitle Use the title and validationMessage attributes to set per input element messages
     *  @example
     *     <input type="tel" pattern="\d{10}" validationMessage="Plase enter a ten digit phone number" />
     *
     *  @section <h4>Triggering validation</h4>
     *  <p>In order to trigger the element(s) validation, <strong>validate</strong> method should be used. It will return either <em>true</em> if validation succeeded or <em>false</em> in case of a failure. </p>
     *  <p>
     *  Note that if a HTML form element is set as validation container, the form submits will be automatically prevented if validation fails.
     *  </p>
     *  @section <h4>Initialize Kendo Validator with specific tooltip position</h4>
     *
     *  <p>
     *      Ideally Kendo Validator places its tooltips besides the validated input. However, if the input is later enhanced to a ComboBox, AutoComplete or other Kendo Widget, placing the
     *      tooltip beside the input may cover important information or break the widget rendering. In this case, you can specify where exactly do you want the tooltip to be placed by
     *      adding a span with data-for attribute set to the validated input ID and a class .k-invalid-msg. Check the example below:
     *  </p>
     *
     *  @exampleTitle <b>Validator</b> initialization with specific tooltip placement (the tooltip will remain outside of the AutoComplete widget after enhancement)
     *  @example
     *  <div id="myform">
     *      <input type="text" id="name" name="name" required />
     *      <span class="k-invalid-msg" data-for="name"></span>
     *  </div>
     *
     *  <script>
     *      $("#name").kendoAutoComplete({
     *                     dataSource: data,
     *                     separator: ", "
     *                 });
     *
     *      $("#myform").kendoValidator();
     *  </script>
     */
    var Validator = Widget.extend(/** @lends kendo.ui.Validator.prototype */{ /**
         * @constructs
         * @extends kendo.ui.Widget
         * @param {DomElement} element DOM element
         * @param {Object} options Configuration options.
         * @option {Object} [rules] Set of validation rules. Those rules will extend the built-in ones.
         * _example
         * $("#myform").kendoValidator({
         *      rules: {
         *          custom: function(input) {
         *              return input.is("[name=firstname]") && input.val() === "Tom"; // Only Tom will be a valid value for FirstName input
         *          }
         *      }
         * });
         * @option {Object} [messages] Set of messages (either strings or functions) which will be shown when given validation rule fails.
         *  By setting already existing key the appropriate built-in message will be overridden.
         * _example
         * $("#myform").kendoValidator({
         *      rules: {
         *          custom: function(input) {
         *             //...
         *          }
         *      },
         *      messages: {
         *          custom: "Please enter valid value for my custom rule",// defines message for the 'custom' validation rule
         *          required: "My custom required message", // overrides the built-in message for required rule
         *          email: function(input) { // overrides the built-in email rule message with a custom function which return the actual message
         *              return getMessage(input);
         *          }
         *      }
         * });
         */
        init: function(element, options) {
            var that = this;

            Widget.fn.init.call(that, element, options);

            that._errorTemplate = kendo.template(that.options.errorTemplate);

            if (that.element.is(FORM)) {
                that.element.attr(NOVALIDATE, NOVALIDATE);
            }

            that._errors = {};
            that._attachEvents();
        },

        options: {
            name: "Validator",
            errorTemplate: '<span class="k-widget k-tooltip k-tooltip-validation">' +
                '<span class="k-icon k-warning"> </span> ${message}</span>',
            messages: {
                required: "{0} is required",
                pattern: "{0} is not valid",
                min: "{0} should be greater than or equal to {1}",
                max: "{0} should be smaller than or equal to {1}",
                step: "{0} is not valid",
                email: "{0} is not valid email",
                url: "{0} is not valid URL",
                date: "{0} is not valid date"
            },
            rules: {
                required: function(input) {
                    var checkbox = input.filter("[type=checkbox]").length && input.attr("checked") !== "checked",
                        value = input.val();

                    return !(hasAttribute(input, "required") && (value === "" || !value  || checkbox));

                },
                pattern: function(input) {
                    if (input.filter("[type=text],[type=email],[type=url],[type=tel],[type=search]").filter("[pattern]").length && input.val() !== "") {
                        return patternMatcher(input.val(), input.attr("pattern"));
                    }
                    return true;
                },
                min: function(input) {
                    if (input.filter(NUMBERINPUTSELECTOR + ",[" + kendo.attr("type") + "=number]").filter("[min]").length && input.val() !== "") {
                        var min = parseFloat(input.attr("min")) || 0,
                            val = parseFloat(input.val());

                        return min <= val;
                    }
                    return true;
                },
                max: function(input) {
                    if (input.filter(NUMBERINPUTSELECTOR + ",[" + kendo.attr("type") + "=number]").filter("[max]").length && input.val() !== "") {
                        var max = parseFloat(input.attr("max")) || 0,
                            val = parseFloat(input.val());

                        return max >= val;
                    }
                    return true;
                },
                step: function(input) {
                    if (input.filter(NUMBERINPUTSELECTOR + ",[" + kendo.attr("type") + "=number]").filter("[step]").length && input.val() !== "") {
                        var min = parseFloat(input.attr("min")) || 0,
                            step = parseFloat(input.attr("step")) || 0,
                            val = parseFloat(input.val());

                        return (((val-min)*10)%(step*10)) / 100 === 0;
                    }
                    return true;
                },
                email: function(input) {
                    return matcher(input, "[type=email],[" + kendo.attr("type") + "=email]", emailRegExp);
                },
                url: function(input) {
                    return matcher(input, "[type=url],[" + kendo.attr("type") + "=url]", urlRegExp);
                },
                date: function(input) {
                    if (input.filter("[type^=date],[" + kendo.attr("type") + "=date]").length && input.val() !== "") {
                        return kendo.parseDate(input.val(), input.attr(kendo.attr("format"))) !== null;
                    }
                    return true;
                }
            },
            validateOnBlur: true
        },

        _submit: function(e) {
            if (!this.validate()) {
                e.stopPropagation();
                e.stopImmediatePropagation();
                e.preventDefault();
                return false;
            }
            return true;
        },

        _attachEvents: function() {
            var that = this;

            if (that.element.is(FORM)) {
                that.element.submit(proxy(that._submit, that));
            }

            if (that.options.validateOnBlur) {
                if (!that.element.is(INPUTSELECTOR)) {
                    that.element.delegate(INPUTSELECTOR, BLUR, function() {
                        that.validateInput($(this));
                    });
                } else {
                    that.element.bind(BLUR, function() {
                        that.validateInput(that.element);
                    });
                }
            }
        },

        /**
         * Validates the input element(s) against the declared validation rules.
         * @returns {Boolean} If all rules are passed successfully.
         * @example
         * // get a reference to the validatable form
         * var validatable = $("#myform").kendoValidator().data("kendoValidator");
         * // check validation on save button click
         * $("#save").click(function() {
         *     if (validatable.validate()) {
         *         save();
         *     }
         * });
         */
        validate: function() {
            var that = this,
                inputs,
                idx,
                invalid = false,
                length;

            that._errors = {};

            if (!that.element.is(INPUTSELECTOR)) {
                inputs = that.element.find(INPUTSELECTOR);

                for (idx = 0, length = inputs.length; idx < length; idx++) {
                    if (!that.validateInput(inputs.eq(idx))) {
                        invalid = true;
                    }
                }
                return !invalid;
            }
            return that.validateInput(that.element);
        },

        /**
         * Validates the input element against the declared validation rules.
         * @param {DomElement} input Input element to be validated.
         * @returns {Boolean} If all rules are passed successfully.
         */
        validateInput: function(input) {
            input = $(input);

            var that = this,
                template = that._errorTemplate,
                result = that._checkValidity(input),
                valid = result.valid,
                className = "." + INVALIDMSG,
                fieldName = (input.attr(NAME) || "").replace(nameSpecialCharRegExp, "\\$1"),
                DATAFOR = kendo.attr("for"),
                lbl = that.element.find(className + "[" + DATAFOR +"=" + fieldName + "]").add(input.next(className)).hide(),
                messageText;

            if (!valid) {
                messageText = that._extractMessage(input, result.key);
                that._errors[fieldName] = messageText;

                var messageLabel = $(template({ message: messageText })).addClass(INVALIDMSG).attr(DATAFOR, fieldName || "");
                if (!lbl.replaceWith(messageLabel).length) {
                    messageLabel.insertAfter(input)
                }
                messageLabel.show();
            }

            input.toggleClass(INVALIDINPUT, !valid);

            return valid;
        },

        _extractMessage: function(input, ruleKey) {
            var that = this,
                customMessage = that.options.messages[ruleKey],
                fieldName = input.attr(NAME);

            customMessage = $.isFunction(customMessage) ? customMessage(input) : customMessage;

            return kendo.format(input.attr(kendo.attr(ruleKey + "-msg")) || input.attr("validationMessage") || input.attr("title") || customMessage || "", fieldName, input.attr(ruleKey));
        },

        _checkValidity: function(input) {
            var rules = this.options.rules,
                rule;

            for (rule in rules) {
                if (!rules[rule](input)) {
                    return { valid: false, key: rule };
                }
            }

            return { valid: true };
        },

        /**
         * Get the error messages if any.
         * @returns {Array} Messages for the failed validation rules.
         * @example
         * // get a reference to the validatable form
         * var validatable = $("#myform").kendoValidator().data("kendoValidator");
         * $("#save").click(function() {
         *     if (validatable.validate() === false) {
         *         // get the errors and write them out to the "errors" html container
         *         var errors = validatable.errors();
         *         $(errors).each(function() {
         *             $("#errors").html(this);
         *         });
         *     }
         * });
         */
        errors: function() {
            var results = [],
                errors = this._errors,
                error;

            for (error in errors) {
                results.push(errors[error]);
            }
            return results;
        }
    });

    kendo.ui.plugin(Validator);
})(jQuery);
