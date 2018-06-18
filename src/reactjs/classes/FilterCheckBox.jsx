var FilterCheckbox = React.createClass({
    getInitialState: function () {
        return { checked: false };
    },
    render: function(){
        var slug = getSlug( this.props.title+" "+new Date().valueOf() );
        var className = 'filter-item '+(this.props.count==0 ? 'no-results' : '');
        return  <div className={className} data-count={this.props.count}>
            <input type="checkbox" value={this.props.title} name={this.props.filter_name} id={slug} onChange={this.props.handleSelection} disabled={this.props.count==0}/>
            <img src={scope.image_dir + "checkbox-normal.svg"} />
            <img src={scope.image_dir + "checkbox-checked.svg"} />
            <label htmlFor={slug}>{this.props.title}</label>
        </div>
    }

});