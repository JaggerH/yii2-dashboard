/**
 *  Dashboard trigger event depend on situation:
 *  _load.dashboard
 *  load_.dashboard
 *  aftersubmit.dashboard { url: url, success: true|false, submit_type: create|update|index, submit_response: response } 
 *  unload.dashboard
 **/

function dataLoad($target, options) {
    var $loadingTip = $('<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; width: 40px; height: 40px; margin: auto;"> <i class="fa fa-spinner fa-spin" style="font-size: 40px; "></i></div>'),
        beforeLoadEvent = $.Event('_load.dashboard', { url: options.url }),
        afterLoadEvent = $.Event('load_.dashboard', { url: options.url })
        unloadEvent = $.Event('unload.dashboard')

    $target.trigger(unloadEvent)
    $target.trigger(beforeLoadEvent)
    if ($target.attr('id') == "dashboard-modal") {
        $target.modal('show')
        $target = $target.find('.modal-content')
    }
    $target.html('').append($loadingTip)
    $.ajax(options)
        .success(function(response) {
            $target.html(response)
            $target.trigger(afterLoadEvent)
        })
        .fail(function(response) {
            if ($target.attr('id') == "dashboard-modal") {
                $target = $target.find('.modal-content')
            }
            if (response.status == 403) {
                $target.html('<div class="text-center" style="margin-top: 200px;"><i class="fa fa-ban" style="font-size: 60px"></i><h3>Permission Denied</h3></div>')
            } else if (response.status == 404) {
                $target.html('<div class="text-center" style="margin-top: 200px;"><h1>404</h1><h3>The Page requested NOT FOUND</h3></div>')
            } else if (response.status == 500) {
                $target.html('<div class="text-center" style="margin-top: 200px;"><h1>500</h1><h3>There is a internal error, Contact Admin</h3></div>')
            }
        })
        .done(function(data) {
            $target.trigger(afterLoadEvent)
        })
}

