/*
* Kendo UI Web v2012.1.322 (http://kendoui.com)
* Copyright 2012 Telerik AD. All rights reserved.
*
* Kendo UI Web commercial licenses may be obtained at http://kendoui.com/web-license
* If you do not own a commercial license, this file shall be governed by the
* GNU General Public License (GPL) version 3.
* For GPL requirements, please review: http://www.gnu.org/copyleft/gpl.html
*/
(function ($, undefined) {
    /**
     * @name kendo.ui.AutoComplete.Description
     *
     * @section
     * <p>
     *  The <b>AutoComplete</b> provides suggestions depending on the typed
     *  text. It also allows multiple value entries. The suggestions shown by
     *  the <b>AutoComplete</b> can come from a local Array or from a remote
     *  data service.
     * </p>
     * <h3>Getting Started</h3>
     *
     * @exampleTitle Create a HTML input element
     * @example
     * <input id="autoComplete" />
     *
     * @exampleTitle Initialize the AutoComplete using a jQuery selector
     * @example
     * $(document).ready(function() {
     *  $("#autoComplete").kendoAutoComplete(["Item1", "Item2"]);
     * });
     *
     * @section <h3>AutoComplete Suggestions</h3>
     * <p>
     *  There are two primary ways to provide the <b>AutoComplete</b>
     *  suggestions:
     * </p>
     * <ol>
     *  <li>From a local array</li>
     *  <li>From a remote data service</li>
     * </ol>
     * <p>
     *  Locally defined values are best for small, fixed sets of suggestions.
     *  Remote suggestions should be used for larger data sets. When used
     *  with the <a href="../datasource/index.html">DataSource</a> component,
     *  filtering large remote data services can be pushed to the server as
     *  well, maximizing client-side performance.
     * </p>
     * <h3>Local Suggestions</h3>
     * <p>
     *  To configure and provide <b>AutoComplete</b> suggestions locally, you
     *  can either pass an array directly to its constructor or you can set
     *  the dataSource property to an local array.
     * </p>
     *
     * @exampleTitle Directly initialize suggestions in constructor
     * @example
     * $("#autoComplete").kendoAutoComplete(["Item1", "Item2", "Item3"]);
     *
     * @exampleTitle Using dataSource property to bind to local Array
     * @example
     * var data = ["Item1", "Item2", "Item3"];
     * $("#autoComplete").kendoAutoComplete({
     *    dataSource: data
     * });
     *
     * @section
     * <h3>Remote Suggestions</h3>
     * <p>
     *  The easiest way to bind an <b>AutoComplete</b> to remote
     *  suggestions is to use the
     *  <a href="../datasource/index.html">DataSource</a> component; an
     *  abstraction for local and remote data. The <b>DataSource</b>
     *  component can be used to serve data from a variety of data services,
     *  such as
     *  <a href="http://en.wikipedia.org/wiki/XML">XML</a>,
     *  <a href="http://en.wikipedia.org/wiki/JSON">JSON</a>, and
     *  <a href="http://en.wikipedia.org/wiki/JSONP">JSONP</a>.
     * </p>
     *
     * @exampleTitle Using the Kendo UI Web DataSource component to bind to
     * remote suggestions with OData
     * @example
     * $(document).ready(function(){
     *  $("#autoComplete").kendoAutoComplete({
     *   minLength: 3,
     *   dataTextField: "Name", // JSON property name to use
     *   dataSource: new kendo.data.DataSource({
     *    type: "odata", // specifies data protocol
     *    pageSize: 10, // limits result set
     *    transport: {
     *     read: "http://odata.netflix.com/Catalog/Titles"
     *    }
     *   })
     *  })
     * });
     *
     * @exampleTitle Using the Kendo UI Web DataSource to bind to JSONP
     * suggestions
     * @example
     * $(document).ready(function(){
     *  $("#autoComplete").kendoAutoComplete({
     *   minLength:6,
     *   dataTextField:"title",
     *   filter: "contains",
     *   dataSource: new kendo.data.DataSource({
     *    transport: {
     *     read: {
     *      url: "http://api.geonames.org/wikipediaSearchJSON",
     *      data: {
     *       q: function(){
     *        return $("#autoComplete").data("kendoAutoComplete").value();
     *       },
     *       maxRows: 10,
     *       username: "demo"
     *      }
     *     }
     *    },
     *    schema: {
     *     data:"geonames"
     *    }
     *   }),
     *   change: function(){
     *    this.dataSource.read();
     *   }
     *  })
     * });
     *
     * @section
     * <h3>Accessing an Existing AutoComplete</h3>
     * <p>
     *  You can reference an existing <b>AutoComplete</b> instance via
     *  <a href="http://api.jquery.com/jQuery.data/">jQuery.data()</a>.
     *  Once a reference has been established, you can use the API to control
     *  its behavior.
     * </p>
     *
     * @exampleTitle Accessing an existing AutoComplete instance
     * @example
     * var autoComplete = $("#autoComplete").data("kendoAutoComplete");
     *
     */
    var kendo = window.kendo,
        support = kendo.support,
        placeholderSupported = support.placeholder,
        ui = kendo.ui,
        keys = kendo.keys,
        DataSource = kendo.data.DataSource,
        List = ui.List,
        CHANGE = "change",
        DEFAULT = "k-state-default",
        DISABLED = "disabled",
        FOCUSED = "k-state-focused",
        SELECTED = "k-state-selected",
        STATEDISABLED = "k-state-disabled",
        HOVER = "k-state-hover",
        HOVEREVENTS = "mouseenter mouseleave",
        caretPosition = List.caret,
        selectText = List.selectText,
        proxy = $.proxy;

    function indexOfWordAtCaret(caret, text, separator) {
        return separator ? text.substring(0, caret).split(separator).length - 1 : 0;
    }

    function wordAtCaret(caret, text, separator) {
        return text.split(separator)[indexOfWordAtCaret(caret, text, separator)];
    }

    function replaceWordAtCaret(caret, text, word, separator) {
        var words = text.split(separator);

        words.splice(indexOfWordAtCaret(caret, text, separator), 1, word);

        if (separator && words[words.length - 1] !== "") {
            words.push("");
        }

        return words.join(separator);
    }

    function moveCaretAtEnd(element) {
        var length = element.value.length;

        selectText(element, length, length);
    }

    var AutoComplete = List.extend/** @lends kendo.ui.AutoComplete.prototype */({
        /**
        * @constructs
        * @extends kendo.ui.List
        * @param {DomElement} element DOM element
        * @param {Object} options Configuration options.
        * @option {Object | kendo.data.DataSource } [dataSource] The set of data that the AutoComplete will be bound to.
        *  Either a local JavaScript object, or an instance of the Kendo UI DataSource.
        * _exampleTitle Bind to local array
        * _example
        * var items = [ { Name: "Item 1" }, { Name: "Item 2"} ];
        * $("#autoComplete").kendoAutoComplete({ dataSource: items });
        * _exampleTitle Bind to a remote URL
        * _example
        * $("#autocomplete").kendoAutoComplete({
        *     dataSource: new kendo.data.DataSource({
        *         transport: {
        *             read: "Items/GetData" // url to server method which returns data
        *         }
        *     });
        * });
        * @option {Boolean} [enable] <true> Controls whether the AutoComplete should be initially enabled.
        * _example
        * // disable the autocomplete when it is created (enabled by default)
        * $("#autoComplete").kendoAutoComplete({
        *     enable: false
        * });
        * @option {Boolean} [suggest] <false> Controls whether the AutoComplete should automatically auto-type the rest of text.
        * _example
        * // turn on auto-typing (off by default)
        * $("#autoComplete").kendoAutoComplete({
        *     suggest: true
        * });
        * @option {Number} [delay] <200> Specifies the delay in ms after which the AutoComplete will start filtering the dataSource.
        * _example
        * // set the delay to 500 milliseconds
        * $("#autoComplete").kendoAutoComplete({
        *     delay: 500
        * });
        * @option {Number} [minLength] <1> Specifies the minimum number of characters that should be typed before the AutoComplete queries
        * the dataSource.
        * _example
        * // wait until the user types 3 characters before querying the server
        * $("#autoComplete").kendoAutoComplete({
        *     minLength: 3
        * });
        * @option {String} [dataTextField] <null> Sets the field of the data item that provides the text content of the list items.
        * _example
        * var items = [ { ID: 1, Name: "Item 1" }, { ID: 2, Name: "Item 2"} ];
        * $("#autoComplete").kendoAutoComplete({
        *     dataSource: items,
        *     dataTextField: "Name"
        * });
        * @option {String} [filter] <"startswith"> Defines the type of filtration. This value is handled by the remote data source.
        * _example
        * // send a filter value of 'contains' to the server
        * $("#autoComplete").kendoAutoComplete({
        *     filter: 'contains'
        * });
        * @option {Number} [height] <200> Sets the height of the drop-down list in pixels.
        * _example
        * // set the height of the drop-down list that appears when the autocomplete is activated to 500px
        * $("#autoComplete").kendoAutoComplete({
        *     height: 500
        * });
        * @option {String} [separator] <""> Sets the separator for completion. Empty by default, allowing for only one completion.
        * _example
        * // set completion separator to ,
        * $("#autoComplete").kendoAutoComplete({
        *     separator: ", "
        * });
        * @option {Function} [template] Template to be used for rendering the items in the list.
        * _example
        *  //template
        * &lt;script id="template" type="text/x-kendo-tmpl"&gt;
        *       # if (data.BoxArt.SmallUrl) { #
        *           &lt;img src="${ data.BoxArt.SmallUrl }" alt="${ data.Name }" /&gt;Title:${ data.Name }, Year: ${ data.Name }
        *       # } else { #
        *           &lt;img alt="${ data.Name }" /&gt;Title:${ data.Name }, Year: ${ data.Name }
        *       # } #
        *  &lt;/script&gt;
        *
        *  //autocomplete initialization
        *  &lt;script&gt;
        *      $("#autocomplete").kendoAutoComplete({
        *          dataSource: dataSource,
        *          dataTextField: "Name",
        *          dataValueField: "Id",
        *          template: kendo.template($("#template").html())
        *      });
        *  &lt;/script&gt;
        * @option {Object} [animation] <> Animations to be used for opening/closing the popup. Setting to false will turn of the animation.
        * @option {Object} [animation.open] <> Animation to be used for opening of the popup.
        * _example
        *  //autocomplete initialization
        *  &lt;script&gt;
        *      $("#autocomplete").kendoAutoComplete({
        *          dataSource: dataSource,
        *          animation: {
        *             open: {
        *                 effects: "fadeIn",
        *                 duration: 300,
        *                 show: true
        *             }
        *          }
        *      });
        *  &lt;/script&gt;
        * @option {Object} [animation.close] <> Animation to be used for closing of the popup.
        * _example
        *  //autocomplete initialization
        *  &lt;script&gt;
        *      $("#autocomplete").kendoAutoComplete({
        *          dataSource: dataSource,
        *          animation: {
        *             close: {
        *                 effects: "fadeOut",
        *                 duration: 300,
        *                 hide: true
        *                 show: false
        *             }
        *          }
        *      });
        *  &lt;/script&gt;
        *  @option {String} [placeholder] <""> A string that appears in the textbox when it has no value.
        *  _example
        *  //autocomplete initialization
        *  <script>
        *      $("#autocomplete").kendoAutoComplete({
        *          dataSource: dataSource,
        *          placeholder: "Enter value..."
        *      });
        *  </script>
        *  _example
        *  <input id="autocomplete" placeholder="Enter value..." />
        *
        *  //combobox initialization
        *  <script>
        *      $("#autocomplete").kendoAutoComplete({
        *          dataSource: dataSource
        *      });
        *  </script>
        */
        init: function (element, options) {
            var that = this, wrapper, placeholder;

            options = $.isArray(options) ? { dataSource: options} : options;

            List.fn.init.call(that, element, options);

            element = that.element;
            options = that.options;

            options.placeholder = options.placeholder || element.attr("placeholder");
            if (placeholderSupported) {
                element.attr("placeholder", options.placeholder);
            }

            that._wrapper();

            that._accessors();

            that._dataSource();

            element[0].type = "text";
            wrapper = that.wrapper;

            element
                .attr("autocomplete", "off")
                .addClass("k-input")
                .bind({
                    keydown: proxy(that._keydown, that),
                    paste: proxy(that._search, that),
                    focus: function () {
                        that._prev = that.value();
                        that._placeholder(false);
                        wrapper.addClass(FOCUSED);
                        clearTimeout(that._bluring);
                    },
                    blur: function () {
                        that._change();
                        that._placeholder();
                        wrapper.removeClass(FOCUSED);
                    }
                });

            that._enable();

            that._popup();

            that._old = that.value();

            that._placeholder();

            kendo.notify(that);
        },

        options: {
            name: "AutoComplete",
            suggest: false,
            template: "",
            dataTextField: "",
            minLength: 1,
            delay: 200,
            height: 200,
            filter: "startswith",
            highlightFirst: false,
            separator: null,
            placeholder: "",
            animation: {}
        },

        _dataSource: function() {
            var that = this;

            if (that.dataSource && that._refreshHandler) {
                that.dataSource.unbind(CHANGE, that._refreshHandler);
            } else {
                that._refreshHandler = proxy(that.refresh, that);
            }

            that.dataSource = DataSource.create(that.options.dataSource)
                .bind(CHANGE, that._refreshHandler);
        },

        setDataSource: function(dataSource) {
            this.options.dataSource = dataSource;

            this._dataSource();
        },

        events: [
        /**
        * Fires when the drop-down list is opened
        * @name kendo.ui.AutoComplete#open
        * @event
        * @param {Event} e
        * @example
        * $("#autoComplete").kendoAutoComplete({
        *     open: function(e) {
        *         // handle event
        *     }
        * });
        * @example
        * var autoComplete = $("#autoComplete").data("kendoAutoComplete");
        * autoComplete.bind("open", function(e) {
        *     // handle event
        * });
        */
        "open",
        /**
        * Fires when the drop-down list is closed
        * @name kendo.ui.AutoComplete#close
        * @event
        * @param {Event} e
        * @example
        * $("#autoComplete").kendoAutoComplete({
        *     close: function(e) {
        *         // handle event
        *     }
        * });
        * @exampleTitle To set after initialization
        * @example
        * var autoComplete = $("#autoComplete").data("kendoAutoComplete");
        * autoComplete.bind("close", function(e) {
        *     // handle event
        * });
            */
        "close",
            /**
            * Fires when the value has been changed.
            * @name kendo.ui.AutoComplete#change
            * @event
            * @param {Event} e
            * $("#autoComplete").kendoAutoComplete({
            *     change: function(e) {
            *         // handle event
            *     }
            * });
            * @exampleTitle To set after initialization
            * @example
            * var autoComplete = $("#autoComplete").data("kendoAutoComplete");
            * $("#autoComplete").data("kendoAutoComplete").bind("change", function(e) {
            *     // handle event
            * });
                */
               CHANGE,
               /**
               *
               * Triggered when a Li element is selected.
               *
               * @name kendo.ui.AutoComplete#select
               * @event
               *
               * @param {Event} e
               *
               * @param {jQuery} e.item
               * The selected item chosen by a user.
               *
               * @exampleTitle Attach select event handler during initialization; detach via unbind()
               * @example
               * // event handler for select
               * var onSelect = function(e) {
               *     // access the selected item via e.item (jQuery object)
               * };
               *
               * // attach select event handler during initialization
               * var autocomplete = $("#autocomplete").kendoAutoComplete({
               *     select: onSelect
               * });
               *
               * // detach select event handler via unbind()
               * autocomplete.data("kendoAutoComplete").unbind("select", onSelect);
               *
               * @exampleTitle Attach select event handler via bind(); detach via unbind()
               * @example
               * // event handler for select
               * var onSelect = function(e) {
               *     // access the selected item via e.item (jQuery object)
               * };
               *
               * // attach select event handler via bind()
               * $("#autocomplete").data("kendoAutoComplete").bind("select", onSelect);
               *
               * // detach select event handler via unbind()
               * $("#autocomplete").data("kendoAutoComplete").unbind("select", onSelect);
               *
               */
               "select",
               "dataBinding",
               "dataBound"
            ],
        setOptions: function(options) {
            List.fn.setOptions.call(this, options);

            this._template();
            this._accessors();
        },

        /**
        * Returns the raw data record at the specified index
        * @name kendo.ui.AutoComplete#dataItem
        * @function
        * @param {Number} index The zero-based index of the data record
        * @returns {Object} The raw data record. Returns <i>undefined</i> if no data.
        * @example
        * var autocomplete = $("#autocomplete").data("kendoAutoComplete");
        *
        * // get the dataItem corresponding to the passed index.
        * var dataItem = autocomplete.dataItem(1);
        */

        /**
        * Enable/Disable the autocomplete widget.
        * @param {Boolean} enable The argument, which defines whether to enable/disable the autocomplete.
        * @example
        * // get a reference to the autocomplete widget
        * var autocomplete = $("autocomplete").data("kendoAutoComplete");
        *
        * // disables the autocomplete
        * autocomplete.enable(false);
        *
        * // enables the autocomplete
        * autocomplete.enable(true);
        */
        enable: function(enable) {
            var that = this,
                element = that.element,
                wrapper = that.wrapper;

            if (enable === false) {
                wrapper
                    .removeClass(DEFAULT)
                    .addClass(STATEDISABLED)
                    .unbind(HOVEREVENTS);

                element.attr(DISABLED, DISABLED);
            } else {
                wrapper
                    .removeClass(STATEDISABLED)
                    .addClass(DEFAULT)
                    .bind(HOVEREVENTS, that._toggleHover);

                element
                    .removeAttr(DISABLED);
            }
        },

        /**
        * Closes the drop-down list.
        * @example
        * // get a reference to the autocomplete widget
        * var autocomplete = $("autocomplete").data("kendoAutoComplete");
        *
        * autocomplete.close();
        */
        close: function () {
            var that = this;
            that._current = null;
            that.popup.close();
        },

        /**
        * Re-render the items in drop-down list.
        * @name kendo.ui.AutoComplete#refresh
        * @function
        * @example
        * // get a referenence to the Kendo UI AutoComplete
        * var autocomplete = $("autocomplete").data("kendoAutoComplete");
        * // re-render the items in drop-down list.
        * autocomplete.refresh();
        */
        refresh: function () {
            var that = this,
            ul = that.ul[0],
            popup = that.popup,
            options = that.options,
            suggest = options.suggest,
            data = that.dataSource.view(),
            length = data.length;

            that.trigger("dataBinding");

            ul.innerHTML = kendo.render(that.template, data);

            that._height(length);

            if (length) {
                if (suggest || options.highlightFirst) {
                    that.current($(ul.firstChild));
                }

                if (suggest) {
                    that.suggest(that._current);
                }
            }

            if (that._open) {
                that._open = false;
                popup[length ? "open" : "close"]();
            }

            if (that._touchScroller) {
                that._touchScroller.reset();
            }

            that._makeUnselectable();

            that.trigger("dataBound");
        },

        /**
        * Selects drop-down list item and sets the text of the autocomplete.
        * @param {jQuery Object} li The LI element.
        * @example
        * // get a reference to the autocomplete widget
        * var autocomplete = $("autocomplete").data("kendoAutoComplete");
        *
        * // selects by jQuery object
        * autocomplete.select(autocomplete.ul.children().eq(0));
        */
        select: function (li) {
            this._select(li);
        },

        /**
        * Filters dataSource using the provided parameter and rebinds drop-down list.
        * @param {string} word The filter value.
        * @example
        * // get a reference to the autocomplete widget
        * var autocomplete = $("autocomplete").data("kendoAutoComplete");
        *
        * // Searches for item which has "Inception" in the name.
        * autocomplete.search("Inception");
        */
        search: function (word) {
            var that = this,
            word = word || that.value(),
            options = that.options,
            separator = options.separator,
            length,
            caret,
            index;

            that._current = null;

            clearTimeout(that._typing);

            if (separator) {
                word = wordAtCaret(caretPosition(that.element[0]), word, separator);
            }

            length = word.length;

            if (!length) {
                that.popup.close();
            } else if (length >= that.options.minLength) {
                that._open = true;
                that.dataSource.filter({ field: options.dataTextField, operator: options.filter, value: word });
            }
        },

        /**
        * Forces a suggestion onto the text of the AutoComplete.
        * @param {string} value Characters to force a suggestion.
        * @example
        * // note that this suggest is not the same as the configuration method
        * // suggest which enables/disables auto suggesting for the AutoComplete
        * //
        * // get a referenence to the Kendo UI AutoComplete
        * var autoComplete = $("#autoComplete").data("kendoAutoComplete");
        *
        * // force a suggestion to the item with the name "Inception"
        * autoComplete.suggest("Inception");
        */
        suggest: function (word) {
            var that = this,
                key = that._last,
                value = that.value(),
                element = that.element[0],
                caret = caretPosition(element),
                separator = that.options.separator,
                words = value.split(separator),
                wordIndex = indexOfWordAtCaret(caret, value, separator),
                selectionEnd = caret,
                idx;

            if (key == keys.BACKSPACE || key == keys.DELETE) {
                that._last = undefined;
                return;
            }

            word = word || "";

            if (typeof word !== "string") {
                idx = word.index();

                if (idx > -1) {
                    word = that._text(that.dataSource.view()[idx]);
                } else {
                    word = "";
                }
            }

            if (caret <= 0) {
                caret = value.toLowerCase().indexOf(word.toLowerCase()) + 1;
            }

            idx = value.substring(0, caret).lastIndexOf(separator);
            idx = idx > -1 ? caret - (idx + separator.length) : caret;
            value = words[wordIndex].substring(0, idx);

            if (word) {
                idx = word.toLowerCase().indexOf(value.toLowerCase());
                if (idx > -1) {
                    word = word.substring(idx + value.length);

                    selectionEnd = caret + word.length;

                    value += word;
                }

                if (separator && words[words.length - 1] !== "") {
                    words.push("");
                }

            }

            words[wordIndex] = value;

            that.value(words.join(separator || ""));

            selectText(element, caret, selectionEnd);
        },

        /**
        * Gets/Sets the value of the autocomplete.
        * @param {String} value The value to set.
        * @returns {String} The value of the autocomplete.
        * @example
        * // get a reference to the autocomplete widget
        * var autocomplete = $("autocomplete").data("kendoAutoComplete");
        *
        * // get the text of the autocomplete.
        * var value = autocomplete.value();
        */
        value: function (value) {
            var that = this,
                element = that.element[0];

            if (value !== undefined) {
                element.value = value;
            } else {
                return element.value;
            }
        },

        _accept: function (li) {
            var that = this;

            that._focus(li);
            moveCaretAtEnd(that.element[0]);
        },

        _keydown: function (e) {
            var that = this,
                ul = that.ul[0],
                key = e.keyCode,
                current = that._current,
                visible = that.popup.visible();

            that._last = key;

            if (key === keys.DOWN) {
                if (visible) {
                    that._move(current ? current.next() : $(ul.firstChild));
                }
                e.preventDefault();
            } else if (key === keys.UP) {
                if (visible) {
                    that._move(current ? current.prev() : $(ul.lastChild));
                }
                e.preventDefault();
            } else if (key === keys.ENTER || key === keys.TAB) {

                if (that.popup.visible()) {
                    e.preventDefault();
                }

                that._accept(current);
            } else if (key === keys.ESC) {
                that.close();
            } else {
                that._search();
            }
        },

        _move: function (li) {
            var that = this;

            li = li[0] ? li : null;

            that.current(li);

            if (that.options.suggest) {
                that.suggest(li);
            }
        },

        _placeholder: function(show) {
            if (placeholderSupported) {
                return;
            }

            var that = this,
                element = that.element,
                placeholder = that.options.placeholder,
                value;

            if (placeholder) {
                value = element.val();

                if (show === undefined) {
                    show = !value;
                }

                if (!show) {
                    if (value !== placeholder) {
                        placeholder = value;
                    } else {
                        placeholder = "";
                    }
                }

                element.toggleClass("k-readonly", show)
                       .val(placeholder);
            }
        },

        _search: function () {
            var that = this;
            clearTimeout(that._typing);

            that._typing = setTimeout(function () {
                if (that._prev !== that.value()) {
                    that._prev = that.value();
                    that.search();
                }
            }, that.options.delay);
        },

        _select: function (li) {
            var that = this,
                separator = that.options.separator,
                data = that.dataSource.view(),
                text,
                idx;

            li = $(li);

            if (li[0] && !li.hasClass(SELECTED)) {
                idx = List.inArray(li[0], that.ul[0]);

                if (idx > -1) {
                    data = data[idx];
                    text = that._text(data);

                    if (separator) {
                        text = replaceWordAtCaret(caretPosition(that.element[0]), that.value(), text, separator);
                    }

                    that.value(text);
                    that.current(li.addClass(SELECTED));
                }
            }
        },

        _toggleHover: function(e) {
            if (!support.touch) {
                $(e.currentTarget).toggleClass(HOVER, e.type === "mouseenter");
            }
        },

        _wrapper: function () {
            var that = this,
                element = that.element,
                DOMelement = element[0],
                TABINDEX = "tabIndex",
                wrapper;

            wrapper = element.parent();

            if (!wrapper.is("span.k-widget")) {
                wrapper = element.wrap("<span />").parent();
            }

            wrapper[0].style.cssText = DOMelement.style.cssText;
            element.css({
                width: "100%",
                height: DOMelement.style.height
            });

            that._focused = that.element;
            that.wrapper = wrapper
                              .addClass("k-widget k-autocomplete k-header")
                              .addClass(DOMelement.className);
        }
    });

    ui.plugin(AutoComplete);
})(jQuery);
