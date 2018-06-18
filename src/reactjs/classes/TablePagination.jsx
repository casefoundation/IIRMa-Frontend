var TablePagination = React.createClass({
    getInitialState: function () {
        addCallback("updateTablePagination", this.onUpdateData);
        var initialState = {};
        if (typeof this.props.pagination != 'undefined') {
            this.props.pagination.total_pages = Math.ceil(this.props.pagination.total / this.props.pagination.per_page);
            initialState = this.props.pagination;
        }
        
        return initialState;
    },
    onUpdateData: function(params){
        
        if(typeof params.total != 'undefined'){
            
            params.total_pages = Math.ceil(params.total/params.per_page);
            this.setState(params);
        }
    },
    updatePagination: function(e){
        scopeDispatchEvent("paginateTable", {delta:1});
        e.preventDefault();
    },
    specificPage: function(number){
        scopeDispatchEvent("paginateTable", {page:number});
    },
    firstPage: function(e){
        this.specificPage(1);
        e.preventDefault();
    },
    prevPage: function(e){
        scopeDispatchEvent("paginateTable", {delta:-1});
        e.preventDefault();
    },
    nextPage: function(e){
        scopeDispatchEvent("paginateTable", {delta:1});
        e.preventDefault();
    },
    lastPage: function(e){
        this.specificPage(this.state.total_pages);
        e.preventDefault();
    },
    jumpToPage:function(e){
        this.specificPage(Number(e.target.getAttribute("data-page")));
        e.preventDefault();
    },
    render: function(){
        if(typeof this.state.total != 'undefined'){
            var total_str = thousandNotation(this.state.total);
            var showing_pages = 2;
            var pages_number = [];

            var left_side = [];
            var right_side_calculation = (this.state.total_pages-this.state.current_page);
            var right_side_val = right_side_calculation > showing_pages ? showing_pages : right_side_calculation;

            for(var i=this.state.current_page-1;i>(this.state.current_page-(showing_pages*2));i--){
                if( (
                        left_side.length<showing_pages ||
                        ((right_side_val+left_side.length)<(showing_pages*2))
                    )
                    && i>0
                ){
                    left_side = [i].concat(left_side);
                }
            }

            var right_side = [];

            for(var i=this.state.current_page+1;i<=(this.state.current_page+(showing_pages*2));i++){

                if( (
                        right_side.length<showing_pages ||
                        ((right_side.length+left_side.length)<(showing_pages*2))
                    )
                    && i<=this.state.total_pages

                ){
                    right_side.push(i);
                }
            }

            pages_number = left_side.concat([this.state.current_page]);
            pages_number = pages_number.concat(right_side);


            var rowsList = pages_number.map((index) => {
                if(index==this.state.current_page){
                    return (
                        <i key={index}>{thousandNotation(index)}</i>
                    )
                }
                    return (
                        <a key={index} href="#" data-page={index} onClick={this.jumpToPage}>{thousandNotation(index)}</a>
                    )

            });

            var forward_action = "";
            var last_page_action = "";
            var backward_action = "";
            var first_page_action = "";

            if(this.state.current_page!=this.state.total_pages){
                forward_action = <a href="#" onClick={this.nextPage}><i className="fa fa-caret-right" aria-hidden="true"></i></a>;
                last_page_action = <a href="#" onClick={this.lastPage}><i className="fa fa-forward" aria-hidden="true"></i></a>;
            }
            if(this.state.current_page!=1){
                backward_action = <a href="#" onClick={this.prevPage}><i className="fa fa-caret-left" aria-hidden="true"></i></a>;
                first_page_action = <a href="#" onClick={this.firstPage}><i className="fa fa-backward" aria-hidden="true"></i></a>;
            }



            return <div className="results-wrapper"><span>
                Displaying results {thousandNotation(this.state.from)}-{thousandNotation(this.state.to)} of {total_str}
            </span> | <span className="pagination">
                {first_page_action}

                {backward_action}

                {rowsList}

                {forward_action}

                {last_page_action}
            </span></div>
        } else {
            return <div></div>;
        }
    }
});