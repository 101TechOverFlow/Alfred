class Templates{
    
    render(html, options) {
        var re = /<%([^%>]+)?%>/g, reExp = /(^( )?(if|for|else|switch|case|break|{|}))(.*)?/g, code = 'var r=[];\n', cursor = 0, match;
        var add = function(line, js) {
            js? (code += line.match(reExp) ? line + '\n' : 'r.push(' + line + ');\n') :
                (code += line != '' ? 'r.push("' + line.replace(/"/g, '\\"') + '");\n' : '');
            return add;
        }
        while(match = re.exec(html)) {
            add(html.slice(cursor, match.index))(match[1], true);
            cursor = match.index + match[0].length;
        }
        add(html.substr(cursor, html.length - cursor));
        code += 'return r.join("");';
        return new Function(code.replace(/[\r\t\n]/g, '')).apply(options);
    }
    
    buildAutocompleteTag(tags){
        var url_file_tpl = "html/templates/files/autocompleteTag.php";
        var target =".edit-tag .dropdown-content";
        $.get(
            url_file_tpl, 
            function(template){                     
                 $(target).html(_TPL.render(template,tags));
            }
        );
    }
    
    buildAutocompleteShare(shares){
        var url_file_tpl = "html/templates/files/autocompleteShare.php";
        var target =".edit-share .dropdown-content";
        $.get(
            url_file_tpl, 
            function(template){                     
                 $(target).html(_TPL.render(template,shares));
            }
        );
    }
    
    buildActionMenu(){
        var url ="";
        var target ="#content .header .menu-action";
        switch (_MODULE) {
                case "files":
                    url = "html/templates/files/menuaction.file.php";
                    break;
                case "trash":
                    url = "html/templates/files/menuaction.trash.php";
                    break;
        }
        if(url !== ""){
            $.get(
                url, 
                function(template){           
                    $(target).append(template);
                }
            );
        }        
    }
    
    buildFiles(files){
        var url_file_tpl = "html/templates/files/file.php";
        var target ="#content .content";
        $.get(
            url_file_tpl, 
            function(template){                     
                 $(target).append(_TPL.render(template,files));
            }
        );        
    }
    
    buildBreadcrumb(hash_data){
        var tags = {};
        if(hash_data !== undefined){
            var data=hash_data.replace("%20"," ");
            if(data !== ""){
                tags.data = data.split(" ");
                $.get(
                    "html/templates/files/breadcrumb.php", 
                    function(template){                     
                         $("#content .header .breadcrumb").html(_TPL.render(template,tags));
                    }
                ); 
                
            }    
        }
    }
    
    buildTags(tags){
        var url_file_tpl = "html/templates/files/tags.php";
        var target ="#side-panel";
        $.get(
            url_file_tpl, 
            function(template){                     
                 $(target).append(_TPL.render(template,tags));
            }
        ); 
    }
}

