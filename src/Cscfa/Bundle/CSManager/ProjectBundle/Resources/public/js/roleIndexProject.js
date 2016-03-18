
$(".accordion").accordion({
    collapsible: true
});

function runFormSubmit(selector)
{
    $(selector).on("submit", function (event) {
        
        pageLoader.setLoading();
        
        $.ajax({type:event.currentTarget.method, data: $(this).serialize(), url:event.currentTarget.action,
            success: function (data) {
                pageLoader.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("<div title='Error'></div>").html(textStatus).dialog();
            }
        });
        
        event.preventDefault();
        return false;
    });
}

function updateRole(data)
{

    $(data.properties).each(function (index, element) {
        var mode = "";
        var add = "";
        var rem = "";
        var attr = "";
        if (data.mode) {
            mode = "allow";
            add = "btn-success";
            rem = "btn-danger";
            attr = data.type+".desallow";
        } else {
            mode = "desallow";
            rem = "btn-success";
            add = "btn-danger";
            attr = data.type+".allow";
        }
        $("button[owner='"+data.owner+"'][action='"+data.type+"."+mode+"'][data='"+element+"']").removeClass(rem);
        $("button[owner='"+data.owner+"'][action='"+data.type+"."+mode+"'][data='"+element+"']").addClass(add);
        $("button[owner='"+data.owner+"'][action='"+data.type+"."+mode+"'][data='"+element+"']").attr("action", attr);
    });
    console.log(data);
}

function modifyRole(owner, url, action, property)
{
    var element = {
        url: url,
        method: "PUT",
        owner: owner,
        action: action,
        property: property,
        beforeSend: function (xhr) {
        },
        success: function (data, textStatus, jqXHR) {
            updateRole(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
        },
        complete: function (jqXHR, textStatus) {
        },
        ajax: function () {
            return {
                url: this.url,
                method: this.method,
                data: { property: this.property, owner: this.owner, action: this.action },
                beforeSend: this.beforeSend,
                success: this.success,
                error: this.error,
                complete: this.complete
            };
        },
        send: function () {
            return $.ajax(this.ajax());
        }
    };
    
    return element;
}

function multiRoleChoice(event)
{
    event.preventDefault();
    var owner = $(event.currentTarget).attr("owner");
    var action = $(event.currentTarget).attr("action");
    var checked = $(".roleCheckbox[owner="+owner+"]:checked");
    
    var props = [];
    $(checked).each(function (index, element) {
        props.push($(element).attr("data"));
    });

    var mr = modifyRole(owner, roleModifyUrl, action, props);
    mr.complete = function (jqXHR, textStatus) {
        $(checked).prop("checked", false);
    };
    mr.send();
    
    $(".dropdown-toggle[aria-expanded=true]").attr("aria-expanded", "false").parent().removeClass("open");
    return false;
}

function changeCheckbox(event)
{
    event.preventDefault();
    var owner = $(event.currentTarget).attr("owner");
    var unchecked = $(".roleCheckbox[owner="+owner+"]:not(:checked)");
    $(".roleCheckbox[owner="+owner+"]:checked").prop("checked", false);
    $(unchecked).prop("checked", true);
    return false;
}

function checkCheckbox(event)
{
    event.preventDefault();
    var owner = $(event.currentTarget).attr("owner");
    $(".roleCheckbox[owner="+owner+"]").prop("checked", true);
    return false;
}

function uncheckCheckbox(event)
{
    event.preventDefault();
    var owner = $(event.currentTarget).attr("owner");
    $(".roleCheckbox[owner="+owner+"]").prop("checked", false);
    return false;
}

$(function () {
    runFormSubmit(".formAddUserContainer");
    
    $(".roleButton").on("click", function (event) {
        var property = [$(event.currentTarget).attr("data")];
        var owner = $(event.currentTarget).attr("owner");
        var action = $(event.currentTarget).attr("action");
        
        modifyRole(owner, roleModifyUrl, action, property).send();
    });
    
    $(".multipleActionButton").on("click", multiRoleChoice);
    $(".changeCheckbox").on("click", changeCheckbox);
    $(".checkCheckbox").on("click", checkCheckbox);
    $(".uncheckCheckbox").on("click", uncheckCheckbox);
});
