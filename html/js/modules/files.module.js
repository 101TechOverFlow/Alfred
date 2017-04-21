class Files {
    
    constructor(){
        this.trash = false;
        this.files = [];
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

