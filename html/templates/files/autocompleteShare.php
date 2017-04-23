<%for(var i in this.data) {%>
    <div class="tag add" onclick="javascript:_FILES.share('<%this.data[i][0]%>')">
        <span><%this.data[i][1]%></span>
    </div>
<%}%>