$(document).ready(function() {
    /**------------------------------------------
     *       Dashboard Menu Controller
     ------------------------------------------*/
    $('nav .submenu')
        .on('show.bs.collapse', function(e) {
            $(e.target).parents('nav').find('[aria-expanded="true"]').click().end().end().parent().addClass('opened')
        })
        .on('hide.bs.collapse', function(e) {
            $(e.target).parent().removeClass('opened')
        })

    // .submenu的data-load，点击后添加selected
    $('#menu-sidebar .submenu [data-load]').click(function() {
        if ($(this).data('load') == "#dashboard-list") {
            $("#dashboard-content").html('')
        }
        $('#menu-sidebar [data-load].selected').removeClass("selected")
        $(this).addClass("selected")
    })

    // 主menu的data-load，点击后关闭其他展开的二级菜单，添加当前选项的selected
    $('#menu-sidebar > ul > li > [data-load]').click(function() {
        if ($(this).data('load') == "#dashboard-list") {
            $("#dashboard-content").html('')
        }
        $('#menu-sidebar [data-load].selected').removeClass("selected")
        $('#menu-sidebar [aria-expanded="true"]').click()
        $(this).addClass("selected")
    })

    /**------------------------------------------
     *       Dashboard List Controller
     ------------------------------------------*/
    $(document).on('click', '#dashboard-list .item[data-load]', function() {
        $('#dashboard-list .item[data-load]').removeClass('selected')
        $(this).addClass('selected')
    })


    /**------------------------------------------
     *       Dashboard Navigation Controller
     ------------------------------------------*/

    function Alert($target, response) {
        $tips = $('<div class="dashboard-tips"></div>')
        if (response.success || (response.code && response.code == 29999)) {
            $tips.addClass("tips-success")
        } else {
            $tips.addClass("tips-danger")
        }
        $tips.text(response.message)
        $handler = $target.attr('id') == "dashboard-modal" ? $('#modal-tips') : $('#content-tips')
        $handler.html($tips).collapse('show')
        setTimeout(function() {
            $handler.collapse('hide')
        }, 2000)
    }



    function reloadList(active_key) {
        var url = $('#dashboard-list').attr('current-url')

        function select() {
            $('#dashboard-list').off('load_.dashboard.reload')
            $('#dashboard-list .item[data-key="' + active_key + '"]').addClass('selected')
        }
        if (active_key != "null") {
            $('#dashboard-list').on('load_.dashboard.reload', select)
        }
        dataLoad($('#dashboard-list'), { url: url, type: 'get' })
    }

    $(document).on('click', '[data-load]', function(e) {
        if ($(this).is('a')) e.preventDefault() // prevent redirect
        var url = $(this).data('url') == undefined ? $(this).attr('href') : $(this).data('url'),
            $target = $($(this).data('load')),
            method = $(this).attr('data-method') ? $(this).attr('data-method') : 'get'
            // target ["#dashboard-list", "#dashboard-content", "#dashboard-modal"]
        if ($target.length == 0) return
        if (url == undefined) return

        // 根据Menu中的expanded设置，为.dashboard-main添加属性以达到控制list, content宽度的目的
        if ($(this).attr('expanded') == "true") {
            $target.parents('.dashboard-main').attr('expanded', 'true')
        } else {
            $target.parents('.dashboard-main').removeAttr('expanded')
        }
        // dataLoad($target, this, method, url, null)
        dataLoad($target, { url: url, type: method })
    })

    $(document).on('submit', 'form', function(e) {
        e.preventDefault()
        var data = {},
            url = $(this).attr("action"),
            method = $(this).attr("method") ? $(this).attr("method") : "post"

        if ($(this).parents('.dashboard-content').length != 0) {
            $target = $('#dashboard-content')
        } else if ($(this).parents('#dashboard-modal').length != 0) {
            $target = $('#dashboard-modal')
        } else if ($(this).parents('#dashboard-list').length != 0) {
            $target = $('#dashboard-list')
        } else {
            throw "Not Found data-load target"
        }

        $(this).find('input, select, textarea').each(function() {
            if (this.type == "checkbox") {
                if (this.checked) {
                    data[$(this).attr("name")] = $(this).val()
                }
            } else {
                data[$(this).attr("name")] = $(this).val()
            }
        })

        var matches = url.match(/\/\w+[\w+-]*\/(index|create|update)/)
        $.ajax({ url: url, type: method, data: data })
            .success(function(response) {
                if (typeof response == "object") {
                    if (response.success || (response.code && response.code == 29999)) {
                        var submitEvent = $.Event('aftersubmit.dashboard', { url: url, success: true, submit_type: matches[1], submit_response: response })
                        $target.trigger(submitEvent)
                        return Alert($target, response)
                    } else {
                        var submitEvent = $.Event('aftersubmit.dashboard', { url: url, success: false, submit_type: matches[1], submit_response: response })
                        $target.trigger(submitEvent)
                    }
                } else {
                    if ($target.attr('id') == "dashboard-modal") {
                        $target.find('.modal-content').html(response)
                    } else {
                        $target.html(response)
                    }
                    switch (matches[1]) {
                        case 'index':
                            var submitEvent = $.Event('aftersubmit.dashboard', { url: url, success: true, submit_type: matches[1] })
                            $target.trigger(submitEvent)
                            break;
                        case 'create':
                            var submitEvent = $.Event('aftersubmit.dashboard', { url: url, submit_type: matches[1], success: false })
                        case 'update':
                            var submitEvent = $.Event('aftersubmit.dashboard', { url: url, submit_type: matches[1], success: false })
                            $target.trigger(submitEvent)
                            Alert($target, { success: false, message: "更新失败！" })
                            break;
                    }
                }
            })
            .fail(function(response) {
                if ($target.attr('id') == "dashboard-modal") {
                    $target = $target.find('.modal-content')
                }
                if (response.status == 403) {
                    $target.html('<div class="text-center" style="margin-top: 200px;"><i class="fa fa-ban" style="font-size: 60px"></i><h3>Permission Denied</h3></div>')
                } else if (response.status == 404) {
                    $target.html('<div class="text-center" style="margin-top: 200px;"><h1>404</h1><h3>The Page requested NOT FOUND</h3></div>')
                } else if (response.status == 500) {
                    $target.html('<div class="text-center" style="margin-top: 200px;"><h1>500</h1><h3>There is a internal error, Contact Admin</h3></div>')
                }
            })
    })

    $(document).on('aftersubmit.dashboard', '#dashboard-modal', function(e) {
        if (e.success) {
            $('#dashboard-modal').modal('hide')
        }
    })

    $(document).on('aftersubmit.dashboard', '#dashboard-content', function(e) {
        if (e.success) {
            reloadList(e.submit_response.data.id)
            if (e.submit_type == "create") {
                // console.log(e)
                var url = e.url.replace(/\/create.*/, "/update") + "?id=" + e.submit_response.data.id
                dataLoad($(this), { url: url, method: 'get' })
            }
        }
    })

    /**------------------------------------------
     *  Dashboard back to before search handler
     ------------------------------------------*/
    // 对待search事件，在加载之前需要设定一个可读取的before-search url, 加载后为其添加data-load属性
    $(document).on('_load.dashboard', '#dashboard-list', function(e) {
        $(this).attr('current-url', e.url)
    })

    $(document).on('aftersubmit.dashboard', '#dashboard-list', function(e) {
        if (e.submit_type == "index") {
            $('#dashboard-list').attr('before-search', $(this).attr('current-url'))
            $('#dashboard-list [action-bk2bsearch]').attr('data-load', '#dashboard-list')
                .attr('data-url', $(this).attr('before-search'))
        }
    })

    /**------------------------------------------
     *  Dashboard Delete handler
     ------------------------------------------*/
    var origin_header;
    $(document).on('click.dashboard', '#dashboard-list .toolbar[multi-choose-mode]', function(e) {
        // 移除并保存原有dashboard-header
        // 注入新的dashboard-header
        // 为dashboard-list .item 添加多选按钮
        var $header = $('#dashboard-list .dashboard-header'),
            $items = $('#dashboard-list .list-view > .item')
        origin_header = $header.children().detach()
        $header.append('<a class="multi-choose" multi-choose> <div class="checkbox" data-toggle="checkbox" style="margin: 0;"> <input type="checkbox" name="role" value="ChooseAll"> <div class="checkbox-label"><i class="fa fa-check"></i></div> </div> </a>')
            .append('<a class="toolbar pull-right" cancel-multi-choose> <i class="fa fa-arrow-right"></i> </a>')
            .append('<a class="toolbar pull-right" multi-delete> <i class="fa fa-trash-o"></i> </a>')

        $items.each(function() {
            $(this).prepend('<a class="multi-choose"> <div class="checkbox" data-toggle="checkbox" style="margin: 0;"> <input type="checkbox" name="role"> <div class="checkbox-label"><i class="fa fa-check"></i></div> </div> </a>')
        })
    })

    $(document).on('click.dashboard', '#dashboard-list .toolbar[cancel-multi-choose]', function(e) {
        var $header = $('#dashboard-list .dashboard-header'),
            $items_choose = $('#dashboard-list .list-view > .item > .multi-choose')

        $header.children().remove()
        origin_header.each(function(index, el) {
            $(this).appendTo($header)
        })
        $items_choose.remove()
    })

    $(document).on('click.dashboard', '#dashboard-list .dashboard-header > [multi-choose]', function(e) {
        var status = $(this).find('[type="checkbox"]')[0].checked,
            toggle = status ? "on" : "off"
        $('#dashboard-list .list-view > .item > .multi-choose > .checkbox').checkbox(toggle)
    })

    $(document).on('click.dashboard', '#dashboard-list .dashboard-header > [multi-delete]', function(e) {
        $(this).confirm({
            confirm: function() {
                var finish_delete = false

                    !(function() {
                    $('#dashboard-list .list-view > .item > .multi-choose > .checkbox').each(function() {
                        var checked = $(this).find('[type="checkbox"]')[0].checked
                        if (checked) {
                            var delete_url = $(this).parents('.item').data('delete-url')
                            $.post(delete_url)
                        }
                    })
                    finish_delete = true
                })()

                var refresh = setInterval(function() {
                    // reload after delete
                    if (finish_delete) {
                        var url = $('#dashboard-list').attr('current-url')
                        dataLoad($('#dashboard-list'), { url: url, type: 'get' })
                        clearInterval(refresh)
                    }
                }, 200)
            }
        })
        $(this).confirm('show')
    })

    $(document).on('click.dashboard', '#dashboard-list .item > .multi-choose', function(e) {
        e.stopPropagation()
    })

    $(document).on('click.dashboard', '[action-logout]', function(e) {
        $.post('site/logout').done(function(e) {
            window.location.path = '/dashboard'
        })
    })
})
