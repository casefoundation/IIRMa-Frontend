var SortTable = React.createClass({
    getInitialState: function () {
        var statesDef = {currentPage:1, data:[], titles:[]};

        statesDef.per_page = 30;
        statesDef.current_page = 1;

        addCallback("onTableResponse", this.onResponse);

        addCallback("updateTableView", this.onUpdateData);

        addCallback("paginateTable", this.paginateTable);

        addCallback("search", this.onSearch);

        statesDef.scope = this.props.scope;

        if (typeof this.props.data != 'undefined') {
            statesDef = this.analyzeData(this.props.data);
            statesDef.total_pages = Math.ceil(statesDef.data.length / statesDef.per_page);
            statesDef.from = (statesDef.current_page - 1) * statesDef.per_page;
            statesDef.to = (statesDef.current_page) * statesDef.per_page > statesDef.data.length ? statesDef.data.length : (statesDef.current_page) * statesDef.per_page;
        } else {
            this.onUpdateData({type:'company', filtersParam:[] })
        }
        return statesDef;
    },
    paginateTable: function(params){
        var jump_to_page = 0;
        if(typeof params.page != 'undefined'){
            jump_to_page = params.page;


        }
        if(typeof params.delta != 'undefined'){
            if( (this.state.current_page+params.delta)<this.state.total_pages &&  (this.state.current_page+params.delta)>=0 ){
                jump_to_page = this.state.current_page+params.delta;
            }
        }

        if(jump_to_page!=0){
            nmTrackEvent('Explore', 'List View Page', jump_to_page);
            this.setState({current_page:jump_to_page});
        }

    },
    onSearch: function(keywords){
        var searchResult = [];
        var i = this.state.data.length;
        while ( i-- ) {
            if(this.state.data[i].nameLowerCase.indexOf(keywords)!=-1){
                if(searchResult.length<100){
                    searchResult.push(this.state.data[i]);
                }
            }
        }
        return searchResult;
    },
    analyzeData: function(res){

            var titlesArr = [];
            var dataArr = [];
            
            dataArr = [];

            var types = ["company","investor"];

            for (var type_i in types) {

                var type = types[type_i];

                for (var i in res.graph.nodes[type]) {
                    var ob = res.graph.nodes[type][i];
                    dataArr.push({
                        name : ob.n,
                        funding_total: typeof ob.f == 'undefined' ? 0 : ob.f,
                        id: ob.i,
                        type: type,
                        nameLowerCase: ob.n.toLowerCase(),
                        index:dataArr.length + 1,
                    });
                }
            }
            var numberLabel = window.innerWidth<768 ? '#' : 'Number';
            nmTrackEvent('Explore', 'List View Page', 1);
            return {
                titles: [
                    {key:'index', title:numberLabel, sort:'both'},
                    {key:'name', title:"Name", sort:'both'}, 
                    {key:'funding_total', title:"Number of investments", sort:'both'}
                ],
                data: dataArr,
                current_page: 1,
                total_pages:Math.ceil(dataArr.length / this.state.per_page)
            };

    },
    onUpdateData: function(param){

        jQuery('.nodata-status').removeAttr("style");

        if( (typeof param.type !='undefined') && (typeof param.filtersParam!='undefined') ){
        
            param.filtersParam.type = param.type;
            param.filtersParam.page = typeof param.currentPage == 'undefined' ? 1 : param.currentPage;
            param.filtersParam.reviewed_only = typeof param.reviewedOnly == 'undefined' ? false : param.reviewedOnly;

            scope.latestCallParams = param.filtersParam;
            scope.latestCallType = param.type;

            var call_url = ajaxCall("get_data", param.filtersParam);

            jQuery.ajax({url:call_url}).done(function( result ) {
                scopeDispatchEvent("onTableResponse", JSON.parse(result) );
            });
        }
    },
    onResponse: function(result){
        scopeDispatchEvent("updateResults", result.info);

        scopeDispatchEvent("updatePagination", result.pagination);

        var newStates = this.analyzeData(result);

        this.setState(newStates);

        if(result.pagination.total==0){
            jQuery('.nodata-status').show();

            nmTrackEvent('Explore', 'No Data Results', JSON.stringify({params:scope.latestCallParams, type:scope.latestCallType}));
        }

        jQuery('.preloader-status').hide();
    },
    handleClickNode: function(event){
        var type = event.target.getAttribute("data-type");
        var id = event.target.getAttribute("data-id");

        showNodeDescription(type, id);

        hideTableView();

        event.preventDefault();

    },
    sortColumn: function(event){
        var field = event.target.getAttribute("data-field");
        var sort = "both";

        for(var i in this.state.titles){
            if(this.state.titles[i].key==field){
                if(this.state.titles[i].sort=='asc'){
                    this.state.titles[i].sort = 'desc';
                } else {
                    this.state.titles[i].sort = 'asc';
                }
                sort = this.state.titles[i].sort;
            } else {
                this.state.titles[i].sort = 'both';
            }
        }

        this.state.data.sort(function(a, b) {
            var x = a[field];
            var y = b[field];

            if (typeof x == "string")
            {
                x = x.toLowerCase();
            }
            if (typeof y == "string")
            {
                y = y.toLowerCase();
            }
            if(sort=='desc'){
                return ((x > y) ? -1 : ((x < y) ? 1 : 0));
            }
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
        this.setState({data: this.state.data, current_page:1});
    },
    render: function(){

            var titlesList = this.state.titles.map((dataInfo) => {
                var className = "th-inner sortable " + dataInfo.sort;
                return (
                    <th key={dataInfo.key} data-field={dataInfo.key} tabIndex="0">
                        <div className={className} data-field={dataInfo.key}
                             onClick={this.sortColumn}>{dataInfo.title}</div>
                        <div className="fht-cell"></div>
                    </th>
                )
            });


            var displaying_data = [];

            var from = (this.state.current_page - 1) * this.state.per_page;
            var to = (this.state.current_page) * this.state.per_page > this.state.data.length ? this.state.data.length : (this.state.current_page) * this.state.per_page;

            for (var i = from; i < to; i++) {
                displaying_data.push(this.state.data[i]);
            }

            var rowsList = displaying_data.map((dataInfo, index) => {
                return (
                    <tr data-index={index} key={index}>
                        <td >{dataInfo.index}</td>
                        <td><a href='#' onClick={this.handleClickNode} data-id={dataInfo.id} data-type={dataInfo.type}
                               className={dataInfo.type}>{dataInfo.name}</a></td>
                        <td>{ thousandNotation(dataInfo.funding_total) }</td>
                    </tr>
                )
            });

            scopeDispatchEvent("updateTablePagination", {
                current_page: this.state.current_page,
                from: from + 1,
                to: to,
                total: this.state.data.length,
                per_page: this.state.per_page,
            });

            if(isMobile.any() || jQuery(window).width()<768){
                if(this.state.data.length==0){
                    jQuery('.table-view').attr("style", 'display: none !important;');
                } else {
                    jQuery('.table-view').removeAttr('style');
                }
            }

            return <div className="bootstrap-table">
                <div className="fixed-table-container">
                    <div className="fixed-table-body">
                        <table className="table table-hover">
                            <thead className="thead-inverse">
                            <tr>
                                {titlesList}
                            </tr>
                            </thead>
                            <tbody>
                            {rowsList}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    }
});