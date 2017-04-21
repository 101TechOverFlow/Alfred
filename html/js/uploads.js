$(function(){
    
    var ul = $('form#upload ul');

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#wrapper'),
        maxChunkSize: 10000000,
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
            $("li#uploads").addClass("active");

            var tpl = $("<li class=\"working\">"
			+"<span class=\"content\">"+data.files[0].name+" (" + formatBytes(data.files[0].size) + ") </span>"
			+"<span class=\"closebtn\">&times;</span>"
			+"<span class=\"time\"></span>"
			+"</br>"
			+"<div class=\"transfert\"><div class=\"progress\"></div></div>"
		+"</li>");
           
            data.context = tpl.appendTo(ul);

            
            tpl.find('span.closebtn').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                    if(ul.html() == ""){  
                        $("#uploads").removeClass("active");
                        $("#uploads i").removeClass("fa-circle-o-notch fa-spin");
                        $("#uploads i").addClass("fa-upload");
                    }                    
                });
                

            });
            
            r = confirm("start upload ?");     
            if(r){
                $("#uploads i").removeClass("fa-upload");
                $("#uploads i").addClass("fa-circle-o-notch fa-spin");                
                var jqXHR = data.submit();
            }
            
        },
        done: function (e, data){
            if(data.result == "success"){
                setTimeout(function(){                    
                $(data.context).remove();
                if(ul.html() == ""){     
                $("#uploads").removeClass("active");
                $("#uploads i").removeClass("fa-circle-o-notch fa-spin");
                $("#uploads i").addClass("fa-upload");
                
                    setTimeout(function(){                        
                        hash = window.location.hash;
                        _ROUTE.loadModule(hash);
                    },500);
                }
                },1000);
            }
            else {
                data.context.addClass('error');
                alert(data.result);
            }                       
        },
        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);
            console.log(data);

            data.context.find("span.time").html("("+progress+"% "+formatBitrate(data.bitrate)+" "+formatTime((data.total - data.loaded) * 8 / data.bitrate)+")");

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('.transfert .progress').width(progress+"%");
            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });
});