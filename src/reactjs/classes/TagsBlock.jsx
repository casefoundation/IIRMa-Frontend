var TagsBlock = React.createClass({
    render: function(){
        var already_rendered = {};

        var listItems = this.props.filters.map((filterInfo) => {

            if(typeof already_rendered[filterInfo.title] == 'undefined'){
                already_rendered[filterInfo.title] = 0;
            }
            already_rendered[filterInfo.title]++;

            return (
                already_rendered[filterInfo.title]==1 ? (filterInfo.selected && !filterInfo.just_title ? <Tag title={filterInfo.title} filter_name={this.props.filter_name} key={filterInfo.title} handleClick={this.props.handleUnselection} /> : "") : ''
            )
        });
        return  <div className="tags-content">{listItems}</div>
    }
});