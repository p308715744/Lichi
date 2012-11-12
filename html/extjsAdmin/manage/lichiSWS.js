//var removeTopicCategory,removeTopic;
//var topicCategoryLoader=Global.topicCategoryLoader;
var chooser;


/*美达用户管理菜单
*/
LichiSWSPanel=function()
{	
	LichiSWSPanel.superclass.constructor.call(this, {
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
   		text:'查看所有',
		icon:'./extjsAdmin/images/20070417191607553.gif',
   		listeners:{'click':function(){
			var panel=Ext.getCmp("LichiWSWManage");
			
			if(!panel)
			{
					panel=new LichiWSWManage();
				}
				
				
			editTopic=function(id)
			{
				var record=panel.grid.getSelectionModel().getSelected();
				if(!record){
					Ext.Msg.alert("提示","请先选择要编辑的行11111!");
					return;
				}
				else
				{
					Ext.Msg.alert("提示","eeeee11111!");
					return;
					}
			};
			main.openTab(panel);

   		}}	
   		})); 
   
}
Ext.extend(LichiSWSPanel, Ext.tree.TreePanel,{});



/*展示管理
*/
LichiWSWManage=Ext.extend(GaoP.Ext.CrudPanel,{
	id:"LichiWSWManage",
	title:"荔枝视物所",
	height:500,
	
	
    ShowDom:function(obj){
			lichiShowDom();
	}, 
	showImage:function (value) {
		return '<div class="thumb-wrap" ><div class="thumb"><img src="'+value+'" ></div></div>';
		
	},
	
	showDate:function (value) {
		
		 var dat = new Date(parseInt(value) * 1000);
		 
		 var m = dat.getMonth();
		 m++;
		 
		 var time = dat.getFullYear()+"-"+m+"-"+dat.getDate();
		 
   		 return time;
	},

	inttochar:function (value){
		
		if(value == 1)
			return "是";
		else
			return "NO";
	},


	storeMapping:["show_id","user_id","title","username","dateline","show_img","temp_img","text","show_face","views","discuss_count","public","available","main","sortvlue"],	

    initComponent : function()
	{
			this.store=new Ext.data.JsonStore({
				id:"Id",
				url: '?Controller=ExtjsAdmin&action=LichiSWSManange',
				root:"rows",
				totalProperty:"totalCount",
				remoteSort:true,  		
				fields:this.storeMapping});
	
	
			this.cm=new Ext.grid.ColumnModel([
										  
				new Ext.grid.RowNumberer({header:"序",width:25,sortable:true}),	
				
				{
				   header: "展示ID",
				   dataIndex: 'show_id',
				   width: 50
				},{
				   header: "标题",
				   dataIndex: 'title',
				   width: 50
				},{
				   header: "创建时间",
				   dataIndex: 'dateline',
				   width: 50,
				   renderer:this.showDate
				},{
				   header: "封面图片",
				   dataIndex: 'show_img',
				   width: 50,
				   renderer:this.showImage
				},{
				   header: "简介",
				   dataIndex: 'text',
				   width: 50
				},{
				   header: "浏览量",
				   dataIndex: 'views',
				   width: 50
				},{
				   header: "排序值",
				   dataIndex: 'sortvlue',
				   width: 50
				},{
				   header: "评论数",
				   dataIndex: 'discuss_count',
				   width: 50
				},{
				   header: "公开",
				   dataIndex: 'public',
				   width: 50,
				   renderer:this.inttochar
				}
			]);  
	
	
			this.topbar = ['   ',
					 
					{    
						text: '添加', 
						pressed: true,           
						handler: function(){ 
								lichiShowDom(this.store);
						},
						scope:this
					},'   ',
					{    
						text: '修改',  
						pressed: true,           
						handler: function(){ 
							var record=this.grid.getSelectionModel().getSelected();
							if(!record)
								Ext.Msg.alert("提示","请先选择要编辑的行!");
							else
								ModaerShowEdit(record,this.store) ;
						},
						scope:this
					},'   ',
					{    
						text: '删除',  
						pressed: true,           
						handler: function(){ 
									Ext.Msg.prompt('提示!', '输入口令', function(btn, text){
										if (btn == 'ok'){
											if(text == "我爱美达")
											{
															var record=this.grid.getSelectionModel().getSelected();
															if(!record){
																Ext.Msg.alert("提示","请选择后操作");
																return;
															}
															store = this.store;
															Ext.Ajax.request({
																   url: '?Controller=ExtjsAdmin&action=LichiShowDom',
																	params: {
																		show_id : record.get("show_id"),
																		setback : '1'
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
	
	
				LichiWSWManage.superclass.initComponent.call(this);
	}




	});

	function lichiShowDom(store)
	{
		

				var form_info = new Ext.FormPanel({//初始化表单面板
												  
							id: 'form_info',
							name: 'form_info',
							labelWidth: 60, // 默认标签宽度板
							labelAlign: 'right',
							width: 320,
							frame: true,
							defaultType: 'textfield',//默认字段类型
							items: [{
								xtype: 'fieldset',
								title: '添加新视物',
								defaults: {
									xtype: 'textfield',
									width: 200
								},
								items: [{
									name: ' user_id',
									xtype: 'hidden'
								},{
									name: 'show_id',
									fieldLabel: '展示ID',
									allowBlank: false,
									blankText: '展示ID不能为空'
								}]
					}],
					buttons: [{
								text: '确定',
								handler: function(){
									if (form_info_widow.getComponent('form_info').form.isValid()) {
										form_info_widow.getComponent('form_info').form.submit({
											waitTitle: '请稍候',
											waitMsg: '正在提交数据,请稍候....',
											url: '?Controller=ExtjsAdmin&action=LichiShowDom',
											method: 'POST',
											success: function(form, action){
												var Result = action.result.success;
												Ext.MessageBox.alert('提示', action.result.message);
												form_info_widow.close();
												store.reload();
											},
											failure: function(form, action){
												Ext.MessageBox.alert('提示', action.result.message);
												store.reload();
											}
										})
									}
								}
							}, {
								text: '关闭',
								handler: function(){
									form_info_widow.close();
								}
							}]
				});
				
				var form_info_widow = new Ext.Window({
					title: "添加新视物",
					modal: true,
					maximizable: true,
					items: form_info
				});


				form_info_widow.show();

			
		}

