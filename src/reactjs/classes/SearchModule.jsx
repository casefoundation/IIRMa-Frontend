var SearchModule = React.createClass({
    getInitialState: function () {
        addCallback("resetSearch", this.resetSearch);
        addCallback("updateNodesType", this.updateNodesType);

        return {
            keywords    : "",
            timestamp   : new Date().getTime(),
            placeholder : 'Search Companies'
        }
    },
    resetSearch: function(){
        jQuery('#search-keywords').val('');
        this.setState({keywords:""});

        nmTrackEvent('Explore', 'Reset Search');
    },
    updateNodesType: function(type){
        var placeholder = "Search ";
        if(type=='company'){
            placeholder+='Companies';
        } else if(type=='investor'){
            placeholder+='Investors';
        }
        this.setState({placeholder:placeholder});
    },

    searchKeyword: function(event){
        var keywords = event.target.value.toLowerCase();

        clearInterval(this.state.timestamp);

        var results = scopeDispatchEvent("search", keywords, true);

        // TRACKING INFO

        var search_results = [];
        for(var i in results){
            search_results.push(results[i].name);
        }

        var search_tracking_obj = {keywords: keywords, results:search_results.length};

        nmTrackSearch(search_tracking_obj);

        // SET STATE

        this.setState({results:results, keywords:keywords});


    },
    checkNodeInfo: function(event){

        var type = event.target.getAttribute("data-type");
        var id = event.target.getAttribute("data-id");



        jQuery('.results-list').hide();
        jQuery('.table-view').hide();
        jQuery('.graph-area .graph-header .header-controls').show();

        showAllNodes();
        showNodeDescription(type, id);

        event.preventDefault();
    },
    showPreviousSearch: function(e){
        showAllNodes();
        jQuery('.results-list').show();
    },
    graphShowResults(){
        var show_in_graph = [];
        for(var i in this.state.results){
            show_in_graph.push(this.state.results[i].type+'_'+this.state.results[i].id);
        }

        showNodes(show_in_graph);
    },
    render: function(){


        var resultsList = "";

        if( this.state.keywords!=""){

            this.graphShowResults();

            var resultsItems = this.state.results.map((itemInfo, index) => {



                var name = itemInfo.name.toLowerCase();
                var keyword_position = name.indexOf(this.state.keywords);
                var type = itemInfo.type;
                var id = itemInfo.id;

                var title_str = <span data-type={type} data-id={id}>{itemInfo.name}</span>;


                if(keyword_position!=-1){
                    var first_string = itemInfo.name.substring(0, keyword_position);
                    var second_string = itemInfo.name.substring(keyword_position, keyword_position+this.state.keywords.length);
                    var third_string = itemInfo.name.substring(keyword_position+this.state.keywords.length, name.length);
                    title_str = <span data-type={type} data-id={id}><span data-type={type} data-id={id}>{first_string}</span><b data-type={type} data-id={id}>{second_string}</b><span data-type={type} data-id={id}>{third_string}</span></span>;
                }


                return (
                    <li className="" key={index} >
                        <a href="#" data-type={type} data-id={id} onClick={this.checkNodeInfo} className={itemInfo.type}>
                            {title_str}
                        </a>
                    </li>
                )
            });
            resultsList = <ul className="results-list">{resultsItems}</ul>;
        } else if(this.state.keywords==''){
            showAllNodes();
        }

        return <div className="sidebar-block search-block">
                <form id='search-module'>
                    <div className="input-wrapper">
                        <input type="text" id="search-keywords" name="keyword" className="search-input" autoComplete="off" placeholder={this.state.placeholder} onChange={this.searchKeyword} onFocus={this.showPreviousSearch}/>
                        {resultsList}
                    </div>
                </form>

            </div>;
    }
});
