
function ajaxForm(constructor)
{
    return Object.assign({
        selector: ".cs-ajax-field",
        event: "click",
        selected: [],
        htmlTarget: "<div class='dialog-helper' title='modify'></div>",
        submitErrorHtml: "<p style='text-align: center;'>An error occured</p>",
        submitingHtml: "<p style='text-align: center;'>Submiting</p>",
        ajaxErrorHtml: "<p style='text-align: center;'>An error occured</p>",
        ajaxSourceProperty: "pathFrom",
        ajaxDataProperty: "value",
        ajaxSourceMethod: "GET",
        checkAuthenticated: undefined,
        select: function () {
            this.selected = $(this.selector).filter(function (index, element) {
                return !$(element).is("[disabled]");
            });
            return this.selected.length;
        },
        attachEvent : function () {
            var selfRef = this;
            
            this.selected.each(
                function (index, element) {
                    $(element).on(selfRef.event, {selfRef: selfRef}, function (event) {
                        event.data.selfRef.callback(event, event.data.selfRef);
                    });
                }
            );
        },
        callTop: function () {
        },
        callback: function (event, selfRef) {
            
            var target = event.currentTarget;

            var ajaxSource = target.getAttribute(event.data.selfRef.ajaxSourceProperty);
            var ajaxData = target.getAttribute(event.data.selfRef.ajaxDataProperty);
            
            var htmlTarget = $(selfRef.htmlTarget);
            $(htmlTarget).dialog({width: (screen.width*0.33)});
            
            if (selfRef.checkAuthenticated !== undefined) {
                $.ajax({
                    url: selfRef.checkAuthenticated,
                    async: false,
                    success: function (data) {
                        if (data === false) {
                            window.location.reload();
                            throw new Error("authentication fail - reload");
                        }
                    }
                });
            }
            
            $.ajax({
                url: ajaxSource,
                method: selfRef.ajaxSourceMethod,
                htmlTarget: htmlTarget,
                selfRef: selfRef,
                firstEvent: event,
            }).success(selfRef.ajaxSuccess)
            .error(selfRef.ajaxError);
        },
        run: function () {
            this.callTop();
            this.select();
            this.attachEvent();
        },
        relaxe: function () {
            var selfRef = this;

            this.selected.each(
                function (index, element) {
                    $(element).off(selfRef.event);
                }
            );
        },
        ajaxSuccess: function (data) {
            var ajaxCaller = this;
            $(this.htmlTarget).html(data);
            $(this.htmlTarget).find("form").each(function (index, element) {
                $(element).submit(function (event) {
                    event.preventDefault();
                    
                    ajaxCaller.selfRef.submit(ajaxCaller.selfRef, this, ajaxCaller.htmlTarget, ajaxCaller.firstEvent);
                    
                    return false;
                });
            });
        },
        submit: function (selfRef, form, htmlTarget, firstEvent) {
            
            $.ajax({
                type:form.method,
                data: $(form).serialize(),
                url:form.action,
                beforeSend: function () {
                    $(htmlTarget).html(selfRef.submitingHtml);
                },
                success: function (data) {
                    $(firstEvent.currentTarget).attr("value", data);
                    $(firstEvent.currentTarget).html(data);
                    $(htmlTarget).remove();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(htmlTarget).html(selfRef.submitErrorHtml+jqXHR.responseText);
                }
            });
            
        },
        ajaxError: function () {
            $(this.htmlTarget).html(this.selfRef.ajaxErrorHtml);
        }
    }, constructor);
}
