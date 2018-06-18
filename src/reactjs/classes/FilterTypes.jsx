/* ****************************************
   *** TYPE SELECTOR (company, investor)
   ****************************************
*/

var FilterTypes = React.createClass({
    getInitialState: function () {
        var typesArr = [];
        var currentSet;

        for (var key in this.props.scope.data) {
            var type_set = this.props.scope.data[key];
            var custom_set = {};

            for (var filter_name in type_set) {
                var filters = [];
                for (var i in type_set[filter_name]) {
                    if (typeof type_set[filter_name][i] == 'string') {
                        filters.push({
                            title: type_set[filter_name][i],
                            selected: false,
                            just_title: false,
                            count: i.split('-')[1]
                        });
                    } else if (typeof type_set[filter_name][i] == 'object') {
                        filters.push({title: i, selected: false, just_title: true});
                        for (var j in type_set[filter_name][i]) {
                            filters.push({
                                title: type_set[filter_name][i][j],
                                selected: false,
                                just_title: false,
                                count: j.split('-')[1]
                            });
                        }
                    }
                }
                custom_set[filter_name] = filters;
            }
            typesArr.push({title: key, data: custom_set});
        }

        for (var i in typesArr) {
            if (typesArr[i].title == this.props.scope.currentType) {
                currentSet = typesArr[i].data;
                break;
            }
        }
        var currentSetData = [];
        var filters_order = ["impact objective", "industry", "geography", "fundraising round", "legal structure", "certifications", "investor type", "mechanism", "vehicle"];
        for (var i in filters_order) {
            for (var key in currentSet) {

                    var newKey;
                    switch (key) {
                        case "industries":
                            newKey = "industry";
                            break;
                        case "legal structures":
                            newKey = "legal structure";
                            break;
                        case "funding round":
                            newKey = "fundraising round";
                            break;
                        default:
                            newKey = key;
                            break;
                    }
                if(filters_order[i]==newKey) {
                    currentSetData.push({title: key, displayTitle:newKey, data: currentSet[key]});
                }
            }
        }

        addCallback("updatePagination", this.updatePagination);

        return {
            data: typesArr,
            currentType:this.props.scope.currentType,
            currentSet: currentSetData,
            originalScope: this.props.scope,
            currentFilters: [],
            filtersParam: [],
            currentPage:1,
            reviewedOnly: false
        };
    },
    handleChangeType: function(event){

        var target_id = event.target.getAttribute("data-target");
        var target_ob = document.getElementById(target_id);
        var selector = "active-type";
        var type = target_ob.getAttribute("data-type");

        // REMOVE ACTIVE CLASS FROM ALREADY ACTIVE ELEMENT
        var alreadyActiveEl = document.querySelector("a."+selector);

        if(alreadyActiveEl){
            alreadyActiveEl.className = alreadyActiveEl.className.split(selector).join('');
        }

        // ADD ACTIVE CLASS TO CURRENT ITEM
        target_ob.className += " "+selector;

        var currentSet;
        for(var i in this.state.data){
            if(type==this.state.data[i].title){
                currentSet = this.state.data[i].data;
                break;
            }
        }
        var currentSetData = [];
        var filters_order = ["impact objective", "investor type", "industry", "geography", "fundraising round", "legal structure", "certifications", "investor type", "mechanism", "vehicle"];
        for (var i in filters_order) {
            for (var key in currentSet) {

                var newKey;
                switch (key) {
                    case "industries":
                        newKey = "industry";
                        break;
                    case "legal structures":
                        newKey = "legal structure";
                        break;
                    case "funding round":
                        newKey = "fundraising round";
                        break;
                    default:
                        newKey = key;
                        break;
                }
                if(filters_order[i]==newKey) {
                    currentSetData.push({title: key, displayTitle:newKey, data: currentSet[key]});
                }
            }
        }

        var params_obj = {};
        for(var filter_key in currentSetData){
            var filter_obj = currentSetData[filter_key];
            var activeFilters = [];
            for( var i in currentSetData[filter_key].data){
                var filter_status = currentSetData[filter_key].data[i];
                if(filter_status.selected && !filter_status.just_title){
                    activeFilters.push(encodeURIComponent(filter_status.title));
                }
            }

            if(activeFilters.length>0){
                params_obj[getSlug(filter_obj.title)] = activeFilters.join("[][]");
            }
        }


        this.state.originalScope.currentType = type;

        scopeDispatchEvent('updateNodesType', type);

        this.setState({
            data: this.state.data,
            currentType: type,
            currentSet: currentSetData,
            originalScope: this.state.originalScope,
            currentFilters: [],
            filtersParam: params_obj,
            currentPage:1,
        });

        event.preventDefault();
    },
    updateFiltersBatch: function(name, filters){
        for(var i in this.state.currentSet){
            if(getSlug(this.state.currentSet[i].title)==name){
                for(var k in filters){
                    for(var j in this.state.currentSet[i].data){
                        if(this.state.currentSet[i].data[j].title == filters[k]){
                            this.state.currentSet[i].data[j].selected = true;
                            break;
                        }
                    }
                }

            }
        }

        this.updateFilterHandler();
    },
    updateFilterHandler: function(){
        var params_obj = {};
        var prev_t = '-- first';

        for(var filter_key in this.state.currentSet){
            var filter_obj = this.state.currentSet[filter_key];
            var activeFilters = [];
            for( var i in this.state.currentSet[filter_key].data){
                var filter_status = this.state.currentSet[filter_key].data[i];
                if(filter_status.selected && !filter_status.just_title){

                    activeFilters.push(encodeURIComponent(filter_status.title));
                }
                prev_t = filter_status.title;
            }

            if(activeFilters.length>0){
                params_obj[getSlug(filter_obj.title)] = activeFilters.join("[][]");
            }
        }

        for(var filter_key in this.state.currentSet) {
            var filter_obj = this.state.currentSet[filter_key];
            for (var i in this.state.currentSet[filter_key].data) {
                var filter_status = this.state.currentSet[filter_key].data[i];


                for(var j in this.state.data){
                    for(var k in this.state.data[j].data){
                        if(k==filter_obj.title){
                            for(var l in this.state.data[j].data[k]){
                                if(this.state.data[j].data[k][l].title == filter_status.title) {
                                    this.state.data[j].data[k][l].selected = filter_status.selected;
                                }
                            }
                        }
                    }
                }

            }
        }

        this.setState({
            currentSet: this.state.currentSet,
            currentFilters: this.state.currentFilters,
            filtersParam: params_obj,
            currentPage:1
        });

        var trackingData = this.state.currentSet.map(function(set) {
          return {
            'title': set.title,
            'selected': set.data.filter(function(item) {
              return item.selected;
            }).map(function(item) {
              return item.title
            })
          }
        })
        nmTrackFilter(trackingData)
    },
    setFilterSelection: function(name, value, isSelected){

        for(var i in this.state.currentSet){
            if(this.state.currentSet[i].title == name ) {
                for(var j in this.state.currentSet[i].data){
                    if(this.state.currentSet[i].data[j].title == value ) {
                        this.state.currentSet[i].data[j].selected = isSelected;
                    }
                }
            }
        }
    },
    handleUnselection: function(e){
        this.setFilterSelection(e.target.getAttribute("data-name"), e.target.getAttribute("data-value"), false);
        this.updateFilterHandler();
        e.preventDefault();
    },

    handleSelection: function(e){
        this.setFilterSelection(e.target.name, e.target.value, true);
        this.updateFilterHandler();
        e.preventDefault();
    },
    resetFilters: function(e){

        for(var i in this.state.currentSet){
            for(var j in this.state.currentSet[i].data){
                if(this.state.currentSet[i].data[j].selected && !this.state.currentSet[i].data[j].just_title){
                    this.state.currentSet[i].data[j].selected = false;
                }
            }
        }
        for(var i in this.state.data){
            for(var j in this.state.data[i].data){
                for(var k in this.state.data[i].data[j]){
                    this.state.data[i].data[j][k].selected = false;
                }
            }
        }

        this.setState({
            data: this.state.data,
            currentFilters: this.state.currentFilters,
            filtersParam: [],
            currentPage:1
        });

        e.preventDefault();
    },
    updatePagination: function(params){
        if(typeof params.page != 'undefined'){
            if(this.state.currentPage!=params.page){
                this.setState({
                        currentFilters: this.state.currentFilters,
                        filtersParam: this.state.filtersParam,
                        currentPage:params.page
                });
            }
        } else {
            var delta = params.delta;
            if(this.state.currentPage+delta > 0){
                this.setState({
                    currentFilters: this.state.currentFilters,
                    filtersParam: this.state.filtersParam,
                    currentPage:this.state.currentPage+delta
                });
            }
        }
    },
    onReviewedOnly: function(){
        this.setState({reviewedOnly: jQuery('#reviewed_only').is(':checked')});
    },
    render: function(){
        var listItems = this.state.data.map((typeData) => {
            var normalizedTitle = typeData.title.toLowerCase();
            var className = "type-icon icon-"+normalizedTitle;
            var itemId = "filter-type-"+normalizedTitle;
            var linkClass = this.state.currentType == typeData.title ? "active-type" : "";


            return (
                    <li key={typeData.title}>
                        <a href="#" onClick={this.handleChangeType} id={itemId} data-target={itemId} className={linkClass} data-type={typeData.title}>
                            <span className={className} data-target={itemId}></span>
                            <span className="type-label" data-target={itemId}>{typeData.title}</span>
                        </a>
                    </li>
                )
            }
        );
        
        var filterTypesList =  this.state.currentSet.map((dataInfo) => {
                return (
                    (dataInfo.data.length>0 || (typeof dataInfo.data.length == 'undefined') ) ? <Filter title={dataInfo.title} filters={dataInfo.data} displayTitle={dataInfo.displayTitle} key={dataInfo.title} handleSelection={this.handleSelection} handleUnselection={this.handleUnselection} updateFiltersBatch={this.updateFiltersBatch}/> : ""
                )
            }
        );

        createNodes(this.state.currentType, this.state.filtersParam, this.state.reviewedOnly, this.state.currentPage);

        scopeDispatchEvent("updateTableView", {
            type:this.state.currentType,
            filtersParam: this.state.filtersParam,
            reviewedOnly: this.state.reviewedOnly,
            currentPage:this.state.currentPage,
        });

        closeNodeDetails();


        return (
            <div>
                <div className="sidebar-block title-block">
                    <h3>Filter by</h3>
                </div>
                <div className="sidebar-block">
                    <h3>{this.props.title}:</h3>
                    <div className="type-filters">
                        <ul>
                            {listItems}
                        </ul>
                    </div>
                </div>
                <div className="sidebar-block">
                    <div className="filter-item big-check">
                        <input type="checkbox" value="reviewed" id="reviewed_only" onChange={this.onReviewedOnly}/>
                        <img src={scope.image_dir + "big_checkbox.svg"} />
                        <img src={scope.image_dir + "big_checkbox_checked.svg"} />
                        <label htmlFor="reviewed_only">Show reviewed information only <span className="tooltip" title="Check this box to view information which has been reviewed and confirmed by our data partner, Impact Space. While Impact Space data is crowdsourced, Impact Space is continuously working to improve the quality of their information.">i</span></label>

                    </div>
                </div>
                {filterTypesList}
                <div className="sidebar-block buttons-block">
                    <a href="#" className="primary-btn btn-border reset-filters-btn" onClick={this.resetFilters}>Reset Filters</a>
                </div>

            </div>
        )
    }
});
