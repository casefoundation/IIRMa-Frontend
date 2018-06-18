var ResultsCaption = React.createClass({
    getInitialState: function () {
        addCallback("updateResults", this.onUpdateData);
        if (typeof this.props.scope.results != 'undefined') {
            return this.props.scope.results
        } else {
            return {}
        }


    },
    onUpdateData: function(params){
        this.setState(params);
    },
    render: function() {
        if (typeof this.state.investors_count != 'undefined') {
            if (this.state.investors_count == 0) {
                return <span>
                    Results: This data shows {this.state.companies_count} companies.
                </span>;
            }
            return <span>
                Results: This data shows {thousandNotation(this.state.investors_count)} investors and {thousandNotation(this.state.companies_count)} companies.
            </span>
        } else {
            return <span><i className="fa fa-refresh fa-spin" aria-hidden="true"></i></span>
        }
        ;
    }
});