
var MooTreeIcon=['I','L','Lminus','Lplus','Rminus','Rplus','T','Tminus','Tplus','_closed','_doc','_open','minus','plus'];var MooTreeControl=new Class({initialize:function(config,options){options.control=this;options.div=config.div;this.root=new MooTreeNode(options);this.index=new Object();this.enabled=true;this.theme=config.theme||'mootree.gif';this.loader=config.loader||{icon:'mootree_loader.gif',text:'Loading...',color:'#a0a0a0'};this.selected=null;this.mode=config.mode;this.grid=config.grid;this.onExpand=config.onExpand||new Function();this.onSelect=config.onSelect||new Function();this.onClick=config.onClick||new Function();this.root.update(true);},insert:function(options){options.control=this;return this.root.insert(options);},select:function(node){this.onClick(node);node.onClick();if(this.selected===node)return;if(this.selected){this.selected.select(false);this.onSelect(this.selected,false);}
this.selected=node;node.select(true);this.onSelect(node,true);},expand:function(){this.root.toggle(true,true);},collapse:function(){this.root.toggle(true,false);},get:function(id){return this.index[id]||null;},adopt:function(id,parentNode){if(parentNode===undefined)parentNode=this.root;this.disable();this._adopt(id,parentNode);parentNode.update(true);document.id(id).destroy();this.enable();},_adopt:function(id,parentNode){e=document.id(id);var i=0,c=e.getChildren();for(i=0;i<c.length;i++){if(c[i].nodeName=='LI'){var con={text:''},comment='',node=null,subul=null;var n=0,z=0,se=null,s=c[i].getChildren();for(n=0;n<s.length;n++){switch(s[n].nodeName){case'A':for(z=0;z<s[n].childNodes.length;z++){se=s[n].childNodes[z];switch(se.nodeName){case'#text':con.text+=se.nodeValue;break;case'#comment':comment+=se.nodeValue;break;}}
con.data=s[n].getProperties('href','target','title','name');break;case'UL':subul=s[n];break;}}
if(con.label!=''){con.data.url=con.data['href'];if(comment!=''){var bits=comment.split(';');for(z=0;z<bits.length;z++){var pcs=bits[z].trim().split(':');if(pcs.length==2)con[pcs[0].trim()]=pcs[1].trim();}}
if($chk(c[i].id)){con.id='node_'+c[i].id;}
node=parentNode.insert(con);if(subul)this._adopt(subul,node);}}}},disable:function(){this.enabled=false;},enable:function(){this.enabled=true;this.root.update(true,true);}});var MooTreeNode=new Class({initialize:function(options){this.text=options.text;this.id=options.id||null;this.nodes=new Array();this.parent=null;this.last=true;this.control=options.control;this.selected=false;this.color=options.color||null;this.data=options.data||{};this.onExpand=options.onExpand||new Function();this.onSelect=options.onSelect||new Function();this.onClick=options.onClick||new Function();this.open=options.open?true:false;this.icon=options.icon;this.openicon=options.openicon||this.icon;if(this.id)this.control.index[this.id]=this;this.div={main:new Element('div').addClass('mooTree_node'),indent:new Element('div'),gadget:new Element('div'),icon:new Element('div'),text:new Element('div').addClass('mooTree_text'),sub:new Element('div')}
this.div.main.adopt(this.div.indent);this.div.main.adopt(this.div.gadget);this.div.main.adopt(this.div.icon);this.div.main.adopt(this.div.text);document.id(options.div).adopt(this.div.main);document.id(options.div).adopt(this.div.sub);this.div.gadget._node=this;this.div.gadget.onclick=this.div.gadget.ondblclick=function(){this._node.toggle();}
this.div.icon._node=this.div.text._node=this;this.div.icon.onclick=this.div.icon.ondblclick=this.div.text.onclick=this.div.text.ondblclick=function(){this._node.control.select(this._node);}},insert:function(options){options.div=this.div.sub;options.control=this.control;var node=new MooTreeNode(options);node.parent=this;var n=this.nodes;if(n.length)n[n.length-1].last=false;n.push(node);node.update();if(n.length==1)this.update();if(n.length>1)n[n.length-2].update(true);return node;},remove:function(){var p=this.parent;this._remove();p.update(true);},_remove:function(){var n=this.nodes;while(n.length)n[n.length-1]._remove();delete this.control.index[this.id];this.div.main.destroy();this.div.sub.destroy();if(this.parent){var p=this.parent.nodes;p.erase(this);if(p.length)p[p.length-1].last=true;}},clear:function(){this.control.disable();while(this.nodes.length)this.nodes[this.nodes.length-1].remove();this.control.enable();},update:function(recursive,invalidated){var draw=true;if(!this.control.enabled){this.invalidated=true;draw=false;}
if(invalidated){if(!this.invalidated){draw=false;}else{this.invalidated=false;}}
if(draw){var x;this.div.main.className='mooTree_node'+(this.selected?' mooTree_selected':'');var p=this,i='';while(p.parent){p=p.parent;i=this.getImg(p.last||!this.control.grid?'':'I')+i;}
this.div.indent.innerHTML=i;x=this.div.text;x.empty();x.appendText(this.text);if(this.color)x.style.color=this.color;this.div.icon.innerHTML=this.getImg(this.nodes.length?(this.open?(this.openicon||this.icon||'_open'):(this.icon||'_closed')):(this.icon||(this.control.mode=='folders'?'_closed':'_doc')));this.div.gadget.innerHTML=this.getImg((this.control.grid?(this.control.root==this?(this.nodes.length?'R':''):(this.last?'L':'T')):'')+(this.nodes.length?(this.open?'minus':'plus'):''));this.div.sub.style.display=this.open?'block':'none';}
if(recursive)this.nodes.forEach(function(node){node.update(true,invalidated);});},getImg:function(name){var html='<div class="mooTree_img"';if(name!=''){var img=this.control.theme;var i=MooTreeIcon.indexOf(name);if(i==-1){var x=name.split('#');img=x[0];i=(x.length==2?parseInt(x[1])-1:0);}
html+=' style="background-image:url('+img+'); background-position:-'+(i*18)+'px 0px;"';}
html+="></div>";return html;},toggle:function(recursive,state){this.open=(state===undefined?!this.open:state);this.update();this.onExpand(this.open);this.control.onExpand(this,this.open);if(recursive)this.nodes.forEach(function(node){node.toggle(true,this.open);},this);},select:function(state){this.selected=state;this.update();this.onSelect(state);},load:function(url,vars){if(this.loading)return;this.loading=true;this.toggle(false,true);this.clear();this.insert(this.control.loader);var f=function(){new Request({method:'GET',url:url,onSuccess:this._loaded.bind(this),onFailure:this._load_err.bind(this)}).send(url,vars||'');}.bind(this).delay(20);},_loaded:function(text,xml){this.control.disable();this.clear();this._import(xml.documentElement);this.control.enable();this.loading=false;},_import:function(e){var n=e.childNodes;for(var i=0;i<n.length;i++)if(n[i].tagName=='node'){var opt={data:{}};var a=n[i].attributes;for(var t=0;t<a.length;t++){switch(a[t].name){case'text':case'id':case'icon':case'openicon':case'color':case'open':opt[a[t].name]=a[t].value;break;default:opt.data[a[t].name]=a[t].value;}}
var node=this.insert(opt);if(node.data.load){node.open=false;node.insert(this.control.loader);node.onExpand=function(state){this.load(this.data.load);this.onExpand=new Function();}}
if(n[i].childNodes.length)node._import(n[i]);}},_load_err:function(req){window.alert('Error loading: '+this.text);}});