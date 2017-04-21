<%for(var i in this.data) {%>
    <div class="tag remove" onclick="javascript:_ROUTE.removeSearchTag('<%this.data[i]%>')">
        <span><%this.data[i]%></span>
    </div>
<%}%>