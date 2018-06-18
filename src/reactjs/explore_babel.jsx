/* **************************
   *** CALLBACKS HANDLING
   **************************
*/

function scopeDispatchEvent(command, params, do_return=false){

    if(typeof scope.callbacks[command]!='undefined'){
        for(var i in scope.callbacks[command]){
            var value = scope.callbacks[command][i](params);
            if(do_return){
                return value;
            }
        }
    }
}
function addCallback(command, callback){
    if(typeof scope.callbacks[command]=='undefined'){
        scope.callbacks[command] = [];
    }
    scope.callbacks[command].push(callback);
}

function showNodeDescription(type, id, not_set_slug=false, animate=true){
    nmTrackEvent('Explore', 'Show right-hand detail view');
    scopeDispatchEvent("checkNodeInfo", {type:type, id:id});
    if(typeof s != 'undefined'){
        if(s!=null) {
            var nodes = s.graph.nodes();
            var i = nodes.length;
            while(i--){
                var node = nodes[i];
                if(node.id==type+'_'+id){
                    s.zoomToNode(node, 0.05, s.camera, animate);



                    i=0;
                    break;
                }
            }
        }
    }
    if(!not_set_slug){
        window.history.pushState(type+'/'+id, '', ajax_object.root_url+'/explore/'+type+'/'+id);
    }


}
function checkSlugNode(){

}

function closeNodeDetails(){


    if(!jQuery('.node-details').hasClass('collapsed')){
        jQuery('.node-details').animate({right:-500, time:500}, function(){
            jQuery('.node-details').removeAttr('style').addClass("collapsed");
        });
    }
}
function hideTableView(){
    jQuery('.table-view').fadeOut();
    jQuery('.graph-area .graph-header .header-controls').fadeIn();
    nmTrackEvent('Explore', 'Map View');
}
function showGraphZoom(zoom){
    if(s!=null){
        s.camera.goTo({
                x: s.camera.x,
                y: s.camera.y,
                ratio : zoom,
            });

        s.refresh();
    }
}
function showNodes(nodes_ids){

    if(s!=null){
        var add_nodes = [];
        s.graph.edges().forEach(function(edge){
            for(var i in nodes_ids){
                if(edge.source == nodes_ids[i] || edge.target == nodes_ids[i]){
                    add_nodes.push(nodes_ids[i]);
                }
            }
        });
        var ids_arr = nodes_ids.join("___");
        s.graph.nodes().forEach(function(node) {
            node.hidden = ids_arr.indexOf(node.id)==-1;
        });
        s.refresh();

        showGraphZoom(2);
    }
}
function showAllNodes(){
    if(s!=null){
        s.graph.nodes().forEach(function(node) {
            node.hidden = false;
        });
        s.refresh();
    }
}

/* **************************
   *** UTILITIES
   **************************
*/

