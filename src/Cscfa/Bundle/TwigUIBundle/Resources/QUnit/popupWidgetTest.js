
//@require:../public/js/widget/popupWidget.js

var popupWidget = new PopupWidget();

assert.equal(
    popupWidget.getClassHidder(),
    "popup_widget_base_hidden",
    "popupWidget.getClassHidder() must return popup_widget_base_hidden"
);

assert.equal(
    popupWidget.getCloser(),
    ".popup_widget_close",
    "popupWidget.getClassHidder() must return .popup_widget_close"
);

assert.equal(
    popupWidget.getEventType(),
    "click",
    "popupWidget.getEventType() must return click"
);

assert.equal(
    popupWidget.getSelector(),
    ".popup_widget_base",
    "popupWidget.getEventType() must return .popup_widget_base"
);
