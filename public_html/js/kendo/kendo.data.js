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
    var extend = $.extend,
        proxy = $.proxy,
        isFunction = $.isFunction,
        isPlainObject = $.isPlainObject,
        isEmptyObject = $.isEmptyObject,
        isArray = $.isArray,
        grep = $.grep,
        ajax = $.ajax,
        map,
        each = $.each,
        noop = $.noop,
        kendo = window.kendo,
        Observable = kendo.Observable,
        Class = kendo.Class,
        STRING = "string",
        FUNCTION = "function",
        CREATE = "create",
        READ = "read",
        UPDATE = "update",
        DESTROY = "destroy",
        CHANGE = "change",
        GET = "get",
        MULTIPLE = "multiple",
        SINGLE = "single",
        ERROR = "error",
        REQUESTSTART = "requestStart",
        crud = [CREATE, READ, UPDATE, DESTROY],
        identity = function(o) { return o; },
        getter = kendo.getter,
        stringify = kendo.stringify,
        math = Math,
        push = [].push,
        join = [].join,
        pop = [].pop,
        splice = [].splice,
        shift = [].shift,
        slice = [].slice,
        unshift = [].unshift,
        toString = {}.toString,
        stableSort = kendo.support.stableSort,
        dateRegExp = /^\/Date\((.*?)\)\/$/,
        quoteRegExp = /(?=['\\])/g;

    var ObservableArray = Observable.extend({
        init: function(array, type) {
            var that = this;

            that.type = type || ObservableObject;

            Observable.fn.init.call(that);

            that.length = array.length;

            that.wrapAll(array, that);
        },

        wrapAll: function(source, target) {
            var idx, length;

            target = target || [];

            for (idx = 0, length = source.length; idx < length; idx++) {
                target[idx] = this.wrap(source[idx]);
            }

            return target;
        },

        wrap: function(object) {
            var that = this;

            if (object !== null && toString.call(object) === "[object Object]") {
                var observable = object instanceof that.type || object instanceof Model;
                if (!observable) {
                    object = object instanceof ObservableObject ? object.toJSON() : object;
                    object = new that.type(object);
                }

                object.bind(CHANGE, function(e) {
                    that.trigger(CHANGE, {
                        field: e.field,
                        items: [this],
                        action: "itemchange"
                    });
                });
            }

            return object;
        },

        push: function() {
            var index = this.length,
                items = this.wrapAll(arguments),
                result;

            result = push.apply(this, items);

            this.trigger(CHANGE, {
                action: "add",
                index: index,
                items: items
            });

            return result;
        },

        slice: slice,

        join: join,

        pop: function() {
            var length = this.length, result = pop.apply(this);

            if (length) {
                this.trigger(CHANGE, {
                    action: "remove",
                    index: length - 1,
                    items:[result]
                });
            }

            return result;
        },

        splice: function(index, howMany, item) {
            var items = this.wrapAll(slice.call(arguments, 2)),
                result;

            result = splice.apply(this, [index, howMany].concat(items));

            if (result.length) {
                this.trigger(CHANGE, {
                    action: "remove",
                    index: index,
                    items: result
                });
            }

            if (item) {
                this.trigger(CHANGE, {
                    action: "add",
                    index: index,
                    items: items
                });
            }
            return result;
        },

        shift: function() {
            var length = this.length, result = shift.apply(this);

            if (length) {
                this.trigger(CHANGE, {
                    action: "remove",
                    index: 0,
                    items:[result]
                });
            }

            return result;
        },

        unshift: function() {
            var items = this.wrapAll(arguments),
                result;

            result = unshift.apply(this, items);

            this.trigger(CHANGE, {
                action: "add",
                index: 0,
                items: items
            });

            return result;
        },

        indexOf: function(item) {
            var that = this,
                idx,
                length;

            for (idx = 0, length = that.length; idx < length; idx++) {
                if (that[idx] === item) {
                    return idx;
                }
            }
            return -1;
        }
    });

    var ObservableObject = Observable.extend({
        init: function(value) {
            var that = this,
                member,
                field,
                type;

            Observable.fn.init.call(this);

            for (field in value) {
                member = value[field];
                if (field.charAt(0) != "_") {
                    type = toString.call(member);

                    member = that.wrap(member, field);
                }
                that[field] = member;
            }

            that.uid = kendo.guid();
        },

        shouldSerialize: function(field) {
            return this.hasOwnProperty(field) && field !== "_events" && typeof this[field] !== FUNCTION
            && field !== "uid";
        },

        toJSON: function() {
            var result = {}, field;

            for (field in this) {
                if (this.shouldSerialize(field)) {
                    result[field] = this[field];
                }
            }

            return result;
        },

        get: function(field, call) {
            var that = this, result, getter;

            that.trigger(GET, { field: field });

            if (field === "this") {
                result = that;
            } else {
                getter = kendo.getter(field, true);

                result = getter(that);

                if (call && typeof result === FUNCTION) {
                    result = result.call(that);
                }
            }

            return result;
        },

        _set: function(field, value) {
            var that = this;
            if (field.indexOf(".")) {
                var paths = field.split("."),
                    path = "";

                while (paths.length > 1) {
                    path += paths.shift();
                    var obj = kendo.getter(path, true)(that);
                    if (obj instanceof ObservableObject) {
                        obj.set(paths.join("."), value);
                        return;
                    }
                    path += ".";
                }
            }

            kendo.setter(field)(that, value);
        },

        set: function(field, value) {
            var that = this,
                current = that[field],
                setter;

            if (current != value) {
                if (!that.trigger("set", { field: field, value: value })) {

                    that._set(field, that.wrap(value, field));

                    that.trigger(CHANGE, { field: field });
                }
            }
        },

        wrap: function(object, field) {
            var that = this,
                type = toString.call(object);

            if (object !== null && type === "[object Object]" && !(object instanceof DataSource)) {
                if (!(object instanceof ObservableObject)) {
                    object = new ObservableObject(object);
                }

                (function(field) {
                    object.bind(GET, function(e) {
                        e.field = field + "." + e.field;
                        that.trigger(GET, e);
                    });

                    object.bind(CHANGE, function(e) {
                        e.field = field + "." + e.field;
                        that.trigger(CHANGE, e);
                    });
                })(field);
            } else if (object !== null && type === "[object Array]") {
                object = new ObservableArray(object);

                (function(field) {
                    object.bind(CHANGE, function(e) {
                        that.trigger(CHANGE, { field: field, index: e.index, items: e.items, action: e.action});
                    });
                })(field);
            }
            return object;
        }
    });

    function equal(x, y) {
        if (x === y) {
            return true;
        }

        var xtype = $.type(x), ytype = $.type(y), field;

        if (xtype !== ytype) {
            return false;
        }

        if (xtype === "date") {
            return x.getTime() === y.getTime();
        }

        if (xtype !== "object" && xtype !== "array") {
            return false;
        }

        for (field in x) {
            if (!equal(x[field], y[field])) {
                return false;
            }
        }

        return true;
    }

    var parsers = {
        "number": function(value) {
            return kendo.parseFloat(value);
        },

        "date": function(value) {
            if (typeof value === STRING) {
                var date = dateRegExp.exec(value);
                if (date) {
                    return new Date(parseInt(date[1]));
                }
            }
            return kendo.parseDate(value);
        },

        "boolean": function(value) {
            if (typeof value === STRING) {
                return value.toLowerCase() === "true";
            }
            return !!value;
        },

        "string": function(value) {
            return value + "";
        },

        "default": function(value) {
            return value;
        }
    };

    var defaultValues = {
        "string": "",
        "number": 0,
        "date": new Date(),
        "boolean": false,
        "default": ""
    }

    var Model = ObservableObject.extend({
        init: function(data) {
            var that = this;

            if (!data || $.isEmptyObject(data)) {
                data = $.extend({}, that.defaults, data);
            }

            ObservableObject.fn.init.call(that, data);

            that.dirty = false;

            if (that.idField) {
                that.id = that.get(that.idField);

                if (that.id === undefined) {
                    that.id = that._defaultId;
                }
            }
        },

        shouldSerialize: function(field) {
            return ObservableObject.fn.shouldSerialize.call(this, field)
            && field !== "uid"
            && !(this.idField !== "id" && field === "id")
            && field !== "dirty" && field !== "_accessors";
        },

        _parse: function(field, value) {
            var that = this,
            parse;

            field = (that.fields || {})[field];
            if (field) {
                parse = field.parse;
                if (!parse && field.type) {
                    parse = parsers[field.type.toLowerCase()];
                }
            }

            return parse ? parse(value) : value;
        },

        editable: function(field) {
            field = (this.fields || {})[field];
            return field ? field.editable !== false : true;
        },

        set: function(field, value, initiator) {
            var that = this;

            if (that.editable(field)) {
                value = that._parse(field, value);

                if (!equal(value, that.get(field))) {
                    ObservableObject.fn.set.call(that, field, value, initiator);
                    that.dirty = true;
                }
            }
        },

        accept: function(data) {
            var that = this;

            extend(that, data);

            if (that.idField) {
                that.id = that.get(that.idField);
            }
            that.dirty = false;
        },

        isNew: function() {
            return this.id === this._defaultId;
        }
    });

    Model.define = function(options) {
        var model,
            proto = extend({}, { defaults: {} }, options),
            id = proto.id;

        if (id) {
            proto.idField = id;
        }

        if (proto.id) {
            delete proto.id;
        }

        if (id) {
            proto.defaults[id] = proto._defaultId = "";
        }

        for (var name in proto.fields) {
            var field = proto.fields[name],
            type = field.type || "default",
            value = null;

            name = field.field || name;

            if (!field.nullable) {
                value = proto.defaults[name] = field.defaultValue !== undefined ? field.defaultValue : defaultValues[type.toLowerCase()];
            }

            if (options.id === name) {
                proto._defaultId = value;
            }

            proto.defaults[name] = value;

            field.parse = field.parse || parsers[type];
        }

        model = Model.extend(proto);

        if (proto.fields) {
            model.fields = proto.fields;
            model.idField = proto.idField;
        }

        return model;
    }

    var Comparer = {
        selector: function(field) {
            return isFunction(field) ? field : getter(field);
        },

        asc: function(field) {
            var selector = this.selector(field);
            return function (a, b) {
                a = selector(a);
                b = selector(b);

                return a > b ? 1 : (a < b ? -1 : 0);
            };
        },

        desc: function(field) {
            var selector = this.selector(field);
            return function (a, b) {
                a = selector(a);
                b = selector(b);

                return a < b ? 1 : (a > b ? -1 : 0);
            };
        },

        create: function(descriptor) {
            return Comparer[descriptor.dir.toLowerCase()](descriptor.field);
        },

        combine: function(comparers) {
            return function(a, b) {
                var result = comparers[0](a, b),
                idx,
                length;

                for (idx = 1, length = comparers.length; idx < length; idx ++) {
                    result = result || comparers[idx](a, b);
                }

                return result;
            }
        }
    };

    var PositionComparer = {
        selector: function(field) {
            return isFunction(field) ? field : getter(field);
        },

        asc: function(field) {
            var selector = this.selector(field);
            return function (a, b) {
                var valueA = selector(a);
                var valueB = selector(b);

                if (valueA === valueB) {
                    return a.__position - b.__position;
                }
                return valueA > valueB ? 1 : (valueA < valueB ? -1 : 0);
            };
        },

        desc: function(field) {
            var selector = this.selector(field);
            return function (a, b) {
                var valueA = selector(a);
                var valueB = selector(b);

                if (valueA === valueB) {
                    return a.__position - b.__position;
                }

                return valueA < valueB ? 1 : (valueA > valueB ? -1 : 0);
            };
        },

        create: function(descriptor) {
            return PositionComparer[descriptor.dir.toLowerCase()](descriptor.field);
        },

        combine: function(comparers) {
             return function(a, b) {
                 var result = comparers[0](a, b),
                     idx,
                     length;

                 for (idx = 1, length = comparers.length; idx < length; idx ++) {
                     result = result || comparers[idx](a, b);
                 }

                 return result;
             }
        }
    };

    map = function (array, callback) {
        var idx, length = array.length, result = new Array(length);

        for (idx = 0; idx < length; idx++) {
            result[idx] = callback(array[idx], idx, array);
        }

        return result;
    };

    var operators = (function(){

        function quote(value) {
            return value.replace(quoteRegExp, "\\");
        }

        function operator(op, a, b, ignore) {
            var date;

            if (b != undefined) {
                if (typeof b === STRING) {
                    b = quote(b);
                    date = dateRegExp.exec(b);
                    if (date) {
                        b = new Date(+date[1]);
                    } else if (ignore) {
                        b = "'" + b.toLowerCase() + "'";
                        a = a + ".toLowerCase()";
                    } else {
                        b = "'" + b + "'";
                    }
                }

                if (b.getTime) {
                    //b looks like a Date
                    a = "(" + a + "?" + a + ".getTime():" + a + ")";
                    b = b.getTime();
                }
            }

            return a + " " + op + " " + b;
        }

        return {
            eq: function(a, b, ignore) {
                return operator("==", a, b, ignore);
            },
            neq: function(a, b, ignore) {
                return operator("!=", a, b, ignore);
            },
            gt: function(a, b, ignore) {
                return operator(">", a, b, ignore);
            },
            gte: function(a, b, ignore) {
                return operator(">=", a, b, ignore);
            },
            lt: function(a, b, ignore) {
                return operator("<", a, b, ignore);
            },
            lte: function(a, b, ignore) {
                return operator("<=", a, b, ignore);
            },
            startswith: function(a, b, ignore) {
                if (ignore) {
                    a = a + ".toLowerCase()";
                    if (b) {
                        b = b.toLowerCase();
                    }
                }

                if (b) {
                    b = quote(b);
                }

                return a + ".lastIndexOf('" + b + "', 0) == 0";
            },
            endswith: function(a, b, ignore) {
                if (ignore) {
                    a = a + ".toLowerCase()";
                    if (b) {
                        b = b.toLowerCase();
                    }
                }

                if (b) {
                    b = quote(b);
                }

                return a + ".lastIndexOf('" + b + "') == " + a + ".length - " + (b || "").length;
            },
            contains: function(a, b, ignore) {
                if (ignore) {
                    a = a + ".toLowerCase()";
                    if (b) {
                        b = b.toLowerCase();
                    }
                }

                if (b) {
                    b = quote(b);
                }

                return a + ".indexOf('" + b + "') >= 0"
            }
        };
    })();

    function Query(data) {
        this.data = data || [];
    }

    Query.normalizeFilter = normalizeFilter;

    Query.filterExpr = function(expression) {
        var expressions = [],
        logic = { and: " && ", or: " || " },
        idx,
        length,
        filter,
        expr,
        fieldFunctions = [],
        operatorFunctions = [],
        field,
        operator,
        filters = expression.filters;

        for (idx = 0, length = filters.length; idx < length; idx++) {
            filter = filters[idx];
            field = filter.field;
            operator = filter.operator;

            if (filter.filters) {
                expr = Query.filterExpr(filter);
                //Nested function fields or operators - update their index e.g. __o[0] -> __o[1]
                filter = expr.expression
                .replace(/__o\[(\d+)\]/g, function(match, index) {
                    index = +index;
                    return "__o[" + (operatorFunctions.length + index) + "]";
                })
                .replace(/__f\[(\d+)\]/g, function(match, index) {
                    index = +index;
                    return "__f[" + (fieldFunctions.length + index) + "]";
                });

                operatorFunctions.push.apply(operatorFunctions, expr.operators);
                fieldFunctions.push.apply(fieldFunctions, expr.fields);
            } else {
                if (typeof field === FUNCTION) {
                    expr = "__f[" + fieldFunctions.length +"](d)";
                    fieldFunctions.push(field);
                } else {
                    expr = kendo.expr(field);
                }

                if (typeof operator === FUNCTION) {
                    filter = "__o[" + operatorFunctions.length + "](" + expr + ", " + filter.value + ")";
                    operatorFunctions.push(operator);
                } else {
                    filter = operators[(operator || "eq").toLowerCase()](expr, filter.value, filter.ignoreCase !== undefined? filter.ignoreCase : true);
                }
            }

            expressions.push(filter);
        }

        return  { expression: "(" + expressions.join(logic[expression.logic]) + ")", fields: fieldFunctions, operators: operatorFunctions };
    };

    function normalizeSort(field, dir) {
        if (field) {
            var descriptor = typeof field === STRING ? { field: field, dir: dir } : field,
            descriptors = isArray(descriptor) ? descriptor : (descriptor !== undefined ? [descriptor] : []);

            return grep(descriptors, function(d) { return !!d.dir; });
        }
    }

    var operatorMap = {
        "==": "eq",
        equals: "eq",
        isequalto: "eq",
        equalto: "eq",
        equal: "eq",
        "!=": "neq",
        ne: "neq",
        notequals: "neq",
        isnotequalto: "neq",
        notequalto: "neq",
        notequal: "neq",
        "<": "lt",
        islessthan: "lt",
        lessthan: "lt",
        less: "lt",
        "<=": "lte",
        le: "lte",
        islessthanorequalto: "lte",
        lessthanequal: "lte",
        ">": "gt",
        isgreaterthan: "gt",
        greaterthan: "gt",
        greater: "gt",
        ">=": "gte",
        isgreaterthanorequalto: "gte",
        greaterthanequal: "gte",
        ge: "gte"
    };

    function normalizeOperator(expression) {
        var idx,
        length,
        filter,
        operator,
        filters = expression.filters;

        if (filters) {
            for (idx = 0, length = filters.length; idx < length; idx++) {
                filter = filters[idx];
                operator = filter.operator;

                if (operator && typeof operator === STRING) {
                    filter.operator = operatorMap[operator.toLowerCase()] || operator;
                }

                normalizeOperator(filter);
            }
        }
    }

    function normalizeFilter(expression) {
        if (expression && !isEmptyObject(expression)) {
            if (isArray(expression) || !expression.filters) {
                expression = {
                    logic: "and",
                    filters: isArray(expression) ? expression : [expression]
                }
            }

            normalizeOperator(expression);

            return expression;
        }
    }

    function normalizeAggregate(expressions) {
        return expressions = isArray(expressions) ? expressions : [expressions];
    }

    function normalizeGroup(field, dir) {
        var descriptor = typeof field === STRING ? { field: field, dir: dir } : field,
        descriptors = isArray(descriptor) ? descriptor : (descriptor !== undefined ? [descriptor] : []);

        return map(descriptors, function(d) { return { field: d.field, dir: d.dir || "asc", aggregates: d.aggregates }; });
    }

    Query.prototype = {
        toArray: function () {
            return this.data;
        },
        range: function(index, count) {
            return new Query(this.data.slice(index, index + count));
        },
        skip: function (count) {
            return new Query(this.data.slice(count));
        },
        take: function (count) {
            return new Query(this.data.slice(0, count));
        },
        select: function (selector) {
            return new Query(map(this.data, selector));
        },
        orderBy: function (selector) {
            var result = this.data.slice(0),
            comparer = isFunction(selector) || !selector ? Comparer.asc(selector) : selector.compare;

            return new Query(result.sort(comparer));
        },
        orderByDescending: function (selector) {
            return new Query(this.data.slice(0).sort(Comparer.desc(selector)));
        },
        sort: function(field, dir, comparer) {
            var idx,
            length,
            descriptors = normalizeSort(field, dir),
            comparers = [];

            comparer = comparer || Comparer;

            if (descriptors.length) {
                for (idx = 0, length = descriptors.length; idx < length; idx++) {
                    comparers.push(comparer.create(descriptors[idx]));
                }

                return this.orderBy({ compare: comparer.combine(comparers) });
            }

            return this;
        },

        filter: function(expressions) {
            var idx,
            current,
            length,
            compiled,
            predicate,
            data = this.data,
            fields,
            operators,
            result = [],
            filter;

            expressions = normalizeFilter(expressions);

            if (!expressions || expressions.filters.length === 0) {
                return this;
            }

            compiled = Query.filterExpr(expressions);
            fields = compiled.fields;
            operators = compiled.operators;

            predicate = filter = new Function("d, __f, __o", "return " + compiled.expression);

            if (fields.length || operators.length) {
                filter = function(d) {
                    return predicate(d, fields, operators);
                };
            }

            for (idx = 0, length = data.length; idx < length; idx++) {
                current = data[idx];

                if (filter(current)) {
                    result.push(current);
                }
            }
            return new Query(result);
        },

        group: function(descriptors, allData) {
            descriptors =  normalizeGroup(descriptors || []);
            allData = allData || this.data;

            var that = this,
            result = new Query(that.data),
            descriptor;

            if (descriptors.length > 0) {
                descriptor = descriptors[0];
                result = result.groupBy(descriptor).select(function(group) {
                    var data = new Query(allData).filter([ { field: group.field, operator: "eq", value: group.value } ]);
                    return {
                        field: group.field,
                        value: group.value,
                        items: descriptors.length > 1 ? new Query(group.items).group(descriptors.slice(1), data.toArray()).toArray() : group.items,
                        hasSubgroups: descriptors.length > 1,
                        aggregates: data.aggregate(descriptor.aggregates)
                    }
                });
            }
            return result;
        },

        groupBy: function(descriptor) {
            if (isEmptyObject(descriptor) || !this.data.length) {
                return new Query([]);
            }

            var field = descriptor.field,
                sorted = this._sortForGrouping(field, descriptor.dir || "asc"),
                accessor = kendo.accessor(field),
                item,
                groupValue = accessor.get(sorted[0], field),
                group = {
                    field: field,
                    value: groupValue,
                    items: []
                },
                currentValue,
                idx,
                len,
                result = [group];

            for(idx = 0, len = sorted.length; idx < len; idx++) {
                item = sorted[idx];
                currentValue = accessor.get(item, field);
                if(!groupValueComparer(groupValue, currentValue)) {
                    groupValue = currentValue;
                    group = {
                        field: field,
                        value: groupValue,
                        items: []
                    };
                    result.push(group);
                }
                group.items.push(item);
            }
            return new Query(result);
        },

        _sortForGrouping: function(field, dir) {
            var idx, length,
                data = this.data;

            if (!stableSort) {
                for (idx = 0, length = data.length; idx < length; idx++) {
                    data[idx].__position = idx;
                }

                data = new Query(data).sort(field, dir, PositionComparer).toArray();

                for (idx = 0, length = data.length; idx < length; idx++) {
                    delete data[idx].__position;
                }
                return data;
            }
            return this.sort(field, dir).toArray();
        },

        aggregate: function (aggregates) {
            var idx,
            len,
            result = {};

            if (aggregates && aggregates.length) {
                for(idx = 0, len = this.data.length; idx < len; idx++) {
                    calculateAggregate(result, aggregates, this.data[idx], idx, len);
                }
            }
            return result;
        }
    }

    function groupValueComparer(a, b) {
        if (a && a.getTime && b && b.getTime) {
            return a.getTime() === b.getTime();
        }
        return a === b;
    }

    function calculateAggregate(accumulator, aggregates, item, index, length) {
        aggregates = aggregates || [];
        var idx,
        aggr,
        functionName,
        fieldAccumulator,
        len = aggregates.length;

        for (idx = 0; idx < len; idx++) {
            aggr = aggregates[idx];
            functionName = aggr.aggregate;
            var field = aggr.field;
            accumulator[field] = accumulator[field] || {};
            accumulator[field][functionName] = functions[functionName.toLowerCase()](accumulator[field][functionName], item, kendo.accessor(field), index, length);
        }
    }

    var functions = {
        sum: function(accumulator, item, accessor) {
            return accumulator = (accumulator || 0) + accessor.get(item);
        },
        count: function(accumulator, item, accessor) {
            return (accumulator || 0) + 1;
        },
        average: function(accumulator, item, accessor, index, length) {
            accumulator = (accumulator || 0) + accessor.get(item);
            if(index == length - 1) {
                accumulator = accumulator / length;
            }
            return accumulator;
        },
        max: function(accumulator, item, accessor) {
            var accumulator =  (accumulator || 0),
            value = accessor.get(item);
            if(accumulator < value) {
                accumulator = value;
            }
            return accumulator;
        },
        min: function(accumulator, item, accessor) {
            var value = accessor.get(item),
            accumulator = (accumulator || value)
            if(accumulator > value) {
                accumulator = value;
            }
            return accumulator;
        }
    };

    function toJSON(array) {
        var idx, length = array.length, result = new Array(length);

        for (idx = 0; idx < length; idx++) {
            result[idx] = array[idx].toJSON();
        }

        return result;
    }

    function process(data, options) {
        var query = new Query(data),
        options = options || {},
        group = options.group,
        sort = normalizeGroup(group || []).concat(normalizeSort(options.sort || [])),
        total,
        filter = options.filter,
        skip = options.skip,
        take = options.take;

        if (filter) {
            query = query.filter(filter);
            total = query.toArray().length;
        }

        if (sort) {
            query = query.sort(sort);

            if (group) {
                data = query.toArray();
            }
        }

        if (skip !== undefined && take !== undefined) {
            query = query.range(skip, take);
        }

        if (group) {
            query = query.group(group, data);
        }

        return {
            total: total,
            data: query.toArray()
        };
    }

    function calculateAggregates(data, options) {
        var query = new Query(data),
        options = options || {},
        aggregates = options.aggregate,
        filter = options.filter;

        if(filter) {
            query = query.filter(filter);
        }
        return query.aggregate(aggregates);
    }

    var LocalTransport = Class.extend({
        init: function(options) {
            this.data = options.data;
        },

        read: function(options) {
            options.success(this.data);
        },
        update: function(options) {
            options.success(options.data);
        },
        create: function(options) {
            options.success(options.data);
        },
        destroy: noop
    });

    var RemoteTransport = Class.extend( {
        init: function(options) {
            var that = this, parameterMap;

            options = that.options = extend({}, that.options, options);

            each(crud, function(index, type) {
                if (typeof options[type] === STRING) {
                    options[type] = {
                        url: options[type]
                    };
                }
            });

            that.cache = options.cache? Cache.create(options.cache) : {
                find: noop,
                add: noop
            }

            parameterMap = options.parameterMap;

            that.parameterMap = isFunction(parameterMap) ? parameterMap : function(options) {
                var result = {};

                each(options, function(option, value) {
                    if (option in parameterMap) {
                        option = parameterMap[option];
                        if (isPlainObject(option)) {
                            value = option.value(value);
                            option = option.key;
                        }
                    }

                    result[option] = value;
                });

                return result;
            };
        },

        options: {
            parameterMap: identity
        },

        create: function(options) {
            return ajax(this.setup(options, CREATE));
        },

        read: function(options) {
            var that = this,
                success,
                error,
                result,
                cache = that.cache;

            options = that.setup(options, READ);

            success = options.success || noop;
            error = options.error || noop;

            result = cache.find(options.data);

            if(result !== undefined) {
                success(result);
            } else {
                options.success = function(result) {
                    cache.add(options.data, result);

                    success(result);
                };

                $.ajax(options);
            }
        },

        update: function(options) {
            return ajax(this.setup(options, UPDATE));
        },

        destroy: function(options) {
            return ajax(this.setup(options, DESTROY));
        },

        setup: function(options, type) {
            options = options || {};

            var that = this,
                parameters,
                operation = that.options[type],
                data = isFunction(operation.data) ? operation.data() : operation.data;

            options = extend(true, {}, operation, options);
            parameters = extend(data, options.data);

            options.data = that.parameterMap(parameters, type);

            if (isFunction(options.url)) {
                options.url = options.url(parameters);
            }

            return options;
        }
    });

    Cache.create = function(options) {
        var store = {
            "inmemory": function() { return new Cache(); }
        };

        if (isPlainObject(options) && isFunction(options.find)) {
            return options;
        }

        if (options === true) {
            return new Cache();
        }

        return store[options]();
    }

    function Cache() {
        this._store = {};
    }

    Cache.prototype = /** @ignore */ {
        add: function(key, data) {
            if(key !== undefined) {
                this._store[stringify(key)] = data;
            }
        },
        find: function(key) {
            return this._store[stringify(key)];
        },
        clear: function() {
            this._store = {};
        },
        remove: function(key) {
            delete this._store[stringify(key)];
        }
    }

    var DataReader = Class.extend({
        init: function(schema) {
            var that = this, member, get, model;

            schema = schema || {};

            for (member in schema) {
                get = schema[member];

                that[member] = typeof get === STRING ? getter(get) : get;
            }

            if (isPlainObject(that.model)) {
                that.model = model = kendo.data.Model.define(that.model);

                var dataFunction = that.data,
                getters = {};

                if (model.fields) {
                    each(model.fields, function(field, value) {
                        if (isPlainObject(value) && value.field) {
                            getters[value.field] = getter(value.field);
                        } else {
                            getters[field] = getter(field);
                        }
                    });
                }

                that.data = function(data) {
                    var record,
                        getter,
                        idx,
                        length,
                        modelInstance = new that.model();

                    data = dataFunction(data);

                    if (!isEmptyObject(getters)) {
                        for (idx = 0, length = data.length; idx < length; idx++) {
                            record = data[idx];
                            for (getter in getters) {
                                record[getter] = modelInstance._parse(getter, getters[getter](record));
                            }
                        }
                    }

                    return data;
                }
            }
        },
        parse: identity,
        data: identity,
        total: function(data) {
            return data.length;
        },
        groups: identity,
        status: function(data) {
            return data.status;
        },
        aggregates: function() {
            return {};
        }
    });

    var DataSource = Observable.extend({
        init: function(options) {
            var that = this, id, model, transport;

            options = that.options = extend({}, that.options, options);

            extend(that, {
                _map: {},
                _prefetch: {},
                _data: [],
                _ranges: [],
                _view: [],
                _pristine: [],
                _destroyed: [],
                _pageSize: options.pageSize,
                _page: options.page  || (options.pageSize ? 1 : undefined),
                _sort: normalizeSort(options.sort),
                _filter: normalizeFilter(options.filter),
                _group: normalizeGroup(options.group),
                _aggregate: options.aggregate
            });

            Observable.fn.init.call(that);

            transport = options.transport;

            if (transport) {
                transport.read = typeof transport.read === STRING ? { url: transport.read } : transport.read;

                if (options.type) {
                    transport = extend(true, {}, kendo.data.transports[options.type], transport);
                    options.schema = extend(true, {}, kendo.data.schemas[options.type], options.schema);
                }

                that.transport = isFunction(transport.read) ? transport: new RemoteTransport(transport);
            } else {
                that.transport = new LocalTransport({ data: options.data });
            }

            that.reader = new kendo.data.readers[options.schema.type || "json" ](options.schema);

            model = that.reader.model || {};

            that._data = that._observe(that._data);

            id = model.id;

            if (id) {
                that.id = function(record) {
                    return id(record);
                };
            }

            that.bind([ERROR, CHANGE, REQUESTSTART], options);
        },

        options: {
            data: [],
            schema: {},
            serverSorting: false,
            serverPaging: false,
            serverFiltering: false,
            serverGrouping: false,
            serverAggregates: false,
            sendAllFields: true,
            batch: false
        },

        get: function(id) {
            var idx, length, data = this._data;

            for (idx = 0, length = data.length; idx < length; idx++) {
                if (data[idx].id == id) {
                    return data[idx];
                }
            }
        },

        getByUid: function(id) {
            var idx, length, data = this._data;

            for (idx = 0, length = data.length; idx < length; idx++) {
                if (data[idx].uid == id) {
                    return data[idx];
                }
            }
        },

        sync: function() {
            var that = this,
            idx,
            length,
            created = [],
            updated = [],
            destroyed = that._destroyed,
            data = that._data;

            if (!that.reader.model) {
                return;
            }

            for (idx = 0, length = data.length; idx < length; idx++) {
                if (data[idx].isNew()) {
                    created.push(data[idx]);
                } else if (data[idx].dirty) {
                    updated.push(data[idx]);
                }
            }

            var promises = that._send("create", created);

            promises.push.apply(promises ,that._send("update", updated));
            promises.push.apply(promises ,that._send("destroy", destroyed));

            $.when.apply(null, promises)
            .then(function() {
                var idx,
                length;

                for (idx = 0, length = arguments.length; idx < length; idx++){
                    that._accept(arguments[idx]);
                }

                that._change();
            });

        },

        _accept: function(result) {
            var that = this,
            models = result.models,
            response = result.response,
            idx = 0,
            pristine = that.reader.data(that._pristine),
            type = result.type,
            length;

            if (response) {
                response = that.reader.data(that.reader.parse(response));
            } else {
                response = $.map(models, function(model) { return model.toJSON(); } );
            }

            if (!$.isArray(response)) {
                response = [response];
            }

            if (type === "destroy") {
                that._destroyed = [];
            }

            for (idx = 0, length = models.length; idx < length; idx++) {
                if (type !== "destroy") {
                    models[idx].accept(response[idx]);

                    if (type === "create") {
                        pristine.push(models[idx]);
                    } else if (type === "update") {
                        extend(pristine[that._pristineIndex(models[idx])], response[idx]);
                    }
                } else {
                    pristine.splice(that._pristineIndex(models[idx]), 1);
                }
            }
        },

        _pristineIndex: function(model) {
            var that = this,
            idx,
            length,
            pristine = that.reader.data(that._pristine);

            for (idx = 0, length = pristine.length; idx < length; idx++) {
                if (pristine[idx][model.idField] === model.id) {
                    return idx;
                }
            }
            return -1;
        },

        _promise: function(data, models, type) {
            var that = this,
            transport = that.transport;

            return $.Deferred(function(deferred) {
                transport[type].call(transport, extend({
                    success: function(response) {
                        deferred.resolve({
                            response: response,
                            models: models,
                            type: type
                        });
                    },
                    error: function(response) {
                        deferred.reject(response);
                        that.trigger(ERROR, response);
                    }
                }, data)
                );
            }).promise();
        },

        _send: function(method, data) {
            var that = this,
            idx,
            length,
            promises = [],
            transport = that.transport;

            if (that.options.batch) {
                if (data.length) {
                    promises.push(that._promise( { data: { models: toJSON(data) } }, data , method));
                }
            } else {
                for (idx = 0, length = data.length; idx < length; idx++) {
                    promises.push(that._promise( { data: data[idx].toJSON() }, [ data[idx] ], method));
                }
            }

            return promises;
        },

        add: function(model) {
            return this.insert(this._data.length, model);
        },

        insert: function(index, model) {
            if (!model) {
                model = index;
                index = 0;
            }

            if (!(model instanceof Model)) {
                if (this.reader.model) {
                    model = new this.reader.model(model);
                } else {
                    model = new ObservableObject(model);
                }
            }

            this._data.splice(index, 0, model);

            return model;
        },

        cancelChanges: function(model) {
            var that = this,
                pristineIndex,
                pristine = that.reader.data(that._pristine),
                index;

            if (model instanceof kendo.data.Model) {
                index = that.indexOf(model);
                pristineIndex = that._pristineIndex(model);
                if (index != -1) {
                    if (pristineIndex != -1 && !model.isNew()) {
                        extend(true, that._data[index], pristine[pristineIndex]);
                    } else {
                        that._data.splice(index, 1);
                    }
                }
            } else {
                that._data = that._observe(pristine);
                that._change();
            }
        },

        read: function(data) {
            var that = this, params = that._params(data);

            that._queueRequest(params, function() {
                that.trigger(REQUESTSTART);
                that._ranges = [];
                that.transport.read({
                    data: params,
                    success: proxy(that.success, that),
                    error: proxy(that.error, that)
                });
            });
        },

        indexOf: function(model) {
            var idx, length, data = this._data;

            if (model) {
                for (idx = 0, length = data.length; idx < length; idx++) {
                    if (data[idx].uid == model.uid) {
                        return idx;
                    }
                }
            }
            return -1;
        },

        _params: function(data) {
            var that = this,
            options =  extend({
                take: that.take(),
                skip: that.skip(),
                page: that.page(),
                pageSize: that.pageSize(),
                sort: that._sort,
                filter: that._filter,
                group: that._group,
                aggregate: that._aggregate
            }, data);

            if (!that.options.serverPaging) {
                delete options.take;
                delete options.skip;
                delete options.page;
                delete options.pageSize;
            }
            return options;
        },

        _queueRequest: function(options, callback) {
            var that = this;
            if (!that._requestInProgress) {
                that._requestInProgress = true;
                that._pending = undefined;
                callback();
            } else {
                that._pending = { callback: proxy(callback, that), options: options };
            }
        },

        _dequeueRequest: function() {
            var that = this;
            that._requestInProgress = false;
            if (that._pending) {
                that._queueRequest(that._pending.options, that._pending.callback);
            }
        },

        remove: function(model) {
            var idx, length, data = this._data;

            for (idx = 0, length = data.length; idx < length; idx++) {
                if (data[idx].uid == model.uid) {
                    model = data[idx];
                    data.splice(idx, 1);
                    return model;
                }
            }
        },

        error: function(xhr, status, errorThrown) {
            this.trigger(ERROR, { xhr: xhr, status: status, errorThrown: errorThrown });
        },

        success: function(data) {
            var that = this,
            options = {},
            result,
            hasGroups = that.options.serverGrouping === true && that._group && that._group.length > 0;

            data = that.reader.parse(data);

            that._pristine = isPlainObject(data) ? $.extend(true, {}, data) : data.slice(0);

            that._total = that.reader.total(data);

            if (that._aggregate && that.options.serverAggregates) {
                that._aggregateResult = that.reader.aggregates(data);
            }

            if (hasGroups) {
                data = that.reader.groups(data);
            } else {
                data = that.reader.data(data);
            }

            that._data = that._observe(data);

            var start = that._skip || 0,
            end = start + that._data.length;

            that._ranges.push({ start: start, end: end, data: that._data });
            that._ranges.sort( function(x, y) { return x.start - y.start; } );

            that._dequeueRequest();
            that._process(that._data);
        },

        _observe: function(data) {
            var that = this,
                model = that.reader.model,
                wrap = false;

            if (model && data.length) {
                wrap = !(data[0] instanceof model);
            }

            if (data instanceof ObservableArray) {
                if (wrap) {
                    data.type = that.reader.model;
                    data.wrapAll(data, data);
                }
            } else {
                data = new ObservableArray(data, that.reader.model);
            }

            return data.bind(CHANGE, proxy(that._change, that));
        },

        _change: function(e) {
            var that = this, idx, length, action = e ? e.action : "";

            if (action === "remove") {
                for (idx = 0, length = e.items.length; idx < length; idx++) {
                    if (!e.items[idx].isNew || !e.items[idx].isNew()) {
                        that._destroyed.push(e.items[idx]);
                    }
                }
            }

            if (that.options.autoSync && (action === "add" || action === "remove" || action === "itemchange")) {
                that.sync();
            } else {
                var total = that._total || that.reader.total(that._pristine);
                if (action === "add") {
                    total++;
                } else if (action === "remove") {
                    total--;
                } else if (action !== "itemchange" && !that.options.serverPaging) {
                    total = that.reader.total(that._pristine);
                }

                that._total = total;
                that._process(that._data, e);
            }
        },

        _process: function (data, e) {
            var that = this,
            options = {},
            result,
            hasGroups = that.options.serverGrouping === true && that._group && that._group.length > 0;

            if (that.options.serverPaging !== true) {
                options.skip = that._skip;
                options.take = that._take || that._pageSize;

                if(options.skip === undefined && that._page !== undefined && that._pageSize !== undefined) {
                    options.skip = (that._page - 1) * that._pageSize;
                }
            }

            if (that.options.serverSorting !== true) {
                options.sort = that._sort;
            }

            if (that.options.serverFiltering !== true) {
                options.filter = that._filter;
            }

            if (that.options.serverGrouping !== true) {
                options.group = that._group;
            }

            if (that.options.serverAggregates !== true) {
                options.aggregate = that._aggregate;
                that._aggregateResult = calculateAggregates(data, options);
            }

            result = process(data, options);

            that._view = result.data;

            if (result.total !== undefined && !that.options.serverFiltering) {
                that._total = result.total;
            }

            that.trigger(CHANGE, e);
        },

        at: function(index) {
            return this._data[index];
        },

        data: function(value) {
            var that = this;
            if (value !== undefined) {
                that._data = this._observe(value);

                that._total = that._data.length;

                that._process(that._data);
            } else {
                return that._data;
            }
        },

        view: function() {
            return this._view;
        },

        query: function(options) {
            var that = this,
            result,
            remote = that.options.serverSorting || that.options.serverPaging || that.options.serverFiltering || that.options.serverGrouping || that.options.serverAggregates;

            if (options !== undefined) {
                that._pageSize = options.pageSize;
                that._page = options.page;
                that._sort = options.sort;
                that._filter = options.filter;
                that._group = options.group;
                that._aggregate = options.aggregate;
                that._skip = options.skip;
                that._take = options.take;

                if(that._skip === undefined) {
                    that._skip = that.skip();
                    options.skip = that.skip();
                }

                if(that._take === undefined && that._pageSize !== undefined) {
                    that._take = that._pageSize;
                    options.take = that._take;
                }

                if (options.sort) {
                    that._sort = options.sort = normalizeSort(options.sort);
                }

                if (options.filter) {
                    that._filter = options.filter = normalizeFilter(options.filter);
                }

                if (options.group) {
                    that._group = options.group = normalizeGroup(options.group);
                }
                if (options.aggregate) {
                    that._aggregate = options.aggregate = normalizeAggregate(options.aggregate);
                }
            }

            if (remote || (that._data === undefined || that._data.length == 0)) {
                that.read(options);
            } else {
                that.trigger(REQUESTSTART);
                result = process(that._data, options);

                if (!that.options.serverFiltering) {
                    if (result.total !== undefined) {
                        that._total = result.total;
                    } else {
                        that._total = that._data.length;
                    }
                }

                that._view = result.data;
                that._aggregateResult = calculateAggregates(that._data, options);
                that.trigger(CHANGE);
            }
        },

        fetch: function(callback) {
            var that = this;

            if (callback && isFunction(callback)) {
                that.one(CHANGE, callback);
            }

            that._query();
        },

        _query: function(options) {
            var that = this;

            that.query(extend({}, {
                page: that.page(),
                pageSize: that.pageSize(),
                sort: that.sort(),
                filter: that.filter(),
                group: that.group(),
                aggregate: that.aggregate()
            }, options));
        },

        page: function(val) {
            var that = this,
            skip;

            if(val !== undefined) {
                val = math.max(math.min(math.max(val, 1), that.totalPages()), 1);
                that._query({ page: val });
                return;
            }
            skip = that.skip();

            return skip !== undefined ? math.round((skip || 0) / (that.take() || 1)) + 1 : undefined;
        },

        pageSize: function(val) {
            var that = this;

            if(val !== undefined) {
                that._query({ pageSize: val });
                return;
            }

            return that.take();
        },

        sort: function(val) {
            var that = this;

            if(val !== undefined) {
                that._query({ sort: val });
                return;
            }

            return that._sort;
        },

        filter: function(val) {
            var that = this;

            if (val === undefined) {
                return that._filter;
            }

            that._query({ filter: val, page: 1 });
        },

        group: function(val) {
            var that = this;

            if(val !== undefined) {
                that._query({ group: val });
                return;
            }

            return that._group;
        },

        total: function() {
            return this._total || 0;
        },

        aggregate: function(val) {
            var that = this;

            if(val !== undefined) {
                that._query({ aggregate: val });
                return;
            }

            return that._aggregate;
        },

        aggregates: function() {
            return this._aggregateResult;
        },

        totalPages: function() {
            var that = this,
            pageSize = that.pageSize() || that.total();

            return math.ceil((that.total() || 0) / pageSize);
        },

        inRange: function(skip, take) {
            var that = this,
            end = math.min(skip + take, that.total());

            if (!that.options.serverPaging && that.data.length > 0) {
                return true;
            }

            return that._findRange(skip, end).length > 0;
        },

        range: function(skip, take) {
            skip = math.min(skip || 0, this.total());
            var that = this,
            pageSkip = math.max(math.floor(skip / take), 0) * take,
            size = math.min(pageSkip + take, that.total()),
            data;

            data = that._findRange(skip, math.min(skip + take, that.total()));

            if (data.length) {
                that._skip = skip > that.skip() ? math.min(size, (that.totalPages() - 1) * that.take()) : pageSkip;

                that._take = take;

                var paging = that.options.serverPaging;
                var sorting = that.options.serverSorting;
                try {
                    that.options.serverPaging = true;
                    that.options.serverSorting = true;
                    that._process(data);
                } finally {
                    that.options.serverPaging = paging;
                    that.options.serverSorting = sorting;
                }

                return;
            }

            if (take !== undefined) {
                if (!that._rangeExists(pageSkip, size)) {
                    that.prefetch(pageSkip, take, function() {
                        if (skip > pageSkip && size < that.total() && !that._rangeExists(size, math.min(size + take, that.total()))) {
                            that.prefetch(size, take, function() {
                                that.range(skip, take);
                            });
                        } else {
                            that.range(skip, take);
                        }
                    });
                } else if (pageSkip < skip) {
                    that.prefetch(size, take, function() {
                        that.range(skip, take);
                    });
                }
            }
        },

        _findRange: function(start, end) {
            var that = this,
                ranges = that._ranges,
                range,
                data = [],
                skipIdx,
                takeIdx,
                startIndex,
                endIndex,
                rangeData,
                rangeEnd,
                processed,
                options = that.options,
                remote = options.serverSorting || options.serverPaging || options.serverFiltering || options.serverGrouping || options.serverAggregates,
                length;

            for (skipIdx = 0, length = ranges.length; skipIdx < length; skipIdx++) {
                range = ranges[skipIdx];
                if (start >= range.start && start <= range.end) {
                    var count = 0;

                    for (takeIdx = skipIdx; takeIdx < length; takeIdx++) {
                        range = ranges[takeIdx];

                        if (range.data.length && start + count >= range.start /*&& count + count <= range.end*/) {
                            rangeData = range.data;
                            rangeEnd = range.end;

                            if (!remote) {
                                processed = process(range.data, { sort: that.sort(), filter: that.filter() });
                                rangeData = processed.data;

                                if (processed.total !== undefined) {
                                    rangeEnd = processed.total;
                                }
                            }

                            startIndex = 0;
                            if (start + count > range.start) {
                                startIndex = (start + count) - range.start;
                            }
                            endIndex = rangeData.length;
                            if (rangeEnd > end) {
                                endIndex = endIndex - (rangeEnd - end);
                            }
                            count += endIndex - startIndex;
                            data = data.concat(rangeData.slice(startIndex, endIndex));

                            if (end <= range.end && count == end - start) {
                                return data;
                            }
                        }
                    }
                    break;
                }
            }
            return [];
        },

        skip: function() {
            var that = this;

            if (that._skip === undefined) {
                return (that._page !== undefined ? (that._page  - 1) * (that.take() || 1) : undefined);
            }
            return that._skip;
        },

        take: function() {
            var that = this;
            return that._take || that._pageSize;
        },

        prefetch: function(skip, take, callback) {
            var that = this,
            size = math.min(skip + take, that.total()),
            range = { start: skip, end: size, data: [] },
            options = {
                take: take,
                skip: skip,
                page: skip / take + 1,
                pageSize: take,
                sort: that._sort,
                filter: that._filter,
                group: that._group,
                aggregate: that._aggregate
            };

            if (!that._rangeExists(skip, size)) {
                clearTimeout(that._timeout);

                that._timeout = setTimeout(function() {
                    that._queueRequest(options, function() {
                        that.transport.read({
                            data: options,
                            success: function (data) {
                                that._dequeueRequest();
                                var found = false;
                                for (var i = 0, len = that._ranges.length; i < len; i++) {
                                    if (that._ranges[i].start === skip) {
                                        found = true;
                                        range = that._ranges[i];
                                        break;
                                    }
                                }
                                if (!found) {
                                    that._ranges.push(range);
                                }

                                data = that.reader.parse(data);
                                range.data = that._observe(that.reader.data(data));
                                range.end = range.start + range.data.length;
                                that._ranges.sort( function(x, y) { return x.start - y.start; } );
                                that._total = that.reader.total(data);
                                if (callback) {
                                    callback();
                                }
                            }
                        });
                    });
                }, 100);
            } else if (callback) {
                callback();
            }
        },

        _rangeExists: function(start, end) {
            var that = this,
            ranges = that._ranges,
            idx,
            length;

            for (idx = 0, length = ranges.length; idx < length; idx++) {
                if (ranges[idx].start <= start && ranges[idx].end >= end) {
                    return true;
                }
            }
            return false;
        }
    });

    DataSource.create = function(options) {
        options = options && options.push ? { data: options } : options;

        var dataSource = options || {},
        data = dataSource.data,
        fields = dataSource.fields,
        table = dataSource.table,
        select = dataSource.select,
        idx,
        length,
        model = {},
        field;

        if (!data && fields && !dataSource.transport) {
            if (table) {
                data = inferTable(table, fields);
            } else if (select) {
                data = inferSelect(select, fields);
            }
        }

        if (kendo.data.Model && fields && (!dataSource.schema || !dataSource.schema.model)) {
            for (idx = 0, length = fields.length; idx < length; idx++) {
                field = fields[idx];
                if (field.type) {
                    model[field.field] = field;
                }
            }

            if (!isEmptyObject(model)) {
                dataSource.schema = extend(true, dataSource.schema, { model:  { fields: model } });
            }
        }

        dataSource.data = data;

        return dataSource instanceof DataSource ? dataSource : new DataSource(dataSource);
    }

    function inferSelect(select, fields) {
        var options = $(select)[0].children,
            idx,
            length,
            data = [],
            record,
            firstField = fields[0],
            secondField = fields[1],
            value,
            option;

        for (idx = 0, length = options.length; idx < length; idx++) {
            record = {};
            option = options[idx];

            record[firstField.field] = option.text;

            value = option.attributes.value;

            if (value && value.specified) {
                value = option.value;
            } else {
                value = option.text;
            }

            record[secondField.field] = value;

            data.push(record);
        }

        return data;
    }

    function inferTable(table, fields) {
        var tbody = $(table)[0].tBodies[0],
        rows = tbody ? tbody.rows : [],
        idx,
        length,
        fieldIndex,
        fieldCount = fields.length,
        data = [],
        cells,
        record,
        cell,
        empty;

        for (idx = 0, length = rows.length; idx < length; idx++) {
            record = {};
            empty = true;
            cells = rows[idx].cells;

            for (fieldIndex = 0; fieldIndex < fieldCount; fieldIndex++) {
                cell = cells[fieldIndex];
                if(cell.nodeName.toLowerCase() !== "th") {
                    empty = false;
                    record[fields[fieldIndex].field] = cell.innerHTML;
                }
            }
            if(!empty) {
                data.push(record);
            }
        }

        return data;
    }

    extend(true, kendo.data, /** @lends kendo.data */ {
        readers: {
            json: DataReader
        },
        Query: Query,
        DataSource: DataSource,
        ObservableObject: ObservableObject,
        ObservableArray: ObservableArray,
        LocalTransport: LocalTransport,
        RemoteTransport: RemoteTransport,
        Cache: Cache,
        DataReader: DataReader,
        Model: Model
    });
})(jQuery);
