$(function () {

    function valid(type)
    {
        $("#" + type + "LoadingImg").addClass("imgDN");
        $("#" + type + "UnvalidImg").addClass("imgDN");
        $("#" + type + "ValidImg").removeClass("imgDN");
    }

    function unvalid(type)
    {
        $("#" + type + "LoadingImg").addClass("imgDN");
        $("#" + type + "ValidImg").addClass("imgDN");
        $("#" + type + "UnvalidImg").removeClass("imgDN");
    }

    var vendorDir = null;
    var srcDir = $.get("qunit/search/src").done(function () {
        valid("src");
    }).fail(function () {
        unvalid("src");
    }).always(function () {
        $("#vendorTestSearch").removeClass("infoParaDN");
        vendorDir = $.get("qunit/search/vendor").done(function () {
            valid("vendor");
        }).fail(function () {
            unvalid("vendor");
        }).always(function () {

            var srcDirectories = null;
            var vendorDirectories = null;
            if (srcDir.status == 200) {
                srcDirectories = JSON.parse(srcDir.responseText);
            } else {
                srcDirectories = [];
            }

            if (vendorDir.status == 200) {
                vendorDirectories = JSON.parse(vendorDir.responseText);
            } else {
                vendorDirectories = [];
            }

            var completeDirectories = srcDirectories.concat(vendorDirectories);

            $("#scriptTestSearch").removeClass("infoParaDN");
            var script = $.post("qunit/test", {
                paths : completeDirectories
            }).done(function () {
                $("#loader").remove();
                $("#qunit").removeClass("qunitDiv");
                $("#qunit-fixture").removeClass("qunitDiv");
                $("#script").html(script.responseText);
            }).fail(function () {
                unvalid("script");
                $("#loadImg").addClass("imgDN");
                $("#errImg").removeClass("imgDN");
            });
        });
    });
});
