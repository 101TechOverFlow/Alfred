

function selectFile(fId){
    $(".file[data-f_id="+fId+"]").toggleClass("selected");
    if($(".file[data-f_id="+fId+"]").hasClass("selected")){
        selectedFile.push(fId);
    }
    else{
        selectedFile.splice(selectedFile.indexOf(fId), 1);
    }
    console.log(selectedFile);
}

function detailFile(fId){
    $(".file[data-f_id="+fId+"]").toggleClass("selected");
    $(".file[data-f_id="+fId+"]").toggleClass("active");
    if($(".file[data-f_id="+fId+"]").hasClass("active")){
        $(".file[data-f_id="+fId+"] .file-settings-icon i").removeClass("fa-caret-right");
        $(".file[data-f_id="+fId+"] .file-settings-icon i").addClass("fa-caret-left");
    }
    else{
        $(".file[data-f_id="+fId+"] .file-settings-icon i").removeClass("fa-caret-left");
        $(".file[data-f_id="+fId+"] .file-settings-icon i").addClass("fa-caret-right");        
    }
}
