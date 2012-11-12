

/*美达用户管理菜单
*/
ModaTalkPanel=function()
{	
	ModaTalkPanel.superclass.constructor.call(this, {
        autoScroll:true,
        animate:true,
        border:false,
        rootVisible:false,
        root:new Ext.tree.TreeNode({
        text: '用户管理菜单',
        draggable:false,
       	expanded:true
   	 })        
    });
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"NEW",
		icon:'./extjsAdmin/images/20070417191601340.gif',
   		listeners:{'click':function(){
				//window.showModalDialog('?Controller=ExtjsAdmin&action=NewLichiMV','ModaNews','dialogWidth:880px;dialogHeight:770px;center:yes;help:yes;resizable:yes;status:yes')
				window.open('?Controller=ExtjsAdmin&action=NewLichiMV','ModaNews',"width=880,height=770")
				
			
   		}}	
   		})); 
   
   
   this.root.appendChild(new Ext.tree.TreeNode({
   		text:"查看所有",
		icon:'./extjsAdmin/images/20070417191603968.gif',
   		listeners:{'click':function(){

			var panel=Ext.getCmp("ModaTalkList");
			if(!panel){
					panel=new ModaTalkList();

			}		
			
			main.openTab(panel);
			
				
   			}}	
   		})); 
   
}
Ext.extend(ModaTalkPanel, Ext.tree.TreePanel,{});




/*查看所有榜
*/
ModaTalkList=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"ModaTalkList",
	title:"荔枝MV",

	showHeight:function (value) {
		return value+"/CM";
		
	},
	
	showImage:function (value) {
		return '<div class="thumb-wrap" ><div class="thumb"><img src="'+value+'" ></div></div>';
		
	},
		
    operationRender:function(obj){
		return !obj||obj=="-1"?"":"<a href='javascript:showDetail("+obj+")'>[详图]</a>";
		
	}, 
	
	
	showDate:function (value) {
		
		 var dat = new Date(parseInt(value) * 1000);
		 
		 var m = dat.getMonth();
		 m++;
		 
		 var time = dat.getFullYear()+"-"+m+"-"+dat.getDate();
		 
   		 return time;
	},
	modaNewsDel:function (value) {
		  var record=this.grid.getSelectionModel().getSelected();
		  if(!record){
			  Ext.Msg.alert("提示","请先选择要编辑的行");
			  return;
		  }
		store = this.store;  
		Ext.Msg.prompt('提示!', '输入口令', function(btn, text){
			if (btn == 'ok'){
				if(text == "我爱美达")
				{
					  Ext.Ajax.request({
							 url: '?Controller=ExtjsAdmin&action=LichiNewsDel',
							  params: {
								  
								  news_id : record.get("news_id")
							  },
							 success:function(request){                   //发送成功的回调函数
								 var message = request.responseText;  //取得从JSP文件out.print(...)传来的文本
								 Ext.Msg.alert('信息',message);        //弹出对话框
					  			 store.reload();
							}
					  });
						
					}
				else
				{
					Ext.Msg.alert('SORRY!', '口令错误');
					}
			}
		});
		  
		  
		  
		 
	},


	
    on_talkEdit:function(obj){
		
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行!");
			return;
		}
		else
		{
			modaTalkEdit(record);
			return;
		}
		
	}, 
    on_RankEdit:function(obj){
		
		var record=this.grid.getSelectionModel().getSelected();
		if(!record){
			Ext.Msg.alert("提示","请先选择要编辑的行!");
			return;
		}
		else
		{
			RankEdit(record);
			return;
		}
		
	}, 

	storeMapping:["news_id","news_st","news_title","news_content","dateline","url","img_url","content","user_id","index_img","video_link"],	

    initComponent : function(){
	
	
		this.store=new Ext.data.JsonStore({
			id:"Id",
			url: '?Controller=ExtjsAdmin&action=ModaTalkList',
			root:"rows",
			totalProperty:"totalCount",
			remoteSort:true,  		
			fields:this.storeMapping});
	
	
	
	
  		this.cm=new Ext.grid.ColumnModel([
									  
			new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
			
			{        
			   header: "荔枝用户ID",
			   dataIndex: 'user_id',
			   width:50
			},{        
			   header: "ID",
			   dataIndex: 'news_id',
			   width:50
			},{
			   header: "MV标题",
			   dataIndex: 'news_title',
			   width: 50
			},{
			   header: "副标题",
			   dataIndex: 'news_st',
			   width: 50
			},{
			   header: "个人页面图片",
			   dataIndex: 'index_img',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "列表图片",
			   dataIndex: 'img_url',
			   width: 50,
			   renderer:this.showImage
			},{
			   header: "视频地址",
			   dataIndex: 'video_link',
			   width: 50
			},{
			   header: "详细内容",
			   dataIndex: 'content',
			   width: 50
			},{
			   header: "创建时间",
			   dataIndex: 'dateline',
			   width: 50,
			   renderer:this.showDate
			}
		]);  
	
	
		this.topbar = ['   ',
				 {    
					text: '修改',  
					pressed: true,           
					handler: function(){
							var record=this.grid.getSelectionModel().getSelected();
							if(!record){
								Ext.Msg.alert("提示","请先选择要编辑的行!");
								return;
							}
							else
							{
								window.showModalDialog('?controller=ExtjsAdmin&action=NewLichiMVEdit&news_id='+record.get("news_id"),'EditNews','dialogWidth:880px;dialogHeight:770px;center:yes;help:yes;resizable:yes;status:yes')  
								
								return;
							}
						},
					scope:this
				},'   ',
				{    
					text: '删除',  
					pressed: true,           
					handler: this.modaNewsDel,
					scope:this
				},'   ',
				{    
					text: '刷新',  
					pressed: true,           
					handler: this.refresh,
					scope:this
				}
				,new Ext.Toolbar.Fill(),
				'Search: ',
				{    
					xtype:"textfield",
					width:100,
					pressed: true, 
					scope:this
				},'   ',
				{    
					text: '查询',   
					pressed: true,           
					handler: this.search,
					scope:this
				},'   '
			];
	
		ModaTalkList.superclass.initComponent.call(this);
	}

	});

