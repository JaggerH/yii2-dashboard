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

    function MessageTips(response) {
        $tips = $('<div class="dashboard-tips"></div>')
        if (response.success) {
            console.log(111)
            $tips.removeClass("tips-danger").addClass("tips-success")
        } else {
            console.log(222)
            $tips.removeClass("tips-success").addClass("tips-danger")
        }
        $tips.text(response.message)
        $('#dashboard-tips-handler').html($tips).collapse('show')
        setTimeout(function() {
            $('#dashboard-tips-handler').collapse('hide')
        }, 2000)
    }

    function dataLoad($target, that, method, url, data, params) {
        var params = $.extend(params, { _relativeTarget: that, url: url }),
            $loadingTip = $('<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; width: 40px; height: 40px; margin: auto;"> <i class="fa fa-spinner fa-spin" style="font-size: 40px; "></i></div>')

        var _loadEvent = $.Event('_load.dashboard', params)
        $target.trigger(_loadEvent)

        // 如果是create||update，不显示正在加载的图标
        if (params && !/\/\w+[\w+-]*\/(create|update)/.test(url)) {
            $target.html('').append($loadingTip)
        }

        $.ajax({
                url: url,
                type: method,
                data: data
            })
            .success(function(response) {
                if (params && params.form_submit == "create") {
                    if (typeof response == "object") {
                        url = url.replace("/create", "/update") + "?id=" + response.data.id
                        dataLoad($target, that, 'get', url, null, null)
                        listReload(response.data.id)
                        MessageTips(response)
                    } else {
                        MessageTips({ message: 'Create Failed!', success: false })
                        $target.html(response)
                    }
                } else if (params && params.form_submit == "update") {
                    var id = url.match(/\?id=(.*)/)[1]
                    if (typeof response == "object") {
                        listReload(id)
                        MessageTips(response)
                    } else {
                        MessageTips({ message: 'Update Failed!', success: false })
                        $target.html(response)
                    }
                } else {
                    $target.html(response)
                }
            })
            .fail(function(response) {
                if (response.status == 403) {
                    $target.html('<div class="text-center" style="margin-top: 200px;"><i class="fa fa-ban" style="font-size: 60px"></i><h3>Permission Denied</h3></div>')
                } else if (response.status == 404) {
                    $target.html('<div class="text-center" style="margin-top: 200px;"><h1>404</h1><h3>The Page requested NOT FOUND</h3></div>')
                }
            })
            .done(function(data) {
                var load_Event = $.Event('load_.dashboard', $.extend(params, { data: data }))
                $target.trigger(load_Event)
            })
    }

    function listReload(active_key) {
        var url = $('#dashboard-list').attr('current-url')

        function select() {
            $('#dashboard-list').off('load_.dashboard.reload')
            $('#dashboard-list .item[data-key="' + active_key + '"]').addClass('selected')
        }
        if (active_key != "null") {
            $('#dashboard-list').on('load_.dashboard.reload', select)
        }
        dataLoad($('#dashboard-list'), null, 'get', url, null, null)
    }

    $(document).on('click', '[data-load]', function(e) {
        // init 
        var url = $(this).data('url') == undefined ? $(this).attr('href') : $(this).data('url'),
            $target = $($(this).data('load')),
            method = 'get'
        if ($target.length == 0) return
        if (url == undefined) return

        // 根据Menu中的expanded设置，为.dashboard-main添加属性以达到控制list, content宽度的目的
        if ($(this).attr('expanded') == "true") {
            $target.parents('.dashboard-main').attr('expanded', 'true')
        } else {
            $target.parents('.dashboard-main').removeAttr('expanded')
        }

        dataLoad($target, this, method, url, null)

        if ($(this).is('a')) e.preventDefault() // prevent redirect
    })

    $(document).on('submit', 'form', function(e) {
        e.preventDefault()
        var data = {},
            url = $(this).attr("action"),
            method = $(this).attr("method"),
            $target = $($(this).data("load"))

        $(this).find('input[type="text"], input[type="password"], input[type="hidden"], select').each(function() {
            data[$(this).attr("name")] = $(this).val()
        })

        $(this).find('textarea').each(function() {
            data[$(this).attr("name")] = $(this).val()
        })

        if (/\/\w+[\w+-]*\/index/.test(url)) {
            dataLoad($target, this, method, url, data, { form_submit: "search" })
        } else if (/\/\w+[\w+-]*\/create/.test(url)) {
            dataLoad($('#dashboard-content'), this, method, url, data, { form_submit: "create" })
        } else if (/\/\w+[\w+-]*\/update/.test(url)) {
            dataLoad($('#dashboard-content'), this, method, url, data, { form_submit: "update" })
        }
    })

    /**------------------------------------------
     *  Dashboard back to before search handler
     ------------------------------------------*/
    // 对待search事件，在加载之前需要设定一个可读取的before-search url, 加载后为其添加data-load属性
    $(document).on('_load.dashboard', '#dashboard-list', function(e) {
        if (typeof e.form_submit != 'undefined') {
            if (e.form_submit == "search") {
                var before_url = $(this).attr('before-search')
                if (typeof before_url == 'undefined' || before_url == '') {
                    before_url = $(this).attr('current-url')
                    $(this).attr('before-search', before_url)
                }
            }
        } else {
            $(this).attr('current-url', e.url)
        }
    })

    $(document).on('load_.dashboard', '#dashboard-list', function(e) {
        if (typeof e.form_submit != 'undefined') {
            if (e.form_submit == "search") {
                var before_url = $(this).attr('before-search')
                $('#dashboard-list [action-bk2bsearch]').attr('data-load', '#dashboard-list')
                    .attr('data-url', before_url)
            }
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
                        dataLoad($('#dashboard-list'), this, 'get', url, null)
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

    /**------------------------------------------
     *       Dashboard resize listen
     ------------------------------------------*/
    // $(document).on('load_.dashboard', '#dashboard-list, #dashboard-content', function(e) {
    //     var $this = $(this)

    //     function resizeHeight() {
    //         if ($this.is('#dashboard-list')) {
    //             $this.find('.list-view').height($(window).height() - $this.find('.dashboard-header').height())
    //         } else {
    //             $this.height($(window).height() - $this.siblings('.dashboard-header').height())
    //             var $editor = $this.find('#quill-editor')
    //             if ($editor) {
    //                 $editor.height($(window).height() - $editor.offset().top - 30)
    //             }
    //         }
    //     }
    //     resizeHeight()
    //     $(window).on('resize.dashboard', function() {
    //         resizeHeight()
    //     })
    // })

    // $(document).on('_load.dashboard', '#dashboard-list, #dashboard-content', function(e) {
    //     var $this = $(this)
    //     $(window).off('resize.dashboard')
    // })
})
