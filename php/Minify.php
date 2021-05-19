<?php
    require dirname(__DIR__,1)."/vendor/autoload.php";
     
    /*
        CSS
    */

    $minCSS = new \MatthiasMullie\Minify\css();

    $minCSS->add(dirname(__DIR__,1)."/css/font-awesome.min.css");
    $minCSS->add(dirname(__DIR__,1)."/css/bootplus.css");
    $minCSS->add(dirname(__DIR__,1)."/css/jquery-confirm.css");
    $minCSS->add(dirname(__DIR__,1)."/css/bootplus-responsive.css");
    $minCSS->add(dirname(__DIR__,1)."/css/docs.css");
    $minCSS->add(dirname(__DIR__,1)."/css/menu_front.css");
    $minCSS->add(dirname(__DIR__,1)."/css/jquery.dataTables.css");
    $minCSS->add(dirname(__DIR__,1)."/css/dataTables.tableTools.css");
    $minCSS->add(dirname(__DIR__,1)."/css/responsive.dataTables.min.css");
    $minCSS->add(dirname(__DIR__,1)."/css/datepicker3.css");
    $minCSS->add(dirname(__DIR__,1)."/css/jquery-ui.css");
    $minCSS->add(dirname(__DIR__,1)."/css/selectric.css");
    $minCSS->add(dirname(__DIR__,1)."/css/pnotify.custom.min.css");
    $minCSS->add(dirname(__DIR__,1)."/plugins/selectize/css/selectize.default.css");
    $minCSS->add(dirname(__DIR__,1)."/css/dropdown.css");
    $minCSS->add(dirname(__DIR__,1)."/css/padrao.css");
    $minCSS->add(dirname(__DIR__,1)."/plugins/Font-Awesome-5.15.3/css/all.min.css");

    $minCSS->minify(dirname(__DIR__,1)."/style.min.css");


    /*/
        JS
    */

    $minJS = new \MatthiasMullie\Minify\JS();

    $minJS->add(dirname(__DIR__,1)."/js/jquery.js");
    $minJS->add(dirname(__DIR__,1)."/js/jquery-1.10.2.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootbox.js");
    $minJS->add(dirname(__DIR__,1)."/js/jquery.selectric.js");
    $minJS->add(dirname(__DIR__,1)."/js/jquery-ui.js");
    $minJS->add(dirname(__DIR__,1)."/js/jquery.maskMoney.js");
    $minJS->add(dirname(__DIR__,1)."/js/jquery.maskedinput.js");
    $minJS->add(dirname(__DIR__,1)."/js/jquery.validate.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-transition.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-alert.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-modal.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-dropdown.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-scrollspy.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-tab.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-tooltip.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-popover.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-button.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-collapse.js");
    $minJS->add(dirname(__DIR__,1)."/js/bootstrap-typeahead.js");
    $minJS->add(dirname(__DIR__,1)."/plugins/selectize/js/standalone/selectize.min.js");
    $minJS->add(dirname(__DIR__,1)."/js/mindmup-editabletable.js");
    $minJS->add(dirname(__DIR__,1)."/js/jquery.dataTables.js");
    $minJS->add(dirname(__DIR__,1)."/plugins/datatables/jquery.dataTables.min.js");
    $minJS->add(dirname(__DIR__,1)."/js/dataTables.tableTools.js");
    $minJS->add(dirname(__DIR__,1)."/plugins/datatables/buttons/js/dataTables.buttons.js");
    $minJS->add(dirname(__DIR__,1)."/plugins/datatables/buttons/js/buttons.flash.js");
    $minJS->add(dirname(__DIR__,1)."/js/jszip.min.js");
    $minJS->add(dirname(__DIR__,1)."/js/pdfmake.min.js");
    $minJS->add(dirname(__DIR__,1)."/js/vfs_fonts.js");
    $minJS->add(dirname(__DIR__,1)."/plugins/datatables/buttons/js/buttons.html5.js");
    $minJS->add(dirname(__DIR__,1)."/plugins/datatables/buttons/js/buttons.print.js");
    $minJS->add(dirname(__DIR__,1)."/js/AutoSuggest.js");
    $minJS->add(dirname(__DIR__,1)."/js/nicEdit.js");
    $minJS->add(dirname(__DIR__,1)."/js/pnotify.custom.min.js");
    $minJS->add(dirname(__DIR__,1)."/js/jquery-confirm.js");
    $minJS->add(dirname(__DIR__,1)."/js/cte.js");
    $minJS->add(dirname(__DIR__,1)."/js/meusmetodos.js");
    $minJS->add(dirname(__DIR__,1)."/js/metodo_mercado.js");
    $minJS->add(dirname(__DIR__,1)."/js/metodo_leitora.js");
    $minJS->add(dirname(__DIR__,1)."/js/editetable.js");
    $minJS->add(dirname(__DIR__,1)."/js/sortable.js");

    $minJS->minify(dirname(__DIR__,1)."/scripts.min.js");

?>