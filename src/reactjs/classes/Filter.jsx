var Filter = React.createClass({
    getInitialState: function () {
        addCallback("updateFiltersModule", this.updateFilters);

        return {
            title: this.props.title,
            filters: this.props.filters,
            displayTitle:this.props.displayTitle,
            updateFilterHandler: this.props.updateFilterHandler,
            slug: getSlug( this.props.title )
        };
    },
    updateFilters: function(params){
        if(params.title==this.state.title){
            this.props.updateFiltersBatch(this.state.slug, params.filters);
        }
    },
    handleCollapse: function(event) {
        var target_id = "module-"+event.target.getAttribute("data-target");

        var selector = "collapsed";

        var target_module = jQuery('#'+target_id);
        if(target_module.hasClass(selector)){
            target_module.find('.filters-content').slideUp();
            target_module.removeClass(selector);
        } else {
            target_module.find('.filters-content').slideDown();
            target_module.addClass(selector);
        }

        event.preventDefault();
    },
    render: function(){

        return <div className="sidebar-block selectable-module" data-attribute={this.state.slug} id={"module-"+this.state.slug}>
            <h3 onClick={this.handleCollapse} data-target={this.state.slug}>
                {this.state.displayTitle}
            </h3>
            <a href="#" className="collapse-block" data-target={this.state.slug}></a>
            <TagsBlock filters={this.props.filters} filter_name={this.state.title} handleUnselection={this.props.handleUnselection}/>
            <FiltersBlock filters={this.props.filters} title={this.state.title} filter_name={this.state.title} handleSelection={this.props.handleSelection} />
        </div>
    }
});
