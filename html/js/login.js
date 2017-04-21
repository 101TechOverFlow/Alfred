$(function(){
   
   $("button.cancelbtn").click(function(){
       $(".content input").val("");
   });
   
   $("button[type='submit']").click(function(){
       username = $("input[name='username']").val();       
       password = $("input[name='password']").val();
       if(username === ""){
           $("input[name='username']").addClass("required");
       }
       if(password === ""){
           $("input[name='password']").addClass("required");
       }
       
       if(password !== "" && username !== ""){
           formData = "api/user/index.php?a=login&p={\"username\":\""+username+"\",\"password\":\""+password+"\"}";
           $.ajax({
	        url:formData,
	        type: 'GET',
	        success: function( data ){	        
                    fsData = JSON.parse(data);	     	        	
                    if(fsData.code === 302){				
                        location.reload();
                    }
                    else{
                        $("input[name='username']").addClass("required");
                        $("input[name='password']").addClass("required");
                        alert(fsData.data);
                    }	        				        	
	        }
           });
       }
   });
   
});