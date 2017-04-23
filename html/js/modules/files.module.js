class Files {
    
    constructor(){
        this.trash = false;
        this.files = [];
    }
    
    delete() {
        if(selectedFile.length > 0){
            var r = confirm("Do you want to delete these files ?");
            if(r){
                for(var i=0;i<selectedFile.length;i++){	
                    var fId = selectedFile[i];
                    var formData = "api/file/index.php?a=delete&p={\"f_id\":"+fId+"}";
                    $.ajax({
                    url:formData,
                    type: 'GET',
                    success: function( data ){
                            var fsData = JSON.parse(data);                   
                            if(fsData.code == 302){                                   
                            }else{
                                    alert(fsData.data);
                            }
                        }
                    });
                    $(".file[data-f_id="+fId+"]").remove();
                    selectedFile = [];
                }
            }
        }
    }
    
    restore() {
        if(selectedFile.length > 0){				
            for(var i=0;i<selectedFile.length;i++){	
                var fId = selectedFile[i];
                var formData = "api/file/index.php?a=restore&p={\"f_id\":"+fId+"}";				
                $.ajax({
                url:formData,
                type: 'GET',
                success: function( data ){
                        var fsData = JSON.parse(data);                        
                        if(fsData.code == 302){                                
                        }else{
                                alert(fsData.data);
                        }
                    }
                });
                $(".file[data-f_id="+fId+"]").remove();
                selectedFile = [];
            }			
        }
    }
    
    destroy() {
        if(selectedFile.length > 0){
            var r = confirm("Do you want to destroy these files ?");
            if(r){
                    for(var i=0;i<selectedFile.length;i++){	
                            var fId = selectedFile[i];
                            var formData = "api/file/index.php?a=destroy&p={\"f_id\":"+fId+"}";
                            $.ajax({
                            url:formData,
                            type: 'GET',
                            success: function( data ){
                                var fsData = JSON.parse(data);                            
                                if(fsData.code == 302){                                    
                                }else{
                                    alert(fsData.data);
                                }
                            }
                        });
                        $(".file[data-f_id="+fId+"]").remove();
                        selectedFile = [];
                    }
            }
        }
    }
    
    download(){
        if(selectedFile.length > 0){
            for(var i=0;i<selectedFile.length;i++){
                window.open("api/file/index.php?a=download&p={\"f_id\":"+selectedFile[i]+"}");				
            }
        }
        selectedFile = [];
        $(".file.selected").css('box-shadow', '').removeClass("selected");
    }
    
    removeTag( fId , tId){
	var r = confirm("Remove this tag ?");
        if(r){
            var formData = "api/file/index.php?a=removeTag&p={\"f_id\":"+fId+",\"t_id\":"+tId+"}";	    	
             $.ajax({
                url:formData,
                type: 'GET',
                success: function( data ){
                        var res = JSON.parse(data);
                        if(res.code == 302){		            
                                $(".file[data-f_id="+fId+"] .tag[data-t_id="+tId+"]").remove();
                        }
                        else{
                                alert(res.data);
                        }
                }
            });
        }    	
    }
    
    addTag( tag ){
        if(selectedFile.length > 0){
            for(var i=0;i<selectedFile.length;i++){
                var formData = "api/file/index.php?a=addTag&p={\"f_id\":"+selectedFile[i]+",\"tag\":\""+tag+"\"}";                  
                $.ajax({
                    url:formData,
                    type: 'GET',
                    success: function( data ){
                        var res = JSON.parse(data);		        	
                        if(res.code === 302){		        		

                        }
                        else{
                                alert(res.data);
                        }                
                    }
                });       		
            }
            $(".edit-tag input").val("");
            $(".edit-tag .dropdown-content").html("");
            $(".edit-tag .dropdown-content").hide();
        }
    }
    
    share( g_id ){
        if(selectedFile.length > 0){
            for(var i=0;i<selectedFile.length;i++){
                var formData = "api/file/index.php?a=share&p={\"f_id\":"+selectedFile[i]+",\"g_id\":"+g_id+",\"rule\":\"read\"}";                  
                $.ajax({
                    url:formData,
                    type: 'GET',
                    success: function( data ){
                        var res = JSON.parse(data);		        	
                        if(res.code === 302){		        		

                        }
                        else{
                                alert(res.data);
                        }                
                    }
                });       		
            }
            $(".edit-share input").val("");
            $(".edit-share .dropdown-content").html("");
            $(".edit-share .dropdown-content").hide();
        }
    }
    
    displayFiles(search=""){
        var formData;
        if(this.trash){
            formData = "api/file/index.php?a=searchFiles&p={\"trash\":1}";
            if(search != ""){
                search=search.replace("%20"," ").split(" ").join("\",\"");
                formData = "api/file/index.php?a=searchFiles&p={\"trash\":1,\"query\":[\""+search+"\"]}";			
            }
        }
        else{
            formData = "api/file/index.php?a=searchFiles";
            if(search != ""){
                search=search.replace("%20"," ").split(" ").join("\",\"");
                formData = "api/file/index.php?a=searchFiles&p={\"query\":[\""+search+"\"]}";			
            }
        }      		
        
        $.ajax({
            url:formData,
            type: 'GET',
            success: function( data ){	        
                    var fsData = JSON.parse(data);	     	        	
                    if(fsData.code === 302){
                        _TPL.buildFiles(fsData)
                    }
                    else{
                        alert(fsData.data);
                    }	        				        	
            }
        });
    }
   
    displayTags(search=""){
        var formData;
        if(this.trash){
            formData = "api/file/index.php?a=searchTags&p={\"tash\":1}";
            if(search != ""){
                search=search.replace("%20"," ").split(" ").join("\",\"");
                formData = "api/file/index.php?a=searchTags&p={\"trash\":1,\"query\":[\""+search+"\"]}";			
            }
        }
        else{
            formData = "api/file/index.php?a=searchTags";
            if(search != ""){
                search=search.replace("%20"," ").split(" ").join("\",\"");
                formData = "api/file/index.php?a=searchTags&p={\"query\":[\""+search+"\"]}";			
            }
        }
        
        $.ajax({
            url:formData,
            type: 'GET',
            success: function( data ){	        
                var fsData = JSON.parse(data);
                    if(fsData.code === 302){
                        _TPL.buildTags(fsData)
                    }
                    else{
                        alert(fsData.data);
                    }	        				        	
            }
        });
    }
    
}

