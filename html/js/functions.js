function formatBytes( bytes , precision=1){
    var units = ["B","Ko","Mo","Go","To","Po"];
    bytes = Math.max(bytes, 0);
    pow = Math.floor((bytes ? Math.log(bytes) : 0 ) / Math.log(1024));
    pow = Math.min(pow, units.length - 1);
    bytes /= Math.pow(1024,pow);
    return Math.round(bytes * Math.pow(10, precision)) / Math.pow(10, precision) +' '+units[pow];
}

function formatBitrate(bits) {
    if (typeof bits !== 'number') {
        return '';
    }
    if (bits >= 1000000000) {
        return (bits / 1000000000).toFixed(2) + ' Gbit/s';
    }
    if (bits >= 1000000) {
        return (bits / 1000000).toFixed(2) + ' Mbit/s';
    }
    if (bits >= 1000) {
        return (bits / 1000).toFixed(2) + ' kbit/s';
    }
    return bits.toFixed(2) + ' bit/s';            
}

function formatTime(seconds) {
    var date = new Date(seconds * 1000),
        days = Math.floor(seconds / 86400);
    days = days ? days + 'd ' : '';
    return days +
        ('0' + date.getUTCHours()).slice(-2) + ':' +
        ('0' + date.getUTCMinutes()).slice(-2) + ':' +
        ('0' + date.getUTCSeconds()).slice(-2);
}

function selectFile(fId){
    $(".file[data-f_id="+fId+"]").toggleClass("selected");
    if($(".file[data-f_id="+fId+"]").hasClass("selected")){
        selectedFile.push(fId);
    }
    else{
        selectedFile.splice(selectedFile.indexOf(fId), 1);
    }
    selectedFile = jQuery.unique(selectedFile);
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
