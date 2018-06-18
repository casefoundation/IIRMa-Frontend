var FiltersBlock = React.createClass({
    getInitialState: function () {
        return {
            title: this.props.title,
            handleSelection: this.props.handleSelection,
        };
    },
    showMoreFilters: function(e){
        var modal_filters = "";
        var allRange = false; // INDICATES IF ARRAY IS OR NOT IN THE "ALL SECTION"
        var all_selected = "";

        for(var i in this.props.filters){
            var filterInfo = this.props.filters[i];
            var slug = getSlug( filterInfo.title+" popup "+new Date().valueOf() );

            // SET ALL RANGE
            if( filterInfo.just_title && filterInfo.title == "all" ){
                allRange = true;
            }   else if(filterInfo.just_title && filterInfo.title != "all"){
                allRange = false;
            }

                if (!allRange) {

                    if( (all_selected).indexOf(filterInfo.title+'[][][]')!=-1){
                        filterInfo.selected = true;
                    }

                    var checked = '';
                    if(filterInfo.selected) {
                        checked = " checked ";
                    }

                    var className = 'filter-item ' + (filterInfo.count == 0 ? 'no-results' : '');
                    if (!filterInfo.just_title) {
                        modal_filters += '<li json="' + JSON.stringify(filterInfo) + '">' +
                            '<div class="filter-item ' + className + '" data-count="' + filterInfo.count + '">' +
                            '<input type="checkbox" value="' + filterInfo.title + '" name="' + this.state.title + '" id="' + slug + '" ' + checked + ' ' + (filterInfo.count == 0 ? 'disabled="true"' : '') + '/>' +
                            '<img src="' + scope.image_dir + 'big_checkbox.svg"/>' +
                            '<img src="' + scope.image_dir + 'big_checkbox_checked.svg"/>' +
                            '<label for="' + slug + '">' + filterInfo.title + '</label>' +
                            '</div>' +
                            '</li>';
                    } else {
                        modal_filters += '<li>' +
                            '<h4>' + filterInfo.title + '</h4>' +
                            '</li>';
                    }
                } else {

                    if(filterInfo.selected){
                        all_selected+=filterInfo.title+'[][][]';
                    }

                }

        }


        var styles = "";
        if(this.state.title=='impact objective'){
            styles = "column-count: 2;";
        }else if(this.state.title=='legal structures'){
            styles = "column-count: 2;";
        }else if(this.state.title=='industries'){
            styles = "column-count: 2;";
        }else if(this.state.title=="geography"){
            styles = "column-count: 4;";
        }
        modal_filters = "<form id=\"modal-filters\" data-callback=\""+this.state.title+"\"><ul class=\"modal-filters\" style=\""+styles+"\">"+modal_filters+"</ul></form>";

        jQuery.cModal({
            type: 'default',
            title: this.state.title+':',
            text: modal_filters,
            buttons: [{
                    text: 'Add',
                    onClick: function(argument) {
                        var filtersStr = jQuery('#modal-filters').serialize();
                        var filtersArr = filtersStr.split('&');

                        var filters= [];
                        for(var i in filtersArr){
                            var filtersParse = filtersArr[i].split('=');
                            filters.push(decodeURIComponent(filtersParse[1]).split('+').join(" "));
                        }

                        scopeDispatchEvent("updateFiltersModule",{title:jQuery('#modal-filters').attr('data-callback'), filters:filters});
                        return true;
                    }
                },
            ],
        });
        e.preventDefault();
    },
    render: function(){
        var listItems = this.props.filters.map((filterInfo, index) => {
            return (
                !filterInfo.selected && !filterInfo.just_title && index<8 ? <FilterCheckbox title={filterInfo.title} filter_name={this.props.filter_name} count={filterInfo.count} key={filterInfo.title} handleSelection={this.state.handleSelection}/> : (filterInfo.just_title ? "" : "")
            )
        });
        var moreButton = "";

        if(this.props.filters.length>8){
            moreButton = <a href="#" onClick={this.showMoreFilters}>more +</a>;
        }

        return  <div className="filters-content">
                    <form>
                        {listItems}
                        {moreButton}
                    </form>
                </div>
        }
});
