
function Route() {	

	this.surfHash = function(){
            hash = window.location.hash;
            if(hash === ""){
                hash = "#files";	        
            }	    
            this.loadModule( hash );
	}
        
        this.loadModule = function(hash){
            selectedFile = [];
            window.location.hash = hash;
            hashArray = hash.split('/');
            _MODULE = hashArray[0].replace('#','');
            action = hashArray[1];
            data = hashArray[2];
            this.resetPage();
            
            _TPL.buildActionMenu();
            _TPL.buildBreadcrumb(data);
            switch (_MODULE) {
                case "files":
                    _FILES.trash = false;
                    _FILES.displayFiles(data);
                    _FILES.displayTags(data);
                    break;
                case "trash":
                    _FILES.trash = true;
                     _FILES.displayFiles(data);
                     _FILES.displayTags(data);
                    break;
                case "logout":
                    this.logout();                    
                    break;
                default:
                    this.loadModule("#files");
                    break;
            }
        }
        
        this.logout = function(){
            var formData = "api/user/index.php?a=logout";                  
            $.ajax({
                url:formData,
                type: 'GET',
                success: function( data ){
                    var res = JSON.parse(data);		        	
                    if(res.code === 302){
                        window.location.hash = "login";
                        location.reload();
                    }
                    else{
                            alert(res.data);
                    }                
                }
            });  
            
        }
        
        this.addSearchTag = function ( tag ){    
	    hash = window.location.hash;
	    array = hash.split("/");
	    if(array[1] === "search" && array[2] !== ""){
	        tagArray = array[2].split(" ");        
	        if(tagArray.indexOf(tag) == -1){
	            hash = hash + " " + tag;
	        }
	    }
	    else if(array[1] === "search" && array[2] === ""){
	        hash = hash+tag;
	    }
	    else{
	        hash = hash+"/search/"+tag;
	    }
	    window.location.hash = hash;
	    this.loadModule(hash);
	}

	this.removeSearchTag = function ( tag ){    
	    hash = window.location.hash;
	    array = hash.split("/");
	    if(array[1] === "search" && array[2] !== ""){
	    	tagArray = array[2].split("%20");
	        index = tagArray.indexOf(tag); 
	        if(index > -1){
	            tagArray.splice(index,1);
	        }
	        tags = tagArray.join(" ");
	        hash = array[0]+"/"+array[1]+"/"+tags;
	    }    
	    window.location.hash = hash;
	    this.loadModule(hash);
	}
        
        this.resetPage = function(){
            $("#content .header .menu-action").html("");
            $("#content .header .breadcrumb").html("");
            $("#content .content").html("");
            $("#side-panel").html("");
        }
        
}