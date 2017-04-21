var _PICTURES = ["image/jpeg","image/png","image/gif"];
var _ROUTE = new Route();
var _TPL = new Templates();
var _FILES = new Files();

var _MODULE;
var selectedFile = [];


$(function(){
    _ROUTE.surfHash();
    
        // Allow dropdown of the header
    $("ul.top-nav>li").click(function(e){       
        $("ul.top-nav>li").removeClass("active");
        $(this).addClass("active");
        e.stopPropagation();
    });

    // Allow to hide dropdown when click somewhere else
    $(document).on("click", function(e) {
        $("#search input").val("");
        if ($(e.target).parents('li.active').length == 0) {
            $("ul.top-nav>li").removeClass("active");
        }

        if($(e.target).parents(".edit-tag.active").length == 0){
            $(".edit-tag").removeClass("active");
            $(".content .file.selected").css('box-shadow', '');
        }

    });

    $(document).on("mouseenter", '.menu-action i', function(){
        if(!$(".menu-action .edit-tag").hasClass("active")){ 
            if($(this).hasClass("fa-trash-o")){
                $(".content .file.selected").css('box-shadow', 'rgba(231, 76, 60, 0.75) 0px 0px 0px 4px');
            }
            if($(this).hasClass("fa-share-alt")){
                $(".content .file.selected").css('box-shadow', 'rgba(155,89,182, 0.75) 0px 0px 0px 4px');
            }
            if($(this).hasClass("fa-cloud-download")){
                $(".content .file.selected").css('box-shadow', 'rgba(76,175,80, 0.75) 0px 0px 0px 4px');
            }
            if($(this).hasClass("fa-edit")){
                $(".content .file.selected").css('box-shadow', 'rgba(230,138,0, 0.75) 0px 0px 0px 4px');
            }
        }
    });

    $(document).on("click", '.menu-action i.fa-edit', function(e){
        $(".edit-tag input").val("");
        $(".edit-tag").toggleClass("active");
        $(".edit-tag input").focus();
        $(".content .file.selected").css('box-shadow', 'rgba(230,138,0, 0.75) 0px 0px 0px 4px');
        e.stopPropagation();        
    });

    $(document).on("mouseleave", '.menu-action i', function(){
        if(!$(".menu-action .edit-tag").hasClass("active")){
            $(".content .file.selected").css('box-shadow', ''); 
        }
               
    });

    // Search Bar :
    $("#search input").keyup(function(e){
        if(e.keyCode == 13 || e.keyCode == 32){
            var val = $(this).val();
            val = val.replace(" ","").replace("%20","");
            _ROUTE.addSearchTag(val);
            $(this).val("");
        }
    });
    
});
