/**
 * 
 * This file is a part of CSCFA TwigUi project.
 * 
 * @author Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license http://opensource.org/licenses/MIT MIT
 * @version 1.0.0
 * @link http://cscfa.fr
 * @module TwigUi/widget/popupWidget
 */

PopupWidget = function() {
    this.selector = ".popup_widget_base";
    this.closer = ".popup_widget_close";
    this.eventType = "click";
    this.classHidder = "popup_widget_base_hidden";
}

PopupWidget.prototype.process = function() {
    var popupsWidgets = $(this.getSelector());
    var selfRef = this;

    if (popupsWidgets.length == 0) {
        return false;
    }

    for (var i = 0; i < popupsWidgets.length; i++) {
        $(popupsWidgets[i]).find(this.getCloser()).on(selfRef.getEventType(), {
            parent : popupsWidgets[i],
            current : selfRef
        }, selfRef.eventFunction);
    }
}

PopupWidget.prototype.eventFunction = function(event) {
    var parent = event.data.parent;
    var current = event.data.current;

    $(parent).addClass(current.getClassHidder());
    return false;
}

PopupWidget.prototype.getClassHidder = function() {
    return this.classHidder;
}

PopupWidget.prototype.setClassHidder = function(classHidder) {
    this.classHidder = classHidder;
    return this;
}

PopupWidget.prototype.getEventType = function() {
    return this.eventType;
}

PopupWidget.prototype.setEventType = function(eventType) {
    this.eventType = eventType;
    return this;
}

PopupWidget.prototype.getSelector = function() {
    return this.selector;
}

PopupWidget.prototype.setSelector = function(selector) {
    this.selector = selector;
    return this;
}

PopupWidget.prototype.getCloser = function() {
    return this.closer;
}

PopupWidget.prototype.setCloser = function(closer) {
    this.closer = closer;
    return this;
}
