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
        $('#menu-sidebar [data-load].selected').removeClass("selected")
        $(this).addClass("selected")
    })

    // 主menu的data-load，点击后关闭其他展开的二级菜单，添加当前选项的selected
    $('#menu-sidebar > ul > li > [data-load]').click(function() {
        $('#menu-sidebar [data-load].selected').removeClass("selected")
        $('#menu-sidebar [aria-expanded="true"]').click()
        $(this).addClass("selected")
    })

    $(document).on('click', '#dashboard-list .item[data-load]', function() {
        $('#dashboard-list .item[data-load]').removeClass('selected')
        $(this).addClass('selected')
    })


    /**------------------------------------------
     *       Dashboard Navigation Controller
     ------------------------------------------*/

    function dataLoad($target, that, method, url, data, params) {
        var params = $.extend(params, { _relativeTarget: that, url: url }),
            $loadingTip = $('<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; width: 40px; height: 40px; margin: auto;"> <i class="fa fa-spinner fa-spin" style="font-size: 40px; "></i></div>')

        var _loadEvent = $.Event('_load.dashboard', params)
        $target.trigger(_loadEvent)

        $target.html('')
        if ($target.attr('id') == "dashboard-list") {
            $("#dashboard-content").html('')
        }
        $loadingTip.appendTo($target)

        $.ajax({
                url: url,
                type: method,
                data: data
            })
            .success(function(data) {
                $target.html(data)
            })
            .done(function(data) {
                var load_Event = $.Event('load_.dashboard', params)
                $target.trigger(load_Event)
            })
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
            $target = $($(this).data("load")),
            $loadingTip = $('<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; width: 40px; height: 40px; margin: auto;"> <i class="fa fa-spinner fa-spin" style="font-size: 40px; "></i></div>')

        $target.html('')

        $(this).find('input[type="text"], input[type="password"]').each(function() {
            data[$(this).attr("name")] = $(this).val()
        })

        dataLoad($target, this, method, url, data, {form_submit: true})
    })

    /**------------------------------------------
     *       Dashboard back to before search handler
     ------------------------------------------*/
    // 对待search事件，在加载之前需要设定一个可读取的before-search url, 加载后为其添加data-load属性
    $(document).on('_load.dashboard', '#dashboard-list', function(e) {
        //如果是由form_submit触发的load事件并且含有index的url，那么这是一个search事件
        if (typeof e.form_submit != 'undefined' && e.form_submit && /\/\w+[\w+-]*\/index/.test(e.url)) {
            var before_url = $(this).attr('before-search')
            if (typeof before_url == 'undefined' || before_url == '') {
                before_url = $(this).attr('current-url')
                $(this).attr('before-search', before_url)
            }
        } else {
            $(this).attr('current-url', e.url)
        }
    })

    $(document).on('load_.dashboard', '#dashboard-list', function(e) {
        // 如果是由form_submit触发的load事件并且含有index的url，那么这是一个search事件
        if (typeof e.form_submit != 'undefined' && e.form_submit && /\/\w+[\w+-]*\/index/.test(e.url)) {
            var before_url = $(this).attr('before-search')
            $('#dashboard-list [action-bk2bsearch]').attr('data-load', '#dashboard-list')
                    .attr('data-url', before_url)
        }
    })

    /**------------------------------------------
     *       Dashboard resize listen
     ------------------------------------------*/
    $(document).on('load_.dashboard', '#dashboard-list, #dashboard-content', function(e) {
        var $this = $(this)

        function resizeHeight() {
            if ($this.is('#dashboard-list')) {
                $this.find('.list-view').height($(window).height() - $this.find('.dashboard-header').height())
            } else {
                $this.height($(window).height() - $this.siblings('.dashboard-header'))
            }
        }
        resizeHeight()
        $(window).on('resize.dashboard', function() {
            resizeHeight()
        })
    })

    $(document).on('_load.dashboard', '#dashboard-list, #dashboard-content', function(e) {
        var $this = $(this)
        $(window).off('resize.dashboard')
    })
})
