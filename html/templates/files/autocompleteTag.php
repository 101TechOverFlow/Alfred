<%for(var i in this.data) {%>
    <div class="tag add" onclick="javascript:_FILES.addTag('<%this.data[i]%>')">
        <span><%this.data[i]%></span>
    </div>
<%}%>