function getSlug(string){
    return string.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
}
function thousandNotation(number_str){
    return Number(number_str).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function ajaxCall(action, params){
    var call_params = ["per_page=7000", "action="+action];
    
    for(var key in params){
        call_params.push(key+'='+params[key]);
    }
    
    return ajax_object.ajax_url+"?"+call_params.join("&");
}
function abbrNumberFormat(num) {
    var num = Number(num);
    if (num >= 1000000000000) {
        return (num / 1000000000000).toFixed(1).replace(/\.0$/, '') + 'T';
    }
     if (num >= 1000000000) {
        return (num / 1000000000).toFixed(1).replace(/\.0$/, '') + 'B';
     }
     if (num >= 1000000) {
        return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
     }
     if (num >= 1000) {
        return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
     }
     return num;
}
function getFormattedDate(date_str, format="long"){
    var monthNamesAbbr = [ "Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec" ]; 
    var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 

    var funded_date = date_str.split('-');

    if(format=='short'){
        
        if(Number(funded_date[2])==0 && Number(funded_date[1])==0 && Number(funded_date[0])!=0){
            return funded_date[0];
        } else if(Number(funded_date[1])!=0){
            return monthNamesAbbr[Number(funded_date[1])-1]+', '+funded_date[0];
        } else {
            return '-';
        }
    }
    if(Number(funded_date[0])==0){
        return 'Undisclosed';
    } else if(Number(funded_date[2])==0 && Number(funded_date[1])==0){
        return funded_date[0];
    } else if(Number(funded_date[2])==0){
        return monthNames[Number(funded_date[1])-1]+', '+funded_date[0];
    } else {
        return monthNames[Number(funded_date[1])-1]+' '+funded_date[2]+', '+funded_date[0];
    }
}
/* **************************
   *** GRAPH PLUGIN HANDLING
   **************************
*/

function createNodes(type, filtersParam, reviewed_only, page){
    if(!isMobile.any()){
        filtersParam.type = type;
        filtersParam.page = page;
        filtersParam.reviewed_only = reviewed_only;
        var call_url = ajaxCall("get_graph", filtersParam);

        jQuery('.preloader-status').show();

        renderNodesWithSigma(call_url);
    }
}
var s = null;
var sTimeout = null;

sigma.prototype.zoomToNode = function(node, ratio, camera, animation=false){
    if(typeof camera == "undefined"){
        camera = this.cameras[0];
    }
    if(animation){
        scope.camera = {
             x: camera.x,
             y: camera.y,
             ratio : camera.ratio,
        };
        jQuery(scope.camera).animate({
            x: node[camera.readPrefix+"x"]+14,
            y: node[camera.readPrefix+"y"]+5,
            ratio: ratio
        }, {
            duration: 3000,
            step: function(now) {
                  camera.goTo({
                    x: scope.camera.x,
                    y: scope.camera.y,
                    ratio : scope.camera.ratio,
             });
                updateZoomControl( Math.floor(scope.camera.ratio * 100) );
            s.refresh();
            }
        });
    } else {
        camera.goTo({
                x: node[camera.readPrefix+"x"]+14,
                y: node[camera.readPrefix+"y"]+5,
                ratio : ratio,
            });
        updateZoomControl( Math.floor(ratio * 100) );
        this.refresh();
    }

}

sigma.prototype.resetZoom = function(camera){
    if(typeof camera == "undefined"){
        camera = this.cameras[0];
    }
    camera.ratio = 1;
    camera.x = 0;
    camera.y = 0;
    this.refresh();
}

function renderNodesWithSigma(call_url) {
    if (s != null) {
        clearTimeout(sTimeout);


        setTimeout(function(){
            s.graph.clear();
            s.graph.kill();
            jQuery("#mynetwork").html('');
        },1);
        //s.graph.kill();



    }

    jQuery.ajax({
        url: call_url,
        dataType: 'json'
    }).done(function(data) {


        s = new sigma({
            container : "mynetwork",
            settings : {
                defaultLabelSize : 14,
                labelSizeRatio: 1,
                labelThreshold: 6,
                webglOversamplingRatio: 2,
                borderSize: 1,
                minArrowSize: 0,
                singleHover: true,

                scalingMode: 'outside',

                zoomMax: 4,
                zoomMin: 0.02,

                minEdgeSize: 0.3,
                maxEdgeSize: 0.7,
                edgeHoverSizeRatio: 1,
                edgeHoverExtremities: false,


                minNodeSize: 1,
                maxNodeSize: 8,

                nodesPowRatio: 0.5,
                edgesPowRatio: 0.05,

                animationsTime: 200
            }
        });

        sigmaAddNodesToGraph('company', s, data.graph.nodes.company, data.counts.max_edges);
        sigmaAddNodesToGraph('investor', s, data.graph.nodes.investor, data.counts.max_edges);

        scope.edges = {};

        for (var i in data.graph.edges) {
            var edge = data.graph.edges[i];
            var edge_id = edge.s + "-" + edge.i + "-" + edge.t;
            if(typeof scope.edges[edge_id] == 'undefined'){
                s.graph.addEdge({
                    id : edge_id,
                    source: 'investor_' + edge.s,
                    target: 'company_' + edge.t,
                    color : "#bbbbbb",

                });
                scope.edges[edge_id] = 1;
            }
        }
        s.bind('clickNode',function(event){

            var id_str = event.data.node.id;

            //s.zoomToNode(event.data.node, 0.05, event.target.camera);
            if(typeof id_str !='undefined'){
                var node_id = id_str.split('_')[1];
                var type_str = id_str.split('_')[0];

                showNodeDescription(type_str, node_id);
            }

        });

        s.startForceAtlas2({
            linLogMode: false,
            adjustSizes: false,
            barnesHutTheta: 0.5,
            outboundAttractionDistribution : false,
            iterationsPerRender: 1,
            barnesHutOptimize : data.graph.edges.length > 150,
            slowDown: data.graph.edges.length > 1000 ? 10 : (data.graph.edges.length > 200 ? 20 : 30 ),
            strongGravityMode: false,
            scalingRatio:  data.graph.edges.length < 200 ? 2 : 10 ,
            startingIterations : 25,
            //edgeWeightInfluence : 0 + (data.edges.length < 400 ? 1.2 : 0),
            worker: true,
            autoStop: true,
            background: true,

            gravity: data.graph.edges.length < 200 ? 2 : 6
        });
        sTimeout = setTimeout(function(){
            s.stopForceAtlas2();

            if (typeof node_type != 'undefined' && typeof node_id != 'undefined') {
                window.history.pushState(node_type + '/' + node_id, '');
                showNodeDescription(node_type, node_id, true, false);
            }

        },2000 + 2000 * (( data.graph.nodes.company.length + data.graph.nodes.investor.length ) / 7000 ) + 2000 * data.graph.edges.length / 2000);

    }).fail(function(){
        alert('An unkown error ocurred. Please try again later.');
    }).always(function() {
        s.cameras[0].goTo({ratio: 1.8});

        setTimeout(function(){
            jQuery('.preloader-status').hide();

        }, 100);


    });
}

function sigmaAddNodesToGraph( type , s, nodes, max_edges ){
    var colors = {
        "company" : "#0e8edf",
        "investor" : "#4c4c4c"
    }

    for (var i in nodes) {
        var node = nodes[i];

        var ratio = 0;

        if (max_edges && node.e <= max_edges) {
            ratio = node.e / max_edges;
        }

        var angle = Math.random() * 2 * Math.PI;
        var radius =  node.e > 0 ?  Math.random() : (0.35 + Math.random() ) ;



        var nodeData = {
            id: type + "_" + node.i,
            label: node.n,
            //x:  (i % step) * 500 / step ,
            //y:  (i / step) * 500 / step,
            x : radius * Math.cos(angle),
            y : radius * Math.sin(angle),
            color: colors[type],
            size: 10 + (60 * ratio)
        };

        s.graph.addNode(nodeData);

    }
}

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i) ? true : false;
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i) ? true : false;
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) ? true : false;
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());
    }
};

