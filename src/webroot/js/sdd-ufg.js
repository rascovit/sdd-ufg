$(document).ready(function() {
    $('.datepicker').datepicker({
        language: 'pt-BR'
    });

    $('.sidebar-menu .treeview a').click(function() {
        var li = $(this).parent();
        var menu = li.parent().parent().hasClass('treeview') ? li.parent().parent() :
            (li.hasClass('treeview') ? li : null);

        if(li.hasClass('treeview') && li.hasClass('active')) {
            sessionStorage.removeItem('menu-tree');
            sessionStorage.removeItem('active-submenu');
        } else {
            sessionStorage.setItem('menu-tree', menu.index());

            if(li.parent().hasClass('treeview-menu')) {
                sessionStorage.setItem('active-submenu', li.index());
            } else {
                sessionStorage.removeItem('active-submenu');
            }
        }
    });

    var menuTree = sessionStorage.getItem('menu-tree');
    if(menuTree !== null) {
        var used = $('.sidebar-menu').children().eq(menuTree);
        used.addClass('active').find('.treeview-menu').addClass('menu-open').css('display', 'block');

        var submenu = sessionStorage.getItem('active-submenu');
        if(submenu != null) {
            used.find('.treeview-menu').children().eq(submenu).addClass('active');
        }
    }

    $(".timepicker").timepicker({
      showInputs: false,
      minuteStep: 5,
      showMeridian: false
    });
});
