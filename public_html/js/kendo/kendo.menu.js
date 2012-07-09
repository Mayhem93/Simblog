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
     * @name kendo.ui.Menu.Description
     *
     * @section
     * <p>
     *  The <b>Menu</b> displays hierarchical data as a multi-level menu. It provides rich styling for unordered lists
     *  of items, and can be used for both navigation and executing JavaScript commands. Items can be defined and
     *  initialized from HTML, or the API can be used to add and remove items.
     * </p>
     * <h3>Getting Started</h3>
     *
     * @exampleTitle Create a HTML hierarchical list of items
     * @example
     * <ul id="menu">
     *     <li>Item 1
     *         <ul>
     *             <li>Item 1.1</li>
     *             <li>Item 1.2</li>
     *         </ul>
     *     </li>
     *     <li>Item 2</li>
     * </ul>
     *
     * @section
     * <p>
     *  Initialization of a <strong>Menu</strong> should occur after the DOM is fully loaded. It is recommended that
     *  initialization the <strong>Menu</strong> occur within a handler is provided to $(document).ready().
     * </p>
     *
     * @exampleTitle Initialize a Menu using a selector within $(document).ready()
     * @example
     * $(document).ready(function() {
     *     $("#menu").kendoMenu();
     * });
     *
     * @exampleTitle Initialize the Menu using JSON data object
     * @example
     * $(document).ready(function() {
     *  $("#menu").kendoMenu({
     *   dataSource: [
     *    {
     *     text: "Menu Item 1",
     *     items: [
     *      { text: "Sub Menu Item 1" },
     *      { text: "Sub Menu Item 2" }
     *     ]
     *    },
     *    {
     *     text: "Menu Item 2"
     *    }
     *   ]
     *  })
     * });
     *
     * @section
     * <h3>Customizing Menu Animations</h3>
     * <p>
     *  By default, the <b>Menu</b> uses a slide animation to expand and
     *  reveal sub-items as the mouse hovers. Animations can be easily
     *  customized using configuration properties, changing the animation
     *  style and delay. Menu items can also be configured to open on click
     *  instead of on hover.
     * </p>
     *
     * @exampleTitle Changing Menu animation and open behavior
     * @example
     * $("#menu").kendoMenu({
     *  animation: {
     *   open: { effects: "fadeIn" },
     *   hoverDelay: 500
     *  },
     *  openOnClick: true
     * });
     *
     * @section
     * <h3>Dynamically configuring Menu items</h3>
     * <p>
     *  The <b>Menu</b> API provides several methods for dynamically adding
     *  or removing Items. To add items, provide the new item as a JSON
     *  object along with a reference item that will be used to determine the
     *  placement in the hierarchy.
     * </p>
     * <p>
     *  A reference item is simply a target Menu Item HTML element that
     *  already exists in the Menu. Any valid jQuery selector can be used to
     *  obtain a reference to the target item. For examples, see the
     *  <a href="../menu/api.html" title="Menu API demos">Menu API demos</a>.
     *  Removing an item only requires a reference to the target element that
     *  should be removed.
     * </p>
     *
     * @exampleTitle Dynamically add a new root Menu item
     * @example
     * var menu = $("#menu").kendoMenu().data("kendoMenu");
     * menu.insertAfter(
     *  { text: "New Menu Item" },
     *  menu.element.children("li:last")
     * );
     *
     * @section
     * <h3>Accessing an Existing Menu</h3>
     * <p>
     *  You can reference an existing <b>Menu</b> instance via
     *  <a href="http://api.jquery.com/jQuery.data/">jQuery.data()</a>.
     *  Once a reference has been established, you can use the API to control
     *  its behavior.
     * </p>
     *
     * @exampleTitle Accessing an existing Menu instance
     * @example
     * var menu = $("#menu").data("kendoMenu");
     *
     */
    var kendo = window.kendo,
        ui = kendo.ui,
        touch = kendo.support.touch,
        extend = $.extend,
        proxy = $.proxy,
        each = $.each,
        template = kendo.template,
        Widget = ui.Widget,
        excludedNodesRegExp = /^(ul|a|div)$/i,
        IMG = "img",
        OPEN = "open",
        MENU = "k-menu",
        LINK = "k-link",
        LAST = "k-last",
        CLOSE = "close",
        CLICK = touch ? "touchend" : "click",
        TIMER = "timer",
        FIRST = "k-first",
        IMAGE = "k-image",
        EMPTY = ":empty",
        SELECT = "select",
        ZINDEX = "zIndex",
        MOUSEENTER = "mouseenter",
        MOUSELEAVE = "mouseleave",
        KENDOPOPUP = "kendoPopup",
        SLIDEINRIGHT = "slideIn:right",
        DEFAULTSTATE = "k-state-default",
        DISABLEDSTATE = "k-state-disabled",
        groupSelector = ".k-group",
        allItemsSelector = ".k-item",
        disabledSelector = ".k-item.k-state-disabled",
        itemSelector = ".k-item:not(.k-state-disabled)",
        linkSelector = ".k-item:not(.k-state-disabled) > .k-link",

        templates = {
            group: template(
                "<ul class='#= groupCssClass(group) #'#= groupAttributes(group) #>" +
                    "#= renderItems(data) #" +
                "</ul>"
            ),
            itemWrapper: template(
                "<#= tag(item) # class='#= textClass(item) #'#= textAttributes(item) #>" +
                    "#= image(item) ##= sprite(item) ##= text(item) #" +
                    "#= arrow(data) #" +
                "</#= tag(item) #>"
            ),
            item: template(
                "<li class='#= wrapperCssClass(group, item) #'>" +
                    "#= itemWrapper(data) #" +
                    "# if (item.items) { #" +
                    "#= subGroup({ items: item.items, menu: menu, group: { expanded: item.expanded } }) #" +
                    "# } #" +
                "</li>"
            ),
            image: template("<img class='k-image' alt='' src='#= imageUrl #' />"),
            arrow: template("<span class='#= arrowClass(item, group) #'></span>"),
            sprite: template("<span class='k-sprite #= spriteCssClass #'></span>"),
            empty: template("")
        },

        rendering = {
            /** @ignore */
            wrapperCssClass: function (group, item) {
                var result = "k-item",
                    index = item.index;

                if (item.enabled === false) {
                    result += " k-state-disabled";
                } else {
                    result += " k-state-default";
                }

                if (group.firstLevel && index == 0) {
                    result += " k-first"
                }

                if (index == group.length-1) {
                    result += " k-last";
                }

                return result;
            },
            /** @ignore */
            textClass: function(item) {
                return LINK;
            },
            /** @ignore */
            textAttributes: function(item) {
                return item.url ? " href='" + item.url + "'" : "";
            },
            /** @ignore */
            arrowClass: function(item, group) {
                var result = "k-icon";

                if (group.horizontal) {
                    result += " k-arrow-down";
                } else {
                    result += " k-arrow-right";
                }

                return result;
            },
            /** @ignore */
            text: function(item) {
                return item.encoded === false ? item.text : kendo.htmlEncode(item.text);
            },
            /** @ignore */
            tag: function(item) {
                return item.url ? "a" : "span";
            },
            /** @ignore */
            groupAttributes: function(group) {
                return group.expanded !== true ? " style='display:none'" : "";
            },
            /** @ignore */
            groupCssClass: function(group) {
                return "k-group";
            }
        };

    function getEffectDirection(direction, root) {
        direction = direction.split(" ")[!root+0] || direction;
        return direction.replace("top", "up").replace("bottom", "down");
    }

    function parseDirection(direction, root) {
        direction = direction.split(" ")[!root+0] || direction;
        var output = { origin: [ "bottom", "left" ], position: [ "top", "left" ] },
            horizontal = /left|right/.test(direction);

        if (horizontal) {
            output.origin = [ "top", direction ];
            output.position[1] = kendo.directions[direction].reverse;
        } else {
            output.origin[0] = direction;
            output.position[0] = kendo.directions[direction].reverse;
        }

        output.origin = output.origin.join(" ");
        output.position = output.position.join(" ");

        return output;
    }

    function contains(parent, child) {
        try {
            return $.contains(parent, child);
        } catch (e) {
            return false;
        }
    }

    function updateItemClasses (item) {
        item = $(item);

        item
            .children(IMG)
            .addClass(IMAGE);
        item
            .children("a")
            .addClass(LINK)
            .children(IMG)
            .addClass(IMAGE);
        item
            .filter(":not([disabled])")
            .addClass(DEFAULTSTATE);
        item
            .filter(".k-separator:empty")
            .append("&nbsp;");
        item
            .filter("li[disabled]")
            .addClass(DISABLEDSTATE)
            .removeAttr("disabled");
        item
            .children("a:focus")
            .parent()
            .addClass("k-state-active");

        if (!item.children("." + LINK).length) {
            item
                .contents()      // exclude groups, real links, templates and empty text nodes
                .filter(function() { return (!this.nodeName.match(excludedNodesRegExp) && !(this.nodeType == 3 && !$.trim(this.nodeValue))); })
                .wrapAll("<span class='" + LINK + "'/>");
        }

        updateArrow(item);
        updateFirstLast(item);
    }

    function updateArrow (item) {
        item = $(item);

        item.find(".k-icon").remove();

        item.filter(":has(.k-group)")
            .children(".k-link:not(:has([class*=k-arrow]))")
            .each(function () {
                var item = $(this),
                    parent = item.parent().parent();

                item.append("<span class='k-icon " + (parent.hasClass(MENU + "-horizontal") ? "k-arrow-down" : "k-arrow-next") + "'/>");
            });
    }

    function updateFirstLast (item) {
        item = $(item);

        item.filter(".k-first:not(:first-child)").removeClass(FIRST);
        item.filter(".k-last:not(:last-child)").removeClass(LAST);
        item.filter(":first-child").addClass(FIRST);
        item.filter(":last-child").addClass(LAST);
    }

    var Menu = Widget.extend({/** @lends kendo.ui.Menu.prototype */
        /**
         * Creates a Menu instance.
         * @constructs
         * @extends kendo.ui.Widget
         * @class Menu UI widget
         * @param {Selector} element DOM element
         * @param {Object} options Configuration options.
         * @option {Object} [animation] A collection of <b>Animation</b> objects, used to change default animations. A value of false will disable all animations in the widget.
         * <p>Available animations for the <b>Menu</b> are listed below.  Each animation has a reverse options which is used for the <b>close</b> effect by default, but can be over-ridden
         * by setting the <b>close</b> animation.  Each animation also has a direction which can be set off the animation (i.e. <b>slideIn:Down</b>).</p>
         * <div class="details-list">
         * <dl>
         *     <dt><b>slideIn</b></dt>
         *     <dd>Menu content slides in from the top</dd>
         *     <dt><b>fadeIn</b></dt>
         *     <dd>Menu content fades in</dd>
         *     <dt><b>expand</b></dt>
         *     <dd>Menu content expands from the top down. Similar to slideIn.</dd>
         * </dl>
         * </div>
         * _example
         *  $("#menu").kendoMenu({
         *      animation: { open: { effects: "fadeIn" } }
         *  });
         * @option {Animation} [animation.open] The animation that will be used when opening sub menus.
         * @option {Animation} [animation.close] The animation that will be used when closing sub menus.
         * @option {String} [orientation] <"horizontal"> Root menu orientation. Could be horizontal or vertical.
         * _example
         *  $("#menu").kendoMenu({
         *      orientation: "vertical"
         *  });
         * @option {Boolean} [closeOnClick] <true> Specifies that sub menus should close after item selection (provided they won't navigate).
         * _example
         *  $("#menu").kendoMenu({
         *      closeOnClick: false
         *  });
         * @option {Boolean} [openOnClick] <false> Specifies that the root sub menus will be opened on item click.
         * _example
         *  $("#menu").kendoMenu({
         *      openOnClick: true
         *  });
         * @option {Number} [hoverDelay] <100> Specifies the delay in ms before the menu is opened/closed - used to avoid accidental closure on leaving.
         * _example
         *  $("#menu").kendoMenu({
         *      hoverDelay: 200
         *  });
         * @option {String} [direction] <"default"> Specifies Menu opening direction. Can be "top", "bottom", "left", "right".
         * You can also specify different direction for root and sub menu items, separating them with space. The example below will initialize the root menu to open upwards and
         * its sub menus to the left.
         * _example
         * $("#menu").kendoMenu({
         *     direction: "top left"
         * });
         */
        init: function(element, options) {
            var that = this;

            Widget.fn.init.call(that, element, options);

            element = that.wrapper = that.element;

            options = that.options;

            if (options.dataSource) {
                that.element.empty().append($(Menu.renderGroup({
                    items: options.dataSource,
                    group: {
                        firstLevel: true,
                        horizontal: that.element.hasClass(MENU + "-horizontal"),
                        expanded: true
                    },
                    menu: {}
                })).children());
            }

            that._updateClasses();

            if (options.animation === false) {
                options.animation = { open: { show: true, effects: {} }, close: { hide: true, effects: {} } };
            }

            that.nextItemZIndex = 100;

            element.delegate(disabledSelector, CLICK, false)
                   .delegate(itemSelector, CLICK, proxy(that._click , that));

            if (!touch) {
                element.delegate(itemSelector, MOUSEENTER, proxy(that._mouseenter, that))
                       .delegate(itemSelector, MOUSELEAVE, proxy(that._mouseleave, that))
                       .delegate(linkSelector, MOUSEENTER + " " + MOUSELEAVE, that._toggleHover);
            } else {
                options.openOnClick = true;
                element.delegate(linkSelector, "touchstart touchend", that._toggleHover);
            }

            $(document).click(proxy( that._documentClick, that ));
            that.clicked = false;

            kendo.notify(that);
        },

        events: [
            /**
            * Fires before a sub menu gets opened.
            * @name kendo.ui.Menu#open
            * @event
            * @param {Event} e
            * @param {Element} e.item The opened item
            * @example
            *  $("#menu").kendoMenu({
            *      open: function(e) {
            *          // handle event
            *      }
            *  });
            * @exampleTitle To set after initialization
            * @example
            *  // get a reference to the menu widget
            *  var menu = $("#menu").data("kendoMenu");
            *  // bind to the open event
            *  menu.bind("open", function(e) {
            *      // handle event
            *  });
            */
            OPEN,
            /**
            * Fires after a sub menu gets closed.
            * @name kendo.ui.Menu#close
            * @event
            * @param {Event} e
            * @param {Element} e.item The closed item
            * @example
            *  $("#menu").kendoMenu({
            *      close: function(e) {
            *          // handle event
            *      }
            *  });
            * @exampleTitle To set after initialization
            * @example
            *  // get a reference to the menu widget
            *  var menu = $("#menu").data("kendoMenu");
            *  // bind to the close event
            *  menu.bind("close", function(e) {
            *      // handle event
            *  });
            */
            CLOSE,
            /**
            * Fires when a menu item gets selected.
            * @name kendo.ui.Menu#select
            * @event
            * @param {Event} e
            * @param {Element} e.item The selected item
            * @example
            *  $("#menu").kendoMenu({
            *      select: function(e) {
            *          // handle event
            *      }
            *  });
            * @exampleTitle To set after initialization
            * @example
            *  // get a reference to the menu widget
            *  var menu = $("#menu").data("kendoMenu");
            *  // bind to the select event
            *  menu.bind("select", function(e) {
            *      // handle event
            *  });
            */
            SELECT
        ],

        options: {
            name: "Menu",
            animation: {
                open: {
                    duration: 200,
                    show: true
                },
                close: { // if close animation effects are defined, they will be used instead of open.reverse
                    duration: 100
                }
            },
            orientation: "horizontal",
            direction: "default",
            openOnClick: false,
            closeOnClick: true,
            hoverDelay: 100
        },

        /**
         *
         * Enables or disables an item of a <strong>Menu</strong>. This can optionally be accomplished on
         * initialization by setting the <b>disabled="disabled"</b> on the desired menu item html element.
         *
         * @param {Selector} element
         * Target element
         *
         * @param {Boolean} enable
         * Desired state
         *
         * @returns {Menu}
         * Returns the Menu object to support chaining.
         *
         * @example
         * // get a reference to the menu widget
         * var menu = $("#menu").data("kendoMenu");
         * // disable the li menu item with the id "secondItem"
         * menu.enable("#secondItem", false);
         */
        enable: function (element, enable) {
            this._toggleDisabled(element, enable !== false);

            return this;
        },

        disable: function (element) {
            this._toggleDisabled(element, false);

            return this;
        },

        /**
         *
         * Appends an item to a <strong>Menu</strong> in the specified referenceItem's sub menu.
         *
         * @param {Selector} item
         * Target item, specified as a JSON object. Can also handle an array of such objects.
         *
         * @param {Item} referenceItem
         * A reference item to append the new item in.
         *
         * @returns {Menu}
         * Returns the Menu object to support chaining.
         *
         * @example
         * // get a reference to the menu widget
         * var menu = $("#menu").data("kendoMenu");
         * //
         * menu.append(
         *     [{
         *         text: "Item 1",
         *         url: "http://www.kendoui.com"                // Link URL if navigation is needed, optional.
         *     },
         *     {
         *         text: "Item 2",
         *         imageUrl: "http://www.kendoui.com/test.jpg", // Item image URL, optional.
         *         items: [{                                    // Sub item collection
         *              text: "Sub Item 1"
         *         },
         *         {
         *              text: "Sub Item 2"
         *         }]
         *     },
         *     {
         *         text: "Item 3",
         *         spriteCssClass: "imageClass3"                // Item image sprite CSS class, optional.
         *     }],
         *     referenceItem
         * );
         */
        append: function (item, referenceItem) {
            referenceItem = this.element.find(referenceItem);

            var inserted = this._insert(item, referenceItem, referenceItem.length ? referenceItem.find("> .k-group, .k-animation-container > .k-group") : null);

            each(inserted.items, function () {
                inserted.group.append(this);
                updateFirstLast(this);
            });

            updateArrow(referenceItem);
            updateFirstLast(inserted.group.find(".k-first, .k-last"));

            return this;
        },

        /**
         *
         * Inserts an item into a <strong>Menu</strong> before the specified referenceItem.
         *
         * @param {Selector} item
         * Target item, specified as a JSON object. Can also handle an array of such objects.
         *
         * @param {Selector} referenceItem
         * A reference item to insert the new item before
         *
         * @returns {Menu}
         * Returns the Menu object to support chaining.
         *
         * @example
         * // get a reference to the menu widget
         * var menu = $("#menu").data("kendoMenu");
         * //
         * menu.insertBefore(
         *     [{
         *         text: "Item 1",
         *         url: "http://www.kendoui.com"                // Link URL if navigation is needed, optional.
         *     },
         *     {
         *         text: "Item 2",
         *         imageUrl: "http://www.kendoui.com/test.jpg", // Item image URL, optional.
         *         items: [{                                    // Sub item collection
         *              text: "Sub Item 1"
         *         },
         *         {
         *              text: "Sub Item 2"
         *         }]
         *     },
         *     {
         *         text: "Item 3",
         *         spriteCssClass: "imageClass3"                // Item image sprite CSS class, optional.
         *     }],
         *     referenceItem
         * );
         */
        insertBefore: function (item, referenceItem) {
            referenceItem = this.element.find(referenceItem);

            var inserted = this._insert(item, referenceItem, referenceItem.parent());

            each(inserted.items, function () {
                referenceItem.before(this);
                updateFirstLast(this);
            });

            updateFirstLast(referenceItem);

            return this;
        },

        /**
         *
         * Inserts an item into a <strong>Menu</strong> after the specified referenceItem.
         *
         * @param {Selector} item
         * Target item, specified as a JSON object. Can also handle an array of such objects.
         *
         * @param {Selector} referenceItem
         * A reference item to insert the new item after.
         *
         * @returns {Menu}
         * Returns the Menu object to support chaining.
         *
         * @example
         * // get a reference to the menu widget
         * var menu = $("#menu").data("kendoMenu");
         * //
         * menu.insertAfter(
         *     [{
         *         text: "Item 1",
         *         url: "http://www.kendoui.com"                // Link URL if navigation is needed, optional.
         *     },
         *     {
         *         text: "Item 2",
         *         imageUrl: "http://www.kendoui.com/test.jpg", // Item image URL, optional.
         *         items: [{                                    // Sub item collection
         *              text: "Sub Item 1"
         *         },
         *         {
         *              text: "Sub Item 2"
         *         }]
         *     },
         *     {
         *         text: "Item 3",
         *         spriteCssClass: "imageClass3"                // Item image sprite CSS class, optional.
         *     }],
         *     referenceItem
         * );
         *
         */
        insertAfter: function (item, referenceItem) {
            referenceItem = this.element.find(referenceItem);

            var inserted = this._insert(item, referenceItem, referenceItem.parent());

            each(inserted.items, function () {
                referenceItem.after(this);
                updateFirstLast(this);
            });

            updateFirstLast(referenceItem);

            return this;
        },

        _insert: function (item, referenceItem, parent) {
            var that = this;

            if (!referenceItem || !referenceItem.length) {
                parent = that.element;
            }

            var plain = $.isPlainObject(item),
                items,
                groupData = {
                    firstLevel: parent.hasClass(MENU),
                    horizontal: parent.hasClass(MENU + "-horizontal"),
                    expanded: true,
                    length: parent.children().length
                };

            if (referenceItem && !parent.length) {
                parent = $(Menu.renderGroup({ group: groupData })).appendTo(referenceItem);
            }

            if (plain || $.isArray(item)) { // is JSON
                items = $.map(plain ? [ item ] : item, function (value, idx) {
                            return $(Menu.renderItem({
                                group: groupData,
                                item: extend(value, { index: idx })
                            }));
                        });
            } else {
                items = $(item);

                updateItemClasses(items);
            }

            return { items: items, group: parent };
        },

        /**
         *
         * Removes a specified item(s) from a <strong>Menu</strong>.
         *
         * @param {Selector} element
         * Target item selector.
         *
         * @returns {Menu}
         * Returns the Menu object to support chaining.
         *
         * @example
         * // get a reference to the menu widget
         * var menu = $("#menu").data("kendoMenu");
         * // remove the item with the id "Item1"
         * menu.remove("#Item1");
         *
         */
        remove: function (element) {
            element = this.element.find(element);

            var that = this,
                parent = element.parentsUntil(that.element, allItemsSelector),
                group = element.parent("ul");

            element.remove();

            if (group && !group.children(allItemsSelector).length) {
                var container = group.parent(".k-animation-container");
                container.length ? container.remove() : group.remove();
            }

            if (parent.length) {
                parent = parent.eq(0);

                updateArrow(parent);
                updateFirstLast(parent);
            }

            return that;
        },

        /**
         *
         * Opens a sub-menu of a specified item(s) in a <strong>Menu</strong>.
         *
         * @param {Selector} element
         * Target item selector.
         *
         * @returns {Menu}
         * Returns the Menu object to support chaining.
         *
         * @example
         * // get a reference to the menu widget
         * var menu = $("#menu").data("kendoMenu");
         * // open the sub menu of "Item1"
         * menu.open("#Item1");
         *
         */
        open: function (element) {
            var that = this,
                options = that.options,
                horizontal = options.orientation == "horizontal",
                direction = options.direction;
            element = that.element.find(element);

            if (/^(top|bottom|default)$/.test(direction)) {
                direction = horizontal ? (direction + " right").replace("default", "bottom") : "right";
            }

            element.each(function () {
                var li = $(this);

                clearTimeout(li.data(TIMER));

                li.data(TIMER, setTimeout(function () {
                    var ul = li.find(".k-group:first:hidden"), popup;

                    if (ul[0] && that.trigger(OPEN, { item: li[0] }) === false) {
                        li.data(ZINDEX, li.css(ZINDEX));
                        li.css(ZINDEX, that.nextItemZIndex ++);

                        popup = ul.data(KENDOPOPUP);
                        var root = li.parent().hasClass(MENU),
                            parentHorizontal = root && horizontal,
                            directions = parseDirection(direction, root),
                            effects = options.animation.open.effects,
                            openEffects = effects !== undefined ? effects : "slideIn:" + getEffectDirection(direction, root);

                        if (!popup) {
                            popup = ul.kendoPopup({
                                origin: directions.origin,
                                position: directions.position,
                                collision: parentHorizontal ? "fit" : "fit flip",
                                anchor: li,
                                appendTo: li,
                                animation: {
                                    open: extend(true, { effects: openEffects }, options.animation.open),
                                    close: options.animation.close
                                }
                            }).data(KENDOPOPUP);
                        } else {
                            popup = ul.data(KENDOPOPUP);
                            popup.options.origin = directions.origin;
                            popup.options.position = directions.position;
                            popup.options.animation.open.effects = openEffects;
                        }

                        popup.open();
                    }

                }, that.options.hoverDelay));
            });

            return that;
        },

        /**
         *
         * Closes a sub-menu of a specified item(s) in a <strong>Menu</strong>.
         *
         * @param {Selector} element Target item selector.
         *
         * @returns {Menu}
         * Returns the Menu object to support chaining.
         *
         * @example
         * // get a reference to the menu widget
         * var menu = $("#menu").data("kendoMenu");
         * // close the sub menu of "Item1"
         * menu.close("#Item1");
         *
         */
        close: function (element) {
            var that = this;
            element = that.element.find(element);

            element.each(function () {
                var li = $(this);

                clearTimeout(li.data(TIMER));

                li.data(TIMER, setTimeout(function () {
                    var ul = li.find(".k-group:first:visible"), popup;
                    if (ul[0] && that.trigger(CLOSE, { item: li[0] }) === false) {
                        li.css(ZINDEX, li.data(ZINDEX));
                        li.removeData(ZINDEX);

                        popup = ul.data(KENDOPOPUP);
                        popup.close();
                    }
                }, that.options.hoverDelay));
            });

            return that;
        },

        _toggleDisabled: function (element, enable) {
            element = this.element.find(element);
            element.each(function () {
                $(this)
                    .toggleClass(DEFAULTSTATE, enable)
                    .toggleClass(DISABLEDSTATE, !enable);
            });
        },

        _toggleHover: function(e) {
            var target = $(kendo.eventTarget(e)).closest(allItemsSelector);

            if (!target.parents("li." + DISABLEDSTATE).length) {
                target.toggleClass("k-state-hover", e.type == MOUSEENTER || e.type == "touchstart");
            }
        },

        _updateClasses: function() {
            var that = this;

            that.element.addClass("k-widget k-reset k-header " + MENU).addClass(MENU + "-" + that.options.orientation);

            var items = that.element
                            .find("li > ul")
                            .addClass("k-group")
                            .end()
                            .find("> li,.k-group > li")
                            .addClass("k-item");

            items.each(function () {
                updateItemClasses(this);
            });
        },

        _mouseenter: function (e) {
            var that = this,
                element = $(e.currentTarget),
                hasChildren = (element.children(".k-animation-container").length || element.children(groupSelector).length);

            if (!that.options.openOnClick || that.clicked) {
                if (!contains(e.currentTarget, e.relatedTarget) && hasChildren) {
                    that.open(element);
                }
            }

            if (that.options.openOnClick && that.clicked) {
                element.siblings().each(proxy(function (_, sibling) {
                    that.close(sibling);
                }, that));
            }
        },

        _mouseleave: function (e) {
            var that = this,
                element = $(e.currentTarget),
                hasChildren = (element.children(".k-animation-container").length || element.children(groupSelector).length);

            if (!that.options.openOnClick && !contains(e.currentTarget, e.relatedTarget) && hasChildren) {
                that.close(element);
            }
        },

        _click: function (e) {
            var that = this, openHandle,
                target = $(kendo.eventTarget(e)),
                link = target.closest("." + LINK),
                href = link.attr("href"),
                element = target.closest(allItemsSelector);

            if (element.hasClass(DISABLEDSTATE)) {
                e.preventDefault();
                return;
            }

            if (touch) {
                element.siblings().each(proxy(function (_, sibling) {
                    that.close(sibling);
                }, that));
            }

            if (!e.handled) // We shouldn't stop propagation.
                that.trigger(SELECT, { item: element[0] });

            e.handled = true;

            if (that.options.closeOnClick && !(href && href.length > 0) && !element.children(groupSelector + ",.k-animation-container").length) {
                that.close(link.parentsUntil(that.element, allItemsSelector));
            }

            if ((!element.parent().hasClass(MENU) || !that.options.openOnClick) && !touch) {
                return;
            }

            e.preventDefault();

            that.clicked = true;
            openHandle = element.children(".k-animation-container, .k-group").is(":visible") ? CLOSE : OPEN;
            that[openHandle](element);
        },

        _documentClick: function (e) {
            var that = this;

            if (contains(that.element[0], e.target)) {
                return;
            }

            if (that.clicked) {
                that.clicked = false;
                that.close(that.element.find(".k-item>.k-animation-container:visible").parent());
            }
        }
    });

    // client-side rendering
    extend(Menu, {
        renderItem: function (options) {
            options = extend({ menu: {}, group: {} }, options);

            var empty = templates.empty,
                item = options.item,
                menu = options.menu;

            return templates.item(extend(options, {
                image: item.imageUrl ? templates.image : empty,
                sprite: item.spriteCssClass ? templates.sprite : empty,
                itemWrapper: templates.itemWrapper,
                arrow: item.items ? templates.arrow : empty,
                subGroup: Menu.renderGroup
            }, rendering));
        },

        renderGroup: function (options) {
            return templates.group(extend({
                renderItems: function(options) {
                    var html = "",
                        i = 0,
                        items = options.items,
                        len = items ? items.length : 0,
                        group = extend({ length: len }, options.group);

                    for (; i < len; i++) {
                        html += Menu.renderItem(extend(options, {
                            group: group,
                            item: extend({ index: i }, items[i])
                        }));
                    }

                    return html;
                }
            }, options, rendering));
        }
    });

    kendo.ui.plugin(Menu);

})(jQuery);
