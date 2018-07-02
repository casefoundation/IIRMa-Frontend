var NodeDescription = React.createClass({
    getInitialState: function () {
        addCallback("checkNodeInfo", this.onUpdateData);
        addCallback("onNodeDescriptionResponse", this.onResponse);
        return {loading:true};
    },
    onUpdateData: function(params){
        if(typeof scope.cache[params.type+'_'+params.id]=='undefined'){
    
            var call_url = ajaxCall("get_node", {type:params.type, id:params.id});

            this.setState({loading:true, type: params.type});

                jQuery.ajax({url:call_url}).done(function( result ) {
                    scopeDispatchEvent("onNodeDescriptionResponse", JSON.parse(result) );
                });


            jQuery('.node-details').css({"display":"block", right:-500}).animate({right:0}).removeClass('collapsed');


        } else {
            this.setState({data:scope.cache[params.type+'_'+params.id], type:params.type, loading:false});

            if(jQuery('.node-details').hasClass('collapsed')){
                jQuery('.node-details').css({"display":"block", right:-500}).animate({right:0}).removeClass('collapsed');
            }
        }
    },
    onResponse: function(result){
        scope.cache[result.type+'_'+result.id] = result;

        this.setState({data:result, type:result.type, loading:false});
    },
    handleClickNode: function(event){
        var type = event.target.getAttribute("data-type");
        var id = event.target.getAttribute("data-id");

        showNodeDescription(type, id);

        hideTableView();

        event.preventDefault();

    },
    render: function(){
        var className = "node-details "+this.state.type;

        var editURL = "https://impactspace.com/"+(this.state.type=='investor' ? 'financial-organization' : "company")+"/"+ (!this.state.loading ? this.state.data.slug : '');

        var header_name = !this.state.loading ? <h4>{this.state.data.name}<a href={editURL} className="edit-entity" target="_blank">Edit</a></h4> : <h4><i className="fa fa-refresh fa-spin" aria-hidden="true"></i></h4>
        var styles_str = !this.state.loading && (typeof this.state.data.company_logo!='undefined') ? {"backgroundImage":"url("+this.state.data.company_logo+")"} : {};
        var header_logo = !this.state.loading && (typeof this.state.data.company_logo!='undefined') ? <div className="logo-container" style={styles_str}></div> : '';

        var do_not_render = ["id", "type", "impactspace_id", "impactspace_updated_at", "created_at", "name", "updated_at", "slug", "investors", "companies", "crunchbase_id", "crunchbase_updated_at", "company_logo", "crunchbase_url", "network_map_ready", "last_existence_verification"];
        var do_not_if_value = ["0000-00-00"];
        var follow_order = ["two-columns","headquarters", "founded", "founders", "industry", "legal structure", "mission statement", "website", "external links", "description", "number of employees", "investor type","phone", "email", "social"];

        var overviewItems = <div></div>;
        if(!this.state.loading){
            var overview_data = [];
            var social = [];

            /* FIRST ELEMENT WITH INVESTMENTS RESUME */
                var total_funding = 0;
                var investors_number = 0;

                var investments = [];
                var investors = [];

                var investments_info = this.state.type=='company' ? this.state.data.investors : this.state.data.companies;

                var most_recent_date = new Date(0,0,0);
                var most_recent_investment;

                for(var key in investments_info){
                    var investor = investments_info[key];


                    for(var i in investor){
                        total_funding+= Number(investor[i].fund.amount);
                        investors_number++;
                        investments.push(investor[i].fund);
                        investors.push(investor[i]);

                        if(typeof investor[i].fund.date != 'undefined'){
                            var date_arr = investor[i].fund.date.split('-');
                            var investment_date = new Date(date_arr[0], date_arr[1], date_arr[2]);
                            
                            if(investment_date.valueOf()>most_recent_date.valueOf()){
                                most_recent_date = investment_date;
                                most_recent_investment = investor[i].fund;
                            }
                        }
                    }

                }

            for(var key in this.state.data){
                var do_render = true;
                var value = this.state.data[key];

                for(var not_render_key in do_not_render){
                    do_render &= key != do_not_render[not_render_key];
                }
                for(var not_render_key in do_not_if_value){
                    do_render &= value != do_not_if_value[not_render_key];
                }

                if( do_render ){
                    
                    var key_str = key.split('_').join(' ');
                    var content_str = this.state.data[key].split("\\'").join("'").split("\\n").join("[[NEWLINE]]").split("\\r").join("\r");
                    var do_add = true;
                    

                    switch(key){
                        case "overview":
                            key_str = "description";
                            var content_arr = content_str.split('[[NEWLINE]]');
                            var content_str = content_arr.map((itemInfo, index) => {
                                return <p key={index}>{itemInfo}</p>;
                            });
                            break;
                        case "external_links":
                            content_str = content_str.split('"').join('');
                            var all_external = content_str.split(',');
                            
                            var all_external_links = all_external.map((external_link_str, index) => {
                                return <li key={index}><a href={external_link_str} target="_blank">{external_link_str}</a></li>;
                            });
                            content_str = <ul>{all_external_links}</ul>;
                            break;
                        case "number_of_employees":
                            do_add = content_str!='';
                            break;
                        case "website":
                            content_str = content_str.split('"').join('');
                            content_str = <a href={content_str} target="_blank">{content_str}</a>;
                            break;
                        case "founded_date":
                            key_str = "founded";
                            content_str = getFormattedDate(content_str, "long");
                            
                            break;
                        case "phone":
                            if(content_str=='' || content_str=='0' || content_str==0){
                                do_add = false;
                            }
                            var url_str = "tel:"+content_str;
                            content_str = <a href={url_str} target="_blank">{content_str}</a>;
                            break;
                        case "email":
                            var url_str = "mailto:"+content_str;
                            content_str = <a href={url_str} target="_blank">{content_str}</a>;
                            break;
                        case "twitter":
                            var twitter_user = content_str.split('@');
                            content_str = "https://twitter.com/"+twitter_user[1];
                            social.push({network:key, url:content_str});
                            do_add = false;
                            break;
                        case "facebook":
                        case "linkedin":
                            social.push({network:key, url:content_str});
                            do_add = false;
                            break;
                    }

                    if(do_add){
                        overview_data.push({title:key_str, content:content_str});
                    }
                }
            }

            /* ADD SOCIAL SECTION */

            if(social.length>0){
                    var social_items = social.map((itemInfo) => {
                        var className = "fa fa-"+itemInfo.network+"-square";
                        return (
                        <a href={itemInfo.url} target="_blank" key={itemInfo.network}>
                            <i className={className} aria-hidden="true"></i>
                        </a>
                        );
                    });
                    overview_data.push({title:"social", content:social_items});
            }
            
            var overview_data_sort = [];
            for(var order_index in follow_order){
                for(var overview_data_index in overview_data){
                    if(follow_order[order_index] == overview_data[overview_data_index].title){
                        overview_data_sort.push(overview_data[overview_data_index]);
                        overview_data.splice(overview_data_index, 1);
                        break;
                    }
                }
            }
            
            if(overview_data.length>0){
                for(var overview_data_index in overview_data){
                    overview_data_sort.push(overview_data[overview_data_index]);
                }
            }

            overviewItems = overview_data_sort.map((itemInfo) => {

                var slug = getSlug( itemInfo.title )
                var className = "details-item details-"+slug;

                if(itemInfo.title=='two-columns'){
                    slug = getSlug( itemInfo.content.column_a.title+' '+itemInfo.content.column_b.title );

                    return <div className="details-item two-columns" key={slug}>
                                <div className="details-column column-a">
                                    <span>{itemInfo.content.column_a.title}</span>
                                    <p>{itemInfo.content.column_a.content}</p>
                                </div>
                                <div className="details-column column-b">
                                    <span>{itemInfo.content.column_b.title}</span>
                                    <p>{itemInfo.content.column_b.content}</p>
                                </div>
                            </div>;
                }

                return (
                    <div className={className} key={slug}>
                        <span>{itemInfo.title}: </span>
                        <p>{itemInfo.content}</p>
                    </div>
                )
            });
        }

        var funding_tab_content = "";
        var funding_tab = "";
        if(!this.state.loading){
            var total_funding = 0;
            var investors_number = 0;
            
            var investments = [];
            var investors = [];

            var investments_info = this.state.type=='company' ? this.state.data.investors : this.state.data.companies;


            for(var key in investments_info){
                var investor = investments_info[key];

                for(var i in investor){
                    investor[i].id = key;
                    total_funding+= Number(investor[i].fund.amount);
                    investors_number++;
                    investments.push(investor[i].fund);
                    investors.push(investor[i]);
                }
                
            }
            
            var funding_rounds_table = investments.map((itemInfo, index) => {

                var fund_date_str = getFormattedDate(itemInfo.date, "short");

                var fund_type = '-';
                if(typeof itemInfo.type != 'undefined'){
                    fund_type = itemInfo.type;
                }
                return <tr key={index}>
                        <td>{fund_date_str}</td>
                        <td>${abbrNumberFormat(itemInfo.amount)} / {fund_type}</td>
                        <td>1</td>
                    </tr>;
            });

            var investor_label = "Investors";
            if(this.state.type=='investor'){
                investor_label = "Companies";
            }

            var investors_table = investors.map((itemInfo, index) => {
                var titles = "";
                var investmentAmount = Number(itemInfo.fund.amount)==0 ? ' Undisclosed' : abbrNumberFormat(itemInfo.fund.amount);
                if(index==0){
                    titles = <thead className="title">
                            <tr>
                                <th>Date</th>
                                <th>Round</th>
                                <th>Total Round Amount</th>
                            </tr>
                        </thead>;
                }
                var fund_type = 'Undisclosed';
                if(typeof itemInfo.fund.type != 'undefined'){
                    fund_type = itemInfo.fund.type;
                }


                return <table className="table-details" key={index}>
                        {titles}
                        
                        <thead>
                            <tr>
                              <th>
                                  <a href='#' onClick={this.handleClickNode} data-id={itemInfo.id} data-type={this.state.type=='investor'?'company':'investor'}>
                                  {itemInfo.name}
                                  </a>
                              </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{getFormattedDate(itemInfo.fund.date)}</td>
                                <td>{fund_type}</td>
                                <td>{investmentAmount}</td>
                            </tr>
                        </tbody>
                    </table>
            });   
            
            
            funding_tab = <li><a href="#funding-details" data-toggle="tab">Funding Details</a></li>;
            funding_tab_content = <div className="tab-pane" id="funding-details">
                <div className="details-item table">
                    <h3  className="notification"><i>{investor_label}</i><span>{investors_number}</span></h3>

                    {investors_table}

                </div>

            </div>;
        }
        var body = !this.state.loading ? <div className="details-body">
                                                <div id="details-body">
                                                    <ul  className="nav nav-pills">
                                                        <li className="active">
                                                            <a  href="#overview" data-toggle="tab">Overview</a>
                                                        </li>
                                                        {funding_tab}
                                                    </ul>
                                                    <div className="tab-content clearfix">
                                                        <div className="tab-pane active" id="overview">
                                                            {overviewItems}
                                                        </div>
                                                        {funding_tab_content}
                                                    </div>
                                                </div>

                                            </div> : '';

        return  <div className={className}>
                    <a href="#" className="close-module"><i className="fa fa-times" aria-hidden="true"></i></a>
                    <div className="details-header">
                        {header_logo}
                        {header_name}
                    </div>
                    {body}
                </div>
        }
});