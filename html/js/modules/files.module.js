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
                                    $(".file[data-f_id="+fId+"]").remove();
                            }else{
                                    alert(fsData.data);
                            }
                        }
                    });
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
                    console.log(data);
                        var fsData = JSON.parse(data);
                        
                        if(fsData.code == 302){
                                $(".file[data-f_id="+fId+"]").remove();
                        }else{
                                alert(fsData.data);
                        }
                    }
                });
            }			
        }
    }
    
    removeTag( fId , tId){
	$(".file[data-f_id="+fId+"]").toggleClass("selected");    
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
    
    addTag( event ){
        if(selectedFile.length > 0){
            var input = event.target;
            if(event.which === 32 || event.which === 13){
            var tag = input.value.replace(/\s+/g,'');
                if(tag !== ""){
                    for(var i=0;i<selectedFile.length;i++){	
                        var formData = "api/file/index.php?a=addTag&p={\"f_id\":"+selectedFile[i]+",\"tag\":\""+tag+"\"}";                  
                        $.ajax({
                            url:formData,
                            type: 'GET',
                            success: function( data ){
                                var res = JSON.parse(data);		        	
                                if(res.code == 302){		        		

                                }
                                else{
                                        alert(res.data);
                                }
                                input.value = "";
                            },
                            error: function(){
                               input.value = "";
                            }
                        });       		
                    }
                }
            }
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

