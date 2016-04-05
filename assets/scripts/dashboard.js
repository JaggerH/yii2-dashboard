$(document).ready(function() {
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
    
    $(document).on('click', '[data-load]', function(e) {
        var url = $(this).data('url') == undefined ? $(this).attr('href') : $(this).data('url'),
            $target = $($(this).data('load'))
        if ($target.length == 0) return
        if (url == undefined) return

        var params = $(this).data('params'),
            params = typeof params == 'undefined' ? null : params,
            $loadingTip = $('<div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0; width: 40px; height: 40px; margin: auto;"> <i class="fa fa-spinner fa-spin" style="font-size: 40px; "></i></div>')

        var loadEvent = $.Event('load.dashboard')
        $target.trigger(loadEvent)

        $target.html('')
        $loadingTip.appendTo($target)

        $.get(url, params)
            .done(function(data) {
                $target.html(data)
                var loadedEvent = $.Event('loaded.dashboard')
                $target.trigger(loadedEvent)
            })

        if ($(this).is('a')) e.preventDefault() // prevent redirect
    })

    $(document).on('submit', 'form', function(e) {
        e.preventDefault()

    })

    $(document).on('loaded.dashboard', '#dashboard-list, #dashboard-content', function() {
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

    $(document).on('load.dashboard', '#dashboard-list, #dashboard-content', function() {
        var $this = $(this)
        $(window).off('resize.dashboard')
    })
})
