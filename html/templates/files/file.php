<%for(var i in this.data) {%>
    <div data-f_id="<%this.data[i].f_id%>" class="file" onclick="javascript:selectFile('<%this.data[i].f_id%>');">
        <div class="file-icon-area"><i class="fa fa-file-o"></i></div>
            <div class="file-settings-icon" onclick="javascript:detailFile('<%this.data[i].f_id%>');">
                <i class="fa fa-caret-right"></i>
                <div class="dropdown-content"></div>
            </div>
            <div class="file-tags">
            <%if(this.data[i].tags) {%>
                <%for(var j in this.data[i].tags) {%>
                    <div data-t_id="<%this.data[i].tags[j].t_id%>" class="tag remove" onclick="">
                        <span><%this.data[i].tags[j].t_name%></span>
                    </div>
                <%}%>
            <%} else {%>
            aucun tags
            <%}%>
            </div>
            <div class="file-details">
                <span class="file-owner" style="float:left"><%this.data[i].u_name%></span>
                <span class="file-size"><%this.data[i].f_size%></span>
                <span class="file-date" style="float:right"><%this.data[i].f_timestamp%></span>
            </div>
            <div class="file-title"><%this.data[i].f_name%>.<%this.data[i].f_extension%></div>                
    </div>

<%}%>