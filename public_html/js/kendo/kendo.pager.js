/*
* Kendo UI Web v2012.1.322 (http://kendoui.com)
* Copyright 2012 Telerik AD. All rights reserved.
*
* Kendo UI Web commercial licenses may be obtained at http://kendoui.com/web-license
* If you do not own a commercial license, this file shall be governed by the
* GNU General Public License (GPL) version 3.
* For GPL requirements, please review: http://www.gnu.org/copyleft/gpl.html
*/
(function($, undefined) {
    var kendo = window.kendo,
        ui = kendo.ui,
        Widget = ui.Widget,
        proxy = $.proxy;

    function button(template, idx, text, numeric) {
        return template( {
            idx: idx,
            text: text,
            ns: kendo.ns,
            numeric: numeric
        });
    }

    var Pager = Widget.extend( {
        init: function(element, options) {
            var that = this;

            Widget.fn.init.call(that, element, options);

            options = that.options;
            that.dataSource = options.dataSource;
            that.linkTemplate = kendo.template(that.options.linkTemplate);
            that.selectTemplate = kendo.template(that.options.selectTemplate);

            that._refreshHandler = proxy(that.refresh, that);

            that.dataSource.bind("change", that._refreshHandler);

            that.list = $('<ul class="k-pager k-reset k-numeric" />').appendTo(that.element);//.html(that.selectTemplate({ text: 1 }));
            that._clickHandler = proxy(that._click, that);

            that.element.delegate("a", "click", that._clickHandler);

            that.refresh();
        },

        destroy: function() {
            var that = this;

            that.element.undelegate("a", "click", that._clickHandler);
            that.dataSource.unbind("change", that._refreshHandler);
        },

        options: {
            name: "Pager",
            selectTemplate: '<li><span class="k-state-active">#=text#</span></li>',
            linkTemplate: '<li><a href="\\#" class="k-link" data-#=ns#page="#=idx#">#=text#</a></li>',
            buttonCount: 10
        },

        refresh: function() {
            var that = this,
                idx,
                end,
                start = 1,
                html = "",
                reminder,
                page = that.page(),
                totalPages = that.totalPages(),
                linkTemplate = that.linkTemplate,
                buttonCount = that.options.buttonCount;

            if (page > buttonCount) {
                reminder = (page % buttonCount);

                start = (reminder == 0) ? (page - buttonCount) + 1 : (page - reminder) + 1;
            }

            end = Math.min((start + buttonCount) - 1, totalPages);

            if(start > 1) {
                html += button(linkTemplate, start - 1, "...", false);
            }

            for(idx = start; idx <= end; idx++) {
                html += button(idx == page ? that.selectTemplate : linkTemplate, idx, idx, true);
            }

            if(end < totalPages) {
                html += button(linkTemplate, idx, "...", false);
            }

            if (html === "") {
                html = that.selectTemplate({ text: 1 });
            }

            that.list.empty().append(html);
        },

        _click: function(e) {
            var page = $(e.currentTarget).attr(kendo.attr("page"));
            e.preventDefault();

            this.dataSource.page(page);

            this.trigger("change", { index: page });
        },

        totalPages: function() {
            return Math.ceil((this.dataSource.total() || 0) / this.pageSize());
        },

        pageSize: function() {
            return this.dataSource.pageSize() || this.dataSource.total();
        },

        page: function() {
            return this.dataSource.page() || 1;
        }
    });

    ui.plugin(Pager);
})(jQuery);
