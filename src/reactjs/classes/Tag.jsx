var Tag = React.createClass({
    render: function(){
        return <div className="tag-item" >
            {this.props.title}
            <a href="#" className="remove-item"  onClick={this.props.handleClick}>
                <img src={scope.image_dir + "close-icon.svg"} data-value={this.props.title} data-name={this.props.filter_name}/>
            </a>
        </div>
    }
});