scope.currentSet = scope.data[scope.currentType];

ReactDOM.render(
    <div>
        <NodeDescription scope={scope}/>

        <div id="sidebar-controls" className="sidebar">
            <SearchModule/>
            <FilterTypes title="Type" scope={scope}/>
        </div>
        <div className="graph-area">
            <div className="graph-header">
                <div className="list-toggle-container header-controls">
                    <button>
                        View list <span className="icon-list-view"></span>
                    </button>
                    <ResultsCaption scope={scope}/>
                </div>
            </div>

            <div className="table-view">

                <div className="header-controls">
                    <button>
                        Close list view <span className="icon-close-icon"></span>
                    </button>
                    <ResultsCaption scope={scope}/>
                </div>
                <SortTable scope={scope}/>
                <div className="table-footer">
                    <TablePagination/>
                </div>

            </div>

            <div id="mynetwork"></div>


            <div className="preloader-status" style={{display:"block"}}>
                <i className="fa fa-refresh fa-spin" aria-hidden="true"></i>
                <span>Processing data</span>
            </div>
            <div className="nodata-status">
                <img src={scope.image_dir+"/nodes_map_graph.svg"} />
                <div className="status-wrapper">
                    <span className="icon-alert-icon"></span>
                    <span>No data to show</span>
                </div>
            </div>

            <div className="graph-footer image-block">
                <div className="zoom-control">
                    <span>zoom 180%</span>
                    <div id="zoom-control" style={{height:100}}></div>
                </div>

                <Pagination/>

                <div className="indications-block">
                    <div className="indications-left">
                        <ul>
                            <li>
                                <div className="round-img company"></div>
                                <span>COMPANY</span></li>
                            <li>
                                <div className="round-img investor"></div>
                                <span>INVESTOR</span></li>
                            <li>
                                <div className="round-img grey"></div>
                                <span>INVESTMENT MADE</span></li>
                        </ul>
                    </div>
                    <div className="indications-right">
                        <ul>
                            <li className="round-img small"></li>
                            <li className="round-img mid"></li>
                            <li className="round-img large"></li>
                        </ul>
                        <span>PROPORTIONAL TO THE # OF INVESTMENTS</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    , document.getElementById('explore-the-network'));